<?php
session_start();
include("../conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $voluntario_id = $_POST['voluntario_id'];
        $evento_id = $_POST['evento_id'];
        $organizador_id = $_SESSION['id_usuario'];
        $puntos_ganados = 1;

        // Verificar si ya existe una confirmación
        $stmtVerificar = $pdo->prepare("
            SELECT confirmado 
            FROM participacion_eventos 
            WHERE voluntario_id = :voluntario_id 
            AND evento_id = :evento_id
        ");
        $stmtVerificar->execute([
            ':voluntario_id' => $voluntario_id,
            ':evento_id' => $evento_id
        ]);
        $asistencia = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if ($asistencia && $asistencia['confirmado'] == 1) {
            echo json_encode([
                'success' => false,
                'error' => 'La asistencia ya ha sido confirmada anteriormente'
            ]);
            exit();
        }

        $pdo->beginTransaction();

        // Registrar o actualizar la asistencia
        $stmt = $pdo->prepare("
            INSERT INTO participacion_eventos 
            (voluntario_id, evento_id, puntos_ganados, confirmado, fecha_confirmacion, organizador_id)
            VALUES 
            (:voluntario_id, :evento_id, :puntos_ganados, 1, NOW(), :organizador_id)
            ON DUPLICATE KEY UPDATE 
            confirmado = 1,
            fecha_confirmacion = NOW(),
            puntos_ganados = :puntos_ganados,
            organizador_id = :organizador_id
        ");

        $stmt->execute([
            ':voluntario_id' => $voluntario_id,
            ':evento_id' => $evento_id,
            ':puntos_ganados' => $puntos_ganados,
            ':organizador_id' => $organizador_id
        ]);

        // Actualizar puntos del voluntario
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET puntos = puntos + :puntos_ganados 
            WHERE id_usuario = :voluntario_id
        ");
        $stmt->execute([
            ':puntos_ganados' => $puntos_ganados,
            ':voluntario_id' => $voluntario_id
        ]);

        // Registrar en el log de puntos
        $stmt = $pdo->prepare("
            INSERT INTO log_puntos (usuario_id, puntos_ganados, descripcion)
            VALUES (:usuario_id, :puntos_ganados, :descripcion)
        ");
        $stmt->execute([
            ':usuario_id' => $voluntario_id,
            ':puntos_ganados' => $puntos_ganados,
            ':descripcion' => 'Asistencia confirmada al evento ID: ' . $evento_id
        ]);

        $pdo->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'error' => 'Error al procesar la solicitud: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido'
    ]);
}
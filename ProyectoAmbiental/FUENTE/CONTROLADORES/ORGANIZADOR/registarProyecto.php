<!-- filepath: c:\xampp1\htdocs\ProyectoAmbiental\PHP\registrarProyecto.php -->
<?php
session_start();
include("../conexion.php");


// Validar que se hayan enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $cupo = intval($_POST['cupo']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $id_organizador = $_SESSION['id_usuario'];

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($descripcion) || empty($cupo) || empty($fecha_inicio) || empty($fecha_fin)) {
        $_SESSION['errores'] = ["Todos los campos son obligatorios."];
        header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php");
        exit();
    }

    // Validar que la fecha de inicio sea anterior a la fecha de fin
    if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
        $_SESSION['errores'] = ["La fecha de inicio debe ser anterior a la fecha de fin."];
        header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php");
        exit();
    }

    try {
        // Insertar el proyecto en la base de datos
        $stmt = $pdo->prepare("INSERT INTO proyectos (nombre, descripcion, cupo, fecha_inicio, fecha_fin, usuario_id) 
                               VALUES (:nombre, :descripcion, :cupo, :fecha_inicio, :fecha_fin, :usuario_id)");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':cupo', $cupo, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $id_organizador, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['mensaje_exito'] = "Proyecto registrado exitosamente.";
        } else {
            $_SESSION['errores'] = ["Error al registrar el proyecto. Inténtalo nuevamente."];
        }
    } catch (PDOException $e) {
        $_SESSION['errores'] = ["Error en la base de datos: " . htmlspecialchars($e->getMessage())];
    }

    // Redirigir al dashboard del organizador
    header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php");
    exit();
} else {
    // Si no se envió el formulario, redirigir al dashboard
    header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php");
    exit();
}
?>
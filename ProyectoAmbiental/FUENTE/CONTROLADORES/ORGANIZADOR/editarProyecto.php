<?php
session_start();
include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id_proyecto = filter_var($_POST['id_proyecto'], FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
        $cupo = filter_var($_POST['cupo'], FILTER_SANITIZE_NUMBER_INT);
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $estado = filter_var($_POST['estado'], FILTER_SANITIZE_STRING);

        $query = "UPDATE proyectos 
                 SET nombre = :nombre, 
                     descripcion = :descripcion, 
                     cupo = :cupo,
                     fecha_inicio = :fecha_inicio, 
                     fecha_fin = :fecha_fin, 
                     estado = :estado 
                 WHERE id_proyecto = :id_proyecto";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_proyecto' => $id_proyecto,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':cupo' => $cupo,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':estado' => $estado
        ]);

        $_SESSION['success_message'] = 'Proyecto actualizado correctamente';
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Error al actualizar el proyecto: ' . $e->getMessage();
    }

    header('Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php');
    exit();
}
?>
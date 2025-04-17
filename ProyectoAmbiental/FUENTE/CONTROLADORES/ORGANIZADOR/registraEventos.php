<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\PHP\registrarEvento.php
session_start();
include("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proyecto_id = $_POST["proyecto_id"];
    $nombre_evento = $_POST["nombre_evento"];
    $descripcion = $_POST["descripcion"];
    $fecha_evento = $_POST["fecha_evento"];

    try {
        // Insertar el evento en la base de datos
        $stmt = $pdo->prepare("INSERT INTO eventos (proyecto_id, nombre_evento, descripcion, fecha_evento) VALUES (:proyecto_id, :nombre_evento, :descripcion, :fecha_evento)");
        $stmt->bindParam(':proyecto_id', $proyecto_id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_evento', $nombre_evento, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_evento', $fecha_evento, PDO::PARAM_STR);
        $stmt->execute();

        // Redirigir al dashboard del organizador con un mensaje de éxito
        header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php?mensaje=evento_registrado");
        exit();

    } catch (PDOException $e) {
        // Redirigir al dashboard del organizador con un mensaje de error
        header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php?mensaje=error_registro_evento");
        exit();
    }
} else {
    // Redirigir al dashboard del organizador si se intenta acceder al archivo directamente
    header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php");
    exit();
}
?>
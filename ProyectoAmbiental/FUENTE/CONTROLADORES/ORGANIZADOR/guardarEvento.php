<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\PHP\guardarEvento.php
session_start();
include("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_evento = $_POST["id_evento"];
    $proyecto_id = $_POST["proyecto_id"];
    $nombre_evento = $_POST["nombre_evento"];
    $descripcion = $_POST["descripcion"];
    $fecha_evento = $_POST["fecha_evento"];
    $direccion_reunion = $_POST["direccion_reunion"];


    try {
        if ($id_evento == "") {
            // Insertar el evento en la base de datos
            $stmt = $pdo->prepare("INSERT INTO eventos (proyecto_id, nombre_evento, descripcion, fecha_evento, direccion_reunion) 
                                 VALUES (:proyecto_id, :nombre_evento, :descripcion, :fecha_evento, :direccion_reunion)");
            $stmt->bindParam(':proyecto_id', $proyecto_id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_evento', $nombre_evento, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_evento', $fecha_evento, PDO::PARAM_STR);
            $stmt->bindParam(':direccion_reunion', $direccion_reunion, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            // Actualizar el evento en la base de datos
            $stmt = $pdo->prepare("UPDATE eventos 
                                 SET proyecto_id = :proyecto_id, 
                                     nombre_evento = :nombre_evento, 
                                     descripcion = :descripcion, 
                                     fecha_evento = :fecha_evento, 
                                     direccion_reunion = :direccion_reunion 
                                 WHERE id_evento = :id_evento");
            $stmt->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
            $stmt->bindParam(':proyecto_id', $proyecto_id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_evento', $nombre_evento, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_evento', $fecha_evento, PDO::PARAM_STR);
            $stmt->bindParam(':direccion_reunion', $direccion_reunion, PDO::PARAM_STR);
            $stmt->execute();
        }

        // Redirigir a la página de eventos del organizador con un mensaje de éxito
        header("Location: ../../VISTA/ORGANIZADOR/eventosOrganizador.php?id_proyecto=" . $proyecto_id . "&mensaje=evento_guardado");
        exit();

    } catch (PDOException $e) {
        // Redirigir a la página de eventos del organizador con un mensaje de error
        header("Location: ../../VISTA/ORGANIZADOR/eventosOrganizador.php?id_proyecto=" . $proyecto_id . "&mensaje=error_guardar_evento");
        exit();
    }
} else {
    // Redirigir al dashboard del organizador si se intenta acceder al archivo directamente
    header("Location: ../../VISTA/ORGANIZADOR/dashboard_organizador.php");
    exit();
}
?>
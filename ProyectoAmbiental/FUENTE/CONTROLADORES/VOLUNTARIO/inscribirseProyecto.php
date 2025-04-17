<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\FUENTE\CONTROLADORES\VOLUNTARIO\inscribirseProyecto.php
session_start();
include("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proyecto_id = $_POST["proyecto_id"];
    $usuario_id = $_SESSION["id_usuario"];

    try {
        // Verificar si el usuario ya está inscrito en el proyecto
        $stmtVerificar = $pdo->prepare("SELECT * FROM participacion_proyectos WHERE usuario_id = :usuario_id AND proyecto_id = :proyecto_id");
        $stmtVerificar->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmtVerificar->bindParam(':proyecto_id', $proyecto_id, PDO::PARAM_INT);
        $stmtVerificar->execute();
        $inscripcionExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if ($inscripcionExistente) {
            // Redirigir al dashboard del voluntario con un mensaje de error
            header("Location: ../../VISTA/VOLUNTARIO/dashboard_voluntario.php?mensaje=ya_inscrito&proyecto_id=" . $proyecto_id);
            exit();
        }

        // Insertar la participación del usuario en el proyecto
        $stmt = $pdo->prepare("INSERT INTO participacion_proyectos (usuario_id, proyecto_id) VALUES (:usuario_id, :proyecto_id)");
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':proyecto_id', $proyecto_id, PDO::PARAM_INT);
        $stmt->execute();

          // Asignar puntos al voluntario
          $puntos_inscripcion = 10;
          $stmtPuntos = $pdo->prepare("UPDATE usuarios SET puntos = puntos + :puntos WHERE id_usuario = :usuario_id");
          $stmtPuntos->bindParam(':puntos', $puntos_inscripcion, PDO::PARAM_INT);
          $stmtPuntos->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
          $stmtPuntos->execute();
  
          // Registrar el log de puntos
          $descripcion_log = "Inscripción al proyecto con ID: " . $proyecto_id;
          $stmtLog = $pdo->prepare("INSERT INTO log_puntos (usuario_id, puntos_ganados, descripcion) VALUES (:usuario_id, :puntos, :descripcion)");
          $stmtLog->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
          $stmtLog->bindParam(':puntos', $puntos_inscripcion, PDO::PARAM_INT);
          $stmtLog->bindParam(':descripcion', $descripcion_log, PDO::PARAM_STR);
          $stmtLog->execute();

          // Verificar si el usuario ha alcanzado una nueva recompensa
        $stmtRecompensas = $pdo->prepare("SELECT * FROM recompensas WHERE puntos_requeridos <= (SELECT puntos FROM usuarios WHERE id_usuario = :usuario_id) AND recompensa_id NOT IN (SELECT recompensa_id FROM voluntarios_recompensas WHERE voluntario_id = :usuario_id)");
        $stmtRecompensas->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmtRecompensas->execute();
        $recompensas = $stmtRecompensas->fetchAll(PDO::FETCH_ASSOC);

        foreach ($recompensas as $recompensa) {
            // Asignar la recompensa al voluntario
            $stmtAsignarRecompensa = $pdo->prepare("INSERT INTO voluntarios_recompensas (voluntario_id, recompensa_id, fecha_asignacion) VALUES (:usuario_id, :recompensa_id, CURDATE())");
            $stmtAsignarRecompensa->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtAsignarRecompensa->bindParam(':recompensa_id', $recompensa['recompensa_id'], PDO::PARAM_INT);
            $stmtAsignarRecompensa->execute();

            // Registrar el log de la recompensa
            $descripcion_log = "Obtención de la recompensa: " . $recompensa['tipo_recompensa'];
            $stmtLog = $pdo->prepare("INSERT INTO log_puntos (usuario_id, puntos_ganados, descripcion) VALUES (:usuario_id, 0, :descripcion)");
            $stmtLog->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtLog->bindParam(':descripcion', $descripcion_log, PDO::PARAM_STR);
            $stmtLog->execute();
        }

        // Función para actualizar el nivel del usuario
        function actualizarNivel($pdo, $usuario_id) {
            $niveles = [
                'Voluntario Bronce' => 0,
                'Voluntario Plata' => 20,
                'Voluntario Oro' => 50,
                'Voluntario Platino' => 100,
                'Voluntario Diamante' => 200,
                'Voluntario Experto' => 500,
                'Voluntario Profesional' => 750,
                'Voluntario Elite' => 1000
            ];

            // Obtener los puntos actuales del usuario
            $stmtPuntos = $pdo->prepare("SELECT puntos FROM usuarios WHERE id_usuario = :usuario_id");
            $stmtPuntos->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtPuntos->execute();
            $puntos = $stmtPuntos->fetch(PDO::FETCH_ASSOC)['puntos'];

            $nuevoNivel = 'Voluntario Bronce';
            foreach ($niveles as $nivel => $puntosRequeridos) {
                if ($puntos >= $puntosRequeridos) {
                    $nuevoNivel = $nivel;
                } else {
                    break; // Encuentra el nivel más alto que cumple
                }
            }

            // Actualizar el nivel del usuario en la base de datos
            $stmtNivel = $pdo->prepare("UPDATE usuarios SET nivel = :nivel WHERE id_usuario = :usuario_id");
            $stmtNivel->bindParam(':nivel', $nuevoNivel, PDO::PARAM_STR);
            $stmtNivel->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtNivel->execute();
        }

        // Después de asignar puntos, actualizar el nivel
        actualizarNivel($pdo, $usuario_id);

        // Redirigir al dashboard del voluntario con un mensaje de éxito
        header("Location: ../../VISTA/VOLUNTARIO/dashboard_voluntario.php?mensaje=inscripcion_exitosa&proyecto_id=" . $proyecto_id);
        exit();

    } catch (PDOException $e) {
        // Redirigir al dashboard del voluntario con un mensaje de error
        header("Location: ../../VISTA/VOLUNTARIO/dashboard_voluntario.php?mensaje=inscripcion_error&proyecto_id=" . $proyecto_id . "&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirigir al dashboard del voluntario si se intenta acceder al archivo directamente
    header("Location: ../../VISTA/VOLUNTARIO/dashboard_voluntario.php");
    exit();
}
?>
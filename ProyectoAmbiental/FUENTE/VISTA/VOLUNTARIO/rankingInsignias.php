<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\FUENTE\VISTA\VOLUNTARIO\rankingInsignias.php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_voluntario = $_SESSION['id_usuario'];

try {
    // Obtener información del voluntario
    $stmtVoluntario = $pdo->prepare("SELECT nombres, apellidos FROM usuarios WHERE id_usuario = :id");
    $stmtVoluntario->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
    $stmtVoluntario->execute();
    $voluntario = $stmtVoluntario->fetch(PDO::FETCH_ASSOC);

    if (!$voluntario) {
        throw new Exception("Voluntario no encontrado.");
    }

} catch (PDOException $e) {
    echo "Error al obtener los datos: " . htmlspecialchars($e->getMessage());
    exit();
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking e Insignias</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/voluntario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Ranking e Insignias</h1>
            <p>Voluntario: <strong><?php echo htmlspecialchars($voluntario['nombres'] . ' ' . $voluntario['apellidos']); ?></strong></p>
            <a href="dashboard_voluntario.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </header>

    <main class="main-content">
        <section class="ranking-insignias">
            <div class="contenedor">
                <h2><i class="fas fa-trophy"></i> Ranking de Voluntarios</h2>
                <ol>
                    <?php
                    // Obtener el ranking de voluntarios (ejemplo)
                    $stmtRanking = $pdo->prepare("SELECT u.nombres, u.apellidos, COUNT(pp.proyecto_id) AS total_proyectos FROM usuarios u INNER JOIN participacion_proyectos pp ON u.id_usuario = pp.usuario_id WHERE u.tipo_usuario_id = 1 GROUP BY u.id_usuario ORDER BY total_proyectos DESC LIMIT 5");
                    $stmtRanking->execute();
                    $ranking = $stmtRanking->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($ranking as $voluntarioRanking): ?>
                        <li><?php echo htmlspecialchars($voluntarioRanking['nombres'] . ' ' . $voluntarioRanking['apellidos'] . ' (' . $voluntarioRanking['total_proyectos'] . ' proyectos)'); ?></li>
                    <?php endforeach; ?>
                </ol>

                <h2><i class="fas fa-award"></i> Insignias</h2>
                <div class="insignias-container">
                    <?php
                    // Obtener las insignias del voluntario (ejemplo)
                    $stmtInsignias = $pdo->prepare("SELECT r.tipo_recompensa FROM recompensas r INNER JOIN voluntarios_recompensas vr ON r.recompensa_id = vr.recompensa_id WHERE vr.voluntario_id = :id_voluntario");
                    $stmtInsignias->bindParam(':id_voluntario', $id_voluntario, PDO::PARAM_INT);
                    $stmtInsignias->execute();
                    $insignias = $stmtInsignias->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($insignias)) {
                        foreach ($insignias as $insignia) {
                            // Mostrar la insignia como una imagen (reemplaza 'ruta_de_la_imagen' con la ruta real de la imagen)
                            echo '<img src="ruta_de_la_imagen/' . htmlspecialchars($insignia['tipo_recompensa']) . '.png" alt="' . htmlspecialchars($insignia['tipo_recompensa']) . '" class="insignia-imagen">';
                        }
                    } else {
                        echo '<p>No tienes insignias todavía.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
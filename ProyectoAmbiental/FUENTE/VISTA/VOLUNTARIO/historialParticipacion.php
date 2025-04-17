<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\FUENTE\VISTA\VOLUNTARIO\historialParticipacion.php
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

    // Obtener el historial de participación del voluntario
$stmtHistorial = $pdo->prepare("SELECT p.id_proyecto, p.nombre, p.descripcion, p.fecha_inicio, p.fecha_fin, p.estado, pp.fecha_union FROM proyectos p INNER JOIN participacion_proyectos pp ON p.id_proyecto = pp.proyecto_id WHERE pp.usuario_id = :id ORDER BY pp.fecha_union DESC");
$stmtHistorial->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
$stmtHistorial->execute();
$historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener los datos: " . htmlspecialchars($e->getMessage());
    exit();
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<style>
    /* Estilos para la página de historial de participación */
..historial {
    background-color: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--shadow);
}

.historial h2 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    color: var(--color-primary);
}

.historial table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.historial table th,
.historial table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.historial table th {
    background-color: var(--color-primary);
    color: white;
}

.historial table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.historial table tr:hover {
    background-color: #f1f1f1;
}

.btn-volver {
    display: inline-block;
    background-color: var(--color-secondary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    transition: var(--transition);
}

.btn-volver:hover {
    background-color: var(--color-primary);
}
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Participación</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/voluntario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Historial de Participación</h1>
            <p>Voluntario: <strong><?php echo htmlspecialchars($voluntario['nombres'] . ' ' . $voluntario['apellidos']); ?></strong></p>
            <div class="config-container">
                <button id="configBtn" class="btn-config">
                    <i class="fas fa-cog"></i> Configuración
                </button>
            </div>
            <a href="dashboard_voluntario.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </header>

    <main class="main-content">
        <section class="historial">
            <div class="contenedor">
                <h2><i class="fas fa-history"></i> Proyectos en los que has participado</h2>
                <?php if (!empty($historial)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre del Proyecto</th>
                                <th>Descripción</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Estado</th>
                                <th>Fecha de Unión</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $proyecto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($proyecto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_fin']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($proyecto['estado'])); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_union']); ?></td>
                                    <td>
                                    <a href="eventosProyectos.php?id_proyecto=<?php echo htmlspecialchars($proyecto['id_proyecto']); ?>">Ver Eventos</a>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No has participado en ningún proyecto todavía.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

        <!-- Panel de configuración deslizante -->
        <div id="configPanel" class="config-panel">
        <div class="config-content">
            <button class="close-config" id="closeConfigBtn">X</button>
            <h2>Configuración</h2>
            <h3><i class="fas fa-chart-bar"></i> Nivel</h3>
            <p>Nivel actual:
                <?php
                $stmtNivel = $pdo->prepare("SELECT nivel FROM usuarios WHERE id_usuario = :id_voluntario");
                $stmtNivel->bindParam(':id_voluntario', $id_voluntario, PDO::PARAM_INT);
                $stmtNivel->execute();
                $nivel = $stmtNivel->fetch(PDO::FETCH_ASSOC);
                echo htmlspecialchars($nivel['nivel']);
                ?>
            </p>
            <ul>
                <li><a href="editarPerfil.php"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                <li><a href="historialParticipacion.php"><i class="fas fa-history"></i> Historial de Participación</a></li>
                <li><a href="rankingInsignias.php"><i class="fas fa-trophy"></i> Ranking e Insignias</a></li>
                <li><a href="cambiarContraseña.php"><i class="fas fa-key"></i> Cambiar Contraseña</a></li>
                <li><a href="../../CONTROLADORES/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>

            <h3><i class="fas fa-star"></i> Puntos</h3>
            <p>Tienes:
                <?php
                $stmtPuntos = $pdo->prepare("SELECT puntos FROM usuarios WHERE id_usuario = :id_voluntario");
                $stmtPuntos->bindParam(':id_voluntario', $id_voluntario, PDO::PARAM_INT);
                $stmtPuntos->execute();
                $puntos = $stmtPuntos->fetch(PDO::FETCH_ASSOC);
                echo htmlspecialchars($puntos['puntos']);
                ?> puntos
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const configBtn = document.getElementById('configBtn');
            const configPanel = document.getElementById('configPanel');
            const closeConfigBtn = document.getElementById('closeConfigBtn');

            configBtn.addEventListener('click', () => {
                configPanel.classList.add('show');
            });

            closeConfigBtn.addEventListener('click', () => {
                configPanel.classList.remove('show');
            });
        });
    </script>

    
</body>
</html>
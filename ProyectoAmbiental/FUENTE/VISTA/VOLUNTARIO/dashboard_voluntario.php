<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\HTML\VOLUNTARIO\dashboard_voluntario.php
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

    // Obtener todos los proyectos disponibles (activos y completados)
    $stmtTodosProyectos = $pdo->prepare("SELECT * FROM proyectos WHERE estado IN ('activo', 'completado')");
    $stmtTodosProyectos->execute();
    $todosProyectos = $stmtTodosProyectos->fetchAll(PDO::FETCH_ASSOC);

    // Obtener proyectos a los que se ha unido el voluntario
    $stmtProyectos = $pdo->prepare("SELECT p.id_proyecto, p.nombre, p.descripcion, p.fecha_inicio, p.fecha_fin, p.estado FROM proyectos p INNER JOIN participacion_proyectos pp ON p.id_proyecto = pp.proyecto_id WHERE pp.usuario_id = :id");
    $stmtProyectos->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
    $stmtProyectos->execute();
    $proyectos = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Dashboard Voluntario</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/voluntario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Dashboard Voluntario</h1>
            <p>Bienvenido, <strong><?php echo htmlspecialchars($voluntario['nombres'] . ' ' . $voluntario['apellidos']); ?></strong></p>
            <div class="config-container">
                <button id="configBtn" class="btn-config">
                    <i class="fas fa-cog"></i> Configuración
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="info">
            <div class="contenedor">
                <p>Estás participando en <strong><?php echo count($proyectos); ?></strong> proyectos.</p>
            </div>
        </section>

        <section class="lista-proyectos">
            <div class="contenedor">
                <h2><i class="fas fa-project-diagram"></i> Explorar Proyectos Disponibles</h2>

                <?php
                // Mostrar mensaje de éxito si existe
                if (isset($_GET['mensaje'])) {
                    if ($_GET['mensaje'] == 'inscripcion_exitosa') {
                        echo '<p class="mensaje-exito">¡Te has inscrito correctamente al proyecto!</p>';
                    } elseif ($_GET['mensaje'] == 'ya_inscrito') {
                        echo '<p class="mensaje-error">Ya estás inscrito en este proyecto.</p>';
                    } elseif ($_GET['mensaje'] == 'inscripcion_error') {
                        echo '<p class="mensaje-error">Error al inscribirte: ' . htmlspecialchars($_GET['error']) . '</p>';
                    }
                }
                ?>

                <?php if (!empty($todosProyectos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todosProyectos as $proyecto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($proyecto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_fin']); ?></td>
                                    <td><?php echo ucfirst(htmlspecialchars($proyecto['estado'])); ?></td>
                                    <td>
                                        <?php
                                        // Verificar si el usuario ya está inscrito en el proyecto
                                        $inscrito = false;
                                        foreach ($proyectos as $proyectoInscrito) {
                                            if ($proyectoInscrito['id_proyecto'] == $proyecto['id_proyecto']) {
                                                $inscrito = true;
                                                break;
                                            }
                                        }
                                        ?>

                                        <form action="../../CONTROLADORES/VOLUNTARIO/inscribirseProyecto.php" method="POST">
                                            <input type="hidden" name="proyecto_id" value="<?php echo htmlspecialchars($proyecto['id_proyecto']); ?>">
                                            <button type="submit" class="btn-inscribirse" <?php if ($proyecto['estado'] == 'completado' || $inscrito) echo 'disabled'; ?>>
                                                <?php echo ($proyecto['estado'] == 'completado') ? 'Completado' : ($inscrito ? 'Inscrito' : 'Inscribirse'); ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay proyectos disponibles actualmente.</p>
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
        // Obtener el nivel del voluntario
        $stmtNivel = $pdo->prepare("SELECT nivel FROM usuarios WHERE id_usuario = :id_voluntario");
        $stmtNivel->bindParam(':id_voluntario', $id_voluntario, PDO::PARAM_INT);
        $stmtNivel->execute();
        $nivel = $stmtNivel->fetch(PDO::FETCH_ASSOC);

        echo htmlspecialchars($nivel['nivel']);
        ?>
    </p>
            <p>Ajusta tus preferencias aquí:</p>
            <ul>
                <li><a href="editarPerfil.php"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                <li><a href="historialParticipacion.php"><i class="fas fa-history"></i> Historial de Participación</a></li>
                <li><a href="rankingInsignias.php"><i class="fas fa-trophy"></i> Ranking e Insignias</a></li>
                <li><a href="cambiarContraseña.php"><i class="fas fa-key"></i> Cambiar Contraseña</a></li>
                <li><a href="../../CONTROLADORES/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>

            </ul>

            </ul>

            <!-- Puntos del voluntario -->
            <h3><i class="fas fa-star"></i> Puntos</h3>
            <p>Tienes:
                <?php
                // Obtener los puntos del voluntario
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

            // Abrir el panel de configuración
            configBtn.addEventListener('click', () => {
                configPanel.classList.add('show');
            });
            

            // Cerrar el panel de configuración
            closeConfigBtn.addEventListener('click', () => {
                configPanel.classList.remove('show');
            });

            // Confirmación de inscripción (opcional, mejora la experiencia del usuario)
            const botonesInscribirse = document.querySelectorAll('.btn-inscribirse');
            botonesInscribirse.forEach(boton => {
                boton.addEventListener('click', function(event) {
                    const proyectoNombre = this.closest('tr').querySelector('td:first-child').textContent;
                    const confirmacion = confirm(`¿Inscribirte al proyecto "${proyectoNombre}"?`);
                    if (!confirmacion) {
                        event.preventDefault(); // Evita que el formulario se envíe si el usuario cancela
                    }
                });
            });
        });
    </script>
</body>
</html>
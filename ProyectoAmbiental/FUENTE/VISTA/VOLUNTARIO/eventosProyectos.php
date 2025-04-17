<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\FUENTE\VISTA\VOLUNTARIO\eventosProyectos.php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_voluntario = $_SESSION['id_usuario'];
$id_proyecto = $_GET['id_proyecto'];

try {
    // Obtener información del voluntario
    $stmtVoluntario = $pdo->prepare("SELECT nombres, apellidos FROM usuarios WHERE id_usuario = :id");
    $stmtVoluntario->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
    $stmtVoluntario->execute();
    $voluntario = $stmtVoluntario->fetch(PDO::FETCH_ASSOC);

    if (!$voluntario) {
        throw new Exception("Voluntario no encontrado.");
    }

    // Obtener información del proyecto
    $stmtProyecto = $pdo->prepare("SELECT nombre FROM proyectos WHERE id_proyecto = :id_proyecto");
    $stmtProyecto->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
    $stmtProyecto->execute();
    $proyecto = $stmtProyecto->fetch(PDO::FETCH_ASSOC);

    if (!$proyecto) {
        throw new Exception("Proyecto no encontrado.");
    }

    // Obtener los eventos del proyecto
    $stmtEventos = $pdo->prepare("SELECT id_evento, nombre_evento, descripcion, fecha_evento, direccion_reunion FROM eventos WHERE proyecto_id = :id_proyecto");    $stmtEventos->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
    $stmtEventos->execute();
    $eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Eventos del Proyecto <?php echo htmlspecialchars($proyecto['nombre']); ?></title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/voluntario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos para la página de eventos del proyecto */
        .eventos {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        .eventos h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--color-primary);
        }

        .eventos table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .eventos table th,
        .eventos table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .eventos table th {
            background-color: var(--color-primary);
            color: white;
        }

        .eventos table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .eventos table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Eventos del Proyecto: <?php echo htmlspecialchars($proyecto['nombre']); ?></h1>
            <p>Voluntario: <strong><?php echo htmlspecialchars($voluntario['nombres'] . ' ' . $voluntario['apellidos']); ?></strong></p>
            <div class="config-container">
                <button id="configBtn" class="btn-config">
                    <i class="fas fa-cog"></i> Configuración
                </button>
            </div>
            <button type="button" onclick="history.back()" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver
            </button>
        </div>
    </header>

    <main class="main-content">
        <section class="eventos">
            <div class="contenedor">
                <h2><i class="fas fa-calendar-alt"></i> Lista de Eventos</h2>

                <?php
                // Mostrar mensaje de éxito si existe
                if (isset($_SESSION['mensaje_exito'])) {
                    echo '<p class="mensaje-exito">' . htmlspecialchars($_SESSION['mensaje_exito']) . '</p>';
                    unset($_SESSION['mensaje_exito']); // Eliminar la variable de sesión
                }

                // Mostrar mensaje de error si existe
                if (isset($_SESSION['mensaje_error'])) {
                    echo '<p class="mensaje-error">' . htmlspecialchars($_SESSION['mensaje_error']) . '</p>';
                    unset($_SESSION['mensaje_error']); // Eliminar la variable de sesión
                }
                ?>

                <?php if (!empty($eventos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventos as $evento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($evento['fecha_evento']); ?></td>
                                    <td><?php echo htmlspecialchars($evento['nombre_evento']); ?></td>
                                    <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($evento['direccion_reunion']); ?></td>

                                    <td>
                                        <?php
                                        // Verificar si el usuario ya ha confirmado su asistencia al evento
                                        $stmtVerificar = $pdo->prepare("SELECT * FROM participacion_eventos WHERE voluntario_id = :usuario_id AND evento_id = :evento_id");
                                        $stmtVerificar->bindParam(':usuario_id', $id_voluntario, PDO::PARAM_INT);
                                        $stmtVerificar->bindParam(':evento_id', $evento['id_evento'], PDO::PARAM_INT);
                                        $stmtVerificar->execute();
                                        $participacionExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

                                        if ($participacionExistente) {
                                            // Si el usuario ya ha confirmado su asistencia, mostrar un mensaje
                                            echo '<p>Asistencia Confirmada</p>';
                                        } else {
                                            // Si el usuario no ha confirmado su asistencia, mostrar un mensaje
                                            echo '<p>Asistencia Pendiente</p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay eventos disponibles para este proyecto.</p>
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
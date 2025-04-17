<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\HTML\ORGANIZADOR\eventos_organizador.php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_organizador = $_SESSION['id_usuario'];
$id_proyecto = $_GET['id_proyecto'];

try {
    // Consultar información del proyecto
    $stmtProyecto = $pdo->prepare("SELECT nombre FROM proyectos WHERE id_proyecto = :id_proyecto AND usuario_id = :id_organizador");
    $stmtProyecto->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
    $stmtProyecto->bindParam(':id_organizador', $id_organizador, PDO::PARAM_INT);
    $stmtProyecto->execute();
    $proyecto = $stmtProyecto->fetch(PDO::FETCH_ASSOC);

    if (!$proyecto) {
        throw new Exception("Proyecto no encontrado o no pertenece al organizador.");
    }

    // Consultar los eventos del proyecto
    $stmtEventos = $pdo->prepare("SELECT * FROM eventos WHERE proyecto_id = :id_proyecto ORDER BY fecha_evento DESC");
    $stmtEventos->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
    $stmtEventos->execute();
    $eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);

    // Consultar los nombres y apellidos del usuario
    $stmtUsuario = $pdo->prepare("SELECT nombres, apellidos FROM usuarios WHERE id_usuario = :id");
    $stmtUsuario->bindParam(':id', $id_organizador, PDO::PARAM_INT);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        throw new Exception("Usuario no encontrado.");
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
    <title>Eventos del Proyecto: <?php echo htmlspecialchars($proyecto['nombre']); ?></title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/organizador.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Eventos del Proyecto: <?php echo htmlspecialchars($proyecto['nombre']); ?></h1>
            <p>Organizador: <strong><?php echo htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']); ?></strong></p>
            <a href="historialProyectos.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Mis Proyectos
            </a>
        </div>
    </header>

    <main class="main-content">
        <section class="eventos">
            <div class="contenedor">
                <h2><i class="fas fa-calendar-alt"></i> Lista de Eventos</h2>
                <?php if (!empty($eventos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventos as $evento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($evento['nombre_evento']); ?></td>
                                    <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($evento['fecha_evento']); ?></td>
                                    <td><?php echo htmlspecialchars($evento['direccion_reunion']); ?></td>
                                    <td>
        <a href="verificarAsistencia.php?id_proyecto=<?php echo htmlspecialchars($evento['proyecto_id']); ?>&id_evento=<?php echo htmlspecialchars($evento['id_evento']); ?>">Verificar Asistencia</a>
    </td>
                                    <td>
                                        <button class="btn-editar-evento" onclick="editarEvento(<?php echo htmlspecialchars(json_encode($evento)); ?>)">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn-eliminar-evento" onclick="eliminarEvento(<?php echo htmlspecialchars($evento['id_evento']); ?>)">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay eventos disponibles para este proyecto.</p>
                <?php endif; ?>
                <button class="btn-agregar-evento" onclick="agregarEvento(<?php echo htmlspecialchars($id_proyecto); ?>)">
                    <i class="fas fa-plus"></i> Agregar Evento
                </button>
            </div>
        </section>
    </main>

    <!-- Modal para agregar/editar evento -->
    <div id="modalEvento" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModalBtn">&times;</span>
            <h2 id="modalTitle">Agregar Evento</h2>
            <form id="formEvento" action="../../CONTROLADORES/ORGANIZADOR/guardarEvento.php" method="POST">
                <input type="hidden" id="id_evento" name="id_evento">
                <input type="hidden" id="proyecto_id" name="proyecto_id" value="<?php echo htmlspecialchars($id_proyecto); ?>">

                <label for="nombre_evento">Nombre del Evento:</label>
                <input type="text" id="nombre_evento" name="nombre_evento" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>

                <label for="direccion_reunion">Dirección de Reunión:</label>
                <input type="text" id="direccion_reunion" name="direccion_reunion" required>

                <label for="fecha_evento">Fecha del Evento:</label>
                <input type="date" id="fecha_evento" name="fecha_evento" required>

                <button type="submit" class="btn btn-cta">Guardar Evento</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalEvento = document.getElementById('modalEvento');
            const closeModalBtn = document.getElementById('closeModalBtn');

            // Cerrar el modal al hacer clic en el botón de cierre
            closeModalBtn.addEventListener('click', () => {
                modalEvento.style.display = 'none';
            });

            // Cerrar el modal al hacer clic fuera del contenido del modal
            window.addEventListener('click', (event) => {
                if (event.target === modalEvento) {
                    modalEvento.style.display = 'none';
                }
            });
        });

        function agregarEvento(proyecto_id) {
            const modalEvento = document.getElementById('modalEvento');
            const modalTitle = document.getElementById('modalTitle');
            const formEvento = document.getElementById('formEvento');

            // Limpiar el formulario
            formEvento.reset();
            document.getElementById('id_evento').value = "";

            // Cambiar el título del modal
            modalTitle.textContent = "Agregar Evento";

            // Mostrar el modal
            modalEvento.style.display = 'block';
        }

        function editarEvento(evento) {
            const modalEvento = document.getElementById('modalEvento');
            const modalTitle = document.getElementById('modalTitle');
            const formEvento = document.getElementById('formEvento');

            // Rellenar el formulario con los datos del evento
            document.getElementById('id_evento').value = evento.id_evento;
            document.getElementById('nombre_evento').value = evento.nombre_evento;
            document.getElementById('descripcion').value = evento.descripcion;
            document.getElementById('direccion_reunion').value = evento.direccion_reunion;
            document.getElementById('fecha_evento').value = evento.fecha_evento;

            // Cambiar el título del modal
            modalTitle.textContent = "Editar Evento";

            // Mostrar el modal
            modalEvento.style.display = 'block';
        }

        function eliminarEvento(id_evento) {
            if (confirm("¿Estás seguro de que quieres eliminar este evento?")) {
                // Enviar una solicitud AJAX para eliminar el evento
                fetch('../../PHP/eliminarEvento.php?id_evento=' + id_evento)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recargar la página para actualizar la lista de eventos
                            location.reload();
                        } else {
                            alert("Error al eliminar el evento.");
                        }
                    });
            }
        }
    </script>
</body>
</html>
<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\HTML\ORGANIZADOR\dashboard_organizador.php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_organizador = $_SESSION['id_usuario'];

try {
    // Consultar todos los proyectos del organizador para la lista
    $stmtTodosProyectos = $pdo->prepare("SELECT * FROM proyectos WHERE usuario_id = :id");
    $stmtTodosProyectos->bindParam(':id', $id_organizador, PDO::PARAM_INT);
    $stmtTodosProyectos->execute();
    $todosProyectos = $stmtTodosProyectos->fetchAll(PDO::FETCH_ASSOC);

    // Consultar proyectos activos del organizador para el contador
    $stmtProyectosActivos = $pdo->prepare("SELECT * FROM proyectos WHERE usuario_id = :id AND estado = 'activo'");
    $stmtProyectosActivos->bindParam(':id', $id_organizador, PDO::PARAM_INT);
    $stmtProyectosActivos->execute();
    $proyectosActivos = $stmtProyectosActivos->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Dashboard Organizador</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/organizador.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Dashboard Organizador</h1>
            <p>Bienvenido, <strong><?php echo htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']); ?></strong></p>
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
                <p>Tienes <strong><?php echo count($proyectosActivos); ?></strong> proyectos activos.</p>
                <button id="btn-agregar" class="btn btn-cta">
                    <i class="fas fa-plus"></i> Agregar Proyecto
                </button>
            </div>
        </section>

        <section class="lista-proyectos">
            <div class="contenedor">
                <h2><i class="fas fa-project-diagram"></i> Mis Proyectos</h2>
                <?php if (!empty($todosProyectos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha Inicio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todosProyectos as $proyecto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($proyecto['id_proyecto']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_inicio']); ?></td>
                                    <td><?php echo ucfirst(htmlspecialchars($proyecto['estado'])); ?></td>
                                    <td>
                                        <button class="btn-editar" onclick="editarProyecto(<?php echo htmlspecialchars(json_encode($proyecto)); ?>)">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn-agregar-evento" onclick="abrirModalAgregarEvento(<?php echo htmlspecialchars($proyecto['id_proyecto']); ?>)">
                                            <i class="fas fa-plus"></i> Agregar Evento
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No tienes proyectos.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Modal para agregar proyecto -->
    <div id="modalAgregarProyecto" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModalBtn">&times;</span>
            <h2>Agregar Proyecto</h2>
            <form id="formAgregarProyecto" action="../../CONTROLADORES/ORGANIZADOR/registarProyecto.php" method="POST">
                <label for="nombre">Nombre del Proyecto:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>

                <label for="cupo">Cupo:</label>
                <input type="number" id="cupo" name="cupo">

                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>

                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>

                <button type="submit" class="btn btn-cta">Guardar Proyecto</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar proyecto -->
    <div id="modalEditarProyecto" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModalEditBtn">&times;</span>
            <h2>Editar Proyecto</h2>
            <form id="formEditarProyecto" action="../../CONTROLADORES/ORGANIZADOR/editarProyecto.php" method="POST">
                <input type="hidden" id="edit_id_proyecto" name="id_proyecto">
                
                <label for="edit_nombre">Nombre del Proyecto:</label>
                <input type="text" id="edit_nombre" name="nombre" required>

                <label for="edit_descripcion">Descripción:</label>
                <textarea id="edit_descripcion" name="descripcion" required></textarea>

                <label for="edit_cupo">Cupo:</label>
                <input type="number" id="edit_cupo" name="cupo" required>

                <label for="edit_fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="edit_fecha_inicio" name="fecha_inicio" required>

                <label for="edit_fecha_fin">Fecha de Fin:</label>
                <input type="date" id="edit_fecha_fin" name="fecha_fin" required>

                <label for="edit_estado">Estado:</label>
                <select id="edit_estado" name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="completado">Completado</option>
                    <option value="cancelado">Cancelado</option>
                </select>

                <button type="submit" class="btn btn-cta">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <!-- Modal para agregar evento -->
    <div id="modalAgregarEvento" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModalEventoBtn">&times;</span>
            <h2>Agregar Evento</h2>
            <form id="formAgregarEvento" action="../../CONTROLADORES/ORGANIZADOR/registrarEvento.php" method="POST">
                <input type="hidden" id="evento_proyecto_id" name="proyecto_id">

                <label for="nombre_evento">Nombre del Evento:</label>
                <input type="text" id="nombre_evento" name="nombre_evento" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>

                <label for="fecha_evento">Fecha del Evento:</label>
                <input type="date" id="fecha_evento" name="fecha_evento" required>

                <button type="submit" class="btn btn-cta">Guardar Evento</button>
            </form>
        </div>
    </div>

    <!-- Panel de configuración deslizante -->
    <div id="configPanel" class="config-panel">
        <div class="config-content">
            <button class="close-config" id="closeConfigBtn">X</button>
            <h2>Configuración</h2>
            <p>Ajusta tus preferencias aquí:</p>
            <ul>
                <li><a href="#">Cambiar Contraseña</a></li>
                <li><a href="#">Editar Perfil</a></li>
                <li><a href="#">Notificaciones</a></li>
                <li><a href="historialProyectos.php">Mis Proyectos</a></li>
                <li><a href="../../CONTROLADORES/logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnAgregar = document.getElementById('btn-agregar');
            const modalAgregarProyecto = document.getElementById('modalAgregarProyecto');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const configBtn = document.getElementById('configBtn');
            const configPanel = document.getElementById('configPanel');
            const closeConfigBtn = document.getElementById('closeConfigBtn');
            const closeModalEditBtn = document.getElementById('closeModalEditBtn');
            const modalEditarProyecto = document.getElementById('modalEditarProyecto');
            const closeModalEventoBtn = document.getElementById('closeModalEventoBtn');
            const modalAgregarEvento = document.getElementById('modalAgregarEvento');

            // Abrir el modal al hacer clic en el botón "Agregar Proyecto"
            btnAgregar.addEventListener('click', () => {
                modalAgregarProyecto.style.display = 'block';
            });

            // Cerrar el modal al hacer clic en el botón de cierre
            closeModalBtn.addEventListener('click', () => {
                modalAgregarProyecto.style.display = 'none';
            });

             // Cerrar el modal de edición al hacer clic en el botón de cierre
             closeModalEditBtn.addEventListener('click', () => {
                modalEditarProyecto.style.display = 'none';
            });

            // Cerrar el modal de agregar evento al hacer clic en el botón de cierre
            closeModalEventoBtn.addEventListener('click', () => {
                modalAgregarEvento.style.display = 'none';
            });

            // Cerrar el modal al hacer clic fuera del contenido del modal
            window.addEventListener('click', (event) => {
                if (event.target === modalAgregarProyecto) {
                    modalAgregarProyecto.style.display = 'none';
                }
                if (event.target === modalEditarProyecto) {
                    modalEditarProyecto.style.display = 'none';
                }
                if (event.target === modalAgregarEvento) {
                    modalAgregarEvento.style.display = 'none';
                }
            });

            // Abrir el panel de configuración
            configBtn.addEventListener('click', () => {
                configPanel.classList.add('show');
            });

            // Cerrar el panel de configuración
            closeConfigBtn.addEventListener('click', () => {
                configPanel.classList.remove('show');
            });

            // Mostrar mensajes de éxito o error
            <?php if (isset($_SESSION['success_message'])): ?>
                alert("<?php echo htmlspecialchars($_SESSION['success_message']); ?>");
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                alert("<?php echo htmlspecialchars($_SESSION['error_message']); ?>");
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            
        });

        

        function editarProyecto(proyecto) {
            const modalEditarProyecto = document.getElementById('modalEditarProyecto');
            modalEditarProyecto.style.display = 'block';

            // Rellenar los campos del formulario con los datos del proyecto
            document.getElementById('edit_id_proyecto').value = proyecto.id_proyecto;
            document.getElementById('edit_nombre').value = proyecto.nombre;
            document.getElementById('edit_descripcion').value = proyecto.descripcion;
            document.getElementById('edit_cupo').value = proyecto.cupo;
            document.getElementById('edit_fecha_inicio').value = proyecto.fecha_inicio;
            document.getElementById('edit_fecha_fin').value = proyecto.fecha_fin;
            document.getElementById('edit_estado').value = proyecto.estado;
        }

        function abrirModalAgregarEvento(proyecto_id) {
            const modalAgregarEvento = document.getElementById('modalAgregarEvento');
            modalAgregarEvento.style.display = 'block';

            // Asignar el ID del proyecto al campo oculto del formulario
            document.getElementById('evento_proyecto_id').value = proyecto_id;
        }
    </script>
</body>
</html>
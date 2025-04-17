<?php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_evento = $_GET['id_evento'];
$id_proyecto = $_GET['id_proyecto'];

try {
    // Obtener información del evento y proyecto
    $stmtEvento = $pdo->prepare("
        SELECT e.*, p.nombre as nombre_proyecto 
        FROM eventos e
        JOIN proyectos p ON e.proyecto_id = p.id_proyecto
        WHERE e.id_evento = :id_evento
    ");
    $stmtEvento->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
    $stmtEvento->execute();
    $evento = $stmtEvento->fetch(PDO::FETCH_ASSOC);

    // Obtener lista de voluntarios con información detallada
    $stmtVoluntarios = $pdo->prepare("
        SELECT 
            u.id_usuario,
            u.nombres,
            u.apellidos,
            u.puntos,
            pp.fecha_union,
            pe.fecha_confirmacion,
            pe.puntos_ganados,
            CASE 
                WHEN pe.confirmado = 1 THEN 'Confirmado'
                WHEN pe.confirmado = 0 THEN 'Pendiente'
                ELSE 'No registrado' 
            END as estado_asistencia
        FROM usuarios u
        INNER JOIN participacion_proyectos pp ON u.id_usuario = pp.usuario_id
        LEFT JOIN participacion_eventos pe ON u.id_usuario = pe.voluntario_id 
            AND pe.evento_id = :id_evento
        WHERE pp.proyecto_id = :id_proyecto AND u.tipo_usuario_id = 1
        ORDER BY u.apellidos, u.nombres
    ");
    $stmtVoluntarios->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
    $stmtVoluntarios->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
    $stmtVoluntarios->execute();
    $voluntarios = $stmtVoluntarios->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Asistencia - <?php echo htmlspecialchars($evento['nombre_evento']); ?></title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/organizador.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Verificar Asistencia</h1>
            <h2><?php echo htmlspecialchars($evento['nombre_evento']); ?></h2>
            <p>Proyecto: <?php echo htmlspecialchars($evento['nombre_proyecto']); ?></p>
            <p>Fecha: <?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?></p>
            <a href="eventosOrganizador.php?id_proyecto=<?php echo htmlspecialchars($id_proyecto); ?>" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Eventos
            </a>
        </div>
    </header>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Voluntarios</h3>
            <p><?php echo count($voluntarios); ?></p>
        </div>
        <div class="stat-box">
            <h3>Asistencias Confirmadas</h3>
            <p><?php echo array_reduce($voluntarios, function($carry, $item) {
                return $carry + ($item['estado_asistencia'] === 'Confirmado' ? 1 : 0);
            }, 0); ?></p>
        </div>
        <div class="stat-box">
            <h3>Pendientes</h3>
            <p><?php echo array_reduce($voluntarios, function($carry, $item) {
                return $carry + ($item['estado_asistencia'] !== 'Confirmado' ? 1 : 0);
            }, 0); ?></p>
        </div>
    </div>

    <main class="main-content">
        <section class="asistencia">
            <div class="contenedor">
                <h2><i class="fas fa-users"></i> Lista de Voluntarios</h2>
                <?php if (!empty($voluntarios)): ?>
                    <div class="table-responsive">
                        <table class="tabla-voluntarios">
                            <thead>
                                <tr>
                                    <th>Apellidos y Nombres</th>
                                    <th>Puntos Actuales</th>
                                    <th>Fecha Unión</th>
                                    <th>Estado</th>
                                    <th>Fecha Confirmación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($voluntarios as $voluntario): ?>
                                    <tr class="<?php echo strtolower($voluntario['estado_asistencia']); ?>">
                                        <td class="nombre-voluntario">
                                            <div class="voluntario-info">
                                                <span class="apellidos"><?php echo htmlspecialchars($voluntario['apellidos']); ?></span>,
                                                <span class="nombres"><?php echo htmlspecialchars($voluntario['nombres']); ?></span>
                                            </div>
                                        </td>
                                        <td class="puntos">
                                            <span class="badge badge-puntos">
                                                <?php echo htmlspecialchars($voluntario['puntos']); ?> pts
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($voluntario['fecha_union'])); ?>
                                        </td>
                                        <td class="estado">
                                            <span class="badge badge-<?php echo strtolower($voluntario['estado_asistencia']); ?>">
                                                <?php echo htmlspecialchars($voluntario['estado_asistencia']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($voluntario['fecha_confirmacion']): ?>
                                                <?php echo date('d/m/Y H:i', strtotime($voluntario['fecha_confirmacion'])); ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="acciones">
                                            <?php if ($voluntario['estado_asistencia'] != 'Confirmado'): ?>
                                                <button class="btn-confirmar" onclick="confirmarAsistencia(<?php echo $voluntario['id_usuario']; ?>, <?php echo $id_evento; ?>, this)">
                                                    <i class="fas fa-check"></i> Confirmar
                                                </button>
                                            <?php else: ?>
                                                <span class="confirmado-por">
                                                    <i class="fas fa-check-circle"></i> Confirmado
                                                    <?php if ($voluntario['puntos_ganados']): ?>
                                                        (+<?php echo $voluntario['puntos_ganados']; ?> pts)
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No hay voluntarios inscritos en este proyecto.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        function mostrarMensaje(tipo, mensaje) {
            const div = document.createElement('div');
            div.className = `alert alert-${tipo}`;
            div.textContent = mensaje;
            document.querySelector('.contenedor').insertBefore(div, document.querySelector('.table-responsive'));
            
            setTimeout(() => {
                div.remove();
            }, 3000);
        }

        // Reemplazar la función confirmarAsistencia existente con esta versión:
function confirmarAsistencia(voluntarioId, eventoId, boton) {
    if (boton.disabled) return; // Evitar múltiples clics

    if (confirm('¿Confirmar la asistencia de este voluntario?')) {
        // Deshabilitar el botón inmediatamente
        boton.disabled = true;
        boton.classList.add('btn-disabled');

        fetch('../../CONTROLADORES/ORGANIZADOR/registrarAsistencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `voluntario_id=${voluntarioId}&evento_id=${eventoId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje('success', 'Asistencia confirmada y puntos asignados correctamente');
                
                // Actualizar la interfaz sin recargar la página
                const fila = boton.closest('tr');
                const estadoCell = fila.querySelector('.estado');
                const accionesCell = fila.querySelector('.acciones');
                
                estadoCell.innerHTML = '<span class="badge badge-confirmado">Confirmado</span>';
                accionesCell.innerHTML = `
                    <span class="confirmado-por">
                        <i class="fas fa-check-circle"></i> Confirmado
                        (+1 pts)
                    </span>`;
                
                // Actualizar estadísticas
                actualizarEstadisticas();
            } else {
                mostrarMensaje('error', data.error || 'Error al confirmar la asistencia');
                // Reactivar el botón en caso de error
                boton.disabled = false;
                boton.classList.remove('btn-disabled');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('error', 'Error al procesar la solicitud');
            // Reactivar el botón en caso de error
            boton.disabled = false;
            boton.classList.remove('btn-disabled');
        });
    }
}

// Agregar función para actualizar estadísticas
function actualizarEstadisticas() {
    const totalVoluntarios = document.querySelectorAll('.tabla-voluntarios tbody tr').length;
    const confirmados = document.querySelectorAll('.badge-confirmado').length;
    const pendientes = totalVoluntarios - confirmados;

    document.querySelector('.stat-box:nth-child(2) p').textContent = confirmados;
    document.querySelector('.stat-box:nth-child(3) p').textContent = pendientes;
}
    </script>
</body>
</html>
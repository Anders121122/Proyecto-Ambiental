<?php
// filepath: c:\xampp1\htdocs\ProyectoAmbiental\HTML\ORGANIZADOR\proyectos_organizador.php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_organizador = $_SESSION['id_usuario'];

try {
    // Consultar todos los proyectos del organizador
    $stmtProyectos = $pdo->prepare("SELECT * FROM proyectos WHERE usuario_id = :id_organizador ORDER BY fecha_inicio DESC");
    $stmtProyectos->bindParam(':id_organizador', $id_organizador, PDO::PARAM_INT);
    $stmtProyectos->execute();
    $proyectos = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Mis Proyectos</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/organizador.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Mis Proyectos</h1>
            <p>Organizador: <strong><?php echo htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']); ?></strong></p>
            <a href="dashboard_organizador.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </header>

    <main class="main-content">
        <section class="proyectos">
            <div class="contenedor">
                <h2><i class="fas fa-project-diagram"></i> Proyectos Creados</h2>
                <?php if (!empty($proyectos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Estado</th>
                                <th>Eventos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($proyecto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['fecha_fin']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($proyecto['estado'])); ?></td>
                                    <td>
                                        <a href="eventosOrganizador.php?id_proyecto=<?php echo htmlspecialchars($proyecto['id_proyecto']); ?>">Ver/Editar Eventos</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No has creado ningún proyecto todavía.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
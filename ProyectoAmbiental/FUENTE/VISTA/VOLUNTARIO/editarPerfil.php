<?php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_voluntario = $_SESSION['id_usuario'];

try {
    // Obtener información del voluntario
    $stmtVoluntario = $pdo->prepare("SELECT nombres, apellidos, correo FROM usuarios WHERE id_usuario = :id");
    $stmtVoluntario->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
    $stmtVoluntario->execute();
    $voluntario = $stmtVoluntario->fetch(PDO::FETCH_ASSOC);

    if (!$voluntario) {
        throw new Exception("Voluntario no encontrado.");
    }

    // Procesar el formulario de edición de perfil
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombres = $_POST["nombres"];
        $apellidos = $_POST["apellidos"];
        $correo = $_POST["correo"];

        // Validar los datos ingresados
        if (empty($nombres) || empty($apellidos) || empty($correo)) {
            $mensaje_error = "Todos los campos son obligatorios.";
        } else {
            // Actualizar la información del voluntario en la base de datos
            $stmtActualizar = $pdo->prepare("UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, correo = :correo WHERE id_usuario = :id");
            $stmtActualizar->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmtActualizar->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmtActualizar->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmtActualizar->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
            $stmtActualizar->execute();

            // Redirigir al dashboard del voluntario con un mensaje de éxito
            header("Location: dashboard_voluntario.php?mensaje=perfil_actualizado");
            exit();
        }
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
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/voluntario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Editar Perfil</h1>
            <p>Voluntario: <strong><?php echo htmlspecialchars($voluntario['nombres'] . ' ' . $voluntario['apellidos']); ?></strong></p>
            <a href="dashboard_voluntario.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </header>

    <main class="main-content">
        <section class="editar-perfil">
            <div class="contenedor">
                <h2><i class="fas fa-user-edit"></i> Editar tu información</h2>
                <?php if (isset($mensaje_error)): ?>
                    <p class="mensaje-error"><?php echo htmlspecialchars($mensaje_error); ?></p>
                <?php endif; ?>
                <form action="editarPerfil.php" method="POST">
                    <label for="nombres">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($voluntario['nombres']); ?>">

                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($voluntario['apellidos']); ?>">

                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($voluntario['correo']); ?>">

                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
<?php
session_start();
include("../../CONTROLADORES/conexion.php");

$id_voluntario = $_SESSION['id_usuario'];

try {
    // Obtener información del voluntario
    $stmtVoluntario = $pdo->prepare("SELECT nombres, apellidos, contraseña FROM usuarios WHERE id_usuario = :id");
    $stmtVoluntario->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
    $stmtVoluntario->execute();
    $voluntario = $stmtVoluntario->fetch(PDO::FETCH_ASSOC);

    if (!$voluntario) {
        throw new Exception("Voluntario no encontrado.");
    }

    // Procesar el formulario de cambio de contraseña
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $contraseña_actual = $_POST["contraseña_actual"];
        $nueva_contraseña = $_POST["nueva_contraseña"];
        $confirmar_contraseña = $_POST["confirmar_contraseña"];

        // Validar los datos ingresados
        if (empty($contraseña_actual) || empty($nueva_contraseña) || empty($confirmar_contraseña)) {
            $mensaje_error = "Todos los campos son obligatorios.";
        } elseif (!password_verify($contraseña_actual, $voluntario['contraseña'])) {
            $mensaje_error = "La contraseña actual es incorrecta.";
        } elseif (strlen($nueva_contraseña) < 8) {
            $mensaje_error = "La nueva contraseña debe tener al menos 8 caracteres.";
        } elseif ($nueva_contraseña !== $confirmar_contraseña) {
            $mensaje_error = "La nueva contraseña y la confirmación no coinciden.";
        } else {
            // Encriptar la nueva contraseña
            $nueva_contraseña_encriptada = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

            // Actualizar la contraseña del voluntario en la base de datos
            $stmtActualizar = $pdo->prepare("UPDATE usuarios SET contraseña = :contraseña WHERE id_usuario = :id");
            $stmtActualizar->bindParam(':contraseña', $nueva_contraseña_encriptada, PDO::PARAM_STR);
            $stmtActualizar->bindParam(':id', $id_voluntario, PDO::PARAM_INT);
            $stmtActualizar->execute();

            // Redirigir al dashboard del voluntario con un mensaje de éxito
            header("Location: dashboard_voluntario.php?mensaje=contraseña_actualizada");
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
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="../../../SERVICIOS/CSS/voluntario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="contenedor">
            <h1>Cambiar Contraseña</h1>
            <p>Voluntario: <strong><?php echo htmlspecialchars($voluntario['nombres'] . ' ' . $voluntario['apellidos']); ?></strong></p>
            <a href="dashboard_voluntario.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </header>

    <main class="main-content">
        <section class="cambiar-contraseña">
            <div class="contenedor">
                <h2><i class="fas fa-key"></i> Cambiar tu contraseña</h2>
                <?php if (isset($mensaje_error)): ?>
                    <p class="mensaje-error"><?php echo htmlspecialchars($mensaje_error); ?></p>
                <?php endif; ?>
                <form action="cambiarContraseña.php" method="POST">
                    <label for="contraseña_actual">Contraseña Actual:</label>
                    <input type="password" id="contraseña_actual" name="contraseña_actual">

                    <label for="nueva_contraseña">Nueva Contraseña:</label>
                    <input type="password" id="nueva_contraseña" name="nueva_contraseña">

                    <label for="confirmar_contraseña">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="confirmar_contraseña" name="confirmar_contraseña">

                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
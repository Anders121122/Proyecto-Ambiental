<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Guardar el correo ingresado en la sesión
    $_SESSION['login_email'] = $email;

    // Validar campos vacíos
    if (empty($email) || empty($password)) {
        $_SESSION['error_login'] = 'Todos los campos son obligatorios.';
        header('Location: ../../Index.php');
        exit();
    }

    // Consultar la base de datos
    $query = "SELECT id_usuario, nombres, apellidos, correo, contraseña, tipo_usuario_id 
              FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':correo' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar credenciales
    if ($usuario && password_verify($password, $usuario['contraseña'])) {
        unset($_SESSION['login_email']); // Limpiar el correo de la sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombres'];
        $_SESSION['apellido'] = $usuario['apellidos'];
        $_SESSION['email'] = $usuario['correo'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario_id'];
    
        // Redirigir según el tipo de usuario
        switch ($usuario['tipo_usuario_id']) {
            case 1: // Voluntario
                header("Location: ../VISTA/VOLUNTARIO/dashboard_voluntario.php");
                break;
            case 2: // Organizador
                header("Location: ../VISTA/ORGANIZADOR/dashboard_organizador.php");
                break;
            case 3: // ADMIN
                header("Location: ../VISTA/ADMIN/dashboard_admin.php");
                break;
            default:
                $_SESSION['error_login'] = "Tipo de usuario desconocido.";
                header("Location: ../../Index.php");
                exit();
        }
        exit();
    } else {
        $_SESSION['error_login'] = 'Correo o contraseña incorrectos.';
        header('Location: ../../Index.php');
        exit();
    }
} else {
    $_SESSION['error_login'] = 'Método de solicitud no válido.';
    header('Location: ../../Index.php');
    exit();
}
?>
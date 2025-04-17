<?php
session_start();
include 'conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Depuración: Imprimir los datos recibidos
    error_log("Datos POST recibidos: " . print_r($_POST, true));

    // Validar y limpiar datos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirmar = $_POST['password_confirmar'] ?? '';
    $tipo_usuario = trim($_POST['tipo_usuario'] ?? '');

    // Guardar datos para rellenar el formulario en caso de error
    $_SESSION['registro_datos'] = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email,
        'tipo_usuario' => $tipo_usuario
    ];

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($password_confirmar)) {
        $_SESSION['error_registro'] = 'Todos los campos son obligatorios.';
        header('Location: ../../Index.php');
        exit();
    }

    // Validar formato de correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_registro'] = 'El formato del correo electrónico no es válido.';
        header('Location: ../../Index.php');
        exit();
    }

    // Validar que las contraseñas coincidan
    if ($password !== $password_confirmar) {
        $_SESSION['error_registro'] = 'Las contraseñas no coinciden.';
        header('Location: ../../Index.php');
        exit();
    }

    // Validar requisitos de la contraseña
    $errores_password = [];
    
    if (strlen($password) < 8) {
        $errores_password[] = "La contraseña debe tener al menos 8 caracteres. ";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errores_password[] = "Debe contener al menos una letra mayúscula. ";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errores_password[] = "Debe contener al menos una letra minúscula. ";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errores_password[] = "Debe contener al menos un número. ";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errores_password[] = "Debe contener al menos un carácter especial (!@#$%^&*(),.?\":{}|<>). ";
    }

    if (!empty($errores_password)) {
        $_SESSION['error_registro'] = 'La contraseña debe cumplir con los siguientes requisitos: ' . 
                                    implode($errores_password);
        header('Location: ../../Index.php');
        exit();
    }

    // Verificar si el correo ya existe
    try {
        $query_check = "SELECT id_usuario FROM usuarios WHERE correo = :correo";
        $stmt_check = $pdo->prepare($query_check);
        $stmt_check->execute([':correo' => $email]);

        if ($stmt_check->fetch()) {
            $_SESSION['error_registro'] = 'El correo ya está registrado.';
            header('Location: ../../Index.php');
            exit();
        }

        // Insertar usuario
        $query = "INSERT INTO usuarios (nombres, apellidos, correo, contraseña, tipo_usuario_id) 
                VALUES (:nombres, :apellidos, :correo, :password, :tipo_usuario_id)";
        
        $stmt = $pdo->prepare($query);
        
        // Depuración: Imprimir la consulta y los valores
        error_log("Query: " . $query);
        error_log("Valores a insertar: " . print_r([
            'nombres' => $nombre,
            'apellidos' => $apellido,
            'correo' => $email,
            'password' => $hashed_password,
            'tipo_usuario_id' => $tipo_usuario_id
        ], true));

        $resultado = $stmt->execute([
            ':nombres' => $nombre,
            ':apellidos' => $apellido,
            ':correo' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':tipo_usuario_id' => ($tipo_usuario === 'voluntario') ? 1 : 2
        ]);

        if ($resultado) {
            $_SESSION['success_login'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
            // Limpiar datos temporales
            unset($_SESSION['registro_datos']);
            header('Location: ../../Index.php');
            exit();
        } else {
            throw new PDOException("Error al ejecutar la consulta");
        }

    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        $_SESSION['error_registro'] = 'Error al registrar el usuario: ' . $e->getMessage();
        header('Location: ../../Index.php');
        exit();
    }
} else {
    $_SESSION['error_registro'] = 'Método de solicitud no válido.';
    header('Location: ../../Index.php');
    exit();
}
?>
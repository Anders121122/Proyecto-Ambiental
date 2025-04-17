<?php
$host = 'localhost';       // Nombre del host, usualmente localhost
$dbname = 'proyecto_ambiental';  // Nombre de la base de datos
$username = 'root';        // Nombre de usuario de la base de datos
$password = '';            // Contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuración para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8mb4"); // Asegura la compatibilidad con caracteres especiales
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>

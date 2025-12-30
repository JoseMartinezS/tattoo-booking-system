<?php
// conexion.php
// Configuración de la conexión a la base de datos

$host = "localhost";      // servidor (si usas hosting puede cambiar)
$user = "root";     // usuario de la base de datos
$password = ""; // contraseña del usuario
$database = "estudio_miguel"; // nombre de la base de datos

try {
    // Conexión con PDO (más seguro que mysqli)
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
    // Configurar errores para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

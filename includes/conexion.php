<?php
// conexion.php
// Configuración de la conexión a la base de datos

$host = "sql103.infinityfree.com";      // servidor (si usas hosting puede cambiar)
$user = "if0_40869187";     // usuario de la base de datos
$password = "c5E5OnYLpSDQ"; // contraseña del usuario
$database = "if0_40869187_estudio_miguel"; // nombre de la base de datos

try {
    // Conexión con PDO (más seguro que mysqli)
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
    // Configurar errores para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<?php
// conexion.php
// Configuración dinámica según entorno

if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
    // Configuración LOCAL
    $host     = "localhost";
    $user     = "root";               // tu usuario local
    $password = "";                   // tu contraseña local (si tienes)
    $database = "estudio_miguel";     // tu base local
} else {
    // Configuración SERVIDOR (InfinityFree)
    $host     = "sql103.infinityfree.com";
    $user     = "if0_40869187";
    $password = "c5E5OnYLpSDQ";
    $database = "if0_40869187_estudio_miguel";
}

try {
    // Conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

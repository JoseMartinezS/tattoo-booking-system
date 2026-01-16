<?php
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    define('BASE_URL', '/web/Miguel/');  // ruta real en localhost
} else {
    define('BASE_URL', '/');             // producción
}

define('BASE_PATH', __DIR__ . '/');
?>

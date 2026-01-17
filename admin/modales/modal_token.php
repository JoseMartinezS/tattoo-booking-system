<?php
session_start();
if (!isset($_SESSION['admin'])) {
    exit("Acceso denegado");
}
include '../../includes/conexion.php';

// Generar token único con expiración
$token  = bin2hex(random_bytes(16));
$expira = date("Y-m-d H:i:s", strtotime("+1 day"));

$stmt = $pdo->prepare("INSERT INTO tokens (token, expira) VALUES (:token, :expira)");
$stmt->execute([
    ':token'   => $token,
    ':expira'  => $expira
]);

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host      = $_SERVER['HTTP_HOST'];
$uri       = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // ej. /web/Miguel/admin/modales

// Subir desde /admin/... al raíz del proyecto (/web/Miguel) y apuntar a /agenda/agendar.php
$base = preg_replace('#/admin(/.*)?$#', '', $uri);
$link = "$protocolo://$host$base/agenda/agendar.php?token=$token";


echo "<p>Comparte este enlace con el cliente:</p>";
echo "<div class='copy-container'>
        <input type='text' id='tokenLinkInput' value='$link' readonly>
        <button onclick='copiarLink()' class='btn-copy'>
          📋 Copiar
        </button>
      </div>";


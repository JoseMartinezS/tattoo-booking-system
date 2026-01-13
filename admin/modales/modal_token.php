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

$link = "http://localhost/web/Miguel/agenda/agendar.php?token=$token";

echo "<p>Comparte este enlace con el cliente:</p>";
echo "<div class='copy-container'>
        <input type='text' id='tokenLinkInput' value='$link' readonly>
        <button onclick='copiarLink()' class='btn-copy'>
          📋 Copiar
        </button>
      </div>";


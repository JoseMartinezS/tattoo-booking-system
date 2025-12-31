<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../includes/conexion.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE disponibilidad SET disponible = NOT disponible WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
header("Location: disponibilidad.php");
exit;

<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    http_response_code(403);
    exit("Acceso denegado");
}

include '../includes/conexion.php';

// Validar datos recibidos
$id   = $_POST['id']   ?? null;
$nota = $_POST['nota'] ?? null;

if (!$id) {
    http_response_code(400);
    exit("ID de cita no proporcionado");
}

try {
    $stmt = $pdo->prepare("UPDATE citas SET nota = :nota WHERE id = :id");
    $stmt->execute([
        ':nota' => $nota,
        ':id'   => $id
    ]);

    echo "Nota guardada correctamente";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al guardar la nota: " . $e->getMessage();
}

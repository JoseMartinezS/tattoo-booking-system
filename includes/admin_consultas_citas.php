<?php
// Contadores de estados
$totalPendientes = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'pendiente'")->fetchColumn();
$totalConfirmadas = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'confirmada'")->fetchColumn();
$totalCanceladas = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'cancelada'")->fetchColumn();

// Paginación
$registrosPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Filtro de fechas
$filtro = $_GET['filtro'] ?? 'todas';
switch ($filtro) {
    case 'hoy':
        $condicionFecha = "fecha = CURDATE()";
        break;
    case 'semana':
        $condicionFecha = "fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        break;
    case 'mes':
        $condicionFecha = "fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
        break;
    case 'todas':
        // 👇 aquí limitamos a solo citas del último mes hacia adelante
        $condicionFecha = "fecha >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        break;
    default:
        $condicionFecha = "1=1";
}


// Filtro por estado
$estado = $_GET['estado'] ?? '';
if ($estado) {
    $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * 
                           FROM citas 
                           WHERE estado = :estado AND $condicionFecha
                           ORDER BY fecha, hora 
                           LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $registrosPorPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * 
                           FROM citas 
                           WHERE $condicionFecha
                           ORDER BY fecha, hora 
                           LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $registrosPorPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de registros para calcular páginas
$totalRegistros = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Vista seleccionada
$vista = $_GET['vista'] ?? 'todas';

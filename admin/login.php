<?php
session_start();
include '../includes/conexion.php'; // conexión a BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Buscar usuario en BD
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
    $stmt->execute([':usuario' => $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['isadmin'] == 1) {
            $_SESSION['admin'] = $user['usuario'];
            header("Location: indexadmin.php");
            exit;
        } else {
            $error = "No tienes permisos de administrador.";
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_login.css">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <main class="contenedor">
  <h1>Acceso al Panel</h1>
  <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="POST">
    <label for="usuario">Usuario</label>
    <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>

    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>

    <button type="submit">Ingresar</button>
  </form>
  </main>


  <?php include '../includes/footer.php'; ?>

</body>
</html>

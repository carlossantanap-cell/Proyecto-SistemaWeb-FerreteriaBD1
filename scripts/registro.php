<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni']; // Esto se usará para loguearse
    $telefono = $_POST['telefono'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $sql = "INSERT INTO Usuarios (Nombre, Dni, Telefono, Password, Rol) VALUES (?, ?, ?, ?, 'Cliente')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $dni, $telefono, $password]);
        header("Location: login.php?registrado=1");
    } catch (PDOException $e) {
        $error = "El DNI ya está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center">Registro de Cliente</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3"><label>DNI (Usuario)</label><input type="text" name="dni" class="form-control" required></div>
            <div class="mb-3"><label>Nombre Completo</label><input type="text" name="nombre" class="form-control" required></div>
            <div class="mb-3"><label>Teléfono</label><input type="text" name="telefono" class="form-control" required></div>
            <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>
        </form>
        <div class="text-center mt-3"><a href="login.php">Volver al Login</a></div>
    </div>
</body>
</html>
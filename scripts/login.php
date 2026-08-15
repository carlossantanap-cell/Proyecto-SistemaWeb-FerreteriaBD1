<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE Dni = ?");
    $stmt->execute([$dni]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['Password'])) {
        $_SESSION['id'] = $usuario['IdUsuario'];
        $_SESSION['nombre'] = $usuario['Nombre'];
        $_SESSION['rol'] = $usuario['Rol'];
        header("Location: index.php");
        exit;
    } else {
        $error = "DNI o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card p-4 shadow" style="width: 350px;">
        <h3 class="text-center">Login Ferretería</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3"><label>DNI</label><input type="text" name="dni" class="form-control" required></div>
            <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-dark w-100">Ingresar</button>
        </form>
        <div class="text-center mt-3"><a href="registro.php">Registrarse como Cliente</a></div>
    </div>
</body>
</html>
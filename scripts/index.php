<?php 
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit; }
include 'conexion.php'; 

// LÓGICA DE CARRITO (Solo Clientes)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_carrito'])) {
    $idProd = $_POST['id_producto'];
    $nombreProd = $_POST['nombre_producto'];
    $precioProd = $_POST['precio_producto'];
    $cantidad = $_POST['cantidad'];

    // Creamos el carrito en la sesión si no existe
    if (!isset($_SESSION['carrito'])) { $_SESSION['carrito'] = []; }

    // Agregamos el producto
    $item = [
        'id' => $idProd,
        'nombre' => $nombreProd,
        'precio' => $precioProd,
        'cantidad' => $cantidad,
        'subtotal' => $precioProd * $cantidad
    ];
    array_push($_SESSION['carrito'], $item);
    $mensaje = "Producto agregado al carrito.";
}

// Consultar Productos
$sql = "SELECT p.*, c.Nombre as Categoria, pr.RazonSocial as Proveedor 
        FROM Productos p 
        JOIN Categorias c ON p.IdCategoria = c.IdCategoria
        JOIN Proveedores pr ON p.IdProveedor = pr.IdProveedor";
$query = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ferretería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .footer-integrantes { margin-top: 50px; padding: 20px; background-color: #343a40; color: white; text-align: center; border-radius: 10px;} </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-4">
        <span class="navbar-brand">🛠️ Ferretería</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">Hola, <?php echo $_SESSION['nombre']; ?> (<?php echo $_SESSION['rol']; ?>)</span>
            
            <?php if ($_SESSION['rol'] == 'Cliente') { ?>
                <a href="carrito.php" class="btn btn-warning">
                    🛒 Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)
                </a>
            <?php } ?>
            
            <?php if ($_SESSION['rol'] == 'Admin') { ?>
                <a href="ver_clientes.php" class="btn btn-info">👥 Ver Clientes y Ventas</a>
            <?php } ?>

            <a href="logout.php" class="btn btn-outline-danger btn-sm">Salir</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if(isset($mensaje)) echo "<div class='alert alert-success'>$mensaje</div>"; ?>
        
        <div class="d-flex justify-content-between mb-3">
            <h3>Productos Disponibles</h3>
            <?php if ($_SESSION['rol'] == 'Admin') { ?>
                <a href="crear.php" class="btn btn-primary">+ Nuevo Producto</a>
            <?php } ?>
        </div>

        <div class="row">
            <?php while ($row = $query->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['Nombre']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['Categoria']; ?></h6>
                            <p class="card-text">Precio: <strong class="text-success">S/ <?php echo number_format($row['Precio'], 2); ?></strong></p>
                            <p>Stock: <?php echo $row['Stock']; ?></p>
                            
                            <?php if ($_SESSION['rol'] == 'Cliente') { ?>
                                <form method="POST">
                                    <input type="hidden" name="id_producto" value="<?php echo $row['IdProducto']; ?>">
                                    <input type="hidden" name="nombre_producto" value="<?php echo $row['Nombre']; ?>">
                                    <input type="hidden" name="precio_producto" value="<?php echo $row['Precio']; ?>">
                                    
                                    <div class="input-group mb-3">
                                        <input type="number" name="cantidad" class="form-control" value="1" min="1" max="<?php echo $row['Stock']; ?>" required>
                                        <button class="btn btn-success" type="submit" name="agregar_carrito">Comprar</button>
                                    </div>
                                </form>
                            <?php } ?>

                            <?php if ($_SESSION['rol'] == 'Admin') { ?>
                                <div class="d-grid gap-2">
                                    <a href="editar.php?id=<?php echo $row['IdProducto']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="eliminar.php?id=<?php echo $row['IdProducto']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Borrar?');">Eliminar</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <div class="footer-integrantes">
            <h5>Equipo de Desarrollo</h5>
            <div class="d-flex justify-content-around">
                <p>Santana Palomino Carlos Henry</p>
                <p>Achalma Galindo Alexandro</p>
                <p>Gonzales Avendaño Juan Carlos Matias</p>
            </div>
            <small>Proyecto Base de Datos I - 2025</small>
        </div>
    </div>
</body>
</html>
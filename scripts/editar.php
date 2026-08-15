<?php
include 'conexion.php';

// 1. VALIDACIÓN: ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// 2. LÓGICA DE ACTUALIZACIÓN
// Si el usuario dio clic en "Actualizar"...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];
    $proveedor = $_POST['proveedor'];

    $sql = "UPDATE Productos SET Nombre=?, Precio=?, Stock=?, IdCategoria=?, IdProveedor=? WHERE IdProducto=?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$nombre, $precio, $stock, $categoria, $proveedor, $id])) {
        header("Location: index.php"); // Regresar al inicio
    } else {
        echo "Error al actualizar.";
    }
}

// 3. CARGAR DATOS ACTUALES
// Buscamos el producto en la BD para llenar los inputs
$stmt = $conn->prepare("SELECT * FROM Productos WHERE IdProducto = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Cargar listas para los combos
$stmtCat = $conn->query("SELECT * FROM Categorias");
$stmtProv = $conn->query("SELECT * FROM Proveedores");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Editar Producto</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="<?php echo $producto['Nombre']; ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Precio:</label>
                            <input type="number" step="0.01" name="precio" class="form-control" 
                                   value="<?php echo $producto['Precio']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Stock:</label>
                            <input type="number" name="stock" class="form-control" 
                                   value="<?php echo $producto['Stock']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Categoría:</label>
                        <select name="categoria" class="form-select">
                            <?php while($cat = $stmtCat->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $cat['IdCategoria']; ?>" 
                                    <?php if($cat['IdCategoria'] == $producto['IdCategoria']) echo 'selected'; ?>>
                                    <?php echo $cat['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Proveedor:</label>
                        <select name="proveedor" class="form-select">
                            <?php while($prov = $stmtProv->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $prov['IdProveedor']; ?>"
                                    <?php if($prov['IdProveedor'] == $producto['IdProveedor']) echo 'selected'; ?>>
                                    <?php echo $prov['RazonSocial']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">Actualizar Producto</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include 'conexion.php';

// 1. LÓGICA PARA GUARDAR (INSERT)
// Si el usuario presionó el botón "Guardar"...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $idCategoria = $_POST['categoria'];
    $idProveedor = $_POST['proveedor'];

    try {
        $sql = "INSERT INTO Productos (Nombre, Precio, Stock, IdCategoria, IdProveedor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $precio, $stock, $idCategoria, $idProveedor]);
        
        // Si sale bien, regresamos a la lista principal
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al guardar: " . $e->getMessage();
    }
}

// 2. LÓGICA PARA LLENAR LOS COMBOS (SELECT)
// Traemos las categorías y proveedores para mostrarlos en la lista desplegable
$stmtCat = $conn->query("SELECT * FROM Categorias");
$stmtProv = $conn->query("SELECT * FROM Proveedores");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Nuevo Producto</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej: Llave Inglesa">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio (S/):</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Inicial:</label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría:</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            <?php while($cat = $stmtCat->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $cat['IdCategoria']; ?>">
                                    <?php echo $cat['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Proveedor:</label>
                        <select name="proveedor" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            <?php while($prov = $stmtProv->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $prov['IdProveedor']; ?>">
                                    <?php echo $prov['RazonSocial']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">💾 Guardar Producto</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
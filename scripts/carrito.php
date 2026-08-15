<?php
session_start();
include 'conexion.php';

// 1. LÓGICA: ELIMINAR PRODUCTO
if (isset($_POST['btn_eliminar'])) {
    $indice = $_POST['indice'];
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// 2. LÓGICA: ACTUALIZAR CANTIDAD (Ahora detecta el cambio automático)
if (isset($_POST['evento_actualizar'])) {
    $indice = $_POST['indice'];
    $nueva_cantidad = $_POST['nueva_cantidad'];
    
    if ($nueva_cantidad > 0) {
        $_SESSION['carrito'][$indice]['cantidad'] = $nueva_cantidad;
        $_SESSION['carrito'][$indice]['subtotal'] = $nueva_cantidad * $_SESSION['carrito'][$indice]['precio'];
    } else {
        // Si pone 0 o vacío, borramos el producto
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// 3. LÓGICA: FINALIZAR COMPRA
if (isset($_POST['finalizar_compra'])) {
    if (!empty($_SESSION['carrito'])) {
        try {
            $totalVenta = 0;
            foreach ($_SESSION['carrito'] as $prod) { $totalVenta += $prod['subtotal']; }

            $sqlVenta = "INSERT INTO Ventas (IdUsuario, Total) VALUES (?, ?)";
            $stmt = $conn->prepare($sqlVenta);
            $stmt->execute([$_SESSION['id'], $totalVenta]);
            $idVenta = $conn->lastInsertId();

            $sqlDetalle = "INSERT INTO Detalle_Venta (IdVenta, IdProducto, Cantidad, PrecioUnitario) VALUES (?, ?, ?, ?)";
            $stmtDetalle = $conn->prepare($sqlDetalle);
            $sqlStock = "UPDATE Productos SET Stock = Stock - ? WHERE IdProducto = ?";
            $stmtStock = $conn->prepare($sqlStock);

            foreach ($_SESSION['carrito'] as $prod) {
                $stmtDetalle->execute([$idVenta, $prod['id'], $prod['cantidad'], $prod['precio']]);
                $stmtStock->execute([$prod['cantidad'], $prod['id']]);
            }

            unset($_SESSION['carrito']);
            $exito = "¡Compra realizada con éxito! ID de Venta: " . $idVenta;

        } catch (Exception $e) {
            $error = "Error al procesar la compra: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Tu Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">🛒 Tu Carrito de Compras</h2>
        
        <?php if(isset($exito)) echo "<div class='alert alert-success'>$exito</div>"; ?>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <?php if (!empty($_SESSION['carrito'])) { ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th style="width: 150px;">Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($_SESSION['carrito'] as $i => $prod) { 
                                    $total += $prod['subtotal'];
                                ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo $prod['nombre']; ?></td>
                                        
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="indice" value="<?php echo $i; ?>">
                                                <input type="hidden" name="evento_actualizar" value="true">
                                                
                                                <input type="number" name="nueva_cantidad" class="form-control text-center" 
                                                       value="<?php echo $prod['cantidad']; ?>" min="1" 
                                                       onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        
                                        <td>S/ <?php echo number_format($prod['precio'], 2); ?></td>
                                        <td>S/ <?php echo number_format($prod['subtotal'], 2); ?></td>
                                        
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="indice" value="<?php echo $i; ?>">
                                                <button type="submit" name="btn_eliminar" class="btn btn-sm btn-danger">
                                                    🗑️ Quitar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr class="table-secondary">
                                    <td colspan="3" class="text-end fw-bold fs-5">TOTAL A PAGAR:</td>
                                    <td colspan="2" class="fw-bold fs-4 text-success">S/ <?php echo number_format($total, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-secondary btn-lg">⬅️ Seguir Comprando</a>
                <form method="POST">
                    <button type="submit" name="finalizar_compra" class="btn btn-success btn-lg">✅ Pagar y Finalizar</button>
                </form>
            </div>

        <?php } else { ?>
            <div class="alert alert-info text-center p-5">
                <h4>Tu carrito está vacío 😢</h4>
                <a href="index.php" class="btn btn-primary mt-3">Ir a Comprar</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>
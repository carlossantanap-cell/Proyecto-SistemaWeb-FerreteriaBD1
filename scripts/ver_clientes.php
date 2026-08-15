<?php
session_start();
// Seguridad: Solo admin
if ($_SESSION['rol'] != 'Admin') { header("Location: index.php"); exit; }
include 'conexion.php';

// Consulta poderosa: Une Usuarios, Ventas y Detalles para ver qué compró cada quién
$sql = "SELECT u.Dni, u.Nombre, u.Telefono, v.Fecha, p.Nombre as Producto, d.Cantidad, d.PrecioUnitario, v.Total
        FROM Usuarios u
        INNER JOIN Ventas v ON u.IdUsuario = v.IdUsuario
        INNER JOIN Detalle_Venta d ON v.IdVenta = d.IdVenta
        INNER JOIN Productos p ON d.IdProducto = p.IdProducto
        WHERE u.Rol = 'Cliente'
        ORDER BY v.Fecha DESC";

$query = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reporte de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👥 Reporte de Clientes y Compras</h2>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>DNI</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Fecha Compra</th>
                        <th>Producto Comprado</th>
                        <th>Cant.</th>
                        <th>Total Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $query->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $row['Dni']; ?></td>
                            <td><?php echo $row['Nombre']; ?></td>
                            <td><?php echo $row['Telefono']; ?></td>
                            <td><?php echo $row['Fecha']; ?></td>
                            <td class="text-primary fw-bold"><?php echo $row['Producto']; ?></td>
                            <td><?php echo $row['Cantidad']; ?></td>
                            <td>S/ <?php echo number_format($row['Total'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
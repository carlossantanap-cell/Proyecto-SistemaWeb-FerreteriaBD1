<?php
include 'conexion.php';

// 1. Validar que recibimos un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 2. Ejecutar el DELETE en SQL Server
        $sql = "DELETE FROM Productos WHERE IdProducto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        // 3. Redireccionar al index
        header("Location: index.php");
        
    } catch (PDOException $e) {
        // Si hay error (ej: intentas borrar un producto que ya tiene ventas registradas)
        echo "Error al eliminar: " . $e->getMessage();
        echo "<br><a href='index.php'>Volver</a>";
    }
} else {
    // Si alguien intenta entrar directo a eliminar.php sin ID
    header("Location: index.php");
}
?>
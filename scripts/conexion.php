<?php
// DATOS DE CONEXIÓN BASADOS EN TU IMAGEN
// Nota importante: En PHP, la barra invertida "\" se escribe doble "\\"
$nombreServidor = "localhost\\SQLEXPRESS01"; 
$nombreBaseDatos = "FerreteriaDB";

try {
    // Cadena de conexión usando Autenticación de Windows (sin usuario ni contraseña)
    // Esto funciona porque estás en tu propia PC
    $conn = new PDO("sqlsrv:server=$nombreServidor;Database=$nombreBaseDatos", NULL, NULL);
    
    // Configuración para que nos avise si hay errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Si ves este mensaje, ¡ya ganaste!
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; font-family: Arial, sans-serif;'>";
    echo "✅ <strong>¡ÉXITO TOTAL!</strong> Conexión establecida con la base de datos: <em>$nombreBaseDatos</em>";
    echo "</div>";
    
} catch (PDOException $e) {
    // Si falla, mostramos el error en rojo
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; font-family: Arial, sans-serif;'>";
    echo "❌ <strong>Error de Conexión:</strong> <br>" . $e->getMessage();
    echo "</div>";
}
?>
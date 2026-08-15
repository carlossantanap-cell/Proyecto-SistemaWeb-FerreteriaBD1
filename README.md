# 🛠️ Sistema Web para Ferretería

Sistema web de gestión de inventario y ventas para una ferretería, desarrollado como proyecto final del curso **Base de Datos I** (UNI, 2025).

Permite administrar productos, categorías y proveedores, y ofrece una experiencia de compra tipo e-commerce para los clientes registrados, con control de stock en tiempo real y reportes de ventas para el administrador.

## ✨ Funcionalidades

- **Autenticación por roles** (Admin / Cliente) con registro y login mediante DNI
- **CRUD de productos**: creación, edición y eliminación (solo Admin)
- **Catálogo de productos** con categoría, proveedor, precio y stock
- **Carrito de compras** con actualización de cantidades en tiempo real y cálculo automático de subtotales/total
- **Checkout transaccional**: al finalizar la compra se registra la venta, el detalle de venta y se descuenta el stock automáticamente
- **Reporte de clientes y ventas** (solo Admin): historial de compras por cliente con fecha, producto y monto

## 🧰 Tecnologías

- **Backend:** PHP (PDO)
- **Base de datos:** SQL Server
- **Frontend:** Bootstrap 5
- **Autenticación:** Sesiones PHP + hashing de contraseñas (`password_hash` / `password_verify`)

## 🗄️ Base de datos

Base de datos relacional normalizada en **3FN**, con las siguientes tablas principales:

- `Usuarios` (con rol Admin/Cliente)
- `Productos`, `Categorias`, `Proveedores`
- `Ventas` y `Detalle_Venta` (relación transaccional para el checkout)

El script de restauración se encuentra en [`database/ferreteriadb_backup.sql`](./database/ferreteriadb_backup.sql).

## 📁 Estructura del repositorio

```
├── database/
│   └── ferreteriadb_backup.sql   # Backup de la base de datos
├── scripts/
│   ├── conexion.php              # Conexión PDO a SQL Server
│   ├── index.php                 # Catálogo + lógica de carrito
│   ├── login.php / registro.php / logout.php
│   ├── crear.php / editar.php / eliminar.php   # CRUD de productos
│   ├── carrito.php               # Carrito y checkout
│   └── ver_clientes.php          # Reporte de ventas (Admin)
└── docs/
    ├── FerreteriaBD.pptx         # Presentación del proyecto
    └── Integrantes.txt
```

## ⚙️ Instalación y ejecución

1. Instalar **SQL Server** y el driver `sqlsrv` para PHP.
2. Restaurar la base de datos ejecutando `database/ferreteriadb_backup.sql` en SQL Server Management Studio.
3. En `scripts/conexion.php`, ajustar el nombre del servidor si es distinto a `localhost\SQLEXPRESS01`.
4. Copiar la carpeta `scripts/` a tu servidor local (XAMPP/WAMP) o correr con el servidor embebido de PHP:
   ```bash
   php -S localhost:8000
   ```
5. Abrir `login.php` en el navegador. Los nuevos usuarios pueden registrarse desde ahí (rol Cliente por defecto).

## 👥 Integrantes

- Santana Palomino, Carlos Henry
- Achalma Galindo, Alexandro
- Gonzales Avendaño, Juan Carlos Matias

*Proyecto desarrollado para el curso Base de Datos I — UNI, 2025.*

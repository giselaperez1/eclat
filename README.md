# Éclat Haute Couture

Tienda online de alta costura desarrollada como Proyecto Final del 
C.F.G.S. Desarrollo de Aplicaciones Web (DAW).

## Requisitos
- XAMPP 8.2 o superior (Apache + MySQL)
- Navegador web moderno
- Conexión a internet (para emails con EmailJS)

## Instalación
1. Copiar la carpeta `eclat` en `C:/xampp/htdocs/`
2. Arrancar Apache y MySQL en XAMPP
3. Crear base de datos `eclat_db` en phpMyAdmin
4. Importar el archivo `eclat_db.sql`
5. Acceder a `localhost/eclat/index.php`

## Credenciales admin
Editar el campo `rol` de cualquier usuario en phpMyAdmin 
y cambiarlo a `admin`.

## Tecnologías
- PHP 8 + MySQL
- HTML5 + CSS3 + JavaScript
- PDO para conexión a base de datos
- EmailJS para confirmación de pedidos

## Estructura del proyecto
- `config/` — conexión a la base de datos y control de acceso admin
- `models/` — clases Producto, Usuario y Pedido
- `controllers/` — lógica del carrito
- `views/` — panel de administración y layout
- `assets/` — imágenes y recursos estáticos

## Funcionalidades principales
- Catálogo con filtros por categoría, color y precio
- Buscador de productos
- Carrito de compra con gestión de stock
- Registro e inicio de sesión de clientes
- Panel de administración para gestionar productos, pedidos y clientes
- Email de confirmación de pedido mediante EmailJS

## Autora
Gisela Pérez Fernández
C.F.G.S. Desarrollo de Aplicaciones Web — 2025/2026

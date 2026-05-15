# Mapache Store - Sistema Web de Venta de Celulares

Proyecto académico desarrollado en PHP, MySQL, HTML, CSS, JavaScript y Bootstrap, organizado con patrón MVC.

## Módulos incluidos

- Landing page responsive
- Registro de usuarios
- Login seguro con `password_hash()` y `password_verify()`
- Autenticación 2FA simulada
- Roles: administrador y cliente
- CRUD de productos
- CRUD de categorías
- Galería de imágenes y videos por producto
- Carrito de compras editable
- Vaciar carrito completo
- Registro de ventas
- Detalle de ventas
- Historial de compras del cliente
- Sistema de favoritos
- Dashboard administrativo
- Control de stock
- Usuarios registrados

## Instalación en XAMPP

1. Copiar la carpeta `mapache-store` dentro de `C:\xampp\htdocs\`.
2. Iniciar Apache y MySQL en XAMPP.
3. Abrir:

```text
http://localhost/mapache-store/instalar.php
```

4. Entrar al sistema:

```text
http://localhost/mapache-store/public/
```

## Usuario administrador

```text
Correo: mapache@gmail.com
Contraseña: mapache3000
```

## Flujo GitHub recomendado

```bash
git add .
git commit -m "Crear estructura MVC de Mapache Store"
git push
```

## División sugerida para 3 estudiantes

- Estudiante 1: estructura MVC, base de datos, login, registro, 2FA.
- Estudiante 2: landing page, productos, categorías, buscador y detalle.
- Estudiante 3: carrito, favoritos, ventas, historial y dashboard admin.

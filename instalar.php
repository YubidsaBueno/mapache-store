<?php
require_once __DIR__ . '/config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(120) NOT NULL,
        correo VARCHAR(150) NOT NULL UNIQUE,
        contraseña VARCHAR(255) NOT NULL,
        rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
        codigo_2fa VARCHAR(10) NULL,
        estado_2fa TINYINT(1) NOT NULL DEFAULT 1,
        fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        nombre_categoria VARCHAR(100) NOT NULL,
        descripcion TEXT NULL,
        estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo'
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS productos (
        id_producto INT AUTO_INCREMENT PRIMARY KEY,
        id_categoria INT NULL,
        nombre VARCHAR(150) NOT NULL,
        marca VARCHAR(100) NOT NULL,
        descripcion TEXT NOT NULL,
        precio DECIMAL(10,2) NOT NULL DEFAULT 0,
        stock INT NOT NULL DEFAULT 0,
        imagen VARCHAR(255) NULL,
        estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
        fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_productos_categorias FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS producto_media (
        id_media INT AUTO_INCREMENT PRIMARY KEY,
        id_producto INT NOT NULL,
        tipo ENUM('imagen','video') NOT NULL,
        archivo VARCHAR(255) NOT NULL,
        principal TINYINT(1) NOT NULL DEFAULT 0,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_media_producto FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS ventas (
        id_venta INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        total DECIMAL(10,2) NOT NULL DEFAULT 0,
        estado_venta ENUM('pendiente','pagado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
        CONSTRAINT fk_ventas_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS detalle_ventas (
        id_detalle INT AUTO_INCREMENT PRIMARY KEY,
        id_venta INT NOT NULL,
        id_producto INT NOT NULL,
        cantidad INT NOT NULL,
        precio_unitario DECIMAL(10,2) NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        CONSTRAINT fk_detalle_venta FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS favoritos (
        id_favorito INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_producto INT NOT NULL,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_favorito (id_usuario, id_producto),
        CONSTRAINT fk_favoritos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_favoritos_productos FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS carrito (
        id_carrito INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_producto INT NOT NULL,
        cantidad INT NOT NULL DEFAULT 1,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_carrito (id_usuario, id_producto),
        CONSTRAINT fk_carrito_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_carrito_productos FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB");

    if (!is_dir(UPLOAD_PRODUCTOS_PATH)) mkdir(UPLOAD_PRODUCTOS_PATH, 0777, true);
    if (!is_dir(UPLOAD_VIDEOS_PATH)) mkdir(UPLOAD_VIDEOS_PATH, 0777, true);

    $adminPass = password_hash('mapache3000', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->execute(['mapache@gmail.com']);
    if (!$stmt->fetch()) {
        $insert = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña, rol, estado_2fa) VALUES (?, ?, ?, 'admin', 1)");
        $insert->execute(['Administrador Mapache', 'mapache@gmail.com', $adminPass]);
    }

    $categorias = [
        ['Samsung', 'Celulares de gama media y alta Samsung.'],
        ['Xiaomi', 'Celulares Xiaomi, Redmi y POCO.'],
        ['iPhone', 'Equipos Apple iPhone.'],
        ['Motorola', 'Celulares Motorola.'],
        ['Honor', 'Celulares Honor modernos y elegantes.'],
        ['Tecno', 'Celulares económicos para uso diario.']
    ];

    foreach ($categorias as $cat) {
        $stmt = $pdo->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
        $stmt->execute([$cat[0]]);
        if (!$stmt->fetch()) {
            $pdo->prepare("INSERT INTO categorias (nombre_categoria, descripcion) VALUES (?, ?)")->execute($cat);
        }
    }

    $count = (int)$pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    if ($count === 0) {
        $mapCategorias = [];
        $stmtCats = $pdo->query("SELECT id_categoria, nombre_categoria FROM categorias");
        foreach ($stmtCats as $cat) {
            $mapCategorias[$cat['nombre_categoria']] = (int)$cat['id_categoria'];
        }

        $productos = [
            [$mapCategorias['Samsung'] ?? null, 'Samsung Galaxy S24', 'Samsung', 'Pantalla AMOLED, cámara avanzada, batería de larga duración y gran rendimiento.', 5200, 8, 'samsung-s24.svg'],
            [$mapCategorias['Xiaomi'] ?? null, 'Xiaomi Redmi Note 13', 'Xiaomi', 'Celular moderno con excelente cámara, buen rendimiento y precio accesible.', 1900, 15, 'xiaomi-note.svg'],
            [$mapCategorias['iPhone'] ?? null, 'iPhone 15', 'Apple', 'Equipo Apple con diseño premium, cámara de alta calidad y excelente sistema operativo.', 7900, 5, 'iphone-15.svg'],
            [$mapCategorias['Motorola'] ?? null, 'Motorola Edge 40', 'Motorola', 'Diseño elegante, pantalla curva, buena autonomía y rendimiento fluido.', 3100, 7, 'motorola-edge.svg'],
            [$mapCategorias['Honor'] ?? null, 'Honor 90', 'Honor', 'Pantalla brillante, cámara de alta resolución y gran capacidad de batería.', 2900, 6, 'honor-90.svg'],
            [$mapCategorias['Tecno'] ?? null, 'Tecno Spark 20', 'Tecno', 'Celular económico ideal para estudiantes, redes sociales y uso diario.', 1300, 12, 'tecno-spark.svg'],
        ];

        $insert = $pdo->prepare("INSERT INTO productos (id_categoria, nombre, marca, descripcion, precio, stock, imagen, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'activo')");
        $insertMedia = $pdo->prepare("INSERT INTO producto_media (id_producto, tipo, archivo, principal) VALUES (?, 'imagen', ?, 1)");

        foreach ($productos as $p) {
            $insert->execute($p);
            $idProducto = $pdo->lastInsertId();
            $insertMedia->execute([$idProducto, $p[6]]);
        }
    }

    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Instalación</title><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head><body class='bg-light'><div class='container py-5'><div class='card shadow border-0'><div class='card-body p-5'><h1 class='text-success'>✅ Instalación completada</h1><p>La base de datos, tablas, carpetas y usuario administrador fueron creados correctamente.</p><p><strong>Admin:</strong> mapache@gmail.com<br><strong>Contraseña:</strong> mapache3000</p><a class='btn btn-primary' href='public/index.php'>Entrar a Mapache Store</a></div></div></div></body></html>";
} catch (Exception $e) {
    echo "<h2>Error en la instalación</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

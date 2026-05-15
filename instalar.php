<?php
// Conexión a la base de datos
require_once __DIR__ . '/../config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // Obtener todos los productos
    $stmt = $pdo->query("SELECT p.*, c.nombre_categoria FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria WHERE p.estado = 'activo'");
    $productos = $stmt->fetchAll();

} catch (Exception $e) {
    echo "<h2>Error en la conexión</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapache Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-img-top { height: 200px; object-fit: contain; background: #fff; padding: 10px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4 text-center text-success">Mapache Store</h1>
        <div class="row g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text"><strong>Marca:</strong> <?php echo htmlspecialchars($producto['marca']); ?></p>
                            <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary w-100">Agregar al carrito</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($productos)): ?>
                <p class="text-center text-muted">No hay productos disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
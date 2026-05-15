<section class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark mb-3">Ofertas especiales en celulares</span>
                <h1 class="display-4 fw-bold text-white">Celulares modernos al mejor precio</h1>
                <p class="lead text-white-50">Compra smartphones Samsung, Xiaomi, iPhone, Motorola y más. Guarda favoritos, arma tu carrito y revisa tu historial de compras.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= url('productos/index') ?>" class="btn btn-warning btn-lg fw-bold">Ver productos</a>
                    <a href="<?= url('auth/register') ?>" class="btn btn-outline-light btn-lg">Crear cuenta</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-card shadow-lg">
                    <div class="phone-mockup">
                        <div class="phone-screen">
                            <i class="bi bi-phone display-1"></i>
                            <h3 class="mt-3">Mapache Store</h3>
                            <p>Tu próxima compra está aquí</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Categorías populares</h2>
            <p class="text-muted mb-0">Filtra por la línea de celular que estás buscando.</p>
        </div>
        <a href="<?= url('productos/index') ?>" class="btn btn-outline-primary">Ver catálogo</a>
    </div>
    <div class="row g-3">
        <?php foreach ($categorias as $categoria): ?>
            <div class="col-6 col-md-3">
                <a class="category-tile" href="<?= url('productos/index') ?>&categoria=<?= $categoria['id_categoria'] ?>">
                    <i class="bi bi-grid-1x2"></i>
                    <span><?= e($categoria['nombre_categoria']) ?></span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Productos destacados</h2>
            <p class="text-muted">Celulares recientes agregados a la tienda.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-img-wrap">
                            <img src="<?= productoImagen($producto['imagen']) ?>" class="card-img-top" alt="<?= e($producto['nombre']) ?>">
                            <span class="badge bg-success product-badge">Disponible</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-1"><?= e($producto['marca']) ?></p>
                            <h5 class="card-title fw-bold"><?= e($producto['nombre']) ?></h5>
                            <p class="price mb-3"><?= money($producto['precio']) ?></p>
                            <a href="<?= url('productos/detalle') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-primary w-100">Ver detalle</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="benefit-card"><i class="bi bi-truck"></i><h5>Entrega rápida</h5><p>Organiza tus compras de manera sencilla.</p></div>
        </div>
        <div class="col-md-3">
            <div class="benefit-card"><i class="bi bi-shield-lock"></i><h5>Login seguro</h5><p>Ingreso con contraseña encriptada y 2FA.</p></div>
        </div>
        <div class="col-md-3">
            <div class="benefit-card"><i class="bi bi-heart"></i><h5>Favoritos</h5><p>Guarda productos que te interesen.</p></div>
        </div>
        <div class="col-md-3">
            <div class="benefit-card"><i class="bi bi-receipt"></i><h5>Historial</h5><p>Consulta tus compras realizadas.</p></div>
        </div>
    </div>
</section>

<section class="container py-4">
    <h1 class="fw-bold mb-1">Mis favoritos</h1>
    <p class="text-muted mb-4">Productos guardados para ver o comprar después.</p>

    <div class="row g-4">
        <?php if (!$favoritos): ?>
            <div class="col-12"><div class="empty-state"><i class="bi bi-heart"></i><h3>No tienes favoritos</h3><a href="<?= url('productos/index') ?>" class="btn btn-primary">Explorar productos</a></div></div>
        <?php endif; ?>
        <?php foreach ($favoritos as $producto): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <div class="product-img-wrap"><img src="<?= productoImagen($producto['imagen']) ?>" class="card-img-top"></div>
                    <div class="card-body d-flex flex-column">
                        <p class="text-muted small mb-1"><?= e($producto['marca']) ?></p>
                        <h5 class="fw-bold"><?= e($producto['nombre']) ?></h5>
                        <p class="price"><?= money($producto['precio']) ?></p>
                        <div class="mt-auto d-grid gap-2">
                            <a href="<?= url('productos/detalle') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-primary">Ver detalle</a>
                            <a href="<?= url('favoritos/eliminar') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-outline-danger">Quitar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

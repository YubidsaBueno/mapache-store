<section class="container py-4">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="detail-image-main shadow-sm">
                <img src="<?= productoImagen($producto['imagen']) ?>" alt="<?= e($producto['nombre']) ?>">
            </div>
            <?php if ($media): ?>
                <div class="row g-3 mt-2">
                    <?php foreach ($media as $item): ?>
                        <div class="col-6 col-md-4">
                            <?php if ($item['tipo'] === 'imagen'): ?>
                                <img class="gallery-thumb" src="<?= asset(UPLOAD_PRODUCTOS_URL . $item['archivo']) ?>" alt="Imagen producto">
                            <?php else: ?>
                                <video class="gallery-thumb" controls>
                                    <source src="<?= asset(UPLOAD_VIDEOS_URL . $item['archivo']) ?>">
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <span class="badge bg-primary mb-3"><?= e($producto['nombre_categoria'] ?? 'Producto') ?></span>
            <h1 class="fw-bold"><?= e($producto['nombre']) ?></h1>
            <p class="text-muted fs-5">Marca: <?= e($producto['marca']) ?></p>
            <h2 class="price mb-3"><?= money($producto['precio']) ?></h2>
            <p><?= nl2br(e($producto['descripcion'])) ?></p>
            <div class="alert <?= $producto['stock'] > 0 ? 'alert-success' : 'alert-danger' ?>">
                Stock disponible: <strong><?= (int)$producto['stock'] ?></strong>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <?php if (Auth::check()): ?>
                    <form method="POST" action="<?= url('carrito/agregar') ?>" class="d-flex gap-2">
                        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="<?= (int)$producto['stock'] ?>" class="form-control" style="width: 100px;">
                        <button class="btn btn-warning btn-lg" <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>><i class="bi bi-cart-plus"></i> Agregar al carrito</button>
                    </form>
                    <a href="<?= url('favoritos/toggle') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-outline-danger btn-lg">
                        <i class="bi bi-heart<?= $esFavorito ? '-fill' : '' ?>"></i> <?= $esFavorito ? 'Quitar favorito' : 'Favorito' ?>
                    </a>
                <?php else: ?>
                    <a href="<?= url('auth/login') ?>" class="btn btn-primary btn-lg">Inicia sesión para comprar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

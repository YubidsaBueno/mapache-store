<section class="container py-4">
    <div class="catalog-header p-4 p-md-5 rounded-4 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <h1 class="fw-bold text-white">Catálogo de celulares</h1>
                <p class="text-white-50 mb-0">Busca por nombre, marca o categoría y agrega productos a tu carrito o favoritos.</p>
            </div>
            <div class="col-lg-5">
                <form method="GET" action="<?= BASE_URL ?>index.php" class="search-box">
                    <input type="hidden" name="c" value="productos">
                    <input type="hidden" name="a" value="index">
                    <input type="text" name="q" class="form-control form-control-lg" placeholder="Buscar celular..." value="<?= e($filtros['q']) ?>">
                </form>
            </div>
        </div>
    </div>

    <form method="GET" action="<?= BASE_URL ?>index.php" class="row g-3 align-items-end mb-4">
        <input type="hidden" name="c" value="productos">
        <input type="hidden" name="a" value="index">
        <div class="col-md-4">
            <label class="form-label">Buscar</label>
            <input type="text" name="q" class="form-control" value="<?= e($filtros['q']) ?>" placeholder="Nombre, marca o descripción">
        </div>
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <select name="categoria" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>" <?= selected($filtros['categoria'], $categoria['id_categoria']) ?>><?= e($categoria['nombre_categoria']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Marca</label>
            <select name="marca" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= e($marca['marca']) ?>" <?= selected($filtros['marca'], $marca['marca']) ?>><?= e($marca['marca']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        </div>
    </form>

    <div class="row g-4">
        <?php if (!$productos): ?>
            <div class="col-12"><div class="alert alert-info">No se encontraron productos.</div></div>
        <?php endif; ?>
        <?php foreach ($productos as $producto): ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <div class="product-img-wrap">
                        <img src="<?= productoImagen($producto['imagen']) ?>" class="card-img-top" alt="<?= e($producto['nombre']) ?>">
                        <?php if (Auth::check()): ?>
                            <a class="favorite-btn <?= !empty($favoritos[$producto['id_producto']]) ? 'active' : '' ?>" href="<?= url('favoritos/toggle') ?>&id=<?= $producto['id_producto'] ?>">
                                <i class="bi bi-heart-fill"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <p class="text-muted small mb-1"><?= e($producto['nombre_categoria'] ?? 'Sin categoría') ?></p>
                        <h5 class="fw-bold mb-1"><?= e($producto['nombre']) ?></h5>
                        <p class="small text-muted mb-2">Marca: <?= e($producto['marca']) ?></p>
                        <p class="price mb-2"><?= money($producto['precio']) ?></p>
                        <p class="small <?= $producto['stock'] > 0 ? 'text-success' : 'text-danger' ?>">Stock: <?= (int)$producto['stock'] ?></p>
                        <div class="mt-auto d-grid gap-2">
                            <a href="<?= url('productos/detalle') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-outline-primary">Ver detalle</a>
                            <?php if (Auth::check()): ?>
                                <form method="POST" action="<?= url('carrito/agregar') ?>">
                                    <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                                    <button class="btn btn-warning w-100" <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>><i class="bi bi-cart-plus"></i> Agregar</button>
                                </form>
                            <?php else: ?>
                                <a href="<?= url('auth/login') ?>" class="btn btn-warning">Inicia sesión para comprar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

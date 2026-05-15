<section class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold">Carrito de compras</h1>
            <p class="text-muted mb-0">Modifica cantidades, elimina productos o vacía todo el carrito.</p>
        </div>
        <?php if ($items): ?>
            <a href="<?= url('carrito/vaciar') ?>" class="btn btn-outline-danger" onclick="return confirm('¿Vaciar todo el carrito?')"><i class="bi bi-trash3"></i> Vaciar todo</a>
        <?php endif; ?>
    </div>

    <?php if (!$items): ?>
        <div class="empty-state">
            <i class="bi bi-cart-x"></i>
            <h3>Tu carrito está vacío</h3>
            <p>Agrega productos para realizar una compra.</p>
            <a href="<?= url('productos/index') ?>" class="btn btn-primary">Ver productos</a>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th style="width: 180px;">Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= productoImagen($item['imagen']) ?>" class="cart-img" alt="Producto">
                                        <div>
                                            <strong><?= e($item['nombre']) ?></strong><br>
                                            <span class="text-muted small"><?= e($item['marca']) ?> | Stock: <?= (int)$item['stock'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= money($item['precio']) ?></td>
                                <td>
                                    <form method="POST" action="<?= url('carrito/actualizar') ?>" class="d-flex gap-2">
                                        <input type="hidden" name="id_producto" value="<?= $item['id_producto'] ?>">
                                        <input type="number" name="cantidad" value="<?= (int)$item['cantidad'] ?>" min="0" max="<?= (int)$item['stock'] ?>" class="form-control">
                                        <button class="btn btn-sm btn-primary">OK</button>
                                    </form>
                                </td>
                                <td><strong><?= money($item['precio'] * $item['cantidad']) ?></strong></td>
                                <td><a class="btn btn-sm btn-outline-danger" href="<?= url('carrito/eliminar') ?>&id=<?= $item['id_producto'] ?>">Eliminar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h3 class="mb-0">Total: <span class="price"><?= money($total) ?></span></h3>
                    <a href="<?= url('carrito/comprar') ?>" class="btn btn-success btn-lg" onclick="return confirm('¿Confirmar compra?')"><i class="bi bi-check-circle"></i> Confirmar compra</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

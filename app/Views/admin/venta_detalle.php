<section class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold">Detalle de venta #<?= $venta['id_venta'] ?></h1>
            <p class="text-muted mb-0">Cliente: <?= e($venta['nombre']) ?> | Fecha: <?= e($venta['fecha']) ?></p>
        </div>
        <a href="<?= Auth::isAdmin() ? url('ventas/admin') : url('ventas/historial') ?>" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php foreach ($detalle as $item): ?>
                        <tr>
                            <td><div class="d-flex align-items-center gap-3"><img src="<?= productoImagen($item['imagen']) ?>" class="cart-img"><div><strong><?= e($item['nombre']) ?></strong><br><span class="text-muted small"><?= e($item['marca']) ?></span></div></div></td>
                            <td><?= (int)$item['cantidad'] ?></td>
                            <td><?= money($item['precio_unitario']) ?></td>
                            <td><strong><?= money($item['subtotal']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot><tr><th colspan="3" class="text-end">Total</th><th class="price"><?= money($venta['total']) ?></th></tr></tfoot>
            </table>
        </div>
    </div>
</section>

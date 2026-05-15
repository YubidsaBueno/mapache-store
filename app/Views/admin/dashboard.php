<section class="container py-4">
    <div class="admin-hero rounded-4 p-4 p-md-5 mb-4">
        <h1 class="fw-bold text-white">Panel administrativo</h1>
        <p class="text-white-50 mb-0">Controla productos, categorías, usuarios, ventas y stock de Mapache Store.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3"><div class="stat-card"><i class="bi bi-phone"></i><span>Productos</span><strong><?= $stats['productos'] ?></strong></div></div>
        <div class="col-md-6 col-xl-3"><div class="stat-card"><i class="bi bi-people"></i><span>Usuarios</span><strong><?= $stats['usuarios'] ?></strong></div></div>
        <div class="col-md-6 col-xl-3"><div class="stat-card"><i class="bi bi-bag-check"></i><span>Ventas</span><strong><?= $stats['ventas'] ?></strong></div></div>
        <div class="col-md-6 col-xl-3"><div class="stat-card"><i class="bi bi-cash-stack"></i><span>Total</span><strong><?= money($stats['total']) ?></strong></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-3"><h5 class="fw-bold mb-0">Últimas ventas</h5></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Código</th><th>Cliente</th><th>Total</th><th>Estado</th></tr></thead>
                        <tbody>
                            <?php foreach ($ultimasVentas as $venta): ?>
                                <tr><td>#<?= $venta['id_venta'] ?></td><td><?= e($venta['nombre']) ?></td><td><?= money($venta['total']) ?></td><td><span class="badge bg-secondary"><?= e($venta['estado_venta']) ?></span></td></tr>
                            <?php endforeach; ?>
                            <?php if (!$ultimasVentas): ?><tr><td colspan="4" class="text-muted">No hay ventas registradas.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-3"><h5 class="fw-bold mb-0">Productos con bajo stock</h5></div>
                <div class="list-group list-group-flush">
                    <?php foreach ($bajoStock as $producto): ?>
                        <div class="list-group-item d-flex justify-content-between"><span><?= e($producto['nombre']) ?></span><strong><?= (int)$producto['stock'] ?></strong></div>
                    <?php endforeach; ?>
                    <?php if (!$bajoStock): ?><div class="list-group-item text-muted">No hay productos con bajo stock.</div><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-4">
    <h1 class="fw-bold">Historial de compras</h1>
    <p class="text-muted">Aquí puedes revisar todas tus compras realizadas.</p>

    <!-- Formulario de filtro -->
    <form method="GET" class="row g-3 mb-4">
    <input type="hidden" name="c" value="ventas">
    <input type="hidden" name="a" value="historial">
    <div class="col-md-4">
        <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por modelo o marca">
    </div>
    <div class="col-md-3">
        <input type="date" name="fecha" value="<?= e($_GET['fecha'] ?? '') ?>" class="form-control">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

    <?php if (!$ventas): ?>
        <div class="empty-state"><i class="bi bi-receipt"></i><h3>Aún no tienes compras</h3><a href="<?= url('productos/index') ?>" class="btn btn-primary">Comprar ahora</a></div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $venta): ?>
                            <?php
                            $buscar = strtolower($_GET['q'] ?? '');
                            $fechaFiltro = $_GET['fecha'] ?? '';
                            $coincide = empty($buscar);
                            $fechaCoincide = empty($fechaFiltro) || strpos($venta['fecha'], $fechaFiltro) === 0;

                            $detalleModel = new DetalleVenta();
                            $detalleItems = $detalleModel->porVenta($venta['id_venta']);
                            foreach ($detalleItems as $item) {
                                if (strpos(strtolower($item['nombre']), $buscar) !== false || strpos(strtolower($item['marca']), $buscar) !== false) {
                                    $coincide = true;
                                    break;
                                }
                            }

                            if (!$coincide || !$fechaCoincide) continue;
                            ?>
                            <tr>
                                <td>#<?= $venta['id_venta'] ?></td>
                                <td><?= e($venta['fecha']) ?></td>
                                <td><strong><?= money($venta['total']) ?></strong></td>
                                <td><span class="badge bg-secondary"><?= e($venta['estado_venta']) ?></span></td>
                                <td><a href="<?= url('ventas/detalle') ?>&id=<?= $venta['id_venta'] ?>" class="btn btn-sm btn-outline-primary">Ver detalle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>
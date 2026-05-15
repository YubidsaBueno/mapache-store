<section class="container py-4">
    <h1 class="fw-bold">Ventas realizadas</h1>
    <p class="text-muted">Historial general de compras y administración de estados de venta.</p>

    <!-- Formulario de filtro -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por cliente, modelo o marca">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Código</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <?php
                        // Filtrado por cliente, marca o modelo
                        $buscar = strtolower($_GET['q'] ?? '');
                        $cliente = strtolower($venta['nombre']);
                        $detalleModel = new DetalleVenta();
                        $detalleItems = $detalleModel->porVenta($venta['id_venta']);
                        $coincide = empty($buscar) || strpos($cliente, $buscar) !== false;
                        if (!$coincide) {
                            foreach ($detalleItems as $item) {
                                if (strpos(strtolower($item['nombre']), $buscar) !== false || strpos(strtolower($item['marca']), $buscar) !== false) {
                                    $coincide = true;
                                    break;
                                }
                            }
                        }
                        if (!$coincide) continue;
                        ?>
                        <tr>
                            <td>#<?= $venta['id_venta'] ?></td>
                            <td><?= e($venta['nombre']) ?><br><span class="text-muted small"><?= e($venta['correo']) ?></span></td>
                            <td><?= e($venta['fecha']) ?></td>
                            <td><strong><?= money($venta['total']) ?></strong></td>
                            <td>
                                <form method="POST" action="<?= url('ventas/cambiarEstado') ?>" class="d-flex gap-2">
                                    <input type="hidden" name="id_venta" value="<?= $venta['id_venta'] ?>">
                                    <select name="estado_venta" class="form-select form-select-sm">
                                        <?php foreach (['pendiente','pagado','entregado','cancelado'] as $estado): ?>
                                            <option value="<?= $estado ?>" <?= selected($venta['estado_venta'], $estado) ?>><?= ucfirst($estado) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-sm btn-primary">OK</button>
                                </form>
                            </td>
                            <td><a href="<?= url('ventas/detalle') ?>&id=<?= $venta['id_venta'] ?>" class="btn btn-sm btn-outline-primary">Detalle</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$ventas): ?><tr><td colspan="6" class="text-muted">No hay ventas todavía.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
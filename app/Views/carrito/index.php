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
            <div class="card-footer bg-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h3 class="mb-0">Total: <span class="price"><?= money($total) ?></span></h3>
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    <i class="bi bi-check-circle"></i> Confirmar compra
                </button>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <form id="whatsappForm" class="modal-content" onsubmit="return confirmarCompra()">
              <div class="modal-header" style="background-color:#0d2b5c; color:white;">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar compra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <h6>Productos:</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre del producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= e($item['nombre']) ?></td>
                                <td><?= (int)$item['cantidad'] ?></td>
                                <td><?= money($item['precio'] * $item['cantidad']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><strong>Total a pagar: <?= money($total) ?></strong></p>
                <div class="mb-3">
                  <label for="direccion" class="form-label">Dirección de envío:</label>
                  <input type="text" class="form-control" id="direccion" required placeholder="Escribe tu dirección completa">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Confirmar</button>
              </div>
            </form>
          </div>
        </div>

<script>
function confirmarCompra() {
    const direccion = document.getElementById('direccion').value.trim();
    if (!direccion) {
        alert("Por favor, ingresa tu dirección de envío");
        return false;
    }

    let mensaje = "Hola, mi compra:\n";
    <?php foreach ($items as $item): ?>
    mensaje += "<?= e($item['nombre']) ?> | Cantidad: <?= (int)$item['cantidad'] ?> | Total: <?= money($item['precio'] * $item['cantidad']) ?>\n";
    <?php endforeach; ?>
    mensaje += "Total a pagar: <?= money($total) ?>\n";
    mensaje += "Dirección: " + direccion;

    const numero = "59164922968";
    const url = "https://api.whatsapp.com/send?phone=" + numero + "&text=" + encodeURIComponent(mensaje);
    window.open(url, "_blank");

    // Mostrar alerta central
    alert("Gracias por su compra");

    // Vaciar carrito y actualizar historial
    fetch("<?= url('carrito/comprar') ?>", { method: "POST" })
        .then(() => window.location.href = "<?= url('ventas/historial') ?>")
        .catch(() => alert("Ocurrió un error al procesar la compra."));

    return false;
}
</script>
    <?php endif; ?>
</section>
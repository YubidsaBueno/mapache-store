<section class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div><h1 class="fw-bold">Gestión de productos</h1><p class="text-muted mb-0">Administra celulares, precios, stock, imágenes y videos.</p></div>
        <a href="<?= url('adminProducto/crear') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo producto</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Imagen</th><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><img src="<?= productoImagen($producto['imagen']) ?>" class="admin-product-img"></td>
                            <td><strong><?= e($producto['nombre']) ?></strong><br><span class="text-muted small"><?= e($producto['marca']) ?></span></td>
                            <td><?= e($producto['nombre_categoria'] ?? 'Sin categoría') ?></td>
                            <td><?= money($producto['precio']) ?></td>
                            <td><?= (int)$producto['stock'] ?></td>
                            <td><span class="badge bg-<?= $producto['estado'] === 'activo' ? 'success' : 'secondary' ?>"><?= e($producto['estado']) ?></span></td>
                            <td>
                                <a href="<?= url('adminProducto/editar') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="<?= url('adminProducto/eliminar') ?>&id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h1 class="fw-bold mb-3"><?= $producto ? 'Editar producto' : 'Crear producto' ?></h1>
                    <form method="POST" action="<?= $accion ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del producto</label>
                                <input type="text" name="nombre" class="form-control" required value="<?= e($producto['nombre'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca" class="form-control" required value="<?= e($producto['marca'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <select name="id_categoria" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['id_categoria'] ?>" <?= selected($producto['id_categoria'] ?? '', $categoria['id_categoria']) ?>><?= e($categoria['nombre_categoria']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" min="0" name="precio" class="form-control" required value="<?= e($producto['precio'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stock</label>
                                <input type="number" min="0" name="stock" class="form-control" required value="<?= e($producto['stock'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="activo" <?= selected($producto['estado'] ?? 'activo', 'activo') ?>>Activo</option>
                                    <option value="inactivo" <?= selected($producto['estado'] ?? '', 'inactivo') ?>>Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Imagen principal</label>
                                <input type="file" name="imagen_principal" class="form-control" accept="image/*">
                                <?php if (!empty($producto['imagen'])): ?><small class="text-muted">Actual: <?= e($producto['imagen']) ?></small><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Más imágenes</label>
                                <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Videos del producto</label>
                                <input type="file" name="videos[]" class="form-control" accept="video/*" multiple>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="5" required><?= e($producto['descripcion'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-success btn-lg">Guardar</button>
                            <a href="<?= url('adminProducto/index') ?>" class="btn btn-outline-secondary btn-lg">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

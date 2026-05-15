<section class="container py-4">
    <h1 class="fw-bold">Gestión de categorías</h1>
    <p class="text-muted">Crea, edita y elimina categorías para organizar los celulares.</p>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold">Nueva categoría</h5>
                    <form method="POST" action="<?= url('categorias/guardar') ?>">
                        <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre_categoria" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Descripción</label><textarea name="descripcion" class="form-control" rows="4"></textarea></div>
                        <button class="btn btn-success w-100">Guardar categoría</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Nombre</th><th>Descripción</th><th>Estado</th><th>Acción</th></tr></thead>
                        <tbody>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <form method="POST" action="<?= url('categorias/actualizar') ?>">
                                        <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
                                        <td><input type="text" name="nombre_categoria" class="form-control" value="<?= e($categoria['nombre_categoria']) ?>"></td>
                                        <td><input type="text" name="descripcion" class="form-control" value="<?= e($categoria['descripcion']) ?>"></td>
                                        <td><select name="estado" class="form-select"><option value="activo" <?= selected($categoria['estado'], 'activo') ?>>Activo</option><option value="inactivo" <?= selected($categoria['estado'], 'inactivo') ?>>Inactivo</option></select></td>
                                        <td class="d-flex gap-2"><button class="btn btn-sm btn-primary">Actualizar</button><a href="<?= url('categorias/eliminar') ?>&id=<?= $categoria['id_categoria'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar categoría?')">Eliminar</a></td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

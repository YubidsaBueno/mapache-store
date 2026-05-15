<section class="container py-4">
    <h1 class="fw-bold">Usuarios registrados</h1>
    <p class="text-muted">Lista de administradores y clientes registrados.</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>2FA</th><th>Registro</th></tr></thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id_usuario'] ?></td>
                            <td><?= e($usuario['nombre']) ?></td>
                            <td><?= e($usuario['correo']) ?></td>
                            <td><span class="badge bg-<?= $usuario['rol'] === 'admin' ? 'primary' : 'secondary' ?>"><?= e($usuario['rol']) ?></span></td>
                            <td><?= $usuario['estado_2fa'] ? 'Activo' : 'Inactivo' ?></td>
                            <td><?= e($usuario['fecha_registro']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold text-center mb-2">Crear cuenta</h2>
                        <p class="text-muted text-center mb-4">Regístrate como cliente para comprar, guardar favoritos y ver tu historial.</p>
                        <form method="POST" action="<?= url('auth/guardarRegistro') ?>" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" name="nombre" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="correo" class="form-control form-control-lg" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control form-control-lg" minlength="6" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <input type="password" name="confirmar" class="form-control form-control-lg" minlength="6" required>
                                </div>
                            </div>
                            <button class="btn btn-warning btn-lg w-100 mt-4 fw-bold">Crear cuenta</button>
                        </form>
                        <hr>
                        <p class="text-center mb-0">¿Ya tienes cuenta? <a href="<?= url('auth/login') ?>">Inicia sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

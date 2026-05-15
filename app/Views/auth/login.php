<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold text-center mb-2">Iniciar sesión</h2>
                        <p class="text-muted text-center mb-4">Ingresa con tu correo y contraseña. Luego se activará el código 2FA.</p>
                        <form method="POST" action="<?= url('auth/procesarLogin') ?>" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="correo" class="form-control form-control-lg" required>
                                <div class="invalid-feedback">Ingresa un correo válido.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                                <div class="invalid-feedback">Ingresa tu contraseña.</div>
                            </div>
                            <button class="btn btn-primary btn-lg w-100">Ingresar</button>
                        </form>
                        <hr>
                        <p class="text-center mb-0">¿No tienes cuenta? <a href="<?= url('auth/register') ?>">Regístrate</a></p>

                        <!-- Correo de emergencia -->
                        <div class="alert alert-info mt-4 small mb-0 text-center">
                            <strong>Correo de emergencia:</strong><br>
                            mapache@gmail.com
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
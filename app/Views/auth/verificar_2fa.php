<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="icon-circle mx-auto mb-3"><i class="bi bi-shield-lock"></i></div>
                        <h2 class="fw-bold">Verificación 2FA</h2>
                        <p class="text-muted">Ingresa el código temporal para acceder al sistema.</p>
                        <div class="alert alert-warning">
                            Código de prueba: <strong class="fs-4"><?= e($codigoDemo) ?></strong>
                        </div>
                        <form method="POST" action="<?= url('twoFactor/confirmar') ?>">
                            <input type="text" name="codigo" class="form-control form-control-lg text-center codigo-input" maxlength="6" required placeholder="000000">
                            <button class="btn btn-primary btn-lg w-100 mt-3">Verificar</button>
                        </form>
                        <p class="small text-muted mt-3 mb-0">Este código simula el envío por correo, como pide la práctica.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

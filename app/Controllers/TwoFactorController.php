<?php
class TwoFactorController extends Controller
{
    public function verificar(): void
    {
        if (empty($_SESSION['2fa_user_id'])) {
            $this->redirect('auth/login');
        }

        $codigoDemo = $_SESSION['2fa_codigo_demo'] ?? '';
        $this->view('auth/verificar_2fa', compact('codigoDemo'));
    }

    public function confirmar(): void
    {
        if (!$this->isPost() || empty($_SESSION['2fa_user_id'])) {
            $this->redirect('auth/login');
        }

        if (time() > ($_SESSION['2fa_expira'] ?? 0)) {
            unset($_SESSION['2fa_user_id'], $_SESSION['2fa_codigo_demo'], $_SESSION['2fa_expira']);
            flash('danger', 'El código 2FA expiró. Inicia sesión nuevamente.');
            $this->redirect('auth/login');
        }

        $codigo = $this->input('codigo');
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorId((int)$_SESSION['2fa_user_id']);

        if (!$usuario || $codigo !== ($usuario['codigo_2fa'] ?? '')) {
            flash('danger', 'Código 2FA incorrecto.');
            $this->redirect('twoFactor/verificar');
        }

        $usuarioModel->limpiarCodigo2FA((int)$usuario['id_usuario']);
        Auth::login($usuario);
        unset($_SESSION['2fa_user_id'], $_SESSION['2fa_codigo_demo'], $_SESSION['2fa_expira']);

        if ($usuario['rol'] === 'admin') {
            $this->redirect('admin/dashboard');
        }

        $this->redirect('productos/index');
    }
}

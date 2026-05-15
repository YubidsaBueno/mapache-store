<?php
class AuthController extends Controller
{
    public function login(): void
    {
        $this->view('auth/login');
    }

    public function procesarLogin(): void
    {
        if (!$this->isPost()) {
            $this->redirect('auth/login');
        }

        $correo = $this->input('correo');
        $password = $_POST['password'] ?? '';

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorCorreo($correo);

        if (!$usuario || !password_verify($password, $usuario['contraseña'])) {
            flash('danger', 'Correo o contraseña incorrectos.');
            $this->redirect('auth/login');
        }

        $codigo = (string)random_int(100000, 999999);
        $usuarioModel->guardarCodigo2FA((int)$usuario['id_usuario'], $codigo);

        $_SESSION['2fa_user_id'] = (int)$usuario['id_usuario'];
        $_SESSION['2fa_codigo_demo'] = $codigo;
        $_SESSION['2fa_expira'] = time() + 300;

        $this->redirect('twoFactor/verificar');
    }

    public function register(): void
    {
        $this->view('auth/register');
    }

    public function guardarRegistro(): void
    {
        if (!$this->isPost()) {
            $this->redirect('auth/register');
        }

        $nombre = $this->input('nombre');
        $correo = $this->input('correo');
        $password = $_POST['password'] ?? '';
        $confirmar = $_POST['confirmar'] ?? '';

        if ($nombre === '' || $correo === '' || $password === '') {
            flash('danger', 'Todos los campos son obligatorios.');
            $this->redirect('auth/register');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            flash('danger', 'El correo electrónico no es válido.');
            $this->redirect('auth/register');
        }

        if (strlen($password) < 6) {
            flash('danger', 'La contraseña debe tener mínimo 6 caracteres.');
            $this->redirect('auth/register');
        }

        if ($password !== $confirmar) {
            flash('danger', 'Las contraseñas no coinciden.');
            $this->redirect('auth/register');
        }

        $usuarioModel = new Usuario();
        if ($usuarioModel->buscarPorCorreo($correo)) {
            flash('danger', 'Ese correo ya está registrado.');
            $this->redirect('auth/register');
        }

        $usuarioModel->crear($nombre, $correo, $password, 'cliente');
        flash('success', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
        $this->redirect('auth/login');
    }

    public function logout(): void
    {
        Auth::logout();
        session_start();
        flash('success', 'Sesión cerrada correctamente.');
        $this->redirect('home/index');
    }
}

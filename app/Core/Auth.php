<?php
class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['usuario']);
    }

    public static function user(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    public static function rol(): ?string
    {
        return $_SESSION['usuario']['rol'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::rol() === 'admin';
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('warning', 'Debes iniciar sesión para continuar.');
            header('Location: ' . url('auth/login'));
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            flash('danger', 'No tienes permisos de administrador.');
            header('Location: ' . url('productos/index'));
            exit;
        }
    }

    public static function login(array $usuario): void
    {
        $_SESSION['usuario'] = [
            'id_usuario' => (int)$usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo'],
            'rol' => $usuario['rol'],
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}

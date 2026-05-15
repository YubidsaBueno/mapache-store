<?php
class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $viewPath = APP_PATH . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die('Vista no encontrada: ' . htmlspecialchars($view));
        }

        $contentView = $viewPath;
        require APP_PATH . '/Views/layouts/' . $layout . '.php';
    }

    protected function redirect(string $route = ''): void
    {
        header('Location: ' . url($route));
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $key, $default = '')
    {
        return trim($_POST[$key] ?? $_GET[$key] ?? $default);
    }
}

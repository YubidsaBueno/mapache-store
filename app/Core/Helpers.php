<?php
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function url(string $route = ''): string
{
    $route = trim($route, '/');
    if ($route === '') {
        return BASE_URL . 'index.php';
    }

    $parts = explode('/', $route);
    $controller = $parts[0] ?? 'home';
    $action = $parts[1] ?? 'index';

    return BASE_URL . 'index.php?c=' . urlencode($controller) . '&a=' . urlencode($action);
}

function asset(string $path): string
{
    return BASE_URL . ltrim($path, '/');
}

function money($number): string
{
    return 'Bs. ' . number_format((float)$number, 2, ',', '.');
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function show_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }

    foreach ($_SESSION['flash'] as $item) {
        echo '<div class="alert alert-' . e($item['type']) . ' alert-dismissible fade show" role="alert">';
        echo e($item['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>';
        echo '</div>';
    }

    unset($_SESSION['flash']);
}

function productoImagen(?string $archivo): string
{
    if ($archivo) {
        return asset(UPLOAD_PRODUCTOS_URL . $archivo);
    }
    return asset('assets/img/producto-default.svg');
}

function selected($actual, $esperado): string
{
    return (string)$actual === (string)$esperado ? 'selected' : '';
}

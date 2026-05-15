<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('assets/css/estilos.css') ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-mapache sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= url('home/index') ?>">
            <span class="logo-circle">M</span> <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPrincipal">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link" href="<?= url('home/index') ?>">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('productos/index') ?>">Productos</a></li>
                <?php if (Auth::check()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('favoritos/index') ?>"><i class="bi bi-heart"></i> Favoritos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('carrito/index') ?>"><i class="bi bi-cart3"></i> Carrito</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('ventas/historial') ?>">Mis compras</a></li>
                    <?php if (Auth::isAdmin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= url('admin/dashboard') ?>">Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?= url('adminProducto/index') ?>">Productos</a></li>
                                <li><a class="dropdown-item" href="<?= url('categorias/index') ?>">Categorías</a></li>
                                <li><a class="dropdown-item" href="<?= url('ventas/admin') ?>">Ventas</a></li>
                                <li><a class="dropdown-item" href="<?= url('admin/usuarios') ?>">Usuarios</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-lg-2" href="<?= url('auth/logout') ?>">Salir</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('auth/login') ?>">Login</a></li>
                    <li class="nav-item"><a class="btn btn-warning btn-sm ms-lg-2" href="<?= url('auth/register') ?>">Crear cuenta</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container mt-4">
        <?php show_flash(); ?>
    </div>

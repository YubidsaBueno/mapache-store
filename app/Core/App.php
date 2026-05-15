<?php
class App
{
    public function run(): void
    {
        $controllerParam = $_GET['c'] ?? 'home';
        $action = $_GET['a'] ?? 'index';

        $controllerClass = ucfirst($controllerParam) . 'Controller';

        if (!class_exists($controllerClass)) {
            $controllerClass = 'HomeController';
            $action = 'index';
            flash('danger', 'La página solicitada no existe.');
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            $action = 'index';
            flash('danger', 'La acción solicitada no existe.');
        }

        $controller->$action();
    }
}

<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();

        $productos = $productoModel->destacados(8);
        $categorias = $categoriaModel->todas(true);

        $this->view('home/index', compact('productos', 'categorias'));
    }
}

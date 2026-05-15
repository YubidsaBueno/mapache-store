<?php
class AdminController extends Controller
{
    public function dashboard(): void
    {
        Auth::requireAdmin();
        $productoModel = new Producto();
        $usuarioModel = new Usuario();
        $ventaModel = new Venta();
        $categoriaModel = new Categoria();

        $stats = [
            'productos' => $productoModel->contar(),
            'usuarios' => $usuarioModel->contar(),
            'ventas' => $ventaModel->contar(),
            'categorias' => $categoriaModel->contar(),
            'total' => $ventaModel->totalGanado(),
        ];

        $bajoStock = $productoModel->bajoStock(5);
        $ultimasVentas = array_slice($ventaModel->todas(), 0, 6);

        $this->view('admin/dashboard', compact('stats', 'bajoStock', 'ultimasVentas'));
    }

    public function usuarios(): void
    {
        Auth::requireAdmin();
        $usuarios = (new Usuario())->todos();
        $this->view('admin/usuarios', compact('usuarios'));
    }
}

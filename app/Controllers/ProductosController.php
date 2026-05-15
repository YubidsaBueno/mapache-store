<?php
class ProductosController extends Controller
{
    public function index(): void
    {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        $favoritoModel = new Favorito();

        $filtros = [
            'q' => $_GET['q'] ?? '',
            'categoria' => $_GET['categoria'] ?? '',
            'marca' => $_GET['marca'] ?? '',
        ];

        $productos = $productoModel->listar($filtros, true);
        $categorias = $categoriaModel->todas(true);
        $marcas = $productoModel->marcas();
        $favoritos = [];

        if (Auth::check()) {
            foreach ($productos as $p) {
                $favoritos[$p['id_producto']] = $favoritoModel->existe(Auth::id(), (int)$p['id_producto']);
            }
        }

        $this->view('productos/index', compact('productos', 'categorias', 'marcas', 'filtros', 'favoritos'));
    }

    public function detalle(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $productoModel = new Producto();
        $mediaModel = new ProductoMedia();
        $favoritoModel = new Favorito();

        $producto = $productoModel->buscar($id);
        if (!$producto || $producto['estado'] !== 'activo') {
            flash('danger', 'Producto no encontrado.');
            $this->redirect('productos/index');
        }

        $media = $mediaModel->porProducto($id);
        $esFavorito = Auth::check() ? $favoritoModel->existe(Auth::id(), $id) : false;

        $this->view('productos/detalle', compact('producto', 'media', 'esFavorito'));
    }
}

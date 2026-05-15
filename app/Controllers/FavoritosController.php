<?php
class FavoritosController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $favoritos = (new Favorito())->porUsuario(Auth::id());
        $this->view('favoritos/index', compact('favoritos'));
    }

    public function toggle(): void
    {
        Auth::requireLogin();
        $idProducto = (int)($_GET['id'] ?? $_POST['id_producto'] ?? 0);
        $resultado = (new Favorito())->toggle(Auth::id(), $idProducto);

        flash('success', $resultado === 'agregado' ? 'Producto guardado en favoritos.' : 'Producto eliminado de favoritos.');
        $volver = $_SERVER['HTTP_REFERER'] ?? url('productos/index');
        header('Location: ' . $volver);
        exit;
    }

    public function eliminar(): void
    {
        Auth::requireLogin();
        $idProducto = (int)($_GET['id'] ?? 0);
        (new Favorito())->eliminar(Auth::id(), $idProducto);
        flash('success', 'Favorito eliminado.');
        $this->redirect('favoritos/index');
    }
}

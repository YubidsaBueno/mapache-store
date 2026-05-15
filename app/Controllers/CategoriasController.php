<?php
class CategoriasController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $categorias = (new Categoria())->todas(false);
        $this->view('admin/categorias', compact('categorias'));
    }

    public function guardar(): void
    {
        Auth::requireAdmin();
        $nombre = $this->input('nombre_categoria');
        $descripcion = $this->input('descripcion');

        if ($nombre === '') {
            flash('danger', 'El nombre de la categoría es obligatorio.');
            $this->redirect('categorias/index');
        }

        (new Categoria())->crear($nombre, $descripcion);
        flash('success', 'Categoría creada correctamente.');
        $this->redirect('categorias/index');
    }

    public function actualizar(): void
    {
        Auth::requireAdmin();
        $id = (int)($_POST['id_categoria'] ?? 0);
        $nombre = $this->input('nombre_categoria');
        $descripcion = $this->input('descripcion');
        $estado = $_POST['estado'] ?? 'activo';

        (new Categoria())->actualizar($id, $nombre, $descripcion, $estado);
        flash('success', 'Categoría actualizada.');
        $this->redirect('categorias/index');
    }

    public function eliminar(): void
    {
        Auth::requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        try {
            (new Categoria())->eliminar($id);
            flash('success', 'Categoría eliminada.');
        } catch (Exception $e) {
            flash('danger', 'No puedes eliminar una categoría que tiene productos asociados.');
        }
        $this->redirect('categorias/index');
    }
}

<?php
class AdminProductoController extends Controller
{
    private function guardarArchivo(array $archivo, string $tipo): ?string
    {
        if (empty($archivo['name']) || $archivo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $imagenes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $videos = ['mp4', 'webm', 'ogg'];

        if ($tipo === 'imagen' && !in_array($extension, $imagenes, true)) {
            return null;
        }

        if ($tipo === 'video' && !in_array($extension, $videos, true)) {
            return null;
        }

        $nombre = uniqid($tipo . '_', true) . '.' . $extension;
        $destino = $tipo === 'imagen' ? UPLOAD_PRODUCTOS_PATH . $nombre : UPLOAD_VIDEOS_PATH . $nombre;

        if (!is_dir(dirname($destino))) {
            mkdir(dirname($destino), 0777, true);
        }

        move_uploaded_file($archivo['tmp_name'], $destino);
        return $nombre;
    }

    public function index(): void
    {
        Auth::requireAdmin();
        $productos = (new Producto())->listar([], false);
        $this->view('admin/productos', compact('productos'));
    }

    public function crear(): void
    {
        Auth::requireAdmin();
        $categorias = (new Categoria())->todas(true);
        $producto = null;
        $accion = url('adminProducto/guardar');
        $this->view('admin/producto_form', compact('categorias', 'producto', 'accion'));
    }

    public function guardar(): void
    {
        Auth::requireAdmin();
        if (!$this->isPost()) {
            $this->redirect('adminProducto/index');
        }

        $imagenPrincipal = $this->guardarArchivo($_FILES['imagen_principal'] ?? [], 'imagen');

        $data = [
            'id_categoria' => (int)($_POST['id_categoria'] ?? 0),
            'nombre' => $this->input('nombre'),
            'marca' => $this->input('marca'),
            'descripcion' => $this->input('descripcion'),
            'precio' => (float)($_POST['precio'] ?? 0),
            'stock' => (int)($_POST['stock'] ?? 0),
            'imagen' => $imagenPrincipal,
            'estado' => $_POST['estado'] ?? 'activo',
        ];

        if ($data['nombre'] === '' || $data['marca'] === '' || $data['precio'] <= 0) {
            flash('danger', 'Nombre, marca y precio son obligatorios.');
            $this->redirect('adminProducto/crear');
        }

        $productoModel = new Producto();
        $mediaModel = new ProductoMedia();
        $idProducto = $productoModel->crear($data);

        if ($imagenPrincipal) {
            $mediaModel->crear($idProducto, 'imagen', $imagenPrincipal, 1);
        }

        if (!empty($_FILES['imagenes']['name'][0])) {
            foreach ($_FILES['imagenes']['name'] as $i => $name) {
                $archivo = [
                    'name' => $_FILES['imagenes']['name'][$i],
                    'type' => $_FILES['imagenes']['type'][$i],
                    'tmp_name' => $_FILES['imagenes']['tmp_name'][$i],
                    'error' => $_FILES['imagenes']['error'][$i],
                    'size' => $_FILES['imagenes']['size'][$i],
                ];
                $nombre = $this->guardarArchivo($archivo, 'imagen');
                if ($nombre) {
                    $mediaModel->crear($idProducto, 'imagen', $nombre, 0);
                }
            }
        }

        if (!empty($_FILES['videos']['name'][0])) {
            foreach ($_FILES['videos']['name'] as $i => $name) {
                $archivo = [
                    'name' => $_FILES['videos']['name'][$i],
                    'type' => $_FILES['videos']['type'][$i],
                    'tmp_name' => $_FILES['videos']['tmp_name'][$i],
                    'error' => $_FILES['videos']['error'][$i],
                    'size' => $_FILES['videos']['size'][$i],
                ];
                $nombre = $this->guardarArchivo($archivo, 'video');
                if ($nombre) {
                    $mediaModel->crear($idProducto, 'video', $nombre, 0);
                }
            }
        }

        flash('success', 'Producto creado correctamente.');
        $this->redirect('adminProducto/index');
    }

    public function editar(): void
    {
        Auth::requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $producto = (new Producto())->buscar($id);
        if (!$producto) {
            flash('danger', 'Producto no encontrado.');
            $this->redirect('adminProducto/index');
        }

        $categorias = (new Categoria())->todas(true);
        $accion = url('adminProducto/actualizar') . '&id=' . $id;
        $this->view('admin/producto_form', compact('categorias', 'producto', 'accion'));
    }

    public function actualizar(): void
    {
        Auth::requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $productoActual = (new Producto())->buscar($id);
        if (!$productoActual) {
            flash('danger', 'Producto no encontrado.');
            $this->redirect('adminProducto/index');
        }

        $nuevaImagen = $this->guardarArchivo($_FILES['imagen_principal'] ?? [], 'imagen');
        $imagenFinal = $nuevaImagen ?: $productoActual['imagen'];

        $data = [
            'id_categoria' => (int)($_POST['id_categoria'] ?? 0),
            'nombre' => $this->input('nombre'),
            'marca' => $this->input('marca'),
            'descripcion' => $this->input('descripcion'),
            'precio' => (float)($_POST['precio'] ?? 0),
            'stock' => (int)($_POST['stock'] ?? 0),
            'imagen' => $imagenFinal,
            'estado' => $_POST['estado'] ?? 'activo',
        ];

        (new Producto())->actualizar($id, $data);
        $mediaModel = new ProductoMedia();

        if ($nuevaImagen) {
            $mediaModel->crear($id, 'imagen', $nuevaImagen, 1);
        }

        if (!empty($_FILES['imagenes']['name'][0])) {
            foreach ($_FILES['imagenes']['name'] as $i => $name) {
                $archivo = [
                    'name' => $_FILES['imagenes']['name'][$i],
                    'type' => $_FILES['imagenes']['type'][$i],
                    'tmp_name' => $_FILES['imagenes']['tmp_name'][$i],
                    'error' => $_FILES['imagenes']['error'][$i],
                    'size' => $_FILES['imagenes']['size'][$i],
                ];
                $nombre = $this->guardarArchivo($archivo, 'imagen');
                if ($nombre) {
                    $mediaModel->crear($id, 'imagen', $nombre, 0);
                }
            }
        }

        if (!empty($_FILES['videos']['name'][0])) {
            foreach ($_FILES['videos']['name'] as $i => $name) {
                $archivo = [
                    'name' => $_FILES['videos']['name'][$i],
                    'type' => $_FILES['videos']['type'][$i],
                    'tmp_name' => $_FILES['videos']['tmp_name'][$i],
                    'error' => $_FILES['videos']['error'][$i],
                    'size' => $_FILES['videos']['size'][$i],
                ];
                $nombre = $this->guardarArchivo($archivo, 'video');
                if ($nombre) {
                    $mediaModel->crear($id, 'video', $nombre, 0);
                }
            }
        }

        flash('success', 'Producto actualizado correctamente.');
        $this->redirect('adminProducto/index');
    }

    public function eliminar(): void
    {
        Auth::requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        (new ProductoMedia())->eliminarPorProducto($id);
        (new Producto())->eliminar($id);
        flash('success', 'Producto eliminado.');
        $this->redirect('adminProducto/index');
    }
}

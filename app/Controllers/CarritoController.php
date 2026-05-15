<?php
class CarritoController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $carrito = new Carrito();
        $items = $carrito->items(Auth::id());
        $total = $carrito->total(Auth::id());
        $this->view('carrito/index', compact('items', 'total'));
    }

    public function agregar(): void
    {
        Auth::requireLogin();
        $idProducto = (int)($_POST['id_producto'] ?? $_GET['id'] ?? 0);
        $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));

        $producto = (new Producto())->buscar($idProducto);
        if (!$producto || $producto['estado'] !== 'activo') {
            flash('danger', 'Producto no disponible.');
            $this->redirect('productos/index');
        }

        if ($cantidad > (int)$producto['stock']) {
            flash('warning', 'No hay suficiente stock disponible.');
            header('Location: ' . url('productos/detalle') . '&id=' . $idProducto);
            exit;
        }

        (new Carrito())->agregar(Auth::id(), $idProducto, $cantidad);
        flash('success', 'Producto agregado al carrito.');
        $this->redirect('carrito/index');
    }

    public function actualizar(): void
    {
        Auth::requireLogin();
        $idProducto = (int)($_POST['id_producto'] ?? 0);
        $cantidad = max(0, (int)($_POST['cantidad'] ?? 0));

        $producto = (new Producto())->buscar($idProducto);
        if ($producto && $cantidad > (int)$producto['stock']) {
            $cantidad = (int)$producto['stock'];
            flash('warning', 'La cantidad fue ajustada al stock disponible.');
        }

        (new Carrito())->actualizarCantidad(Auth::id(), $idProducto, $cantidad);
        flash('success', 'Carrito actualizado.');
        $this->redirect('carrito/index');
    }

    public function eliminar(): void
    {
        Auth::requireLogin();
        $idProducto = (int)($_GET['id'] ?? 0);
        (new Carrito())->eliminar(Auth::id(), $idProducto);
        flash('success', 'Producto eliminado del carrito.');
        $this->redirect('carrito/index');
    }

    public function vaciar(): void
    {
        Auth::requireLogin();
        (new Carrito())->vaciar(Auth::id());
        flash('success', 'Carrito vaciado completamente.');
        $this->redirect('carrito/index');
    }

    public function comprar(): void
{
    Auth::requireLogin();
    try {
        $idVenta = (new Venta())->crearDesdeCarrito(Auth::id());

        $usuario = (new Usuario())->buscarPorId(Auth::id());
        $asunto = "Confirmación de compra #$idVenta";
        $mensaje = "Hola " . $usuario['nombre'] . ",\n\nTu compra ha sido realizada correctamente. Código de venta: #$idVenta\n\nGracias por comprar en Mapache Store.";
        mail($usuario['correo'], $asunto, $mensaje);

        $whatsapp = "https://api.whatsapp.com/send?phone=59164922968&text=" . urlencode("Hola, mi compra #$idVenta ya fue realizada, necesito información.");

        flash('success', "Compra realizada correctamente. Código de venta: #$idVenta. <a href='$whatsapp' target='_blank'>Consultar por WhatsApp</a>");
        $this->redirect('ventas/historial');
    } catch (Exception $e) {
        flash('danger', $e->getMessage());
        $this->redirect('carrito/index');
    }
}
}

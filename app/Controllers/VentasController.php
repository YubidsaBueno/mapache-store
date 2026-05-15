<?php
class VentasController extends Controller
{
    public function historial(): void
    {
        Auth::requireLogin();
        $ventas = (new Venta())->porUsuario(Auth::id());
        $this->view('compras/historial', compact('ventas'));
    }

    public function detalle(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $ventaModel = new Venta();
        $venta = $ventaModel->buscar($id);
        $detalle = $ventaModel->detalle($id);

        if (!$venta) {
            flash('danger', 'Venta no encontrada.');
            $this->redirect('ventas/historial');
        }

        if (!Auth::isAdmin() && (int)$venta['id_usuario'] !== Auth::id()) {
            flash('danger', 'No puedes ver esta venta.');
            $this->redirect('ventas/historial');
        }

        $this->view('admin/venta_detalle', compact('venta', 'detalle'));
    }

    public function admin(): void
    {
        Auth::requireAdmin();
        $ventas = (new Venta())->todas();
        $this->view('admin/ventas', compact('ventas'));
    }

    public function cambiarEstado(): void
    {
        Auth::requireAdmin();
        $id = (int)($_POST['id_venta'] ?? 0);
        $estado = $_POST['estado_venta'] ?? 'pendiente';
        (new Venta())->cambiarEstado($id, $estado);
        flash('success', 'Estado de venta actualizado.');
        $this->redirect('ventas/admin');
    }
}

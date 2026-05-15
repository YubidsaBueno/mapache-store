<?php
class VentasController extends Controller
{
    public function historial(): void
    {
        Auth::requireLogin();

        // Capturamos los filtros enviados por GET
        $filtro = [
            'q' => $_GET['q'] ?? null,          // modelo o marca
            'fecha' => $_GET['fecha'] ?? null,  // fecha de compra
        ];

        // Se pasa al modelo para filtrar compras del usuario
        $ventas = (new Venta())->porUsuarioFiltrado(Auth::id(), $filtro);

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

        // Filtros opcionales por GET
        $filtro = [
            'q' => $_GET['q'] ?? null,
            'fecha' => $_GET['fecha'] ?? null,
        ];

        $ventas = (new Venta())->todasFiltrado($filtro);

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
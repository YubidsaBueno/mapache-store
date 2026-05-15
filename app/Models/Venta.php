<?php
class Venta extends Model
{
    public function crearDesdeCarrito(int $idUsuario): int
    {
        $carrito = new Carrito();
        $productoModel = new Producto();
        $items = $carrito->items($idUsuario);

        if (!$items) {
            throw new Exception('El carrito está vacío.');
        }

        foreach ($items as $item) {
            if ((int)$item['cantidad'] > (int)$item['stock']) {
                throw new Exception('No hay suficiente stock para: ' . $item['nombre']);
            }
        }

        $total = $carrito->total($idUsuario);
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("INSERT INTO ventas (id_usuario, total, estado_venta) VALUES (?, ?, 'pendiente')");
            $stmt->execute([$idUsuario, $total]);
            $idVenta = (int)$this->db->lastInsertId();

            $detalle = $this->db->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");

            foreach ($items as $item) {
                $subtotal = (float)$item['precio'] * (int)$item['cantidad'];
                $detalle->execute([$idVenta, $item['id_producto'], $item['cantidad'], $item['precio'], $subtotal]);
                $productoModel->descontarStock((int)$item['id_producto'], (int)$item['cantidad']);
            }

            $carrito->vaciar($idUsuario);
            $this->db->commit();
            return $idVenta;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function porUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare("SELECT * FROM ventas WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public function todas(): array
    {
        return $this->db->query("SELECT v.*, u.nombre, u.correo
            FROM ventas v
            INNER JOIN usuarios u ON u.id_usuario = v.id_usuario
            ORDER BY v.fecha DESC")->fetchAll();
    }

    public function buscar(int $idVenta): ?array
    {
        $stmt = $this->db->prepare("SELECT v.*, u.nombre, u.correo
            FROM ventas v
            INNER JOIN usuarios u ON u.id_usuario = v.id_usuario
            WHERE v.id_venta = ?");
        $stmt->execute([$idVenta]);
        $venta = $stmt->fetch();
        return $venta ?: null;
    }

    public function detalle(int $idVenta): array
    {
        $stmt = $this->db->prepare("SELECT d.*, p.nombre, p.marca, p.imagen
            FROM detalle_ventas d
            INNER JOIN productos p ON p.id_producto = d.id_producto
            WHERE d.id_venta = ?");
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll();
    }

    public function cambiarEstado(int $idVenta, string $estado): bool
    {
        $permitidos = ['pendiente', 'pagado', 'entregado', 'cancelado'];
        if (!in_array($estado, $permitidos, true)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE ventas SET estado_venta = ? WHERE id_venta = ?");
        return $stmt->execute([$estado, $idVenta]);
    }

    public function contar(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM ventas")->fetchColumn();
    }

    public function totalGanado(): float
    {
        return (float)$this->db->query("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE estado_venta <> 'cancelado'")->fetchColumn();
    }
}

<?php
class Carrito extends Model
{
    public function agregar(int $idUsuario, int $idProducto, int $cantidad = 1): bool
    {
        $stmt = $this->db->prepare("SELECT id_carrito, cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
        $stmt->execute([$idUsuario, $idProducto]);
        $item = $stmt->fetch();

        if ($item) {
            $nuevaCantidad = (int)$item['cantidad'] + $cantidad;
            $update = $this->db->prepare("UPDATE carrito SET cantidad = ? WHERE id_carrito = ?");
            return $update->execute([$nuevaCantidad, $item['id_carrito']]);
        }

        $insert = $this->db->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
        return $insert->execute([$idUsuario, $idProducto, $cantidad]);
    }

    public function items(int $idUsuario): array
    {
        $stmt = $this->db->prepare("SELECT ca.*, p.nombre, p.marca, p.precio, p.stock, p.imagen, p.estado
            FROM carrito ca
            INNER JOIN productos p ON p.id_producto = ca.id_producto
            WHERE ca.id_usuario = ?
            ORDER BY ca.id_carrito DESC");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public function actualizarCantidad(int $idUsuario, int $idProducto, int $cantidad): bool
    {
        if ($cantidad <= 0) {
            return $this->eliminar($idUsuario, $idProducto);
        }

        $stmt = $this->db->prepare("UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
        return $stmt->execute([$cantidad, $idUsuario, $idProducto]);
    }

    public function eliminar(int $idUsuario, int $idProducto): bool
    {
        $stmt = $this->db->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
        return $stmt->execute([$idUsuario, $idProducto]);
    }

    public function vaciar(int $idUsuario): bool
    {
        $stmt = $this->db->prepare("DELETE FROM carrito WHERE id_usuario = ?");
        return $stmt->execute([$idUsuario]);
    }

    public function total(int $idUsuario): float
    {
        $stmt = $this->db->prepare("SELECT SUM(ca.cantidad * p.precio) AS total
            FROM carrito ca
            INNER JOIN productos p ON p.id_producto = ca.id_producto
            WHERE ca.id_usuario = ?");
        $stmt->execute([$idUsuario]);
        return (float)($stmt->fetchColumn() ?: 0);
    }

    public function contar(int $idUsuario): int
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(cantidad), 0) FROM carrito WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        return (int)$stmt->fetchColumn();
    }
}

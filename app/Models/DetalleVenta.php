<?php
class DetalleVenta extends Model
{
    public function porVenta(int $idVenta): array
    {
        $stmt = $this->db->prepare("SELECT d.*, p.nombre, p.marca
            FROM detalle_ventas d
            INNER JOIN productos p ON p.id_producto = d.id_producto
            WHERE d.id_venta = ?");
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll();
    }
}

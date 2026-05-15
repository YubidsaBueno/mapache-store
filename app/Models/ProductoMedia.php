<?php
class ProductoMedia extends Model
{
    public function crear(int $idProducto, string $tipo, string $archivo, int $principal = 0): bool
    {
        if ($principal === 1 && $tipo === 'imagen') {
            $this->db->prepare("UPDATE producto_media SET principal = 0 WHERE id_producto = ? AND tipo = 'imagen'")->execute([$idProducto]);
        }

        $stmt = $this->db->prepare("INSERT INTO producto_media (id_producto, tipo, archivo, principal) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$idProducto, $tipo, $archivo, $principal]);
    }

    public function porProducto(int $idProducto): array
    {
        $stmt = $this->db->prepare("SELECT * FROM producto_media WHERE id_producto = ? ORDER BY principal DESC, id_media ASC");
        $stmt->execute([$idProducto]);
        return $stmt->fetchAll();
    }

    public function eliminarPorProducto(int $idProducto): bool
    {
        $stmt = $this->db->prepare("DELETE FROM producto_media WHERE id_producto = ?");
        return $stmt->execute([$idProducto]);
    }
}

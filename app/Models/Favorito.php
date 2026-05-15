<?php
class Favorito extends Model
{
    public function toggle(int $idUsuario, int $idProducto): string
    {
        if ($this->existe($idUsuario, $idProducto)) {
            $this->eliminar($idUsuario, $idProducto);
            return 'eliminado';
        }

        $stmt = $this->db->prepare("INSERT INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
        $stmt->execute([$idUsuario, $idProducto]);
        return 'agregado';
    }

    public function existe(int $idUsuario, int $idProducto): bool
    {
        $stmt = $this->db->prepare("SELECT id_favorito FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
        $stmt->execute([$idUsuario, $idProducto]);
        return (bool)$stmt->fetch();
    }

    public function eliminar(int $idUsuario, int $idProducto): bool
    {
        $stmt = $this->db->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
        return $stmt->execute([$idUsuario, $idProducto]);
    }

    public function porUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare("SELECT f.*, p.nombre, p.marca, p.precio, p.stock, p.imagen, p.descripcion
            FROM favoritos f
            INNER JOIN productos p ON p.id_producto = f.id_producto
            WHERE f.id_usuario = ?
            ORDER BY f.fecha DESC");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public function contar(int $idUsuario): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favoritos WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        return (int)$stmt->fetchColumn();
    }
}

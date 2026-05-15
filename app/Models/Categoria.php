<?php
class Categoria extends Model
{
    public function todas(bool $soloActivas = false): array
    {
        $sql = "SELECT * FROM categorias";
        if ($soloActivas) {
            $sql .= " WHERE estado = 'activo'";
        }
        $sql .= " ORDER BY nombre_categoria ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
        $stmt->execute([$id]);
        $categoria = $stmt->fetch();
        return $categoria ?: null;
    }

    public function crear(string $nombre, string $descripcion): bool
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre_categoria, descripcion) VALUES (?, ?)");
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function actualizar(int $id, string $nombre, string $descripcion, string $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre_categoria = ?, descripcion = ?, estado = ? WHERE id_categoria = ?");
        return $stmt->execute([$nombre, $descripcion, $estado, $id]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        return $stmt->execute([$id]);
    }

    public function contar(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
    }
}

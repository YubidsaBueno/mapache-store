<?php
class Producto extends Model
{
    public function listar(array $filtros = [], bool $soloActivos = true): array
    {
        $where = [];
        $params = [];

        if ($soloActivos) {
            $where[] = "p.estado = 'activo'";
        }

        if (!empty($filtros['q'])) {
            $where[] = "(p.nombre LIKE ? OR p.marca LIKE ? OR p.descripcion LIKE ?)";
            $q = '%' . $filtros['q'] . '%';
            array_push($params, $q, $q, $q);
        }

        if (!empty($filtros['categoria'])) {
            $where[] = "p.id_categoria = ?";
            $params[] = $filtros['categoria'];
        }

        if (!empty($filtros['marca'])) {
            $where[] = "p.marca = ?";
            $params[] = $filtros['marca'];
        }

        $sql = "SELECT p.*, c.nombre_categoria
                FROM productos p
                LEFT JOIN categorias c ON c.id_categoria = p.id_categoria";

        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY p.id_producto DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function destacados(int $limite = 8): array
    {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre_categoria
            FROM productos p
            LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
            WHERE p.estado = 'activo'
            ORDER BY p.id_producto DESC
            LIMIT ?");
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre_categoria
            FROM productos p
            LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
            WHERE p.id_producto = ? LIMIT 1");
        $stmt->execute([$id]);
        $producto = $stmt->fetch();
        return $producto ?: null;
    }

    public function crear(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO productos
            (id_categoria, nombre, marca, descripcion, precio, stock, imagen, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['id_categoria'],
            $data['nombre'],
            $data['marca'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen'],
            $data['estado'] ?? 'activo'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE productos SET
            id_categoria = ?, nombre = ?, marca = ?, descripcion = ?, precio = ?, stock = ?, imagen = ?, estado = ?
            WHERE id_producto = ?");
        return $stmt->execute([
            $data['id_categoria'],
            $data['nombre'],
            $data['marca'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen'],
            $data['estado'],
            $id
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id_producto = ?");
        return $stmt->execute([$id]);
    }

    public function marcas(): array
    {
        return $this->db->query("SELECT DISTINCT marca FROM productos WHERE marca IS NOT NULL AND marca <> '' ORDER BY marca ASC")->fetchAll();
    }

    public function contar(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    }

    public function bajoStock(int $limite = 5): array
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE stock <= ? ORDER BY stock ASC LIMIT 8");
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }

    public function descontarStock(int $idProducto, int $cantidad): bool
    {
        $stmt = $this->db->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ? AND stock >= ?");
        return $stmt->execute([$cantidad, $idProducto, $cantidad]);
    }
}

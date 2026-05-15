<?php
class Usuario extends Model
{
    public function crear(string $nombre, string $correo, string $password, string $rol = 'cliente'): bool
    {
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña, rol, estado_2fa) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $correo, password_hash($password, PASSWORD_DEFAULT), $rol]);
    }

    public function buscarPorCorreo(string $correo): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function guardarCodigo2FA(int $id, string $codigo): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET codigo_2fa = ? WHERE id_usuario = ?");
        return $stmt->execute([$codigo, $id]);
    }

    public function limpiarCodigo2FA(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET codigo_2fa = NULL WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    public function todos(): array
    {
        return $this->db->query("SELECT id_usuario, nombre, correo, rol, estado_2fa, fecha_registro FROM usuarios ORDER BY id_usuario DESC")->fetchAll();
    }

    public function contar(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }
}

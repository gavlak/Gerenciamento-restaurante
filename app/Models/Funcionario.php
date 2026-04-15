<?php
declare(strict_types=1);

namespace app\Models;

class Funcionario extends BaseModel
{
    protected string $table = 'funcionarios';

    public function create(string $nome, string $cargo, string $telefone): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (nome, cargo, telefone)
             VALUES (:nome, :cargo, :telefone)"
        );
        return $stmt->execute([
            ':nome'     => $nome,
            ':cargo'    => $cargo,
            ':telefone' => $telefone,
        ]);
    }

    public function update(int $id, string $nome, string $cargo, string $telefone): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
             SET nome = :nome, cargo = :cargo, telefone = :telefone
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'       => $id,
            ':nome'     => $nome,
            ':cargo'    => $cargo,
            ':telefone' => $telefone,
        ]);
    }
}

<?php
declare(strict_types=1);

namespace App\Models;

class Produto extends BaseModel
{
    protected string $table = 'produtos';

    public function create(
        string $nome,
        float  $quantidade,
        float  $quantidade_minima,
        string $unidade,
        float  $valor,
        string $data_compra
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table}
                (nome, quantidade, quantidade_minima, unidade, valor, data_compra)
             VALUES
                (:nome, :quantidade, :quantidade_minima, :unidade, :valor, :data_compra)"
        );
        return $stmt->execute([
            ':nome'              => $nome,
            ':quantidade'        => $quantidade,
            ':quantidade_minima' => $quantidade_minima,
            ':unidade'           => $unidade,
            ':valor'             => $valor,
            ':data_compra'       => $data_compra,
        ]);
    }

    public function update(
        int    $id,
        string $nome,
        float  $quantidade,
        float  $quantidade_minima,
        string $unidade,
        float  $valor,
        string $data_compra
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
             SET nome = :nome,
                 quantidade = :quantidade,
                 quantidade_minima = :quantidade_minima,
                 unidade = :unidade,
                 valor = :valor,
                 data_compra = :data_compra
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'                => $id,
            ':nome'              => $nome,
            ':quantidade'        => $quantidade,
            ':quantidade_minima' => $quantidade_minima,
            ':unidade'           => $unidade,
            ':valor'             => $valor,
            ':data_compra'       => $data_compra,
        ]);
    }

    public function findComEstoqueBaixo(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table}
             WHERE quantidade < quantidade_minima
             ORDER BY nome ASC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Lista produtos ordenados alfabeticamente (usado nos cardapios).
     */
    public function findAllOrdenado(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table} ORDER BY nome ASC"
        );
        return $stmt->fetchAll();
    }
}

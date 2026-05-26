<?php
declare(strict_types=1);

namespace App\Models;

class Cardapio extends BaseModel
{
    protected string $table = 'cardapios';

    /**
     * Lista todos os cardápios já trazendo os insumos (produtos associados)
     * em formato agrupado: cada cardápio recebe uma chave 'insumos' com array
     * de nomes de produtos.
     */
    public function findAllComInsumos(): array
    {
        $stmt = $this->db->query(
            "SELECT c.*, GROUP_CONCAT(p.nome ORDER BY p.nome SEPARATOR '||') AS insumos_nomes
             FROM {$this->table} c
             LEFT JOIN cardapio_produtos cp ON cp.cardapio_id = c.id
             LEFT JOIN produtos p          ON p.id = cp.produto_id
             GROUP BY c.id
             ORDER BY c.id DESC"
        );
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['insumos'] = !empty($row['insumos_nomes'])
                ? explode('||', $row['insumos_nomes'])
                : [];
            unset($row['insumos_nomes']);
        }

        return $rows;
    }

    /**
     * Retorna os IDs dos produtos (insumos) associados a um cardápio.
     *
     * @return int[]
     */
    public function getInsumoIds(int $cardapioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT produto_id FROM cardapio_produtos WHERE cardapio_id = :id"
        );
        $stmt->execute([':id' => $cardapioId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'produto_id'));
    }

    public function create(
        string $nome,
        string $dia,
        string $detalhes,
        array  $insumoIds
    ): int {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO {$this->table} (nome, dia, detalhes)
                 VALUES (:nome, :dia, :detalhes)"
            );
            $stmt->execute([
                ':nome'     => $nome,
                ':dia'      => $dia,
                ':detalhes' => $detalhes,
            ]);

            $cardapioId = (int) $this->db->lastInsertId();
            $this->syncInsumos($cardapioId, $insumoIds);

            $this->db->commit();
            return $cardapioId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update(
        int    $id,
        string $nome,
        string $dia,
        string $detalhes,
        array  $insumoIds
    ): bool {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table}
                 SET nome = :nome, dia = :dia, detalhes = :detalhes
                 WHERE id = :id"
            );
            $stmt->execute([
                ':id'       => $id,
                ':nome'     => $nome,
                ':dia'      => $dia,
                ':detalhes' => $detalhes,
            ]);

            $this->syncInsumos($id, $insumoIds);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Remove associações antigas e insere as novas.
     */
    private function syncInsumos(int $cardapioId, array $insumoIds): void
    {
        $del = $this->db->prepare(
            "DELETE FROM cardapio_produtos WHERE cardapio_id = :id"
        );
        $del->execute([':id' => $cardapioId]);

        if (empty($insumoIds)) {
            return;
        }

        $ins = $this->db->prepare(
            "INSERT INTO cardapio_produtos (cardapio_id, produto_id)
             VALUES (:cardapio_id, :produto_id)"
        );

        foreach ($insumoIds as $produtoId) {
            $produtoId = (int) $produtoId;
            if ($produtoId <= 0) {
                continue;
            }
            $ins->execute([
                ':cardapio_id' => $cardapioId,
                ':produto_id'  => $produtoId,
            ]);
        }
    }
}

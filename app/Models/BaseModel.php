<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = $this->connect();
    }

    private function connect(): PDO
    {
        static $pdo = null;

        if ($pdo !== null) {
            return $pdo;
        }

        $cfg = require ROOT_PATH . '/config/database.php';

        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};"
             . "dbname={$cfg['dbname']};charset={$cfg['charset']}";

        try {
            $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die('Falha na conexão com o banco de dados. Verifique as configurações em config/database.php.');
        }

        return $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}

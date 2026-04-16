-- ============================================================
-- Migração para bancos já existentes (mvc_app)
-- Rode este script APENAS se o banco já estiver criado com a
-- versão antiga (sem a coluna 'unidade' em produtos e sem as
-- tabelas de cardápios).
--
-- Se for instalar do zero, use database.sql em vez deste.
-- ============================================================

USE mvc_app;

-- ------------------------------------------------------------
-- 1) Adicionar coluna 'unidade' em produtos
-- ------------------------------------------------------------
ALTER TABLE produtos
    ADD COLUMN unidade VARCHAR(10) NOT NULL DEFAULT 'UN'
    AFTER quantidade_minima;

-- ------------------------------------------------------------
-- 2) Criar tabela de cardápios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cardapios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150)  NOT NULL,
    dia        VARCHAR(20)   NOT NULL,
    detalhes   TEXT          NOT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 3) Criar tabela de ligação cardápio <-> produtos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cardapio_produtos (
    cardapio_id INT UNSIGNED NOT NULL,
    produto_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (cardapio_id, produto_id),
    FOREIGN KEY (cardapio_id) REFERENCES cardapios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id)  REFERENCES produtos(id)  ON DELETE CASCADE
);

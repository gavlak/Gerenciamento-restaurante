-- ============================================================
-- Setup do banco de dados — rodar no phpMyAdmin ou MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS mvc_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mvc_app;

-- ============================================================
-- Tabela de usuários (autenticação)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    email         VARCHAR(150)  NOT NULL UNIQUE,
    password_hash VARCHAR(255)  NOT NULL,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Tabela de produtos (estoque do restaurante)
-- ============================================================
CREATE TABLE IF NOT EXISTS produtos (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome              VARCHAR(200)   NOT NULL,
    quantidade        DECIMAL(10,3)  NOT NULL DEFAULT 0,
    quantidade_minima DECIMAL(10,3)  NOT NULL DEFAULT 1,
    unidade           VARCHAR(10)    NOT NULL DEFAULT 'UN',
    valor             DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    data_compra       DATE,
    created_at        TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Tabela de funcionários
-- ============================================================
CREATE TABLE IF NOT EXISTS funcionarios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150)  NOT NULL,
    cargo      VARCHAR(100)  NOT NULL,
    telefone   VARCHAR(20),
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Tabela de cardápios (cada cardápio tem um dia e detalhes)
-- ============================================================
CREATE TABLE IF NOT EXISTS cardapios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150)  NOT NULL,
    dia        VARCHAR(20)   NOT NULL,
    detalhes   TEXT          NOT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Tabela de ligação cardápio <-> produtos (insumos)
-- ============================================================
CREATE TABLE IF NOT EXISTS cardapio_produtos (
    cardapio_id INT UNSIGNED NOT NULL,
    produto_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (cardapio_id, produto_id),
    FOREIGN KEY (cardapio_id) REFERENCES cardapios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id)  REFERENCES produtos(id)  ON DELETE CASCADE
);

-- ============================================================
-- Usuário de teste — senha: secret123
-- Para gerar um novo hash, rode em PHP:
--   echo password_hash('secret123', PASSWORD_BCRYPT);
-- Depois substitua o valor abaixo pelo hash gerado.
-- ============================================================
INSERT INTO users (name, email, password_hash) VALUES (
    'Admin',
    'admin@example.com',
    '$2y$12$PLACEHOLDER_SUBSTITUA_PELO_HASH_GERADO'
);

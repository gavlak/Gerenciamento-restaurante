-- ============================================================
-- Setup do banco de dados — rodar no phpMyAdmin ou MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS mvc_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mvc_app;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    email         VARCHAR(150)  NOT NULL UNIQUE,
    password_hash VARCHAR(255)  NOT NULL,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    description TEXT          NOT NULL,
    price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
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

# Documentação de Instalação — Gestão Restaurante

## Requisitos

- PHP 8.0 ou superior
- Apache com mod_rewrite habilitado
- MySQL 5.7+ ou MariaDB 10.3+
- XAMPP (recomendado para Windows)
- Extensão PHP cURL habilitada (para leitura de NFC-e)

## 1. Configuração do XAMPP

### 1.1 Habilitar mod_rewrite

Edite o arquivo `C:\xampp\apache\conf\httpd.conf`:

1. Descomente a linha: `LoadModule rewrite_module modules/mod_rewrite.so`
2. Procure o bloco `<Directory>` referente à pasta `htdocs` e altere `AllowOverride None` para `AllowOverride All`
3. Reinicie o Apache no XAMPP Control Panel

### 1.2 Habilitar extensão cURL

Edite o arquivo `C:\xampp\php\php.ini`:

1. Procure a linha `;extension=curl` e remova o `;` (ponto e vírgula) do início
2. Reinicie o Apache

## 2. Instalação do Projeto

1. Copie (ou clone) a pasta do projeto para `C:\xampp\htdocs\PhpProjs`
2. Inicie o Apache e o MySQL no XAMPP Control Panel

## 3. Configuração do Banco de Dados

1. Acesse `http://localhost/phpmyadmin`
2. Clique na aba **SQL**
3. Cole o conteúdo do arquivo `database.sql` e clique em **Executar**
4. Isso criará o banco `mvc_app` com as tabelas: `users`, `produtos`, `funcionarios`

## 4. Configuração da Conexão

O arquivo `config/database.php` contém as credenciais do banco:

```php
return [
    'host'    => '127.0.0.1',
    'port'    => '3306',
    'dbname'  => 'mvc_app',
    'charset' => 'utf8mb4',
    'user'    => 'root',
    'pass'    => '',  // Padrão do XAMPP
];
```

Altere `user` e `pass` caso tenha configurado senha no MySQL.

## 5. Gerar Hash da Senha

1. Acesse `http://localhost/PhpProjs/gerar_hash.php`
2. Copie o hash exibido na tela
3. No phpMyAdmin, vá ao banco `mvc_app` → tabela `users` → edite o registro do Admin
4. Cole o hash no campo `password_hash` e salve
5. Delete o arquivo `gerar_hash.php` após este passo

## 6. Acessar o Sistema

- URL: `http://localhost/PhpProjs/login`
- E-mail: `admin@example.com`
- Senha: `secret123`

## Estrutura do Projeto

```
PhpProjs/
├── index.php              ← Ponto de entrada + rotas
├── .htaccess              ← Regras de rewrite do Apache
├── database.sql           ← Schema do banco
├── config/database.php    ← Credenciais do banco
├── core/
│   ├── Router.php         ← Sistema de rotas
│   └── Session.php        ← Gerenciamento de sessão
├── app/
│   ├── Middleware/AuthMiddleware.php
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   ├── AuthController.php
│   │   ├── ProdutoController.php
│   │   ├── FuncionarioController.php
│   │   └── NotaFiscalController.php
│   └── Models/
│       ├── BaseModel.php
│       ├── User.php
│       ├── Produto.php
│       └── Funcionario.php
└── views/
    ├── layout/header.php / footer.php
    ├── auth/login.php
    ├── produtos/index.php / create.php / edit.php / show.php
    ├── funcionarios/index.php / create.php / edit.php
    └── notas/scanner.php
```

## Funcionalidades

- **Autenticação** com sessão (login/logout)
- **CRUD de Produtos** (estoque) com alerta de estoque baixo
- **CRUD de Funcionários**
- **Leitura de QR Code** de notas fiscais (NFC-e) via webcam para importação automática de produtos
- **Validação server-side** em todos os formulários
- **Mensagens de feedback** (sucesso/erro) via flash messages

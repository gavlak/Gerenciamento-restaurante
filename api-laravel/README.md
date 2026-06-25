# Restaurante API — Web Service (Trabalho 3)

API REST para gestão de restaurante, desenvolvida em **Laravel 12** com autenticação via **Laravel Sanctum**. É a evolução dos Trabalhos 1 e 2 (sistema MVC em PHP), agora exposta como Web Service para que aplicações terceiras possam consumir os dados de **produtos (estoque)**, **funcionários** e **cardápios**.

Disciplina: Desenvolvimento Web Servidor — UTFPR

> 🔗 **A API conecta no mesmo banco de dados (`mvc_app`) do sistema base** (`sistema-php/`). Ela lê e grava os mesmos produtos, funcionários e cardápios — um cadastro feito na tela do sistema aparece na API, e o contrário também.

---

## 👥 Integrantes e atividades

| Integrante | Atividades desenvolvidas |
|------------|--------------------------|
| Giovanni Verardi | API REST em Laravel (Trabalho 3): estrutura do projeto, autenticação com Sanctum, models e migrations, controllers, rotas, validações nos controllers, coleção de testes Postman e documentação |
| Paulo Henrique | Sistema base original (Trabalhos 1 e 2) que definiu o domínio e as regras de negócio (produtos, funcionários e cardápios) reaproveitadas nesta API |

---

## 🧰 Tecnologias

- **PHP 8.2+**
- **Laravel 12**
- **Laravel Sanctum** (autenticação por token)
- **MySQL / MariaDB** (XAMPP)
- **Composer**

---

## ✨ Funcionalidades

- Autenticação por **token Bearer** (login/logout) com Laravel Sanctum
- **CRUD de Produtos** (estoque)
- Endpoint extra: **produtos com estoque abaixo do mínimo**
- **CRUD de Funcionários**
- **CRUD de Cardápios** com vínculo de **insumos** (relacionamento N:N com produtos)
- **Validação server-side** dos campos no próprio controller (gravação e atualização)
- **Respostas JSON padronizadas** via trait (`status`, `message`, `data`)

---

## 📦 Instalação

A documentação completa de instalação e configuração está em **[INSTALL.md](INSTALL.md)**.

Resumo rápido:

```bash
# 1. Instalar dependências
composer install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Ajustar credenciais do banco no .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Criar tabelas e dados de teste
php artisan migrate --seed

# 5. Subir o servidor
php artisan serve
```

A API ficará disponível em `http://127.0.0.1:8000/api`.

---

## 🔑 Autenticação

1. Faça `POST /api/login` com e-mail e senha. A resposta traz um **token**.
2. Envie o token em todas as demais requisições no header:

```
Authorization: Bearer <seu-token>
```

**Usuário de teste (criado pelo seeder):**
- E-mail: `admin@example.com`
- Senha: `secret123`

---

## 🛣️ Rotas da API

Todas as rotas têm o prefixo `/api`. Exceto `login`, todas exigem token.

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/login` | Autentica e retorna o token |
| POST | `/api/logout` | Revoga o token atual |
| GET | `/api/me` | Dados do usuário autenticado |
| GET | `/api/produtos` | Lista produtos |
| POST | `/api/produtos` | Cria produto |
| GET | `/api/produtos/{id}` | Mostra um produto |
| PUT | `/api/produtos/{id}` | Atualiza produto |
| DELETE | `/api/produtos/{id}` | Remove produto |
| GET | `/api/produtos-estoque-baixo` | Lista produtos com estoque abaixo do mínimo |
| GET | `/api/funcionarios` | Lista funcionários |
| POST | `/api/funcionarios` | Cria funcionário |
| GET | `/api/funcionarios/{id}` | Mostra um funcionário |
| PUT | `/api/funcionarios/{id}` | Atualiza funcionário |
| DELETE | `/api/funcionarios/{id}` | Remove funcionário |
| GET | `/api/cardapios` | Lista cardápios (com insumos) |
| POST | `/api/cardapios` | Cria cardápio (aceita `insumos: []`) |
| GET | `/api/cardapios/{id}` | Mostra um cardápio |
| PUT | `/api/cardapios/{id}` | Atualiza cardápio |
| DELETE | `/api/cardapios/{id}` | Remove cardápio |

---

## 📐 Padrão de resposta JSON

**Sucesso** (formato padronizado pelo trait `ApiResponse`):
```json
{
    "status": "success",
    "message": "Produto cadastrado com sucesso.",
    "data": { "id": 4, "nome": "Tomate", "unidade": "KG" }
}
```

**Registro não encontrado (HTTP 404):**
```json
{
    "status": "error",
    "message": "Produto nao encontrado.",
    "errors": null
}
```

**Erro de validação (HTTP 422)** — usa o formato padrão do Laravel (`$request->validate`):
```json
{
    "message": "The nome field must be at least 2 characters.",
    "errors": {
        "nome": ["The nome field must be at least 2 characters."],
        "valor": ["The valor field is required."]
    }
}
```

---

## 🧪 Testes

Os testes estão na coleção do **Postman** em [`postman/Restaurante-API.postman_collection.json`](postman/Restaurante-API.postman_collection.json).

Instruções de importação e execução em **[INSTALL.md](INSTALL.md#-testes-com-postman)**.

---

## 🗂️ Estrutura (principais arquivos)

```
restaurante-api/
├── app/
│   ├── Http/
│   │   └── Controllers/Api/   ← AuthController, ProdutoController, FuncionarioController, CardapioController
│   │                            (validação feita no próprio controller via $request->validate)
│   ├── Models/                ← User, Produto, Funcionario, Cardapio
│   └── Traits/ApiResponse.php ← Padronização das respostas JSON (success/error)
├── database/
│   ├── migrations/            ← Tabelas: produtos, funcionarios, cardapios, cardapio_produtos
│   └── seeders/               ← Usuário admin + dados de exemplo
├── routes/api.php             ← Rotas exclusivas da API (GET, POST, PUT, DELETE)
└── postman/                   ← Coleção de testes
```

---

## ⚠️ Observações e limitações

- As mensagens de validação seguem o padrão do Laravel, em inglês (ex.: *"The nome field is required."*).
- Os erros de **validação (422)** usam o formato padrão do Laravel (`message` + `errors`); as respostas de **sucesso** e de **não encontrado (404)** usam o formato padronizado pelo trait (`status`/`message`/`data`).
- O **401** (sem token) retorna o padrão do Laravel: `{ "message": "Unauthenticated." }`.
- Unidades de medida aceitas em produtos: `UN, KG, G, L, ML, CX, PCT` (normalizadas para maiúsculas).
- Dias aceitos em cardápios: `Segunda, Terca, Quarta, Quinta, Sexta, Sabado, Domingo`.

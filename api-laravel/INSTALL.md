# Documentação de Instalação e Configuração — Restaurante API

## Requisitos

- **PHP 8.2+** (o XAMPP recente já inclui)
- **Composer** ([getcomposer.org](https://getcomposer.org))
- **MySQL 5.7+ / MariaDB 10.3+** (XAMPP)
- Extensões PHP habilitadas: `pdo_mysql`, `mbstring`, `openssl`, `zip` (padrão no XAMPP)

> No XAMPP, o PHP fica em `C:\xampp\php\php.exe`. Se o comando `php` não for reconhecido no terminal, adicione `C:\xampp\php` ao PATH do Windows **ou** use o caminho completo `C:\xampp\php\php.exe` no lugar de `php`.

---

## 1. Obter o projeto

```bash
git clone <URL-DO-REPOSITORIO> restaurante-api
cd restaurante-api
```

---

## 2. Instalar dependências

```bash
composer install
```

Isso baixa o Laravel, o Sanctum e as demais bibliotecas para a pasta `vendor/`.

---

## 3. Configurar o ambiente (.env)

Crie o arquivo `.env` a partir do exemplo e gere a chave da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

No Windows (PowerShell), use `copy .env.example .env` caso `cp` não exista.

Edite o `.env` com as credenciais do seu MySQL (padrão do XAMPP: usuário `root`, sem senha):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mvc_app
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Banco de dados (compartilhado com o sistema base)

A API usa o **mesmo banco** do sistema PHP (`sistema-php/`): o **`mvc_app`**. Dessa forma, os dois sistemas leem e gravam **os mesmos dados** (produtos, funcionários, cardápios) — um cadastro feito na tela do sistema base aparece na API, e vice-versa.

Crie o banco, caso ainda não exista (no **phpMyAdmin** ou no MySQL CLI):

```sql
CREATE DATABASE mvc_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

> Inicie o **Apache** e o **MySQL** no XAMPP Control Panel antes deste passo.

---

## 5. Criar as tabelas

Num banco `mvc_app` **vazio**, rode:

```bash
php artisan migrate --seed
```

Isso cria as tabelas (`users`, `produtos`, `funcionarios`, `cardapios`, `cardapio_produtos`, `personal_access_tokens`) e insere o admin (`admin@example.com` / `secret123`) + dados de exemplo.

> **Se o `mvc_app` já tiver as tabelas** (criadas pelo sistema base do `sistema-php/`), rode apenas a migration de tokens do Sanctum, para não recriar o que já existe:
>
> ```bash
> php artisan migrate --path=database/migrations/2026_06_24_224933_create_personal_access_tokens_table.php
> ```

> Para recriar o banco do zero a qualquer momento: `php artisan migrate:fresh --seed`

---

## 6. Subir o servidor

```bash
php artisan serve
```

A API ficará em **`http://127.0.0.1:8000/api`**.

---

## 🧪 Testes com Postman

A coleção de testes está em `postman/Restaurante-API.postman_collection.json`.

### Como importar

1. Abra o **Postman**
2. Clique em **Import** (canto superior esquerdo)
3. Selecione o arquivo `postman/Restaurante-API.postman_collection.json`
4. A coleção **Restaurante API** aparecerá na barra lateral

### Como executar

1. Rode primeiro **Auth → Login**. Um script de teste salva automaticamente o token na variável de coleção `{{token}}`.
2. As demais requisições já usam `{{token}}` no header `Authorization`. Execute-as na ordem que quiser.

> A variável `{{base_url}}` já vem configurada como `http://127.0.0.1:8000/api`. Se mudar a porta do `artisan serve`, ajuste-a em **Variables** da coleção.

### Lista de testes (rotas e dados)

| # | Requisição | Método | Rota | Corpo de exemplo | Resultado esperado |
|---|-----------|--------|------|------------------|--------------------|
| 1 | Login | POST | `/api/login` | `{ "email": "admin@example.com", "password": "secret123" }` | 200 + token |
| 2 | Login inválido | POST | `/api/login` | senha errada | 422 (credenciais incorretas) |
| 3 | Acesso sem token | GET | `/api/produtos` | — (sem header Authorization) | 401 |
| 4 | Listar produtos | GET | `/api/produtos` | — | 200 + lista |
| 5 | Criar produto | POST | `/api/produtos` | `{ "nome":"Tomate","quantidade":8,"quantidade_minima":4,"unidade":"KG","valor":6.5,"data_compra":"2026-06-20" }` | 201 |
| 6 | Criar produto inválido | POST | `/api/produtos` | `{ "nome":"x","unidade":"TONELADA" }` | 422 + erros por campo |
| 7 | Ver produto | GET | `/api/produtos/1` | — | 200 |
| 8 | Atualizar produto | PUT | `/api/produtos/1` | mesmos campos do POST | 200 |
| 9 | Estoque baixo | GET | `/api/produtos-estoque-baixo` | — | 200 + produtos abaixo do mínimo |
| 10 | Remover produto | DELETE | `/api/produtos/1` | — | 200 |
| 11 | Criar funcionário | POST | `/api/funcionarios` | `{ "nome":"Carla Dias","cargo":"Gerente","telefone":"(45) 98888-1234" }` | 201 |
| 12 | Listar/Ver/Atualizar/Remover funcionário | GET/PUT/DELETE | `/api/funcionarios/{id}` | — | 200 |
| 13 | Criar cardápio com insumos | POST | `/api/cardapios` | `{ "nome":"Jantar","dia":"Sexta","detalhes":"Risoto","insumos":[1,3] }` | 201 + insumos vinculados |
| 14 | Cardápio com dia inválido | POST | `/api/cardapios` | `{ "nome":"X","dia":"Feriado","detalhes":"abc" }` | 422 |
| 15 | Listar/Ver/Atualizar/Remover cardápio | GET/PUT/DELETE | `/api/cardapios/{id}` | — | 200 |

> **Cliente HTTP utilizado:** Postman. As mesmas requisições podem ser feitas no Insomnia ou Thunder Client importando a mesma coleção (formato Postman Collection v2.1).

---

## Solução de problemas

| Problema | Solução |
|----------|---------|
| `php` não reconhecido | Use `C:\xampp\php\php.exe` ou adicione `C:\xampp\php` ao PATH |
| `could not find driver` | Habilite `extension=pdo_mysql` no `php.ini` e reinicie |
| `Access denied for user root` | Ajuste `DB_USERNAME`/`DB_PASSWORD` no `.env` conforme seu MySQL |
| `401 Unauthenticated` | Refaça o login e confira o header `Authorization: Bearer <token>` |
| Mudanças no `.env` não aplicadas | Rode `php artisan config:clear` |

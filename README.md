# Gestão de Restaurante — Desenvolvimento Web Servidor (UTFPR)

Repositório do projeto de gestão de restaurante (produtos/estoque, funcionários e cardápios), desenvolvido ao longo da disciplina **Desenvolvimento Web Servidor**.

O repositório reúne as duas fases do projeto:

| Pasta | Entrega | Tecnologia | O que é |
|-------|---------|------------|---------|
| [`sistema-php/`](sistema-php/) | Trabalhos 1 e 2 | PHP puro (MVC + Simple Router) | Sistema web com telas HTML, autenticação por sessão, CRUD de produtos/funcionários/cardápios e leitura de NFC-e |
| [`api-laravel/`](api-laravel/) | Trabalho 3 | Laravel 12 + Sanctum | API REST (somente JSON) sobre o mesmo domínio, para consumo por aplicações terceiras |

A API do Trabalho 3 (`api-laravel/`) foi desenvolvida no **mesmo contexto/tema** do sistema base e **conecta no mesmo banco de dados (`mvc_app`)** — os dois compartilham os mesmos produtos, funcionários e cardápios. Um cadastro feito na tela do `sistema-php/` aparece na API, e vice-versa.

---

## 👥 Integrantes e atividades

| Integrante | Atividades desenvolvidas |
|------------|--------------------------|
| Giovanni Verardi | API REST em Laravel (`api-laravel/`): estrutura do projeto, autenticação com Sanctum, models e migrations, controllers, rotas, validações, coleção de testes Postman e documentação |
| Paulo Henrique | Sistema base em PHP (`sistema-php/`, Trabalhos 1 e 2): estrutura MVC, autenticação por sessão, CRUDs e leitura de NFC-e que definiram o domínio reaproveitado na API |

---

## 📂 Como navegar

- **Trabalho 3 (API Laravel)** — entrega atual:
  - Documentação e rotas: [`api-laravel/README.md`](api-laravel/README.md)
  - Instalação e testes: [`api-laravel/INSTALL.md`](api-laravel/INSTALL.md)
  - Coleção de testes: [`api-laravel/postman/`](api-laravel/postman/)
- **Sistema base (PHP)** — Trabalhos 1 e 2:
  - Instalação: [`sistema-php/INSTALL.md`](sistema-php/INSTALL.md)

---

## ⚙️ Execução rápida

Cada projeto tem suas próprias dependências e documentação de instalação (links acima). Resumo:

- **`api-laravel/`** → `composer install`, configurar `.env`, `php artisan migrate --seed`, `php artisan serve`
- **`sistema-php/`** → copiar para o `htdocs` do XAMPP, `composer install`, importar `database.sql` no MySQL

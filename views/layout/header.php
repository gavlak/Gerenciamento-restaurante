<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC App</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: system-ui, sans-serif;
            max-width: 860px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
            background: #f5f5f5;
            color: #222;
        }

        nav {
            display: flex;
            gap: 1rem;
            align-items: center;
            background: #1e293b;
            padding: .75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        nav a {
            color: #e2e8f0;
            text-decoration: none;
            font-size: .9rem;
        }

        nav a:hover { text-decoration: underline; }

        nav .spacer { flex: 1; }

        nav .user-info { color: #94a3b8; font-size: .85rem; }

        .card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }

        h2 { margin-top: 0; }

        .alert {
            padding: .75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: .9rem;
        }

        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
        .alert-error   { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        .field-error { color: #dc2626; font-size: .8rem; display: block; margin-top: .2rem; }

        label { display: block; margin-bottom: .25rem; font-weight: 500; font-size: .9rem; }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: .5rem .75rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
            background: #fff;
        }

        input.is-invalid,
        textarea.is-invalid { border-color: #dc2626; }

        .form-group { margin-bottom: 1rem; }

        button, .btn {
            display: inline-block;
            padding: .5rem 1.1rem;
            border: none;
            border-radius: 4px;
            font-size: .9rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary  { background: #2563eb; color: #fff; }
        .btn-primary:hover  { background: #1d4ed8; }
        .btn-danger   { background: #dc2626; color: #fff; }
        .btn-danger:hover   { background: #b91c1c; }
        .btn-secondary { background: #e2e8f0; color: #374151; }
        .btn-secondary:hover { background: #cbd5e1; }

        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        th, td { padding: .6rem .8rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-weight: 600; }
        tr:hover td { background: #f8fafc; }

        .actions { display: flex; gap: .4rem; align-items: center; }
    </style>
</head>
<body>
<nav>
    <?php if (\Session::has('user_id')): ?>
        <a href="<?= BASE_URL ?>/items">Itens</a>
        <a href="<?= BASE_URL ?>/items/create">+ Novo Item</a>
        <span class="spacer"></span>
        <span class="user-info">Olá, <?= htmlspecialchars(\Session::get('user_name', ''), ENT_QUOTES) ?></span>
        <a href="<?= BASE_URL ?>/logout">Sair</a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/login">Login</a>
    <?php endif; ?>
</nav>
<div class="card">

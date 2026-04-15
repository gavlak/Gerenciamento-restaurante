<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Restaurante</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f2f5;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        nav {
            background: #1e293b;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            height: 56px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }

        .nav-brand {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            margin-right: 2rem;
            letter-spacing: -.5px;
        }

        .nav-links {
            display: flex;
            gap: .25rem;
            list-style: none;
            flex: 1;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: .875rem;
            padding: .5rem .85rem;
            border-radius: 6px;
            transition: background .15s, color .15s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: #334155;
            color: #fff;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-right .user-info {
            color: #94a3b8;
            font-size: .85rem;
        }

        .nav-right a {
            color: #94a3b8;
            text-decoration: none;
            font-size: .85rem;
            padding: .4rem .75rem;
            border-radius: 6px;
            border: 1px solid #475569;
            transition: background .15s, color .15s;
        }

        .nav-right a:hover {
            background: #475569;
            color: #fff;
        }

        /* ===== MAIN CONTENT ===== */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 1.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
        }

        /* ===== TIPOGRAFIA ===== */
        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .25rem;
        }

        /* ===== ALERTAS ===== */
        .alert {
            padding: .75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: .875rem;
            line-height: 1.5;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .field-error {
            color: #dc2626;
            font-size: .8rem;
            display: block;
            margin-top: .25rem;
        }

        /* ===== FORMULÁRIOS ===== */
        label {
            display: block;
            margin-bottom: .3rem;
            font-weight: 600;
            font-size: .875rem;
            color: #374151;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: .6rem .85rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: .95rem;
            background: #fff;
            color: #1e293b;
            transition: border-color .15s, box-shadow .15s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.15);
        }

        input.is-invalid,
        textarea.is-invalid {
            border-color: #dc2626;
        }

        input.is-invalid:focus,
        textarea.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220,38,38,.15);
        }

        .form-group { margin-bottom: 1.1rem; }

        /* ===== BOTÕES ===== */
        button, .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.15rem;
            border: none;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s, transform .1s;
        }

        button:active, .btn:active { transform: scale(.97); }

        .btn-primary  { background: #2563eb; color: #fff; }
        .btn-primary:hover  { background: #1d4ed8; }

        .btn-danger   { background: #dc2626; color: #fff; }
        .btn-danger:hover   { background: #b91c1c; }

        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-secondary:hover { background: #d1d5db; }

        /* ===== TABELAS ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
        }

        th, td {
            padding: .7rem .85rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        tr:hover td { background: #f8fafc; }
        tr:last-child td { border-bottom: none; }

        .actions {
            display: flex;
            gap: .35rem;
            align-items: center;
        }

        /* ===== UTILITÁRIOS ===== */
        .badge-danger {
            background: #fef2f2;
            color: #dc2626;
            font-size: .7rem;
            font-weight: 700;
            padding: .2rem .5rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        details summary {
            cursor: pointer;
            font-size: .9rem;
            color: #6b7280;
        }

        details summary:hover { color: #374151; }
    </style>
</head>
<body>
<nav>
    <?php if (\Session::has('user_id')): ?>
        <a href="<?= BASE_URL ?>/produtos" class="nav-brand">Gestao Restaurante</a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>/produtos">Estoque</a>
            <a href="<?= BASE_URL ?>/funcionarios">Funcionarios</a>
            <a href="<?= BASE_URL ?>/notas/scanner">Ler QR Code</a>
        </div>
        <div class="nav-right">
            <span class="user-info">Ola, <?= htmlspecialchars(\Session::get('user_name', ''), ENT_QUOTES) ?></span>
            <a href="<?= BASE_URL ?>/logout">Sair</a>
        </div>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/login" class="nav-brand">Gestao Restaurante</a>
    <?php endif; ?>
</nav>
<div class="container">
<div class="card">

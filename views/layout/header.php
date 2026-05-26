<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gavis Restaurante</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 15px;
            line-height: 1.5;
            background: #f0f2f5;
            color: #1e293b;
            min-height: 100vh;
        }

        button, input, select, textarea {
            font-family: inherit;
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
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .5rem;
            letter-spacing: -.5px;
        }

        h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .5rem;
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

        /* ===== CARDS GRID ===== */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-top: 1rem;
        }

        .cardapio-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            transition: transform .15s, box-shadow .15s;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .cardapio-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        .cardapio-card h3 {
            font-size: 1.15rem;
            margin: 0;
            color: #0f172a;
        }

        .cardapio-card .dia-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: .75rem;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: .5px;
            align-self: flex-start;
        }

        .cardapio-card .detalhes {
            color: #475569;
            font-size: .875rem;
            line-height: 1.5;
            flex: 1;
        }

        .cardapio-card .insumos {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
        }

        .cardapio-card .insumos span {
            background: #f1f5f9;
            color: #475569;
            font-size: .75rem;
            padding: .2rem .55rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .cardapio-card .card-actions {
            display: flex;
            gap: .4rem;
            padding-top: .75rem;
            border-top: 1px solid #f1f5f9;
        }

        /* ===== CHECKBOX LIST ===== */
        .checkbox-list {
            max-height: 240px;
            overflow-y: auto;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: .5rem .75rem;
            background: #fff;
        }

        .checkbox-list label {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem 0;
            margin-bottom: 0;
            font-weight: 400;
            font-size: .9rem;
            cursor: pointer;
        }

        .checkbox-list input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        /* ===== INPUT GROUP (quantidade + unidade) ===== */
        .input-group {
            display: flex;
            gap: .5rem;
        }

        .input-group input { flex: 1; }
        .input-group select { max-width: 120px; }

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
    <?php if (\Core\Session::has('user_id')): ?>
        <a href="<?= BASE_URL ?>/produtos" class="nav-brand">Gavis Restaurante</a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>/produtos">Estoque</a>
            <a href="<?= BASE_URL ?>/funcionarios">Funcionarios</a>
            <a href="<?= BASE_URL ?>/cardapios">Cardapios</a>
            <a href="<?= BASE_URL ?>/notas/scanner">Ler QR Code</a>
        </div>
        <div class="nav-right">
            <a href="<?= BASE_URL ?>/logout">Sair</a>
        </div>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/login" class="nav-brand">Gavis Restaurante</a>
    <?php endif; ?>
</nav>
<div class="container">
<div class="card">

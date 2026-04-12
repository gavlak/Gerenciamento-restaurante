<?php declare(strict_types=1); ?>

<h2>Detalhes do Item</h2>

<table style="max-width:500px;">
    <tr>
        <th>ID</th>
        <td><?= (int) $item['id'] ?></td>
    </tr>
    <tr>
        <th>Nome</th>
        <td><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></td>
    </tr>
    <tr>
        <th>Descrição</th>
        <td><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></td>
    </tr>
    <tr>
        <th>Preço</th>
        <td>R$ <?= number_format((float) $item['price'], 2, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Cadastrado em</th>
        <td><?= htmlspecialchars($item['created_at'], ENT_QUOTES) ?></td>
    </tr>
</table>

<div style="display:flex; gap:.5rem; margin-top:1rem;">
    <a href="<?= BASE_URL ?>/items/edit/<?= (int) $item['id'] ?>" class="btn btn-primary">Editar</a>
    <a href="<?= BASE_URL ?>/items" class="btn btn-secondary">Voltar</a>
</div>

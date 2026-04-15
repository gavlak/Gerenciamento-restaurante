<?php declare(strict_types=1); ?>

<?php $estoqueBaixo = (float) $produto['quantidade'] < (float) $produto['quantidade_minima']; ?>

<h2>Detalhes do Produto</h2>

<?php if ($estoqueBaixo): ?>
    <div class="alert alert-error">Atenção: estoque abaixo do mínimo!</div>
<?php endif; ?>

<table style="max-width: 500px;">
    <tr>
        <th>ID</th>
        <td><?= (int) $produto['id'] ?></td>
    </tr>
    <tr>
        <th>Nome</th>
        <td><?= htmlspecialchars($produto['nome'], ENT_QUOTES) ?></td>
    </tr>
    <tr>
        <th>Quantidade</th>
        <td><?= number_format((float) $produto['quantidade'], 1, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Quantidade Mínima</th>
        <td><?= number_format((float) $produto['quantidade_minima'], 1, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Valor Unitário</th>
        <td>R$ <?= number_format((float) $produto['valor'], 2, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Data de Compra</th>
        <td><?= $produto['data_compra'] ? htmlspecialchars($produto['data_compra'], ENT_QUOTES) : '—' ?></td>
    </tr>
    <tr>
        <th>Cadastrado em</th>
        <td><?= htmlspecialchars($produto['created_at'], ENT_QUOTES) ?></td>
    </tr>
</table>

<div style="margin-top:1rem; display:flex; gap:.5rem;">
    <a href="<?= BASE_URL ?>/produtos/edit/<?= (int) $produto['id'] ?>" class="btn btn-primary">Editar</a>
    <a href="<?= BASE_URL ?>/produtos" class="btn btn-secondary">Voltar</a>
</div>

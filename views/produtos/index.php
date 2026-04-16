<?php declare(strict_types=1); ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h2 style="margin:0;">Estoque</h2>
    <a href="<?= BASE_URL ?>/produtos/create" class="btn btn-primary">+ Novo Produto</a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (empty($produtos)): ?>
    <p>Nenhum produto cadastrado. <a href="<?= BASE_URL ?>/produtos/create">Cadastre o primeiro.</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Qtd</th>
                <th>Qtd Mín.</th>
                <th>Valor</th>
                <th>Data Compra</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <?php $estoqueBaixo = (float) $produto['quantidade'] < (float) $produto['quantidade_minima']; ?>
            <?php $unidade = $produto['unidade'] ?? 'UN'; ?>
            <tr style="<?= $estoqueBaixo ? 'background:#fee2e2;' : '' ?>">
                <td><?= (int) $produto['id'] ?></td>
                <td>
                    <?= htmlspecialchars($produto['nome'], ENT_QUOTES) ?>
                    <?php if ($estoqueBaixo): ?>
                        <span class="badge-danger" style="margin-left:.4rem;">Estoque Baixo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?= number_format((float) $produto['quantidade'], 2, ',', '.') ?>
                    <span style="color:#6b7280; font-size:.8rem;"><?= htmlspecialchars($unidade, ENT_QUOTES) ?></span>
                </td>
                <td>
                    <?= number_format((float) $produto['quantidade_minima'], 2, ',', '.') ?>
                    <span style="color:#6b7280; font-size:.8rem;"><?= htmlspecialchars($unidade, ENT_QUOTES) ?></span>
                </td>
                <td>R$ <?= number_format((float) $produto['valor'], 2, ',', '.') ?></td>
                <td><?= $produto['data_compra'] ? htmlspecialchars($produto['data_compra'], ENT_QUOTES) : '—' ?></td>
                <td>
                    <div class="actions">
                        <a href="<?= BASE_URL ?>/produtos/<?= (int) $produto['id'] ?>" class="btn btn-secondary">Ver</a>
                        <a href="<?= BASE_URL ?>/produtos/edit/<?= (int) $produto['id'] ?>" class="btn btn-secondary">Editar</a>
                        <form method="POST" action="<?= BASE_URL ?>/produtos/delete/<?= (int) $produto['id'] ?>"
                              onsubmit="return confirm('Remover este produto?')">
                            <button type="submit" class="btn btn-danger">Remover</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

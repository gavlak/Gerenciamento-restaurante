<?php declare(strict_types=1); ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h2 style="margin:0;">Itens</h2>
    <a href="<?= BASE_URL ?>/items/create" class="btn btn-primary">+ Novo Item</a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (empty($items)): ?>
    <p>Nenhum item cadastrado. <a href="<?= BASE_URL ?>/items/create">Cadastre o primeiro.</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Cadastrado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= (int) $item['id'] ?></td>
                <td><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></td>
                <td>R$ <?= number_format((float) $item['price'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($item['created_at'], ENT_QUOTES) ?></td>
                <td>
                    <div class="actions">
                        <a href="<?= BASE_URL ?>/items/<?= (int) $item['id'] ?>" class="btn btn-secondary">Ver</a>
                        <a href="<?= BASE_URL ?>/items/edit/<?= (int) $item['id'] ?>" class="btn btn-secondary">Editar</a>
                        <form method="POST" action="<?= BASE_URL ?>/items/delete/<?= (int) $item['id'] ?>"
                              onsubmit="return confirm('Remover este item?')">
                            <button type="submit" class="btn btn-danger">Remover</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

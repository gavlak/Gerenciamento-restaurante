<?php declare(strict_types=1); ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h2 style="margin:0;">Funcionários</h2>
    <a href="<?= BASE_URL ?>/funcionarios/create" class="btn btn-primary">+ Novo Funcionário</a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (empty($funcionarios)): ?>
    <p>Nenhum funcionário cadastrado. <a href="<?= BASE_URL ?>/funcionarios/create">Cadastre o primeiro.</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($funcionarios as $f): ?>
            <tr>
                <td><?= (int) $f['id'] ?></td>
                <td><?= htmlspecialchars($f['nome'], ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($f['cargo'], ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($f['telefone'] ?? '—', ENT_QUOTES) ?></td>
                <td>
                    <div class="actions">
                        <a href="<?= BASE_URL ?>/funcionarios/edit/<?= (int) $f['id'] ?>" class="btn btn-secondary">Editar</a>
                        <form method="POST" action="<?= BASE_URL ?>/funcionarios/delete/<?= (int) $f['id'] ?>"
                              onsubmit="return confirm('Remover este funcionário?')">
                            <button type="submit" class="btn btn-danger">Remover</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

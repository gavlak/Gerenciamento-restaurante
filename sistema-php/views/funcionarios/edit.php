<?php declare(strict_types=1); ?>

<h2>Editar Funcionário</h2>

<form method="POST" action="<?= BASE_URL ?>/funcionarios/update/<?= (int) $funcionario['id'] ?>" style="max-width: 500px;">

    <div class="form-group">
        <label for="nome">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= htmlspecialchars($old['nome'] ?? $funcionario['nome'], ENT_QUOTES) ?>"
            class="<?= !empty($errors['nome']) ? 'is-invalid' : '' ?>"
        >
        <?php if (!empty($errors['nome'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['nome'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="cargo">Cargo</label>
        <input
            type="text"
            id="cargo"
            name="cargo"
            value="<?= htmlspecialchars($old['cargo'] ?? $funcionario['cargo'], ENT_QUOTES) ?>"
            class="<?= !empty($errors['cargo']) ? 'is-invalid' : '' ?>"
        >
        <?php if (!empty($errors['cargo'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['cargo'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="telefone">Telefone</label>
        <input
            type="text"
            id="telefone"
            name="telefone"
            value="<?= htmlspecialchars($old['telefone'] ?? ($funcionario['telefone'] ?? ''), ENT_QUOTES) ?>"
        >
    </div>

    <div style="display:flex; gap:.5rem;">
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="<?= BASE_URL ?>/funcionarios" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

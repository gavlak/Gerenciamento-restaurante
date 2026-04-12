<?php declare(strict_types=1); ?>

<h2>Novo Item</h2>

<form method="POST" action="<?= BASE_URL ?>/items/store" style="max-width: 500px;">

    <div class="form-group">
        <label for="name">Nome</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES) ?>"
            class="<?= !empty($errors['name']) ? 'is-invalid' : '' ?>"
        >
        <?php if (!empty($errors['name'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['name'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="description">Descrição</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="<?= !empty($errors['description']) ? 'is-invalid' : '' ?>"
        ><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES) ?></textarea>
        <?php if (!empty($errors['description'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['description'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="price">Preço (R$)</label>
        <input
            type="text"
            id="price"
            name="price"
            value="<?= htmlspecialchars($old['price'] ?? '', ENT_QUOTES) ?>"
            class="<?= !empty($errors['price']) ? 'is-invalid' : '' ?>"
            placeholder="Ex: 29.90"
        >
        <?php if (!empty($errors['price'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['price'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div style="display:flex; gap:.5rem;">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= BASE_URL ?>/items" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

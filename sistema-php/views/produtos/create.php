<?php declare(strict_types=1); ?>

<h2>Novo Produto</h2>

<form method="POST" action="<?= BASE_URL ?>/produtos/store" style="max-width: 520px;">

    <div class="form-group">
        <label for="nome">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= htmlspecialchars($old['nome'] ?? '', ENT_QUOTES) ?>"
            class="<?= !empty($errors['nome']) ? 'is-invalid' : '' ?>"
        >
        <?php if (!empty($errors['nome'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['nome'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="quantidade">Quantidade</label>
        <div class="input-group">
            <input
                type="text"
                id="quantidade"
                name="quantidade"
                value="<?= htmlspecialchars($old['quantidade'] ?? '', ENT_QUOTES) ?>"
                class="<?= !empty($errors['quantidade']) ? 'is-invalid' : '' ?>"
                placeholder="Ex: 2.5"
            >
            <select
                id="unidade"
                name="unidade"
                class="<?= !empty($errors['unidade']) ? 'is-invalid' : '' ?>"
            >
                <?php $unidadeAtual = $old['unidade'] ?? 'UN'; ?>
                <?php foreach ($unidades as $u): ?>
                    <option value="<?= $u ?>" <?= $unidadeAtual === $u ? 'selected' : '' ?>><?= $u ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (!empty($errors['quantidade'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['quantidade'], ENT_QUOTES) ?></span>
        <?php endif; ?>
        <?php if (!empty($errors['unidade'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['unidade'], ENT_QUOTES) ?></span>
        <?php endif; ?>
        <small style="color:#6b7280; font-size:.8rem; display:block; margin-top:.35rem;">
            Aceita valores decimais. Ex: 2.5 KG, 0.75 L, 10 UN
        </small>
    </div>

    <div class="form-group">
        <label for="quantidade_minima">Quantidade Mínima</label>
        <input
            type="text"
            id="quantidade_minima"
            name="quantidade_minima"
            value="<?= htmlspecialchars($old['quantidade_minima'] ?? '1', ENT_QUOTES) ?>"
            class="<?= !empty($errors['quantidade_minima']) ? 'is-invalid' : '' ?>"
            placeholder="Ex: 1"
        >
        <?php if (!empty($errors['quantidade_minima'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['quantidade_minima'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="valor">Valor Unitário (R$)</label>
        <input
            type="text"
            id="valor"
            name="valor"
            value="<?= htmlspecialchars($old['valor'] ?? '', ENT_QUOTES) ?>"
            class="<?= !empty($errors['valor']) ? 'is-invalid' : '' ?>"
            placeholder="Ex: 29.90"
        >
        <?php if (!empty($errors['valor'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['valor'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="data_compra">Data de Compra</label>
        <input
            type="date"
            id="data_compra"
            name="data_compra"
            value="<?= htmlspecialchars($old['data_compra'] ?? date('Y-m-d'), ENT_QUOTES) ?>"
            class="<?= !empty($errors['data_compra']) ? 'is-invalid' : '' ?>"
        >
        <?php if (!empty($errors['data_compra'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['data_compra'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div style="display:flex; gap:.5rem;">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= BASE_URL ?>/produtos" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

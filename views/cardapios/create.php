<?php declare(strict_types=1); ?>

<h2>Novo Cardapio</h2>

<form method="POST" action="<?= BASE_URL ?>/cardapios/store" style="max-width: 560px;">

    <div class="form-group">
        <label for="nome">Nome do Cardapio</label>
        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= htmlspecialchars($old['nome'] ?? '', ENT_QUOTES) ?>"
            class="<?= !empty($errors['nome']) ? 'is-invalid' : '' ?>"
            placeholder="Ex: Almoco Executivo"
        >
        <?php if (!empty($errors['nome'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['nome'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="dia">Dia</label>
        <select
            id="dia"
            name="dia"
            class="<?= !empty($errors['dia']) ? 'is-invalid' : '' ?>"
        >
            <option value="">-- Selecione --</option>
            <?php $diaAtual = $old['dia'] ?? ''; ?>
            <?php foreach ($dias as $d): ?>
                <option value="<?= $d ?>" <?= $diaAtual === $d ? 'selected' : '' ?>><?= $d ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['dia'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['dia'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="detalhes">Detalhes</label>
        <textarea
            id="detalhes"
            name="detalhes"
            rows="5"
            class="<?= !empty($errors['detalhes']) ? 'is-invalid' : '' ?>"
            placeholder="Descreva os pratos, observacoes, alergenicos..."
        ><?= htmlspecialchars($old['detalhes'] ?? '', ENT_QUOTES) ?></textarea>
        <?php if (!empty($errors['detalhes'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['detalhes'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Insumos (Produtos utilizados)</label>
        <?php if (empty($produtos)): ?>
            <p style="color:#6b7280; font-size:.875rem;">
                Nenhum produto cadastrado ainda. <a href="<?= BASE_URL ?>/produtos/create">Cadastre um produto primeiro</a>.
            </p>
        <?php else: ?>
            <?php $selecionados = $selecionados ?? []; ?>
            <div class="checkbox-list">
                <?php foreach ($produtos as $produto): ?>
                    <label>
                        <input
                            type="checkbox"
                            name="insumos[]"
                            value="<?= (int) $produto['id'] ?>"
                            <?= in_array((int) $produto['id'], $selecionados, true) ? 'checked' : '' ?>
                        >
                        <?= htmlspecialchars($produto['nome'], ENT_QUOTES) ?>
                        <span style="color:#9ca3af; font-size:.8rem;">
                            (<?= htmlspecialchars($produto['unidade'] ?? 'UN', ENT_QUOTES) ?>)
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="display:flex; gap:.5rem;">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= BASE_URL ?>/cardapios" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<?php declare(strict_types=1); ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h2 style="margin:0;">Cardapios</h2>
    <a href="<?= BASE_URL ?>/cardapios/create" class="btn btn-primary">+ Novo Cardapio</a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (empty($cardapios)): ?>
    <p>Nenhum cardapio cadastrado. <a href="<?= BASE_URL ?>/cardapios/create">Crie o primeiro.</a></p>
<?php else: ?>
    <div class="cards-grid">
        <?php foreach ($cardapios as $cardapio): ?>
            <div class="cardapio-card">
                <span class="dia-badge"><?= htmlspecialchars($cardapio['dia'], ENT_QUOTES) ?></span>
                <h3><?= htmlspecialchars($cardapio['nome'], ENT_QUOTES) ?></h3>

                <p class="detalhes">
                    <?= nl2br(htmlspecialchars($cardapio['detalhes'], ENT_QUOTES)) ?>
                </p>

                <?php if (!empty($cardapio['insumos'])): ?>
                    <div>
                        <div style="font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.35rem;">
                            Insumos
                        </div>
                        <div class="insumos">
                            <?php foreach ($cardapio['insumos'] as $insumo): ?>
                                <span><?= htmlspecialchars($insumo, ENT_QUOTES) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="font-size:.8rem; color:#9ca3af; font-style:italic;">
                        Sem insumos associados
                    </div>
                <?php endif; ?>

                <div class="card-actions">
                    <a href="<?= BASE_URL ?>/cardapios/edit/<?= (int) $cardapio['id'] ?>" class="btn btn-secondary">Editar</a>
                    <form method="POST" action="<?= BASE_URL ?>/cardapios/delete/<?= (int) $cardapio['id'] ?>"
                          onsubmit="return confirm('Remover este cardapio?')">
                        <button type="submit" class="btn btn-danger">Remover</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

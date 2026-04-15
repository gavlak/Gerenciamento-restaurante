<?php declare(strict_types=1); ?>

<div style="max-width: 380px; margin: 1rem auto;">
    <h2 style="text-align:center; margin-bottom:.5rem;">Entrar</h2>
    <p style="text-align:center; color:#6b7280; font-size:.9rem; margin-bottom:1.5rem;">
        Acesse o sistema de gestao do restaurante
    </p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($errors['general'], ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login">

        <div class="form-group">
            <label for="email">E-mail</label>
            <input
                type="text"
                id="email"
                name="email"
                value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES) ?>"
                class="<?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                autocomplete="email"
                placeholder="seu@email.com"
            >
            <?php if (!empty($errors['email'])): ?>
                <span class="field-error"><?= htmlspecialchars($errors['email'], ENT_QUOTES) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input
                type="password"
                id="password"
                name="password"
                class="<?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                autocomplete="current-password"
                placeholder="Digite sua senha"
            >
            <?php if (!empty($errors['password'])): ?>
                <span class="field-error"><?= htmlspecialchars($errors['password'], ENT_QUOTES) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:.7rem;">Entrar</button>

    </form>
</div>

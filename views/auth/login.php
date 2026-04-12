<?php declare(strict_types=1); ?>

<h2>Login</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($errors['general'], ENT_QUOTES) ?></div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/login" style="max-width: 400px;">

    <div class="form-group">
        <label for="email">E-mail</label>
        <input
            type="text"
            id="email"
            name="email"
            value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES) ?>"
            class="<?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
            autocomplete="email"
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
        >
        <?php if (!empty($errors['password'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['password'], ENT_QUOTES) ?></span>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Entrar</button>

</form>

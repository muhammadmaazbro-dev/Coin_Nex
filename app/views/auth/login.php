<?php
declare(strict_types=1);
?>
<div class="mb-3">
    <h1 style="font-size:1.7rem;"><?= Helper::icon('zap', '22') ?> Welcome back</h1>
    <p class="muted small">Log in to continue your smart spending journey.</p>
</div>

<form method="post" action="<?= Helper::url('login') ?>" autocomplete="on">
    <?= Helper::csrfField() ?>

    <div class="field">
        <label for="email">Email address</label>
        <input class="input" type="email" id="email" name="email" value="<?= Helper::old('email') ?>" placeholder="you@university.edu" required autofocus>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <div class="input-group">
            <input class="input" type="password" id="password" name="password" placeholder="••••••••" required>
            <button type="button" class="btn btn-icon btn-dark" onclick="togglePass(this)" data-target="password" aria-label="Show password">
                <span data-eye=""><?= Helper::icon('eye', '16') ?></span>
                <span data-eye-off="" class="hide"><?= Helper::icon('eye-off', '16') ?></span>
            </button>
        </div>
        <div class="flex between items-center">
            <span></span>
            <a href="<?= Helper::url('forgot-password') ?>" class="small" style="font-weight:600;">Forgot password?</a>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2" style="justify-content:center;">Log in <?= Helper::icon('chevron-right', '17') ?></button>
</form>

<div class="flex items-center gap-2" style="margin:1.4rem 0;">
    <div style="flex:1;height:1px;background:var(--border);"></div>
    <span class="faint small">or</span>
    <div style="flex:1;height:1px;background:var(--border);"></div>
</div>

<div class="flex center gap-2 wrap">
    <span class="muted small">First time here?</span>
    <a href="<?= Helper::url('register') ?>" class="btn btn-dark btn-sm">Create a free account</a>
</div>

<script>
function togglePass(btn) {
    const el = document.getElementById(btn.dataset.target);
    const isPass = el.type === 'password';
    el.type = isPass ? 'text' : 'password';
    const on = btn.querySelector('[data-eye]');
    const off = btn.querySelector('[data-eye-off]');
    if (on) on.classList.toggle('hide', !isPass);
    if (off) off.classList.toggle('hide', isPass);
}
</script>
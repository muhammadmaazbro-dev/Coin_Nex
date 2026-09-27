<?php
declare(strict_types=1);
?>
<div class="mb-3">
    <h1 style="font-size:1.7rem;"><?= Helper::icon('lock', '22') ?> Reset your password</h1>
    <p class="muted small">Enter your account email and we'll send you a secure reset link.</p>
</div>

<form method="post" action="<?= Helper::url('forgot-password') ?>">
    <?= Helper::csrfField() ?>

    <div class="field">
        <label for="email">Email address</label>
        <input class="input" type="email" id="email" name="email" placeholder="you@university.edu" required autofocus>
    </div>

    <button type="submit" class="btn btn-primary btn-block" style="justify-content:center;">Send reset link</button>
</form>

<div class="flex center gap-2 mt-3">
    <span class="muted small">Remembered it?</span>
    <a href="<?= Helper::url('login') ?>" class="btn btn-dark btn-sm">Back to login</a>
</div>
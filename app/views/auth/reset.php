<?php
declare(strict_types=1);
?>
<div class="mb-3">
    <h1 style="font-size:1.7rem;"><?= Helper::icon('shield', '22') ?> Choose a new password</h1>
    <p class="muted small">Make it at least 8 characters — and don't reuse your old one!</p>
</div>

<form method="post" action="<?= Helper::url('reset-password/' . $token) ?>">
    <?= Helper::csrfField() ?>

    <div class="field">
        <label for="password">New password</label>
        <input class="input" type="password" id="password" name="password" placeholder="Min 8 characters" required minlength="8">
    </div>

    <div class="field">
        <label for="password_confirm">Confirm new password</label>
        <input class="input" type="password" id="password_confirm" name="password_confirm" placeholder="Repeat password" required minlength="8">
    </div>

    <button type="submit" class="btn btn-primary btn-block" style="justify-content:center;">Update password</button>
</form>

<div class="flex center gap-2 mt-3">
    <span class="muted small">Changed your mind?</span>
    <a href="<?= Helper::url('login') ?>" class="btn btn-dark btn-sm">Back to login</a>
</div>
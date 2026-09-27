<?php
declare(strict_types=1);
?>
<div class="mb-3">
    <h1 style="font-size:1.7rem;"><?= Helper::icon('sparkle', '22') ?> Create your account</h1>
    <p class="muted small">Takes less than a minute. No bank account required.</p>
</div>

<form method="post" action="<?= Helper::url('register') ?>">
    <?= Helper::csrfField() ?>

    <div class="field">
        <label for="name">Full name</label>
        <input class="input" type="text" id="name" name="name" value="<?= Helper::old('name') ?>" placeholder="e.g. Ayesha Khan" required autofocus minlength="3">
    </div>

    <div class="field">
        <label for="email">University email</label>
        <input class="input" type="email" id="email" name="email" value="<?= Helper::old('email') ?>" placeholder="you@university.edu" required>
    </div>

    <div class="field">
        <label for="academic_year">Academic year <span class="faint">(optional)</span></label>
        <input class="input" type="text" id="academic_year" name="academic_year" value="<?= Helper::old('academic_year') ?>" placeholder="e.g. BSc 2nd Year">
    </div>

    <div class="flex gap-2" style="display:grid;grid-template-columns:1fr 1fr;">
        <div class="field">
            <label for="password">Password</label>
            <input class="input" type="password" id="password" name="password" placeholder="Min 8 characters" required minlength="8">
        </div>
        <div class="field">
            <label for="password_confirm">Confirm</label>
            <input class="input" type="password" id="password_confirm" name="password_confirm" placeholder="Repeat password" required minlength="8">
        </div>
    </div>

    <label class="flex items-center gap-1 mb-2" style="cursor:pointer;">
        <input type="checkbox" required style="accent-color:var(--brand);width:16px;height:16px;">
        <span class="small faint">I agree with the terms of smart spending</span>
    </label>

    <button type="submit" class="btn btn-primary btn-lg btn-block" style="justify-content:center;">Create account <?= Helper::icon('chevron-right', '17') ?></button>
</form>

<div class="flex items-center gap-2" style="margin:1.4rem 0;">
    <div style="flex:1;height:1px;background:var(--border);"></div>
    <span class="faint small">or</span>
    <div style="flex:1;height:1px;background:var(--border);"></div>
</div>

<div class="flex center gap-2 wrap">
    <span class="muted small">Already have an account?</span>
    <a href="<?= Helper::url('login') ?>" class="btn btn-dark btn-sm">Log in</a>
</div>
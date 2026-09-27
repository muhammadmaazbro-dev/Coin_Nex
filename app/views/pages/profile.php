<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('dashboard') ?>">Dashboard</a><span class="sep">/</span><span class="current">My Profile</span></div>
        <h1><?= Helper::icon('user', '26') ?> My Profile</h1>
        <p class="muted small">Keep your details fresh so your insights stay accurate.</p>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1.4fr 1fr;gap:1.4rem;" id="profile-grid">
    <!-- Profile details -->
    <div class="card">
        <div class="card-title"><?= Helper::icon('clipboard', '18') ?> Personal Details</div>
        <div class="flex items-center gap-2 mb-3">
            <span class="avatar-circle" style="width:56px;height:56px;font-size:1.3rem;"><?= Helper::e(mb_strtoupper(mb_substr($user['name'], 0, 1))) ?></span>
            <div>
                <div class="bold"><?= Helper::e($user['name']) ?></div>
                <div class="faint small"><?= Helper::e($user['email']) ?></div>
                <div style="margin-top:.3rem;">
                    <span class="badge <?= $user['role'] === 'admin' ? 'badge-brand' : 'badge-info' ?>"><?= Helper::e(ucfirst($user['role'])) ?></span>
                </div>
            </div>
        </div>

        <form method="post" action="<?= Helper::url('profile/update') ?>">
            <?= Helper::csrfField() ?>
            <div class="flex gap-2" style="display:grid;grid-template-columns:1fr 1fr;">
                <div class="field">
                    <label for="name">Full name</label>
                    <input class="input" type="text" id="name" name="name" value="<?= Helper::e($user['name']) ?>" required minlength="3">
                </div>
                <div class="field">
                    <label for="academic_year">Academic year</label>
                    <input class="input" type="text" id="academic_year" name="academic_year" value="<?= Helper::e($user['academic_year'] ?? '') ?>" placeholder="e.g. BSc 2nd Year">
                </div>
                <div class="field">
                    <label for="monthly_allowance_baseline">Monthly allowance baseline (<?= Helper::e(DEFAULT_CURRENCY_SYMBOL) ?>)</label>
                    <input class="input" type="number" step="0.01" min="0" id="monthly_allowance_baseline" name="monthly_allowance_baseline" value="<?= Helper::e($user['monthly_allowance_baseline'] ?? '0') ?>">
                </div>
                <div class="field">
                    <label for="savings_goal">Monthly savings goal (<?= Helper::e(DEFAULT_CURRENCY_SYMBOL) ?>)</label>
                    <input class="input" type="number" step="0.01" min="0" id="savings_goal" name="savings_goal" value="<?= Helper::e($user['savings_goal'] ?? '0') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </div>

    <!-- Side column -->
    <div class="flex" style="flex-direction:column;gap:1.4rem;">
        <!-- Preferences -->
        <div class="card" id="preferences">
            <div class="card-title"><?= Helper::icon('gear', '18') ?> Preferences</div>
            <form method="post" action="<?= Helper::url('profile/theme') ?>" id="pref-form">
                <?= Helper::csrfField() ?>
                <div class="field">
                    <label>Theme</label>
                    <div class="pill-select">
                        <label class="pill <?= $user['theme'] === 'dark' ? 'active' : '' ?>"><input type="radio" name="theme" value="dark" <?= $user['theme'] === 'dark' ? 'checked' : '' ?>> <?= Helper::icon('moon', '14') ?> Dark</label>
                        <label class="pill <?= $user['theme'] === 'light' ? 'active' : '' ?>"><input type="radio" name="theme" value="light" <?= $user['theme'] === 'light' ? 'checked' : '' ?>> <?= Helper::icon('sun', '14') ?> Light</label>
                    </div>
                </div>
                <div class="field">
                    <label>Font size</label>
                    <input type="range" name="font_scale" class="input" min="0.85" max="1.2" step="0.05" value="<?= Helper::e((string) $user['font_scale']) ?>" oninput="document.getElementById('font-preview').style.fontSize=(this.value*16)+'px'; document.getElementById('font-value').textContent=Math.round(this.value*100)+'%';" style="padding:.4rem .2rem;accent-color:var(--brand);">
                    <div class="flex between items-center"><span class="small" id="font-preview" style="font-size:<?= (float) $user['font_scale'] * 16 ?>px;">Sample preview text</span><span class="faint small" id="font-value"><?= (int) ((float) $user['font_scale'] * 100) ?>%</span></div>
                </div>
                <button type="submit" class="btn btn-dark btn-block">Save preferences</button>
            </form>
        </div>

        <!-- Change password -->
        <div class="card">
            <div class="card-title"><?= Helper::icon('lock', '18') ?> Change Password</div>
            <form method="post" action="<?= Helper::url('profile/password') ?>">
                <?= Helper::csrfField() ?>
                <div class="field">
                    <label for="current_password">Current password</label>
                    <input class="input" type="password" id="current_password" name="current_password" required>
                </div>
                <div class="field">
                    <label for="new_password">New password</label>
                    <input class="input" type="password" id="new_password" name="new_password" required minlength="8" placeholder="Min 8 characters">
                </div>
                <div class="field">
                    <label for="confirm_password">Confirm new password</label>
                    <input class="input" type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                <button type="submit" class="btn btn-danger btn-block">Update password</button>
            </form>
        </div>
    </div>
</div>

<style>
#profile-grid { grid-template-columns: 1.4fr 1fr; }
@media (max-width: 900px) { #profile-grid { grid-template-columns: 1fr; } }
</style>
<script>
(function () {
    const radios = document.querySelectorAll('#pref-form input[name="theme"]');
    radios.forEach(r => r.addEventListener('change', () => {
        document.querySelectorAll('#pref-form .pill').forEach(p => p.classList.toggle('active', p.querySelector('input').checked));
    }));
    window.addEventListener('load', () => {
        const preview = document.getElementById('font-preview');
        if (preview) preview.style.fontSize = (preview.dataset.size || preview.style.fontSize);
    });
})();
</script>
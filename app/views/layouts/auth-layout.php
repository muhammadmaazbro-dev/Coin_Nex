<?php
declare(strict_types=1);
$viewFile = VIEWS_PATH . '/' . $view . '.php';
$pageTitle ??= Helper::pageTitle();
$flashes = Session::flashGet();
?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Urbanist:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Helper::asset('css/style.css') . '?v=20260926-responsive' ?>">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐝</text></svg>">
    <style>
        .auth-side {
            background:
                radial-gradient(700px 480px at 90% 0%, rgba(247,183,51,.14), transparent 60%),
                radial-gradient(600px 420px at 0% 100%, rgba(96,185,249,.1), transparent 60%),
                var(--bg-soft);
        }
        .auth-brandpill {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .4rem .95rem; border-radius: var(--radius-full);
            background: rgba(247,183,51,.1); border: 1px solid rgba(247,183,51,.3);
            color: var(--brand); font-weight: 700; font-size: .8rem;
        }
        .auth-quote { border-left: 3px solid var(--brand); padding-left: 1rem; }
        .auth-feature-row { display: flex; gap: .8rem; align-items: flex-start; }
        .auth-feature-ico { width: 38px; height: 38px; flex: none; border-radius: 12px; display: grid; place-items: center; font-size: 1.1rem; background: var(--surface-strong); border: 1px solid var(--border); }
    </style>
</head>
<body>
    <div class="toast-wrap"></div>
    <?php if ($flashes): ?>
    <script>document.addEventListener('DOMContentLoaded', function () {
        const flash = <?= json_encode($flashes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        flash.forEach(f => toast(f.message, f.type));
    });</script>
    <?php endif; ?>

    <div class="auth-shell">
        <!-- LEFT: brand + form -->
        <div class="auth-left">
            <a href="<?= Helper::url('') ?>" class="flex items-center gap-1 mb-3" style="text-decoration:none;">
                <div class="brand-badge-box site-brand-mark"><span class="brand-badge-initials">CN</span><span class="brand-live-dot"></span></div>
                <div>
                    <div class="brand-name" style="color:var(--text);"><?= Helper::e(APP_NAME) ?></div>
                    <div class="brand-tag"><?= Helper::e(APP_TAGLINE) ?></div>
                </div>
            </a>

            <div class="auth-card animate-up">
                <?php if (is_file($viewFile)) { require $viewFile; } else { Helper::abort("View not found: $view", 500); } ?>

    <!-- Unified Coin_Nex light theme: loaded after page markup to normalize inline page decoration. -->
    <link rel="stylesheet" href="<?= Helper::asset('css/light-theme-overrides.css') . '?v=20260927-light' ?>">
            </div>
        </div>

        <!-- RIGHT: marketing side -->
        <div class="auth-right auth-side animate-up-slow">
            <div style="position:relative;z-index:1;">
                <span class="auth-brandpill mb-2"><?= Helper::icon('bee', '15') ?> Smart Spending · Student Style</span>
                <h2 style="font-size:clamp(1.7rem,3vw,2.5rem);margin-bottom:1rem;">Make every coin count, <span style="background:linear-gradient(120deg,#ffde6b,#f59e0b);-webkit-background-clip:text;background-clip:text;color:transparent;">every month.</span></h2>
                <p class="muted mb-3">Coin_Nex turns your messy student money into a clear, friendly picture — so you can save without the stress.</p>

                <div class="flex" style="flex-direction:column;gap:1rem;margin-bottom:2rem;">
                    <div class="auth-feature-row">
                        <div class="auth-feature-ico"><?= Helper::icon('zap', '20') ?></div>
                        <div><div class="bold">One-second logging</div><div class="muted small">Income &amp; expenses in three taps.</div></div>
                    </div>
                    <div class="auth-feature-row">
                        <div class="auth-feature-ico"><?= Helper::icon('brain', '20') ?></div>
                        <div><div class="bold">AI that gets you</div><div class="muted small">Auto-categorized expenses &amp; plain-language monthly insights.</div></div>
                    </div>
                    <div class="auth-feature-row">
                        <div class="auth-feature-ico"><?= Helper::icon('target', '20') ?></div>
                        <div><div class="bold">Budgets that work</div><div class="muted small">Set caps, watch progress, get alerts before overspending.</div></div>
                    </div>
                    <div class="auth-feature-row">
                        <div class="auth-feature-ico"><?= Helper::icon('lock', '20') ?></div>
                        <div><div class="bold">Private by design</div><div class="muted small">No bank link needed. Your data stays yours.</div></div>
                    </div>
                </div>

                <div class="auth-quote">
                    <p class="muted" style="font-style:italic;">“I used to wonder where my money vanished. Now the BudgetBee shows me exactly where — and how to keep more of it.”</p>
                    <div class="flex items-center gap-1">
                        <span class="avatar-circle" style="width:28px;height:28px;font-size:.65rem;">S</span>
                        <div><div class="small bold">A Sample Student</div><div class="faint" style="font-size:.72rem;">Coin_Nex user</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= Helper::asset('js/main.js') ?>"></script>
</body>
</html>
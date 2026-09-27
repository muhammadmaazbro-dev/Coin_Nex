<?php
declare(strict_types=1);
$viewFile = VIEWS_PATH . '/' . $view . '.php';
$pageTitle ??= Helper::pageTitle();
$flashes = Session::flashGet();
$currentUser = Helper::currentUser();
$navBase = Helper::basePathUri();
$unreadCount = isset($unreadNotifications) ? (int) $unreadNotifications : 0;

$navItems = [
    ['dashboard', 'home', 'Dashboard'],
    ['transactions', 'swap', 'Transactions'],
    ['reports', 'chart', 'Reports'],
    ['budgets', 'target', 'Budgets'],
    ['categories', 'tags', 'Categories'],
    ['insights', 'brain', 'AI Insights'],
    ['tips', 'bulb', 'Saving Tips'],
];

function navItem(string $base, string $target, string $icon, string $label): string {
    $active = ($base === $target) || (str_starts_with($base, $target . '/'));
    $cls = $active ? 'nav-link active' : 'nav-link';
    return '<a class="' . $cls . '" href="' . Helper::url($target) . '" title="' . $label . '"><span class="nav-ico">' . Helper::icon($icon, '20') . '</span><span>' . $label . '</span></a>';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= Helper::e($currentUser['theme'] ?? 'dark') ?>" style="--cc-font-scale:<?= Helper::e((float)($currentUser['font_scale'] ?? 1.0)) ?>;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Urbanist:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Helper::asset('css/style.css') . '?v=20260926-responsive' ?>">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🪙</text></svg>">
</head>
<body class="app-body">
    <div class="toast-wrap"></div>
    <?php if ($flashes): ?>
    <script>document.addEventListener('DOMContentLoaded', function () {
        const flash = <?= json_encode($flashes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        flash.forEach(f => toast(f.message, f.type));
    });</script>
    <?php endif; ?>

    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <a href="<?= Helper::url('dashboard') ?>" style="display:flex;align-items:center;gap:.75rem;text-decoration:none;">
                    <div class="brand-badge-box site-brand-mark"><span class="brand-badge-initials">CN</span><span class="brand-live-dot"></span></div>
                    <div class="brand-text">
                        <div class="brand-name" style="color:var(--text);font-weight:700;font-size:1.1rem;letter-spacing:-0.02em;"><?= Helper::e(APP_NAME) ?></div>
                        <div class="brand-tag" style="color:var(--brand);font-size:.74rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;">NextGen Student Bee</div>
                    </div>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">Student Finance</div>
                <?php foreach (array_slice($navItems, 0, 3) as [$route, $icon, $label]) {
                    echo navItem($navBase, $route, $icon, $label);
                } ?>
                <div class="nav-section">Planning &amp; Habits</div>
                <?php foreach (array_slice($navItems, 3, 4) as [$route, $icon, $label]) {
                    echo navItem($navBase, $route, $icon, $label);
                } ?>

                <?php if (Helper::isAdmin()): ?>
                <div class="nav-section">Administration</div>
                <?= navItem($navBase, 'admin', 'shield', 'Admin Overview'); ?>
                <?= navItem($navBase, 'admin/users', 'user', 'Manage Users'); ?>
                <?= navItem($navBase, 'admin/categories', 'tags', 'Default Categories'); ?>
                <?= navItem($navBase, 'admin/announcements', 'bell', 'Announcements'); ?>
                <?= navItem($navBase, 'admin/tips', 'bulb', 'Saving Tips Pool'); ?>
                <?php endif; ?>
            </nav>

            <div class="sidebar-foot">
                <a href="<?= Helper::url('profile') ?>" class="nav-link" style="border-radius:12px;padding:.55rem .75rem;">
                    <span class="avatar-circle" style="width:36px;height:36px;font-size:.85rem;background:var(--grad-brand);color:#12141a;font-weight:700;"><?= Helper::e(mb_strtoupper(mb_substr($currentUser['name'] ?? 'U', 0, 1))) ?></span>
                    <span class="flex" style="flex-direction:column;gap:0;line-height:1.25;">
                        <span style="font-size:.88rem;font-weight:600;color:var(--text);"><?= Helper::e($currentUser['name'] ?? 'User') ?></span>
                        <span class="faint" style="font-size:.72rem;text-transform:capitalize;color:var(--brand);"><?= Helper::e($currentUser['role'] ?? 'student') ?></span>
                    </span>
                </a>
            </div>
        </aside>

        <div class="main-area">
            <header class="topbar">
                <button class="icon-btn burger" data-sidebar-toggle aria-label="Menu"><?= Helper::icon('layers', '20') ?></button>
                <button class="icon-btn" data-rail-collapse aria-label="Collapse sidebar" title="Collapse sidebar">
                    <span data-rail-expand class="hide"><?= Helper::icon('layers', '20') ?></span>
                    <span data-rail-collapse-ico><?= Helper::icon('x', '20') ?></span>
                </button>

                <div class="topbar-title-wrap">
                    <div class="page-title"><?= Helper::e($pageTitle) ?></div>
                </div>

                <div class="flex items-center gap-1" style="margin-left:auto;">
                    <!-- Quick Spotlight Search Pill (Ctrl+K) -->
                    <button type="button" class="topbar-search-pill" onclick="openCommandPalette()" title="Press Ctrl+K to open spotlight">
                        <span><?= Helper::icon('search', '14') ?> Quick actions…</span>
                        <kbd>Ctrl K</kbd>
                    </button>

                    <!-- Quick Settings Dropdown (Themes, Eye Protection, Audio, Font) -->
                    <div class="dropdown settings-dropdown">
                        <button type="button" class="btn btn-ghost btn-sm flex items-center gap-1" data-dropdown-trigger style="padding:.4rem .7rem;border-color:rgba(247,183,51,0.3);color:var(--text);" title="Display, Eye Protection & Sound Settings">
                            <?= Helper::icon('settings', '15') ?> <span>Settings</span>
                        </button>
                        <div class="dropdown-menu" style="min-width:280px;padding:1rem;border-radius:16px;background:var(--bg-soft);border:1px solid var(--border-strong);box-shadow:var(--shadow-lg);">
                            <div class="bold small mb-2 flex items-center gap-1" style="color:var(--brand);">
                                <?= Helper::icon('settings', '15') ?> <span>Quick Controls</span>
                            </div>

                            <!-- Display Mode -->
                            <div class="mb-3">
                                <label class="faint small mb-1" style="display:block;">Display Mode</label>
                                <div class="flex gap-1">
                                    <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="dark" style="font-size:.78rem;padding:.4rem .2rem;">Dark</button>
                                    <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="light" style="font-size:.78rem;padding:.4rem .2rem;">Light</button>
                                    <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="eye-care" style="font-size:.78rem;padding:.4rem .2rem;" title="Warm amber tint to filter blue light for late night study">Eye Care</button>
                                </div>
                            </div>

                            <!-- Audio Controls -->
                            <div class="mb-3" style="border-top:1px dashed var(--border);padding-top:.7rem;">
                                <div class="flex between items-center">
                                    <span class="small bold">Sound Effects</span>
                                    <button type="button" class="btn btn-ghost btn-sm" onclick="toggleCoinSound()" data-sound-status style="font-size:.75rem;padding:.2rem .6rem;">Sound ON</button>
                                </div>
                                <div class="faint small mt-1" style="font-size:.72rem;">Subtle chimes play once on initial open or entering panels.</div>
                            </div>

                            <!-- Text Zoom -->
                            <div style="border-top:1px dashed var(--border);padding-top:.7rem;">
                                <div class="flex between items-center">
                                    <span class="faint small">Text Zoom</span>
                                    <div class="flex gap-1 font-switcher">
                                        <button type="button" class="btn btn-ghost btn-sm font-scale-btn" onclick="applyFontScale(0.9)" style="padding:.2rem .5rem;font-size:.75rem;">A-</button>
                                        <button type="button" class="btn btn-ghost btn-sm font-scale-btn active" onclick="applyFontScale(1.0)" style="padding:.2rem .5rem;font-size:.75rem;">A</button>
                                        <button type="button" class="btn btn-ghost btn-sm font-scale-btn" onclick="applyFontScale(1.15)" style="padding:.2rem .5rem;font-size:.75rem;">A+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown notif-dot">
                        <button class="icon-btn" data-dropdown-trigger aria-label="Notifications"><?= Helper::icon('bell', '20') ?>
                            <?php if ($unreadCount > 0): ?><span class="notif-count"><?= Helper::e($unreadCount) ?></span><?php endif; ?>
                        </button>
                        <div class="dropdown-menu" style="min-width:320px;">
                            <div class="card-title flex between items-center" style="padding:.6rem .9rem 0;margin:0;">
                                <span class="bold">Notifications</span>
                                <a href="<?= Helper::url('notifications') ?>" class="small link" style="font-weight:600;">View all</a>
                            </div>
                            <div id="notif-menu-content"><div class="skeleton" style="height:60px;margin:.6rem .4rem;"></div></div>
                        </div>
                    </div>

                    <!-- User Account Dropdown -->
                    <div class="dropdown">
                        <button class="icon-btn" data-dropdown-trigger aria-label="Account menu" style="padding:2px;">
                            <span class="avatar-circle" style="width:34px;height:34px;font-size:.78rem;background:var(--grad-brand);color:#12141a;font-weight:700;"><?= Helper::e(mb_strtoupper(mb_substr($currentUser['name'] ?? 'U', 0, 1))) ?></span>
                        </button>
                        <div class="dropdown-menu">
                            <div style="padding:.7rem 1rem;border-bottom:1px solid var(--border);margin-bottom:.4rem;">
                                <div class="bold" style="font-size:.92rem;color:var(--text);"><?= Helper::e($currentUser['name'] ?? 'User') ?></div>
                                <div class="faint small"><?= Helper::e($currentUser['email'] ?? '') ?></div>
                            </div>
                            <a href="<?= Helper::url('profile') ?>" class="dropdown-item"><?= Helper::icon('user', '17') ?> My Profile</a>
                            <a href="<?= Helper::url('transactions/add') ?>?type=income" class="dropdown-item text-success"><?= Helper::icon('income', '17') ?> + Add Income</a>
                            <a href="<?= Helper::url('transactions/add') ?>?type=expense" class="dropdown-item text-danger"><?= Helper::icon('expense', '17') ?> + Add Expense</a>
                            <a href="<?= Helper::url('profile#preferences') ?>" class="dropdown-item"><?= Helper::icon('gear', '17') ?> Settings</a>
                            <div class="dropdown-sep"></div>
                            <a href="<?= Helper::url('logout') ?>" class="dropdown-item" style="color:var(--danger);"><?= Helper::icon('logout', '17') ?> Sign Out</a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="content">
                <?php if (is_file($viewFile)) { require $viewFile; } else { Helper::abort("View not found: $view", 500); } ?>

    <!-- Unified Coin_Nex light theme: loaded after page markup to normalize inline page decoration. -->
    <link rel="stylesheet" href="<?= Helper::asset('css/light-theme-overrides.css') . '?v=20260927-light' ?>">
            </main>
        </div>
    </div>

    <script>
        window.CSRF_TOKEN = <?= json_encode(Helper::csrfToken()) ?>;
        window.CC_BASE = <?= json_encode(BASE_URL) ?>;

        function applyFontScale(scale) {
            document.documentElement.style.setProperty('--cc-font-scale', scale);
            localStorage.setItem('cc_font_scale', scale);
            document.querySelectorAll('.font-scale-btn').forEach(btn => {
                btn.classList.remove('active');
                if ((scale === 0.9 && btn.textContent === 'A-') ||
                    (scale === 1.0 && btn.textContent === 'A') ||
                    (scale === 1.15 && btn.textContent === 'A+')) {
                    btn.classList.add('active');
                }
            });
            // Persist to user profile
            fetch('<?= Helper::url('profile/theme') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: 'csrf_token=' + encodeURIComponent(window.CSRF_TOKEN) + '&theme=' + (document.documentElement.getAttribute('data-theme') || 'dark') + '&font_scale=' + scale
            }).catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', function () {
            const savedScale = localStorage.getItem('cc_font_scale');
            if (savedScale) {
                applyFontScale(parseFloat(savedScale));
            }

            fetch('<?= Helper::url('notifications/fetch') ?>')
                .then(r => r.json())
                .then(data => {
                    const box = document.getElementById('notif-menu-content');
                    if (!data.list || !data.list.length) {
                        box.innerHTML = '<div class="muted small" style="padding:.9rem;text-align:center;">No new notifications</div>';
                        return;
                    }
                    box.innerHTML = data.list.map(n =>
                        '<a href="' + n.link + '" class="dropdown-item" style="white-space:normal;align-items:flex-start;padding:.6rem .8rem;">' +
                        '<span class="notif-ico" style="margin-top:2px;">' + n.icon + '</span><span><span style="font-weight:600;font-size:.85rem;">' + n.message +
                        '</span><br><span class="faint small" style="font-size:.72rem;">' + n.when + '</span></span></a>'
                    ).join('');
                });
        });
    </script>

    <!-- Command Palette (Ctrl+K) -->
    <div class="cmd-palette-backdrop" id="cmd-palette" style="display:none;" onclick="if(event.target===this)closeCommandPalette()">
        <div class="cmd-palette-box">
            <input type="text" class="cmd-search-input" placeholder="Type a command or jump to page… (Esc to exit)" autocomplete="off">
            <div class="cmd-list">
                <a href="<?= Helper::url('transactions/add') ?>?type=expense" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('expense', '18') ?></span>
                    <span><strong>Add Expense</strong> — Record spent money</span>
                    <span class="cmd-item-tag">Quick Add</span>
                </a>
                <a href="<?= Helper::url('transactions/add') ?>?type=income" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('income', '18') ?></span>
                    <span><strong>Add Income</strong> — Record allowance / wages</span>
                    <span class="cmd-item-tag">Quick Add</span>
                </a>
                <a href="<?= Helper::url('dashboard') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('home', '18') ?></span>
                    <span><strong>Dashboard</strong> — Student cashflow overview</span>
                    <span class="cmd-item-tag">Page</span>
                </a>
                <a href="<?= Helper::url('transactions') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('swap', '18') ?></span>
                    <span><strong>Transactions</strong> — View &amp; search all entries</span>
                    <span class="cmd-item-tag">Page</span>
                </a>
                <a href="<?= Helper::url('budgets') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('target', '18') ?></span>
                    <span><strong>Budgets</strong> — Manage category limits &amp; alerts</span>
                    <span class="cmd-item-tag">Page</span>
                </a>
                <a href="<?= Helper::url('insights') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('brain', '18') ?></span>
                    <span><strong>AI Insights &amp; Bee Chat</strong> — Ask financial questions</span>
                    <span class="cmd-item-tag">AI</span>
                </a>
                <a href="<?= Helper::url('tips') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('bulb', '18') ?></span>
                    <span><strong>Saving Tips</strong> — Smart cost-cutting advice</span>
                    <span class="cmd-item-tag">Page</span>
                </a>
                <a href="<?= Helper::url('reports') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('chart', '18') ?></span>
                    <span><strong>Reports &amp; PDF</strong> — Monthly breakdown &amp; trends</span>
                    <span class="cmd-item-tag">Page</span>
                </a>
                <a href="<?= Helper::url('categories') ?>" class="cmd-item">
                    <span class="cmd-item-ico"><?= Helper::icon('tags', '18') ?></span>
                    <span><strong>Categories</strong> — System and custom categories</span>
                    <span class="cmd-item-tag">Settings</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Interactive BudgetBee Mascot Dock -->
    <div class="floating-bee-dock">
        <div class="bee-speech-bubble" id="bee-bubble" style="display:none;">
            <div style="font-weight:700;color:var(--brand);margin-bottom:2px;display:flex;align-items:center;gap:.35rem;"><?= Helper::icon('sparkle', '14') ?> BudgetBee AI</div>
            <div id="bee-bubble-text" style="font-size:.82rem;">Need help with your student budget? Tap me to chat!</div>
            <div style="margin-top:6px;text-align:right;">
                <a href="<?= Helper::url('insights') ?>" class="small link" style="font-weight:700;">Open Chat ➜</a>
            </div>
        </div>
        <button type="button" class="bee-mascot-btn" onclick="toggleBeeSpeech()" title="Chat with BudgetBee AI">
            <?= Helper::icon('brain', '18') ?>
        </button>
    </div>

    <script>
        function toggleBeeSpeech() {
            const b = document.getElementById('bee-bubble');
            if (b) {
                b.style.display = (b.style.display === 'none' || !b.style.display) ? 'block' : 'none';
            }
        }
    </script>
    <script src="<?= Helper::asset('js/vendor/chart.umd.js') ?>"></script>
    <script src="<?= Helper::asset('js/main.js') ?>"></script>
</body>
</html>
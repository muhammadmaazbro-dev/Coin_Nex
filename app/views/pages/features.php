<?php
declare(strict_types=1);
?>
<!-- ================= FEATURES PAGE NAVBAR ================= -->
<nav class="landing-nav" style="position:sticky;top:0;z-index:90;backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);background:rgba(14,17,23,0.94);border-bottom:1px solid var(--border);">
    <div class="container" style="min-height:72px;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.6rem 1rem;">
        <a href="<?= Helper::url('') ?>" class="flex items-center gap-1" style="text-decoration:none;flex-shrink:0;">
            <div class="brand-badge-box site-brand-mark"><span class="brand-badge-initials">CN</span><span class="brand-live-dot"></span></div>
            <div>
                <div class="brand-name" style="color:var(--text);font-size:1.15rem;font-weight:800;letter-spacing:-.02em;"><?= Helper::e(APP_NAME) ?></div>
                <div class="brand-tag" style="font-size:.68rem;color:var(--brand);font-weight:600;">STUDENT FINTECH</div>
            </div>
        </a>

        <div class="nav-center-links">
            <a href="<?= Helper::url('') ?>" class="nav-link-pill">Home</a>
            <a href="<?= Helper::url('features') ?>" class="nav-link-pill active">Features</a>
            <a href="<?= Helper::url('about') ?>" class="nav-link-pill">About</a>
            <a href="<?= Helper::url('faq') ?>" class="nav-link-pill">FAQ</a>
            <a href="<?= Helper::url('contact') ?>" class="nav-link-pill">Contact</a>
        </div>

        <div class="flex items-center gap-1" style="flex-shrink:0;">
            <div class="dropdown settings-dropdown">
                <button type="button" class="btn btn-ghost btn-sm flex items-center gap-1" data-dropdown-trigger style="border-color:rgba(247,183,51,.3);color:var(--text);font-weight:600;" title="Display Mode & Preferences">
                    <?= Helper::icon('gear', '14') ?><span>Settings</span>
                </button>
                <div class="dropdown-menu" style="min-width:280px;padding:1rem;border-radius:16px;background:var(--bg-soft);border:1px solid var(--border-strong);box-shadow:var(--shadow-lg);">
                    <div class="bold small mb-2 flex items-center gap-1" style="color:var(--brand);">
                        <?= Helper::icon('gear', '14') ?><span>Display Preferences</span>
                    </div>
                    <div class="mb-3">
                        <label class="faint small mb-1" style="display:block;">Color Theme</label>
                        <div class="flex gap-1">
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="dark" style="font-size:.78rem;padding:.4rem .2rem;">Dark</button>
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="light" style="font-size:.78rem;padding:.4rem .2rem;">Light</button>
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="eye-care" style="font-size:.78rem;padding:.4rem .2rem;">Eye Care</button>
                        </div>
                    </div>
                    <div class="mb-3" style="border-top:1px dashed var(--border);padding-top:.7rem;">
                        <div class="flex between items-center mb-1"><span class="small bold">Audio Effects</span><button type="button" class="btn btn-ghost btn-sm" onclick="toggleCoinSound()" data-sound-status style="font-size:.75rem;padding:.2rem .6rem;">Sound ON</button></div>
                    </div>
                    <div style="border-top:1px dashed var(--border);padding-top:.7rem;">
                        <div class="flex between items-center"><span class="faint small">Text Zoom</span><div class="flex gap-1 font-switcher">
                            <button type="button" class="btn btn-ghost btn-sm font-scale-btn" onclick="applyFontScale(.9)" style="padding:.2rem .5rem;font-size:.75rem;">A-</button>
                            <button type="button" class="btn btn-ghost btn-sm font-scale-btn active" onclick="applyFontScale(1)" style="padding:.2rem .5rem;font-size:.75rem;">A</button>
                            <button type="button" class="btn btn-ghost btn-sm font-scale-btn" onclick="applyFontScale(1.15)" style="padding:.2rem .5rem;font-size:.75rem;">A+</button>
                        </div></div>
                    </div>
                </div>
            </div>
            <?php if (Helper::isLoggedIn()): ?>
                <?php $currUser = Helper::currentUser(); ?>
                <a href="<?= Helper::url('dashboard') ?>" class="btn btn-primary btn-sm flex items-center gap-1" style="font-weight:700;"><span class="avatar-circle" style="width:22px;height:22px;font-size:.72rem;background:#11141c;color:var(--brand);font-weight:800;display:inline-grid;place-items:center;border-radius:50%;"><?= strtoupper(substr($currUser['name'] ?? 'U', 0, 1)) ?></span><span>Dashboard</span></a>
                <a href="<?= Helper::url('logout') ?>" class="btn btn-ghost btn-sm hide-mobile">Log out</a>
            <?php else: ?>
                <a href="<?= Helper::url('login') ?>" class="btn btn-ghost btn-sm hide-mobile">Log in</a>
                <a href="<?= Helper::url('register') ?>" class="btn btn-primary btn-sm" style="font-weight:700;">Get Started</a>
            <?php endif; ?>
            <div class="dropdown mobile-nav-toggle">
                <button type="button" class="btn btn-ghost btn-sm" data-dropdown-trigger style="padding:.45rem .6rem;" title="Navigation Menu"><?= Helper::icon('list', '18') ?></button>
                <div class="dropdown-menu" style="min-width:200px;padding:.6rem;border-radius:14px;background:var(--bg-soft);border:1px solid var(--border-strong);box-shadow:var(--shadow-lg);">
                    <a href="<?= Helper::url('') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Home</a>
                    <a href="<?= Helper::url('features') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Features</a>
                    <a href="<?= Helper::url('about') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">About Platform</a>
                    <a href="<?= Helper::url('faq') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">FAQ</a>
                    <a href="<?= Helper::url('contact') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Contact Support</a>
                    <div class="dropdown-sep"></div>
                    <?php if (Helper::isLoggedIn()): ?>
                        <a href="<?= Helper::url('dashboard') ?>" class="dropdown-item bold" style="color:var(--brand);">Open Dashboard ➜</a>
                        <a href="<?= Helper::url('logout') ?>" class="dropdown-item" style="color:var(--danger);">Log out</a>
                    <?php else: ?>
                        <a href="<?= Helper::url('login') ?>" class="dropdown-item">Log in</a>
                        <a href="<?= Helper::url('register') ?>" class="dropdown-item bold" style="color:var(--brand);">Get Started Free ➜</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- ================= FEATURES HERO ================= -->
<section class="landing-hero" style="padding:clamp(4rem,8vw,7rem) 0 4.5rem;position:relative;overflow:hidden;">
    <div class="orb orb-1" style="width:650px;height:650px;background:radial-gradient(circle,rgba(247,183,51,.18) 0%,transparent 70%);top:-180px;right:-100px;"></div>
    <div class="orb orb-2" style="width:550px;height:550px;background:radial-gradient(circle,rgba(45,212,167,.13) 0%,transparent 70%);bottom:-180px;left:-120px;"></div>
    <div class="container" style="position:relative;z-index:2;text-align:center;">
        <div class="inline-flex items-center gap-1 mb-2 animate-up" style="background:rgba(247,183,51,.1);border:1px solid rgba(247,183,51,.3);padding:.4rem 1rem;border-radius:var(--radius-full);">
            <span style="font-size:.78rem;font-weight:800;color:var(--brand);text-transform:uppercase;letter-spacing:.08em;">Next-Gen Student FinTech</span>
        </div>
        <h1 style="font-size:clamp(2.7rem,7vw,5.5rem);line-height:1.02;letter-spacing:-.045em;max-width:900px;margin:0 auto 1.25rem;font-weight:900;">Everything You Need to <span class="text-brand">Master Your Money.</span></h1>
        <p class="muted" style="max-width:760px;margin:0 auto 2rem;font-size:clamp(1rem,2vw,1.2rem);line-height:1.7;">Coin_Nex combines smart expense tracking, budgeting, AI-powered insights, safety controls and professional reporting into one student-first financial workspace.</p>
        <div class="flex center gap-2 wrap">
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions/add' : 'register') ?>" class="btn btn-primary btn-lg" style="font-weight:800;box-shadow:0 10px 30px -8px rgba(247,183,51,.5);">Try Coin_Nex Free ➜</a>
            <a href="<?= Helper::url('') ?>" class="btn btn-dark btn-lg">Back to Home</a>
        </div>
    </div>
</section>

<!-- ================= FEATURE GRID ================= -->
<section style="padding:4.5rem 0;background:rgba(18,22,32,.4);border-top:1px solid var(--border);">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge badge-brand mb-2">Core Platform</span>
            <h2 style="font-size:clamp(1.9rem,4vw,3rem);letter-spacing:-.03em;">Powerful Features. Simple Student Experience.</h2>
            <p class="muted" style="max-width:680px;margin:0 auto;">Every major part of Coin_Nex is designed around the way students actually receive, spend, save and review money.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.4rem;" id="features-grid">
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions/add' : 'register') ?>" class="card card-hover clickable-card" style="grid-column:span 2;text-decoration:none;background:linear-gradient(145deg,rgba(24,28,40,.9),rgba(14,18,26,.98));">
                <div class="feature-ico text-brand mb-2"><?= Helper::icon('brain','30') ?></div><span class="badge badge-brand mb-1">AI & AUTOMATION</span>
                <h3>Real-Time AI Auto-Categorization</h3><p class="muted small">Type a simple description such as “McDonald's lunch” or “Uber to university” and Coin_Nex can classify the expense into the right spending category.</p>
                <div class="glass p-2 flex between items-center wrap gap-2" style="border-radius:12px;padding:.8rem 1rem;margin-top:1rem;"><span class="small bold">“Subway 6-inch sub” → Food</span><span class="badge badge-success" style="font-size:.68rem;">AI Assist</span></div>
            </a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'budgets' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-danger mb-2"><?= Helper::icon('alert','30') ?></div><span class="badge badge-danger mb-1">PROTECTION</span><h3>Large Expense Warnings</h3><p class="muted small">Spot unusually large spending and keep major purchases from silently damaging your monthly budget.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'dashboard' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-success mb-2"><?= Helper::icon('shield','30') ?></div><span class="badge badge-success mb-1">DAILY DISCIPLINE</span><h3>Safe-to-Spend Today</h3><p class="muted small">Turn your current balance, income and commitments into a practical daily spending limit.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'reports' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-info mb-2"><?= Helper::icon('download','30') ?></div><span class="badge badge-info mb-1">REPORTING</span><h3>PDF & CSV Reports</h3><p class="muted small">Create organized financial summaries for personal review, parents, scholarship records or budgeting audits.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'insights' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-brand mb-2"><?= Helper::icon('sparkle','30') ?></div><span class="badge badge-brand mb-1">24/7 COPILOT</span><h3>BudgetBee AI Insights</h3><p class="muted small">Ask natural questions about your spending and receive understandable financial insights based on your tracked activity.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-info mb-2"><?= Helper::icon('swap','30') ?></div><span class="badge badge-info mb-1">TRACKING</span><h3>Income & Expense Tracking</h3><p class="muted small">Record allowances, salary, freelance work, gifts and everyday expenses in one searchable transaction history.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'budgets' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-success mb-2"><?= Helper::icon('target','30') ?></div><span class="badge badge-success mb-1">PLANNING</span><h3>Smart Budgets & Goals</h3><p class="muted small">Set category budgets and savings targets, then compare your real spending against the plan throughout the month.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'categories' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-brand mb-2"><?= Helper::icon('tags','30') ?></div><span class="badge badge-brand mb-1">ORGANIZATION</span><h3>Flexible Categories</h3><p class="muted small">Keep food, transport, education, hostel, shopping and custom student categories organized exactly the way you need.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'insights' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-info mb-2"><?= Helper::icon('chart','30') ?></div><span class="badge badge-info mb-1">ANALYTICS</span><h3>Monthly Insights</h3><p class="muted small">See spending patterns, income versus expenses and useful trends without digging through spreadsheets.</p></a>
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'profile' : 'register') ?>" class="card card-hover clickable-card" style="text-decoration:none;"><div class="feature-ico text-success mb-2"><?= Helper::icon('user','30') ?></div><span class="badge badge-success mb-1">PERSONALIZATION</span><h3>Student Profile & Preferences</h3><p class="muted small">Manage your account, preferences and personal settings from one secure place.</p></a>
            <div class="card card-hover" style="grid-column:span 2;background:linear-gradient(145deg,rgba(247,183,51,.08),rgba(45,212,167,.05));"><div class="feature-ico text-brand mb-2"><?= Helper::icon('lock','30') ?></div><span class="badge badge-brand mb-1">PRIVACY FIRST</span><h3>Your Money, Your Control</h3><p class="muted small">Coin_Nex is built around manual financial tracking rather than requiring bank credentials. Keep control of the information you choose to enter and review.</p><div class="flex gap-1 wrap mt-2"><span class="badge">Session Security</span><span class="badge">CSRF Protection</span><span class="badge">Password Hashing</span></div></div>
        </div>
    </div>
</section>

<!-- ================= WORKFLOW ================= -->
<section style="padding:5rem 0;">
    <div class="container">
        <div class="text-center mb-4"><span class="badge badge-brand mb-2">How It Works</span><h2 style="font-size:clamp(1.9rem,4vw,3rem);letter-spacing:-.03em;">From Chaos to Clarity</h2><p class="muted" style="max-width:650px;margin:0 auto;">A simple flow keeps the experience useful instead of overwhelming.</p></div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.2rem;">
            <div class="card card-hover text-center"><div class="avatar-circle mx-auto mb-2" style="background:rgba(247,183,51,.14);color:var(--brand);font-weight:900;">01</div><h3 class="h5">Track</h3><p class="muted small">Add income and expenses as they happen.</p></div>
            <div class="card card-hover text-center"><div class="avatar-circle mx-auto mb-2" style="background:rgba(45,212,167,.14);color:var(--success);font-weight:900;">02</div><h3 class="h5">Organize</h3><p class="muted small">Categorize transactions and assign budgets.</p></div>
            <div class="card card-hover text-center"><div class="avatar-circle mx-auto mb-2" style="background:rgba(56,189,248,.14);color:var(--info);font-weight:900;">03</div><h3 class="h5">Understand</h3><p class="muted small">Use insights, charts and AI assistance.</p></div>
            <div class="card card-hover text-center"><div class="avatar-circle mx-auto mb-2" style="background:rgba(244,114,182,.14);color:#f472b6;font-weight:900;">04</div><h3 class="h5">Improve</h3><p class="muted small">Adjust spending and grow your savings.</p></div>
        </div>
    </div>
</section>

<!-- ================= CTA ================= -->
<section style="padding:5rem 0;text-align:center;background:rgba(18,22,32,.4);border-top:1px solid var(--border);">
    <div class="container">
        <span class="badge badge-brand mb-2">Built for Student Life</span>
        <h2 style="font-size:clamp(2rem,5vw,3.5rem);letter-spacing:-.04em;margin-bottom:1rem;">Ready to Take Control?</h2>
        <p class="muted" style="max-width:620px;margin:0 auto 2rem;font-size:1.05rem;">Start tracking your money, build better habits and turn every rupee into a clearer plan.</p>
        <div class="flex center gap-2 wrap"><a href="<?= Helper::url('register') ?>" class="btn btn-primary btn-lg" style="font-weight:800;">Get Started Free ➜</a><a href="<?= Helper::url('contact') ?>" class="btn btn-dark btn-lg">Talk to Support</a></div>
    </div>
</section>

<footer style="border-top:1px solid var(--border);padding:2.5rem 0;background:rgba(12,15,22,.9);">
    <div class="container flex between wrap gap-3 items-center">
        <div class="flex items-center gap-1"><div class="brand-badge-box site-brand-mark site-brand-mark-sm"><span class="brand-badge-initials">CN</span></div><div><span class="bold" style="color:var(--text);font-size:1rem;"><?= Helper::e(APP_NAME) ?></span><span class="faint small"> · <?= Helper::e(APP_TAGLINE) ?></span></div></div>
        <div class="flex gap-2 wrap faint small"><a href="<?= Helper::url('') ?>" class="link">Home</a><span>·</span><a href="<?= Helper::url('features') ?>" class="link">Features</a><span>·</span><a href="<?= Helper::url('about') ?>" class="link">About Us</a><span>·</span><a href="<?= Helper::url('faq') ?>" class="link">FAQ</a><span>·</span><a href="<?= Helper::url('contact') ?>" class="link">Contact Support</a></div>
        <div class="faint small">© <?= date('Y') ?> <?= Helper::e(APP_NAME) ?>. Built for students.</div>
    </div>
</footer>

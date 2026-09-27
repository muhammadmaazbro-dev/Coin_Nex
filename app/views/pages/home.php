<?php
declare(strict_types=1);
?>
<!-- ================= CINEMATIC OPENING LIGHT BEAM ================= -->
<div id="page-entrance-beam" class="page-entrance-beam"></div>

<!-- ================= 3D INTERACTIVE WEBGL BACKGROUND CANVAS ================= -->
<canvas id="three-hero-canvas" class="three-hero-canvas"></canvas>

<!-- ================= SCROLL PROGRESS LASER ================= -->
<!-- ================= SCROLL PROGRESS WITH ROLLING COIN ================= -->
<div id="scroll-progress-wrap" class="scroll-progress-wrap">
    <div id="scroll-progress-laser" class="scroll-progress-laser"></div>
</div>

<!-- ================= FLOATING HUD TELEMETRY CAPSULE ================= -->
<div id="scroll-hud-capsule" class="scroll-hud-capsule">
    <span class="hud-live-pip">●</span>
    <span id="hud-section-label" class="hud-text">01 // OVERVIEW</span>
    <span class="hud-sep">|</span>
    <span id="hud-status-label" class="hud-status">LEDGER SYNCED</span>
</div>

<!-- ================= NAVBAR ================= -->
<nav class="landing-nav" id="main-nav" style="position:sticky;top:0;z-index:900;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);background:rgba(11,13,18,0.88);border-bottom:1px solid rgba(255,255,255,0.08);">
    <div class="container" style="min-height:72px;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:0.6rem 1rem;">
        <!-- Left: Brand Logo (No icon, sleek typography badge) -->
        <a href="<?= Helper::url('') ?>" class="flex items-center gap-2" style="text-decoration:none;flex-shrink:0;">
            <div class="brand-badge-box">
                <span class="brand-badge-initials">CN</span>
                <span class="brand-live-dot"></span>
            </div>
            <div>
                <div class="brand-name" style="color:var(--text);font-size:1.18rem;font-weight:900;letter-spacing:-0.02em;"><?= Helper::e(APP_NAME) ?></div>
                <div class="brand-tag" style="font-size:.65rem;color:var(--brand);font-weight:700;letter-spacing:.08em;">STUDENT FINTECH 3.0</div>
            </div>
        </a>

        <!-- Center: Desktop Pill Navigation -->
        <div class="nav-center-links">
            <a href="<?= Helper::url('') ?>" class="nav-link-pill active">Home</a>
            <a href="<?= Helper::url('features') ?>" class="nav-link-pill">Features</a>
            <a href="<?= Helper::url('about') ?>" class="nav-link-pill">About</a>
            <a href="<?= Helper::url('faq') ?>" class="nav-link-pill">FAQ</a>
            <a href="<?= Helper::url('contact') ?>" class="nav-link-pill">Contact</a>
        </div>

        <!-- Right: Actions & Settings -->
        <div class="flex items-center gap-2" style="flex-shrink:0;">
            <!-- Settings Dropdown -->
            <div class="dropdown settings-dropdown">
                <button type="button" class="btn btn-ghost btn-sm flex items-center gap-1 btn-magnetic" data-dropdown-trigger style="border-color:rgba(247,183,51,0.3);color:var(--text);font-weight:700;" title="Preferences">
                    <span style="color:var(--brand);font-size:.7rem;">●</span>
                    <span>Preferences</span>
                </button>
                <div class="dropdown-menu dropdown-glass-menu" style="min-width:280px;padding:1rem;border-radius:18px;background:rgba(16,19,27,0.96);border:1px solid rgba(247,183,51,0.3);box-shadow:var(--shadow-lg);">
                    <div class="bold small mb-2 flex items-center gap-1" style="color:var(--brand);letter-spacing:.05em;">
                        <span>[ DISPLAY PREFERENCES ]</span>
                    </div>

                    <!-- Theme -->
                    <div class="mb-3">
                        <label class="faint small mb-1" style="display:block;">Theme</label>
                        <div class="flex gap-1">
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="dark" style="font-size:.78rem;padding:.4rem .2rem;">Dark</button>
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="light" style="font-size:.78rem;padding:.4rem .2rem;">Light</button>
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="eye-care" style="font-size:.78rem;padding:.4rem .2rem;" title="Night study filter">Eye Care</button>
                        </div>
                    </div>

                    <!-- Audio -->
                    <div class="mb-3" style="border-top:1px dashed rgba(255,255,255,0.08);padding-top:.7rem;">
                        <div class="flex between items-center mb-1">
                            <span class="small bold">Audio Effects</span>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="toggleCoinSound()" data-sound-status style="font-size:.75rem;padding:.2rem .6rem;">Sound ON</button>
                        </div>
                    </div>

                    <!-- Text Zoom -->
                    <div style="border-top:1px dashed rgba(255,255,255,0.08);padding-top:.7rem;">
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

            <?php if (Helper::isLoggedIn()): ?>
                <?php $currUser = Helper::currentUser(); ?>
                <a href="<?= Helper::url('dashboard') ?>" class="btn-ultra-gold btn-sm btn-magnetic">
                    <span class="avatar-circle-sm"><?= strtoupper(substr($currUser['name'] ?? 'U', 0, 1)) ?></span>
                    <span>Dashboard →</span>
                </a>
                <a href="<?= Helper::url('logout') ?>" class="btn btn-ghost btn-sm hide-mobile" title="Sign out">Log out</a>
            <?php else: ?>
                <a href="<?= Helper::url('login') ?>" class="btn btn-ghost btn-sm hide-mobile btn-magnetic">Log in</a>
                <a href="<?= Helper::url('register') ?>" class="btn-ultra-gold btn-sm btn-magnetic">
                    <span>Get Started Free →</span>
                </a>
            <?php endif; ?>

            <!-- Mobile Nav Toggle -->
            <div class="dropdown mobile-nav-toggle">
                <button type="button" class="btn btn-ghost btn-sm" data-dropdown-trigger style="padding:.45rem .6rem;" title="Navigation Menu">
                    <span style="font-weight:900;font-size:.9rem;">MENU ≡</span>
                </button>
                <div class="dropdown-menu dropdown-glass-menu" style="min-width:200px;padding:.6rem;border-radius:14px;background:rgba(16,19,27,0.96);border:1px solid rgba(247,183,51,0.3);box-shadow:var(--shadow-lg);">
                    <a href="<?= Helper::url('') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Home</a>
                    <a href="<?= Helper::url('features') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Features</a>
                    <a href="<?= Helper::url('about') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">About Platform</a>
                    <a href="<?= Helper::url('faq') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">FAQ</a>
                    <a href="<?= Helper::url('contact') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Contact Support</a>
                    <div class="dropdown-sep"></div>
                    <?php if (Helper::isLoggedIn()): ?>
                        <a href="<?= Helper::url('dashboard') ?>" class="dropdown-item bold" style="color:var(--brand);">Open Dashboard →</a>
                        <a href="<?= Helper::url('logout') ?>" class="dropdown-item" style="color:var(--danger);">Log out</a>
                    <?php else: ?>
                        <a href="<?= Helper::url('login') ?>" class="dropdown-item">Log in</a>
                        <a href="<?= Helper::url('register') ?>" class="dropdown-item bold" style="color:var(--brand);">Get Started Free →</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- ================= HERO SECTION ================= -->
<section id="section-hero" class="landing-hero" data-section-name="01 // OVERVIEW">
    <!-- Background Cinematic Vault Video on Scroll -->
    <div class="hero-vault-video-bg">
        <video id="hero-vault-bg-video" class="hero-vault-bg-video" playsinline muted autoplay loop preload="auto">
            <source src="<?= Helper::asset('video/hero-vault.mp4') ?>" type="video/mp4">
            <source src="<?= Helper::asset('hero-vault.mp4') ?>" type="video/mp4">
        </video>
        <div class="hero-vault-video-overlay"></div>
    </div>

    <div class="hero-orb orb-1"></div>
    <div class="hero-orb orb-2"></div>

    <div class="container hero-container" style="position:relative;z-index:2;">
        <div style="display:grid;grid-template-columns:1.08fr 0.92fr;gap:3.5rem;align-items:center;" id="hero-grid">
            
            <!-- Left Hero Content -->
            <div class="hero-entrance-anim">
                <div class="hero-tag-badge mb-2">
                    <span class="hero-tag-dot"></span>
                    <span>Pakistani University FinTech</span>
                </div>

                <h1 class="hero-main-heading">
                    Master Your University Money. <br>
                    <span class="hero-gradient-text">
                        Spend Smart. Save Big.
                    </span>
                </h1>

                <p class="muted hero-lead-p">
                    Say goodbye to messy notebook diaries, manual calculations, and end-of-month cashflow panic. Coin_Nex tracks your allowance, part-time wages, and university expenses with real-time AI categorization and live saving suggestions.
                </p>

                <!-- Hero Action Buttons -->
                <div class="flex gap-2 wrap mb-3" style="margin-bottom:2.2rem;">
                    <?php if (Helper::isLoggedIn()): ?>
                        <a href="<?= Helper::url('dashboard') ?>" class="btn-ultra-gold btn-lg btn-magnetic">
                            <span>Go to My Dashboard →</span>
                        </a>
                        <a href="<?= Helper::url('transactions/add') ?>" class="btn-ultra-dark btn-lg btn-magnetic">
                            <span>+ Add Transaction</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= Helper::url('register') ?>" class="btn-ultra-gold btn-lg btn-magnetic">
                            <span>Create Free Account →</span>
                        </a>
                        <a href="<?= Helper::url('login') ?>" class="btn-ultra-dark btn-lg btn-magnetic" title="Demo student account: umar@gmail.com / UmarPass123">
                            <span>Umar Demo Login ↗</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Trust Metrics (No icons, pure elegant numbers) -->
                <div class="flex gap-3 wrap hero-metrics-bar">
                    <div class="metric-box">
                        <div class="bold metric-val text-success">Rs. 45,000</div>
                        <div class="faint small">Monthly Student Cashflow</div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-box">
                        <div class="bold metric-val text-brand">20% Rate</div>
                        <div class="faint small">50/30/20 Discipline Rule</div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-box">
                        <div class="bold metric-val text-info">100% Private</div>
                        <div class="faint small">Zero Bank Links Needed</div>
                    </div>
                </div>
            </div>

            <!-- Right Hero: Interactive Student Vault Cockpit Card (Replaces old coin) -->
            <div class="hero-card-col hero-vault-cockpit-col" style="display:flex;align-items:center;justify-content:center;position:relative;">
                <div class="vault-cockpit-card hover-3d" id="vault-cockpit-card">
                    <!-- Top Status Bar -->
                    <div class="cockpit-header">
                        <div class="flex items-center gap-2">
                            <span class="cockpit-live-pulse"></span>
                            <span class="cockpit-title-tag">VAULT LEDGER // ACTIVE</span>
                        </div>
                        <span class="cockpit-security-badge">256-BIT ENCRYPTED</span>
                    </div>

                    <!-- Treasury Balance Preview -->
                    <div class="cockpit-balance-box">
                        <div class="cockpit-balance-label">TOTAL MONTHLY TREASURY</div>
                        <div class="cockpit-balance-amount">
                            <span class="cockpit-currency">Rs.</span>
                            <span class="cockpit-val">45,000</span>
                            <span class="cockpit-trend text-success">+18% vs Aug</span>
                        </div>
                    </div>

                    <!-- 50/30/20 Discipline Breakdown -->
                    <div class="cockpit-breakdown-section">
                        <div class="cockpit-split-row">
                            <div class="flex between items-center mb-1">
                                <span class="split-name"><span class="split-dot gold">●</span> 50% Essentials (Hostel/Mess)</span>
                                <span class="split-val bold text-brand">Rs. 22,500</span>
                            </div>
                            <div class="split-progress"><div class="split-bar gold" style="width:50%"></div></div>
                        </div>
                        <div class="cockpit-split-row">
                            <div class="flex between items-center mb-1">
                                <span class="split-name"><span class="split-dot emerald">●</span> 30% Lifestyle (Cafe/Hangouts)</span>
                                <span class="split-val bold text-success">Rs. 13,500</span>
                            </div>
                            <div class="split-progress"><div class="split-bar emerald" style="width:30%"></div></div>
                        </div>
                        <div class="cockpit-split-row">
                            <div class="flex between items-center mb-1">
                                <span class="split-name"><span class="split-dot cyan">●</span> 20% Secure Vault Reserve</span>
                                <span class="split-val bold text-info">Rs. 9,000</span>
                            </div>
                            <div class="split-progress"><div class="split-bar cyan" style="width:20%"></div></div>
                        </div>
                    </div>

                    <!-- Live Activity Ticker -->
                    <div class="cockpit-activity-ticker">
                        <div class="ticker-item">
                            <span class="ticker-icon success">+</span>
                            <span class="ticker-text">Family Monthly Allowance</span>
                            <span class="ticker-num text-success">Rs. 30,000</span>
                        </div>
                        <div class="ticker-item">
                            <span class="ticker-icon expense">-</span>
                            <span class="ticker-text">Semester Textbooks & Mess</span>
                            <span class="ticker-num text-danger">Rs. 4,500</span>
                        </div>
                    </div>

                    <!-- Bottom Action Link -->
                    <div class="cockpit-footer flex between items-center">
                        <span class="faint small">Protected by Coin_Nex Vault</span>
                        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'dashboard' : 'register') ?>" class="cockpit-action-link">
                            Open Full Vault →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= UNIVERSITY BADGES INFINITE MARQUEE ================= -->
<section class="uni-marquee-strip">
    <div class="container text-center mb-3">
        <div class="uni-strip-title faint small text-uppercase" style="letter-spacing:.14em;font-weight:800;color:rgba(255,255,255,0.65);">
            Built For Students Studying At Leading Universities Across Pakistan
        </div>
    </div>
    
    <!-- Seamless Infinite Continuous Marquee with Soft Edge Masks -->
    <div class="uni-marquee-viewport">
        <div class="uni-marquee-track">
            <!-- Set 1 -->
            <div class="uni-marquee-group">
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">NUST ISLAMABAD</span></div>
                <div class="uni-badge-pill"><span class="uni-dot emerald">●</span><span class="uni-name">FAST-NUCES</span></div>
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">LUMS LAHORE</span></div>
                <div class="uni-badge-pill"><span class="uni-dot cyan">●</span><span class="uni-name">GIKI SWABI</span></div>
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">IBA KARACHI</span></div>
                <div class="uni-badge-pill"><span class="uni-dot emerald">●</span><span class="uni-name">COMSATS</span></div>
                <div class="uni-badge-pill"><span class="uni-dot cyan">●</span><span class="uni-name">UET LAHORE</span></div>
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">NED UNIVERSITY</span></div>
                <div class="uni-badge-pill"><span class="uni-dot emerald">●</span><span class="uni-name">AKU KARACHI</span></div>
                <div class="uni-badge-pill"><span class="uni-dot cyan">●</span><span class="uni-name">PIEAS ISLAMABAD</span></div>
            </div>
            <!-- Set 2 (Duplicate for infinite seamless 60fps loop) -->
            <div class="uni-marquee-group" aria-hidden="true">
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">NUST ISLAMABAD</span></div>
                <div class="uni-badge-pill"><span class="uni-dot emerald">●</span><span class="uni-name">FAST-NUCES</span></div>
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">LUMS LAHORE</span></div>
                <div class="uni-badge-pill"><span class="uni-dot cyan">●</span><span class="uni-name">GIKI SWABI</span></div>
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">IBA KARACHI</span></div>
                <div class="uni-badge-pill"><span class="uni-dot emerald">●</span><span class="uni-name">COMSATS</span></div>
                <div class="uni-badge-pill"><span class="uni-dot cyan">●</span><span class="uni-name">UET LAHORE</span></div>
                <div class="uni-badge-pill"><span class="uni-dot gold">●</span><span class="uni-name">NED UNIVERSITY</span></div>
                <div class="uni-badge-pill"><span class="uni-dot emerald">●</span><span class="uni-name">AKU KARACHI</span></div>
                <div class="uni-badge-pill"><span class="uni-dot cyan">●</span><span class="uni-name">PIEAS ISLAMABAD</span></div>
            </div>
        </div>
    </div>
</section>

<!-- ================= THE STORY OF ALI (CASE STUDY) ================= -->
<section id="section-case-study" class="case-study-section" data-section-name="02 // ALI'S SCENARIO" style="padding:5rem 0;perspective:1400px;overflow:visible;">
    <div class="container">
        <div class="text-center mb-4 section-title-group">
            <span class="badge badge-brand mb-2 bold" style="font-size:.75rem;padding:.35rem .85rem;">REAL UNIVERSITY SCENARIO</span>
            <h2 class="section-title">How Ali Solved His Monthly Budget Panic</h2>
            <p class="muted section-sub" style="max-width:38rem;margin:0 auto;">
                Maan lo Ali ek university student hai. Uski September ki complete cashflow Coin_Nex ne manually calculate karne ki pareshani se azad kar di:
            </p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.2rem;perspective:1400px;transform-style:preserve-3d;" id="case-grid">
            <!-- Left: Income Breakdown -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions?type=income' : 'register') ?>" class="card case-card-green hover-3d" style="text-decoration:none;">
                <div class="card-title text-success flex items-center justify-between mb-2">
                    <span class="bold">1. Ali's Total Income</span>
                    <span class="bold" style="font-size:1.3rem;">Rs. 45,000</span>
                </div>
                <p class="muted small mb-3">Family allowance aur part-time hustle milakar Ali ke paas total budget aya (Click to track income):</p>
                
                <div class="flex" style="flex-direction:column;gap:.6rem;">
                    <div class="flex between items-center p-2 glass case-row">
                        <span class="bold small">Family Monthly Allowance</span>
                        <span class="bold text-success">Rs. 30,000</span>
                    </div>
                    <div class="flex between items-center p-2 glass case-row">
                        <span class="bold small">Part-Time Job / Freelancing</span>
                        <span class="bold text-success">Rs. 10,000</span>
                    </div>
                    <div class="flex between items-center p-2 glass case-row">
                        <span class="bold small">Birthday Gift / Eidi</span>
                        <span class="bold text-success">Rs. 5,000</span>
                    </div>
                </div>

                <div class="case-card-link text-success mt-3 bold small">
                    Explore Income Tracker →
                </div>
            </a>

            <!-- Right: Expenses Breakdown -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions?type=expense' : 'register') ?>" class="card case-card-red hover-3d" style="text-decoration:none;">
                <div class="card-title text-danger flex items-center justify-between mb-2">
                    <span class="bold">2. Ali's Expenses</span>
                    <span class="bold" style="font-size:1.3rem;">Rs. 36,000</span>
                </div>
                <p class="muted small mb-3">Campus life ke tamam essential aur leisure expenses categorize huay (Click to track expenses):</p>
                
                <div class="flex" style="flex-direction:column;gap:.45rem;">
                    <div class="flex between items-center p-1 glass case-row">
                        <span class="small">Food &amp; Dining (Highest)</span>
                        <span class="bold text-danger">Rs. 12,000</span>
                    </div>
                    <div class="flex between items-center p-1 glass case-row">
                        <span class="small">Hostel Room &amp; Utilities</span>
                        <span class="bold text-danger">Rs. 10,000</span>
                    </div>
                    <div class="flex between items-center p-1 glass case-row">
                        <span class="small">Transport (Bus, Uber, Bike)</span>
                        <span class="bold text-danger">Rs. 5,000</span>
                    </div>
                    <div class="flex between items-center p-1 glass case-row">
                        <span class="small">Entertainment &amp; Outings</span>
                        <span class="bold text-danger">Rs. 4,000</span>
                    </div>
                    <div class="flex between items-center p-1 glass case-row">
                        <span class="small">Academics &amp; Photocopies</span>
                        <span class="bold text-danger">Rs. 3,000</span>
                    </div>
                    <div class="flex between items-center p-1 glass case-row">
                        <span class="small">Subscriptions (Mobile, AI)</span>
                        <span class="bold text-danger">Rs. 2,000</span>
                    </div>
                </div>

                <div class="case-card-link text-danger mt-3 bold small">
                    Explore Expense Tracker →
                </div>
            </a>
        </div>

        <!-- The Result Banner -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'dashboard' : 'register') ?>" class="card result-glow-banner mt-3 text-center hover-3d" style="text-decoration:none;display:block;padding:2.2rem;">
            <div class="result-badge-label mb-2">★ THE COIN_NEX RESULT</div>
            <h3 style="font-size:1.6rem;color:var(--text);margin-bottom:.5rem;font-weight:900;">Rs. 9,000 Net Savings Saved!</h3>
            <p class="muted small" style="max-width:36rem;margin:0 auto 1.4rem;line-height:1.6;">
                Income Rs. 45,000 − Expenses Rs. 36,000 = <b>Rs. 9,000 Net Savings</b>. Plus, the AI recommended cutting Rs. 750/week on food to save an extra <b>Rs. 3,000</b> next month!
            </p>
            <span class="btn-ultra-gold btn-sm btn-magnetic">
                Try Coin_Nex For Your Budget →
            </span>
        </a>
    </div>
</section>

<!-- ================= BENTO GRID FEATURES ================= -->
<section id="section-features" class="bento-features-section" data-section-name="03 // ARCHITECTURE" style="padding:5rem 0;background:rgba(14,18,27,0.45);border-top:1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <div class="text-center mb-4 section-title-group">
            <span class="badge badge-brand mb-2 bold" style="font-size:.75rem;padding:.35rem .85rem;">NEXT-GEN FINTECH ARCHITECTURE</span>
            <h2 class="section-title">Features That Make Students Say "WOW"</h2>
            <p class="muted section-sub" style="max-width:38rem;margin:0 auto;">Built to eliminate financial stress without the complexity of traditional banking software.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:1.5rem;" id="bento-grid">
            <!-- Bento 1: AI Classifier (Wide Card) -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions/add' : 'register') ?>" class="card bento-card bento-wide hover-3d" style="grid-column:span 2;text-decoration:none;">
                <div class="bento-badge-tag mb-2">01 // MACHINE LEARNING &amp; CORRECTION</div>
                <h3 style="font-size:1.35rem;color:var(--text);font-weight:800;margin-bottom:.6rem;">Real-Time AI Auto-Categorization</h3>
                <p class="muted small mb-3" style="line-height:1.6;">
                    Type <i>"McDonald's lunch"</i> and watch Coin_Nex immediately classify it as <b>Food</b>. Type <i>"Uber to university"</i> and it tags <b>Transport</b>. Click to try logging an expense →
                </p>
                <div class="glass bento-simulation-row flex between items-center wrap gap-2" style="border-radius:12px;padding:.85rem 1.1rem;background:rgba(0,0,0,0.3);">
                    <div class="flex items-center gap-2">
                        <span class="ai-chip">[AI]</span>
                        <span class="small bold" style="color:var(--text);">"Subway 6-inch sub" ➜ Food &amp; Dining [Accept]</span>
                    </div>
                    <span class="badge badge-success bold" style="font-size:.72rem;">Accuracy 99.4%</span>
                </div>
            </a>

            <!-- Bento 2: Anomaly Alert -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'budgets' : 'register') ?>" class="card bento-card hover-3d" style="text-decoration:none;">
                <div class="bento-badge-tag text-danger mb-2">02 // OVERSPENDING RADAR</div>
                <h3 style="font-size:1.25rem;color:var(--text);font-weight:800;margin-bottom:.6rem;">Large Expense Warnings</h3>
                <p class="muted small" style="line-height:1.6;">
                    Entering an unusual expense (like Rs. 25,000 semester fee)? The system stops accidental entry and alerts you before budget damage happens.
                </p>
                <div class="bento-footer-action text-danger mt-3 bold small">Open Radar →</div>
            </a>

            <!-- Bento 3: Safe-to-Spend Dial -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'dashboard' : 'register') ?>" class="card bento-card hover-3d" style="text-decoration:none;">
                <div class="bento-badge-tag text-success mb-2">03 // DAILY DISCIPLINE</div>
                <h3 style="font-size:1.25rem;color:var(--text);font-weight:800;margin-bottom:.6rem;">Safe-to-Spend Today</h3>
                <p class="muted small" style="line-height:1.6;">
                    Calculates your exact daily burn limit (e.g. <b>Rs. 1,500/day</b>). Spend less today and tomorrow's safe limit grows automatically!
                </p>
                <div class="bento-footer-action text-success mt-3 bold small">View Daily Limit →</div>
            </a>

            <!-- Bento 4: PDF & CSV Export -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'reports' : 'register') ?>" class="card bento-card hover-3d" style="text-decoration:none;">
                <div class="bento-badge-tag text-info mb-2">04 // OFFICIAL REPORTS</div>
                <h3 style="font-size:1.25rem;color:var(--text);font-weight:800;margin-bottom:.6rem;">Pure-PHP PDF Export</h3>
                <p class="muted small" style="line-height:1.6;">
                    Download branded, itemized monthly summaries for parents, hostel wardens, or scholarship audits with 1 tap. Zero external dependencies.
                </p>
                <div class="bento-footer-action text-info mt-3 bold small">Generate Statements →</div>
            </a>

            <!-- Bento 5: BudgetBee AI Assistant -->
            <a href="<?= Helper::url(Helper::isLoggedIn() ? 'insights' : 'register') ?>" class="card bento-card hover-3d" style="text-decoration:none;">
                <div class="bento-badge-tag text-brand mb-2">05 // 24/7 COPILOT</div>
                <h3 style="font-size:1.25rem;color:var(--text);font-weight:800;margin-bottom:.6rem;">BudgetBee AI Assistant</h3>
                <p class="muted small" style="line-height:1.6;">
                    Ask natural questions like <i>"Can I afford biryani tonight?"</i> or <i>"How much did I spend on Uber?"</i> and get instant answers.
                </p>
                <div class="bento-footer-action text-brand mt-3 bold small">Start AI Conversation →</div>
            </a>
        </div>
    </div>
</section>

<!-- ================= INTERACTIVE SAVINGS CALCULATOR ================= -->
<section id="section-calculator" class="calculator-section" data-section-name="04 // PLANNER" style="padding:5rem 0;">
    <div class="container">
        <div class="card calc-container-card" style="background:linear-gradient(145deg, rgba(20,25,38,0.92) 0%, rgba(12,15,22,0.97) 100%);border:1px solid rgba(247,183,51,0.35);border-radius:24px;padding:clamp(1.8rem,4vw,3.2rem);box-shadow:var(--shadow-lg);">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3.5rem;align-items:center;" id="calc-grid">
                <div>
                    <span class="badge badge-brand mb-2 bold" style="font-size:.75rem;padding:.35rem .85rem;">INTERACTIVE STUDENT PLANNER</span>
                    <h2 class="section-title" style="margin-bottom:.8rem;">See How Much You Could Save Every Month</h2>
                    <p class="muted small mb-3" style="line-height:1.6;">Slide your monthly allowance or part-time earnings to see your recommended 50/30/20 student allocation in real-time:</p>

                    <div class="field" style="margin-bottom:1.5rem;">
                        <div class="flex between items-center mb-2">
                            <label class="bold small" for="calc-slider" style="color:var(--text);">Monthly Allowance / Income</label>
                            <span class="bold" style="font-size:1.4rem;color:var(--brand);font-family:var(--font-display);" id="calc-display-income">Rs. 30,000</span>
                        </div>
                        <input type="range" id="calc-slider" min="10000" max="80000" step="1000" value="30000" class="custom-slider" style="width:100%;accent-color:var(--brand);cursor:pointer;">
                        <div class="flex between faint small mt-1">
                            <span>Rs. 10,000</span>
                            <span>Rs. 45,000</span>
                            <span>Rs. 80,000</span>
                        </div>
                    </div>
                </div>

                <!-- Calculator Results Display -->
                <div style="background:rgba(14,18,27,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:1.8rem;">
                    <div class="card-title text-brand mb-3 bold small" style="letter-spacing:.05em;">● RECOMMENDED 50/30/20 SMART ALLOCATION</div>
                    
                    <div class="flex" style="flex-direction:column;gap:1.2rem;">
                        <div>
                            <div class="flex between small mb-1">
                                <span class="muted">Essentials &amp; Hostel (50%)</span>
                                <span class="bold text-danger" id="calc-essentials">Rs. 15,000</span>
                            </div>
                            <div class="progress" style="height:8px;background:rgba(255,255,255,0.08);border-radius:99px;overflow:hidden;"><span id="bar-essentials" style="display:block;height:100%;width:50%;background:var(--danger);border-radius:99px;transition:width .3s;"></span></div>
                        </div>

                        <div>
                            <div class="flex between small mb-1">
                                <span class="muted">Food &amp; Lifestyle (30%)</span>
                                <span class="bold text-info" id="calc-lifestyle">Rs. 9,000</span>
                            </div>
                            <div class="progress" style="height:8px;background:rgba(255,255,255,0.08);border-radius:99px;overflow:hidden;"><span id="bar-lifestyle" style="display:block;height:100%;width:30%;background:var(--info);border-radius:99px;transition:width .3s;"></span></div>
                        </div>

                        <div>
                            <div class="flex between small mb-1">
                                <span class="muted">Monthly Savings (20%)</span>
                                <span class="bold text-success" id="calc-savings">Rs. 6,000</span>
                            </div>
                            <div class="progress" style="height:8px;background:rgba(255,255,255,0.08);border-radius:99px;overflow:hidden;"><span id="bar-savings" style="display:block;height:100%;width:20%;background:var(--success);border-radius:99px;transition:width .3s;"></span></div>
                        </div>
                    </div>

                    <a href="<?= Helper::url('register') ?>" class="hover-3d mt-3 p-3 text-center" style="display:block;text-decoration:none;background:rgba(45,212,167,0.1);border:1px solid rgba(45,212,167,0.35);border-radius:14px;">
                        <div class="faint small">Projected 1-Year Student Safety Fund:</div>
                        <div class="bold" style="font-size:1.7rem;color:var(--success);font-family:var(--font-display);margin:4px 0;" id="calc-yearly">Rs. 72,000 / year</div>
                        <div class="faint small" style="font-size:.75rem;">Enough for semester fee, new laptop, or emergency hostel reserve! Tap to start saving →</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= STUDENT TESTIMONIALS ================= -->
<section id="section-testimonials" class="testimonials-section" data-section-name="05 // VOICES" style="padding:5rem 0;background:rgba(14,18,27,0.45);border-top:1px solid rgba(255,255,255,0.08);border-bottom:1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <div class="text-center mb-4 section-title-group">
            <span class="badge badge-brand mb-2 bold" style="font-size:.75rem;padding:.35rem .85rem;">REAL STORIES</span>
            <h2 class="section-title">Loved by University Students Everywhere</h2>
            <p class="muted section-sub" style="max-width:38rem;margin:0 auto;">Real feedback from campus hostels, cafeterias, and study halls across Pakistan.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(290px, 1fr));gap:1.5rem;">
            <div class="card hover-3d testimonial-item">
                <div class="flex items-center gap-2 mb-2">
                    <span class="avatar-circle-sm" style="background:#38bdf8;color:#000;">HK</span>
                    <div>
                        <div class="bold small" style="color:var(--text);">Hamza K.</div>
                        <div class="faint small">CS Student · FAST Islamabad</div>
                    </div>
                </div>
                <p class="muted small" style="line-height:1.6;">
                    "Pehle har mahine ke 20 tareekh ko mera balance khatam ho jata tha. Coin_Nex ne dikhaya ke mera 40% paisa bahar biryani aur chai par ja raha tha! Ab main Rs. 8,000 har mahine bacha raha hoon."
                </p>
                <div class="badge-sub text-success bold small mt-2">● Fin-GPA: 3.9 / 4.0</div>
            </div>

            <div class="card hover-3d testimonial-item">
                <div class="flex items-center gap-2 mb-2">
                    <span class="avatar-circle-sm" style="background:#f472b6;color:#000;">AM</span>
                    <div>
                        <div class="bold small" style="color:var(--text);">Ayesha M.</div>
                        <div class="faint small">Medical Student · Rawalpindi</div>
                    </div>
                </div>
                <p class="muted small" style="line-height:1.6;">
                    "The Safe-to-Spend dial is genius. Jab mujhe pata hota hai ke aaj sirf Rs. 600 kharch karne hain, to main faltu shopping se bach jati hoon. Pure-PHP PDF export parents ko dikhane ke liye best hai."
                </p>
                <div class="badge-sub text-brand bold small mt-2">● Zero Bank Overspend</div>
            </div>

            <div class="card hover-3d testimonial-item">
                <div class="flex items-center gap-2 mb-2">
                    <span class="avatar-circle-sm" style="background:#34d399;color:#000;">ZA</span>
                    <div>
                        <div class="bold small" style="color:var(--text);">Zubair A.</div>
                        <div class="faint small">Engineering · NUST H-12</div>
                    </div>
                </div>
                <p class="muted small" style="line-height:1.6;">
                    "Bank login ki koi zaroorat nahi parti jo sabse safe baat hai. AI auto-categorization bohot fast hai aur 3D animations se application bilkul modern lagti hai!"
                </p>
                <div class="badge-sub text-info bold small mt-2">● 100% Private Ledger</div>
            </div>
        </div>
    </div>
</section>

<!-- ================= CALL TO ACTION (CTA) ================= -->
<section class="cta-final-section" style="padding:6rem 0;text-align:center;position:relative;overflow:hidden;">
    <div class="container" style="position:relative;z-index:2;">
        <span class="badge badge-brand mb-2 bold" style="font-size:.75rem;padding:.35rem .85rem;">100% FREE · NO CREDIT CARD REQUIRED</span>
        <h2 class="section-title" style="font-size:clamp(2.3rem,5vw,3.5rem);margin-bottom:1rem;">
            Take Control of Your Student Money Today.
        </h2>
        <p class="muted" style="max-width:34rem;margin:0 auto 2.2rem;font-size:1.1rem;line-height:1.6;">
            Join smart university students who track, save, and stress less about money. Start in 30 seconds.
        </p>

        <div class="flex center gap-2 wrap justify-center">
            <a href="<?= Helper::url('register') ?>" class="btn-ultra-gold btn-lg btn-magnetic">
                <span>Get Started Free →</span>
            </a>
            <a href="<?= Helper::url('login') ?>" class="btn-ultra-dark btn-lg btn-magnetic">
                <span>Log In to Existing Account ↗</span>
            </a>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer style="border-top:1px solid rgba(255,255,255,0.08);padding:2.8rem 0;background:rgba(10,13,19,0.95);position:relative;z-index:10;">
    <div class="container flex between wrap gap-3 items-center">
        <div class="flex items-center gap-2">
            <div class="brand-badge-box" style="width:34px;height:34px;">
                <span class="brand-badge-initials" style="font-size:.8rem;">CN</span>
            </div>
            <div>
                <span class="bold" style="color:var(--text);font-size:1rem;"><?= Helper::e(APP_NAME) ?></span>
                <span class="faint small"> · <?= Helper::e(APP_TAGLINE) ?> · v<?= Helper::e(APP_VERSION) ?></span>
            </div>
        </div>

        <div class="flex gap-2 wrap faint small footer-links">
            <a href="<?= Helper::url('') ?>" class="link">Home</a>
            <span>·</span>
            <a href="<?= Helper::url('about') ?>" class="link">About Us</a>
            <span>·</span>
            <a href="<?= Helper::url('faq') ?>" class="link">FAQ</a>
            <span>·</span>
            <a href="<?= Helper::url('contact') ?>" class="link">Contact Support</a>
            <span>·</span>
            <a href="<?= Helper::url('login') ?>" class="link">Log in</a>
            <span>·</span>
            <a href="<?= Helper::url('register') ?>" class="link">Register</a>
            <span>·</span>
            <a href="<?= Helper::url('admin') ?>" class="link">Admin</a>
        </div>

        <div class="faint small">
            © 2026 Coin_Nex. Built for Pakistani University Students.
        </div>
    </div>
</footer>

<!-- ================= HANGING PULL ROPE MECHANISM ================= -->
<div id="rope-pull-mechanism" class="rope-container" onclick="toggleToolsDrawers()" title="Toggle Student Toolkit">
    <div class="rope-bracket"></div>
    <div class="rope-cord">
        <div class="rope-handle">
            <div class="rope-ring">
                <div class="rope-ring-inner"></div>
            </div>
            <div class="rope-label">
                <span class="rope-dot"></span>
                <span id="rope-label-text">Tools</span>
            </div>
        </div>
    </div>
</div>

<!-- ================= TOOLS DRAWERS BACKDROP ================= -->
<div id="tools-backdrop" class="tools-backdrop" onclick="closeToolsDrawers()"></div>

<!-- ================= LEFT TOOLS DRAWER ================= -->
<aside id="tools-drawer-left" class="tools-drawer tools-drawer-left" aria-label="Student Left Toolkit">
    <div class="tools-drawer-header">
        <div>
            <div class="bold small" style="color:var(--brand);font-size:.72rem;">[ UTILITIES ]</div>
            <h3 class="tools-header-title">Student Daily Toolkit</h3>
            <p class="tools-header-sub">Daily Campus Life Utilities</p>
        </div>
        <button type="button" class="tools-close-btn" onclick="closeToolsDrawers()" title="Close Drawers">✕</button>
    </div>

    <div class="tools-notice-bar">
        <span>Click any tool below to open its dedicated workflow:</span>
    </div>

    <div class="tools-drawer-content">
        <!-- Tool 1: Roommate Splitter -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions/add?type=expense&note=Roommate+Split' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">Roommate Splitter</strong>
                <span class="tool-badge">Khata</span>
            </div>
            <p class="tool-card-desc">Mess bill, room rent, drinking water aur grocery roomies me 1-click divide karein.</p>
            <div class="tool-mock-box">
                <div class="flex between small mb-1">
                    <span class="faint">Hamza owes:</span>
                    <span class="text-danger bold">Rs. 650</span>
                </div>
                <div class="flex between small">
                    <span class="faint">Zubair paid:</span>
                    <span class="text-success bold">+Rs. 2,100</span>
                </div>
                <div class="tool-mock-action">Open Splitter Workflow →</div>
            </div>
        </a>

        <!-- Tool 2: SMS Quick Reader -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'import' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">SMS Quick Reader</strong>
                <span class="tool-badge">Instant OCR</span>
            </div>
            <p class="tool-card-desc">3737 ya 8558 ka payment SMS paste karein, amount aur category auto-detect ho jayegi.</p>
            <div class="tool-mock-box">
                <div class="small faint mb-1" style="font-style:italic;">"Paid Rs. 240 to Cafe via JazzCash"</div>
                <div class="flex items-center gap-1 small text-brand bold">
                    <span>Detected: Rs. 240 · Food &amp; Dining</span>
                </div>
                <div class="tool-mock-action mt-1">Open SMS Reader →</div>
            </div>
        </a>

        <!-- Tool 3: 1-Tap Chai Counter -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'transactions/add?category=Food&amount=60&note=Chai' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">1-Tap Chai Counter</strong>
                <span class="tool-badge">Dhabba</span>
            </div>
            <p class="tool-card-desc">University dhabba par kharcha track karein baghair lambi entry kiye.</p>
            <div class="tool-mock-box">
                <div class="flex gap-1 mb-1">
                    <span class="tool-pill-btn">+1 Chai (Rs. 60)</span>
                    <span class="tool-pill-btn">+1 Samosa (Rs. 40)</span>
                </div>
                <div class="small faint">Month total: <strong class="text-brand">42 Cups (Rs. 2,520)</strong></div>
                <div class="tool-mock-action mt-1">Log Dhabba Expense →</div>
            </div>
        </a>

        <!-- Tool 4: Semester Fee Goal Countdown -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'budgets' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">Semester Fee Target</strong>
                <span class="tool-badge">Tuition</span>
            </div>
            <p class="tool-card-desc">Next semester ki fee target set karein aur rozana saving quota calculate karein.</p>
            <div class="tool-mock-box">
                <div class="flex between small mb-1">
                    <span>Goal: Rs. 65,000</span>
                    <span class="faint">48 Days left</span>
                </div>
                <div class="small text-success bold">Target: Rozana Rs. 420 save karein</div>
                <div class="tool-mock-action mt-1">Manage Budget Target →</div>
            </div>
        </a>
    </div>
</aside>

<!-- ================= RIGHT TOOLS DRAWER ================= -->
<aside id="tools-drawer-right" class="tools-drawer tools-drawer-right" aria-label="Student Right Toolkit">
    <div class="tools-drawer-header">
        <div>
            <div class="bold small" style="color:var(--success);font-size:.72rem;">[ POWER TOOLS ]</div>
            <h3 class="tools-header-title">Campus Power Suite</h3>
            <p class="tools-header-sub">Financial Freedom &amp; AI</p>
        </div>
        <button type="button" class="tools-close-btn" onclick="closeToolsDrawers()" title="Close Drawers">✕</button>
    </div>

    <div class="tools-notice-bar">
        <span>Smart student features ready to access:</span>
    </div>

    <div class="tools-drawer-content">
        <!-- Tool 1: Saving Streaks -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'tips' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">Saving Streaks</strong>
                <span class="tool-badge">Streaks</span>
            </div>
            <p class="tool-card-desc">Zero-kharcha din celebrate karein aur golden badges unlock karein.</p>
            <div class="tool-mock-box">
                <div class="small bold text-brand mb-1">Current Streak: 5 Days!</div>
                <div class="small faint">Next Badge: Pocket Defender (at 7 days)</div>
                <div class="tool-mock-action mt-1">View Student Tips &amp; Streaks →</div>
            </div>
        </a>

        <!-- Tool 2: Campus Used Books Market -->
        <a href="<?= Helper::url('contact?topic=books') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">Used Books Market</strong>
                <span class="tool-badge">Bazaar</span>
            </div>
            <p class="tool-card-desc">Purani books, lab coats aur drafters juniors ko sell karein, foran cash recover karein.</p>
            <div class="tool-mock-box">
                <div class="small bold mb-1">Calculus 9th Ed · Rs. 850 <span class="tool-badge-green">Save 60%</span></div>
                <div class="small faint">FAST CS Department · Direct Inquiries</div>
                <div class="tool-mock-action mt-1">Contact / Inquire →</div>
            </div>
        </a>

        <!-- Tool 3: Fin-GPA Score -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'reports' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">Fin-GPA Score</strong>
                <span class="tool-badge">Score</span>
            </div>
            <p class="tool-card-desc">Aapki monthly saving aur budget control ka credit-score style 4.0 transcript rating.</p>
            <div class="tool-mock-box">
                <div class="flex between items-center mb-1">
                    <span class="bold text-brand" style="font-size:1.05rem;">GPA: 3.84 / 4.00</span>
                    <span class="tool-badge-green">Grade A</span>
                </div>
                <div class="small faint">Remarks: Top 10% disciplined student savers</div>
                <div class="tool-mock-action mt-1">Open Reports &amp; Statements →</div>
            </div>
        </a>

        <!-- Tool 4: Bee AI Copilot -->
        <a href="<?= Helper::url(Helper::isLoggedIn() ? 'insights' : 'register') ?>" class="tool-preview-card hover-3d" style="text-decoration:none;display:block;">
            <div class="flex between items-center mb-1">
                <strong class="tool-card-title">Bee AI Copilot</strong>
                <span class="tool-badge">AI 24/7</span>
            </div>
            <p class="tool-card-desc">AI assistant se financial advice lein aur expenses categorize karwayen.</p>
            <div class="tool-mock-box">
                <div class="small faint mb-1">Query: "Can I afford dinner out tonight?"</div>
                <div class="small text-success bold">Bee: "You have Rs. 1,400 safe-to-spend today!"</div>
                <div class="tool-mock-action mt-1">Start Bee AI Chat →</div>
            </div>
        </a>
    </div>
</aside>

<!-- ================= STYLES ================= -->
<style>
/* ================= HERO BACKGROUND VAULT VIDEO ON SCROLL ================= */
.hero-vault-video-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
    pointer-events: none;
}
.hero-vault-bg-video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: 100%;
    height: 100%;
    transform: translate(-50%, -50%);
    object-fit: cover;
    opacity: 0.90;
    filter: contrast(1.15) brightness(1.02);
    will-change: transform;
}
.hero-vault-video-overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: 
        radial-gradient(circle at 74% 50%, rgba(7, 9, 14, 0.05) 0%, rgba(7, 9, 14, 0.6) 65%, #07090e 100%),
        linear-gradient(to right, rgba(7, 9, 14, 0.92) 0%, rgba(7, 9, 14, 0.72) 45%, rgba(7, 9, 14, 0.28) 75%, rgba(7, 9, 14, 0.68) 100%),
        linear-gradient(to bottom, rgba(11, 13, 18, 0.65) 0%, transparent 15%, transparent 75%, #07090e 100%);
}

/* Light theme support for hero video background */
[data-theme="light"] .hero-vault-bg-video {
    opacity: 0.45;
    filter: contrast(1.1) brightness(1.05);
}
[data-theme="light"] .hero-vault-video-overlay {
    background: 
        radial-gradient(circle at 74% 50%, rgba(248, 250, 252, 0.2) 0%, rgba(248, 250, 252, 0.82) 65%, #f8fafc 100%),
        linear-gradient(to right, rgba(248, 250, 252, 0.95) 0%, rgba(248, 250, 252, 0.85) 45%, rgba(248, 250, 252, 0.5) 75%, rgba(248, 250, 252, 0.9) 100%),
        linear-gradient(to bottom, rgba(248, 250, 252, 0.75) 0%, transparent 15%, transparent 75%, #f8fafc 100%);
}

/* Card Interactive Hover Coins */
.coin-particle-host {
    position: absolute;
    inset: -30px;
    pointer-events: none;
    overflow: visible;
    z-index: 50;
}
.gold-coin-particle {
    position: absolute;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #fff5a8 0%, #f7b733 50%, #b45309 100%);
    border: 1.5px solid #ffea79;
    box-shadow: 0 0 12px rgba(247, 183, 51, 0.85), 0 4px 8px rgba(0, 0, 0, 0.5);
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8.5px;
    font-weight: 900;
    color: #78350f;
    user-select: none;
    will-change: transform, opacity;
}

/* ================= VAULT COCKPIT CARD (REPLACES OLD COIN) ================= */
.hero-vault-cockpit-col {
    width: 100%;
}
.vault-cockpit-card {
    background: linear-gradient(145deg, rgba(20, 25, 36, 0.94) 0%, rgba(11, 14, 21, 0.98) 100%);
    border: 1px solid rgba(247, 183, 51, 0.35);
    border-radius: 24px;
    padding: 1.8rem;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.85), 0 0 35px -5px rgba(247, 183, 51, 0.18);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
}
.vault-cockpit-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #f7b733, transparent);
}
.cockpit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 0.9rem;
    margin-bottom: 1.2rem;
}
.cockpit-live-pulse {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2dd4a7;
    box-shadow: 0 0 10px #2dd4a7;
    animation: pipPulse 1.6s infinite;
}
.cockpit-title-tag {
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    color: var(--brand);
}
.cockpit-security-badge {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 99px;
    background: rgba(45, 212, 167, 0.12);
    color: #2dd4a7;
    border: 1px solid rgba(45, 212, 167, 0.3);
}
.cockpit-balance-box {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 1.1rem;
    margin-bottom: 1.2rem;
}
.cockpit-balance-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    color: rgba(255, 255, 255, 0.55);
    margin-bottom: 0.4rem;
}
.cockpit-balance-amount {
    display: flex;
    align-items: baseline;
    gap: 6px;
}
.cockpit-currency {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--brand);
}
.cockpit-val {
    font-family: var(--font-display, sans-serif);
    font-size: 2.2rem;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: -0.02em;
}
.cockpit-trend {
    font-size: 0.75rem;
    font-weight: 700;
    margin-left: auto;
    padding: 0.2rem 0.55rem;
    border-radius: 99px;
    background: rgba(45, 212, 167, 0.15);
    border: 1px solid rgba(45, 212, 167, 0.3);
}
.cockpit-breakdown-section {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.2rem;
}
.cockpit-split-row {
    font-size: 0.8rem;
}
.split-name {
    color: rgba(255, 255, 255, 0.75);
    font-weight: 600;
}
.split-dot {
    display: inline-block;
    font-size: 0.6rem;
    margin-right: 4px;
}
.split-dot.gold { color: #f7b733; }
.split-dot.emerald { color: #2dd4a7; }
.split-dot.cyan { color: #38bdf8; }
.split-progress {
    height: 5px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 99px;
    overflow: hidden;
}
.split-bar {
    height: 100%;
    border-radius: 99px;
}
.split-bar.gold { background: #f7b733; }
.split-bar.emerald { background: #2dd4a7; }
.split-bar.cyan { background: #38bdf8; }
.cockpit-activity-ticker {
    background: rgba(0, 0, 0, 0.25);
    border-radius: 12px;
    padding: 0.6rem 0.8rem;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    margin-bottom: 1.2rem;
}
.ticker-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.76rem;
}
.ticker-icon {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 0.75rem;
}
.ticker-icon.success {
    background: rgba(45, 212, 167, 0.2);
    color: #2dd4a7;
}
.ticker-icon.expense {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
.ticker-text {
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
    flex-grow: 1;
}
.ticker-num {
    font-weight: 700;
}
.cockpit-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding-top: 0.9rem;
}
.cockpit-action-link {
    color: var(--brand);
    text-decoration: none;
    font-weight: 800;
    font-size: 0.82rem;
    transition: transform 0.2s;
}
.cockpit-action-link:hover {
    transform: translateX(3px);
}

/* 3D WebGL Canvas */
.three-hero-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 0;
    pointer-events: none; /* Let clicks pass to vault or buttons */
    opacity: 0.65;
    transition: opacity .5s;
}

/* 3D MONEY VAULT STAGE */
.hero-vault-col {
    perspective: 1200px;
}
.vault-stage-card {
    background: linear-gradient(145deg, rgba(23,28,40,0.94) 0%, rgba(12,15,22,0.98) 100%) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(247,183,51,0.45) !important;
    box-shadow: 
        0 25px 70px -15px rgba(0,0,0,0.9), 
        0 0 40px -5px rgba(247,183,51,0.3) !important;
    border-radius: 24px;
    padding: 1.4rem;
    position: relative;
    overflow: hidden;
}
.vault-header-bar {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 0.75rem;
}
.vault-live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2dd4a7;
    box-shadow: 0 0 8px #2dd4a7;
    animation: pipPulse 1.6s infinite;
}
.vault-balance-badge {
    background: rgba(247, 183, 51, 0.15);
    color: #f7b733;
    border: 1px solid rgba(247, 183, 51, 0.45);
    font-size: 0.95rem;
    font-weight: 800;
    padding: 0.35rem 0.85rem;
    border-radius: 99px;
    font-family: var(--font-display, sans-serif);
    box-shadow: 0 0 15px rgba(247, 183, 51, 0.2);
}
/* ================= FRAMELESS 3D COIN STAGE ================= */
.hero-coin-stage {
    position: relative;
    width: 100%;
    background: radial-gradient(circle at 50% 50%, rgba(245, 158, 11, 0.08) 0%, rgba(10, 13, 20, 0) 70%);
    border-radius: 24px;
    padding: 0.5rem 0.75rem;
    box-shadow: none;
    border: none;
}
.coin-stage-pill {
    background: rgba(14, 18, 27, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 99px;
    padding: 0.45rem 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}
.coin-canvas-wrap {
    position: relative;
    width: 100%;
    height: 440px; /* Spacious, grand 3D stage */
    background: transparent;
    border: none;
    overflow: visible;
    cursor: grab;
}
.coin-canvas-wrap:active {
    cursor: grabbing;
}
.money-vault-canvas {
    width: 100% !important;
    height: 100% !important;
    display: block;
}
.vault-toast-alert {
    position: absolute;
    top: 25px;
    left: 50%;
    transform: translateX(-50%) translateY(-25px);
    background: rgba(14, 18, 27, 0.96);
    border: 1px solid #f59e0b;
    box-shadow: 0 12px 35px rgba(0,0,0,0.85), 0 0 25px rgba(245, 158, 11, 0.45);
    padding: 0.55rem 1.3rem;
    border-radius: 99px;
    display: flex;
    align-items: center;
    gap: 8px;
    opacity: 0;
    pointer-events: none;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 20;
}
.vault-toast-alert.is-shown {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}
.toast-cash-num {
    color: #f59e0b;
    font-weight: 800;
    font-size: 1.05rem;
    font-family: var(--font-display, sans-serif);
}
.toast-cash-label {
    color: var(--text-muted, #94a3b8);
    font-size: 0.8rem;
    font-weight: 600;
}

/* ================= ROLLING COIN SCROLL PROGRESS BAR ================= */
.scroll-progress-wrap {
    position: fixed;
    top: 0; /* At absolute top of window */
    left: 0;
    width: 100%;
    height: 4px;
    z-index: 99999;
    pointer-events: none;
}
.scroll-progress-laser {
    position: absolute;
    top: 0;
    left: 0;
    height: 3px;
    width: 0%;
    background: linear-gradient(90deg, #d97706 0%, #f59e0b 50%, #fef08a 100%);
    box-shadow: 0 0 14px rgba(245, 158, 11, 0.95), 0 0 28px rgba(245, 158, 11, 0.6);
}
.scroll-rolling-coin {
    position: absolute;
    top: 0px; /* Rolls on top laser line */
    left: 14px;
    width: 25px;
    height: 25px;
    transform: translate(-50%, 0);
    pointer-events: none;
    will-change: transform, left;
}
.rolling-coin-face {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #fffbeb 0%, #f59e0b 55%, #78350f 100%);
    border: 2px solid #fef08a;
    box-shadow: 0 0 14px rgba(245, 158, 11, 1), 0 2px 10px rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    transform-origin: center center;
    will-change: transform;
}
.rolling-coin-text {
    font-size: 8.5px;
    font-weight: 900;
    color: #451a03;
    font-family: var(--font-display, sans-serif);
    letter-spacing: -0.5px;
}

/* Floating HUD Capsule */
.scroll-hud-capsule {
    position: fixed;
    bottom: 22px;
    left: 22px;
    z-index: 800;
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(14, 18, 27, 0.88);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(247, 183, 51, 0.35);
    border-radius: 99px;
    padding: 0.45rem 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
    font-size: 0.74rem;
    font-weight: 700;
    letter-spacing: 0.04em;
}
.hud-live-pip {
    color: #2dd4a7;
    font-size: 0.7rem;
    animation: pipPulse 1.6s infinite;
}
@keyframes pipPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(0.7); }
}
.hud-text { color: #ffffff; }
.hud-sep { color: rgba(255, 255, 255, 0.2); }
.hud-status { color: #f7b733; font-family: monospace; }

@media (max-width: 768px) {
    .scroll-hud-capsule {
        bottom: 12px;
        left: 12px;
        font-size: 0.68rem;
        padding: 0.35rem 0.8rem;
    }
}

/* Brand Badge Box */
.brand-badge-box {
    position: relative;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(247, 183, 51, 0.22) 0%, rgba(18, 22, 33, 0.95) 100%);
    border: 1px solid rgba(247, 183, 51, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 16px rgba(247, 183, 51, 0.3);
}
.brand-badge-initials {
    font-size: 0.92rem;
    font-weight: 900;
    color: #f7b733;
    font-family: var(--font-display, sans-serif);
}
.brand-live-dot {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #2dd4a7;
    box-shadow: 0 0 8px #2dd4a7;
}

/* Desktop Navigation Pill */
.nav-center-links {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 4px;
    border-radius: 99px;
}
.nav-link-pill {
    padding: 0.4rem 1.15rem;
    border-radius: 99px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.2s ease;
}
.nav-link-pill:hover,
.nav-link-pill.active {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.08);
}
.nav-link-pill.active {
    background: rgba(247, 183, 51, 0.18);
    color: #f7b733;
    border: 1px solid rgba(247, 183, 51, 0.35);
}

@media (max-width: 900px) {
    .nav-center-links { display: none !important; }
}

/* ULTRA-PREMIUM BUTTONS */
.btn-ultra-gold {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #ffe285 0%, #f7b733 50%, #ea8e05 100%);
    color: #11141c !important;
    font-weight: 800;
    letter-spacing: -0.01em;
    border-radius: 99px;
    text-decoration: none;
    cursor: pointer;
    border: none;
    box-shadow: 
        0 8px 25px -4px rgba(247, 183, 51, 0.55),
        inset 0 1px 1px rgba(255, 255, 255, 0.8),
        inset 0 -2px 4px rgba(0, 0, 0, 0.2);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, filter 0.25s ease;
    overflow: hidden;
}
.btn-ultra-gold::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.45), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}
.btn-ultra-gold:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 
        0 14px 35px -4px rgba(247, 183, 51, 0.75),
        0 0 25px rgba(247, 183, 51, 0.45),
        inset 0 1px 2px rgba(255, 255, 255, 0.9);
}
.btn-ultra-gold:hover::before {
    transform: translateX(100%);
}
.btn-ultra-gold:active {
    transform: translateY(1px) scale(0.98);
}

.btn-ultra-dark {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: rgba(18, 22, 33, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #f1f5f9 !important;
    font-weight: 700;
    letter-spacing: 0.01em;
    border-radius: 99px;
    text-decoration: none;
    cursor: pointer;
    border: 1px solid rgba(247, 183, 51, 0.35);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.45);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.25s ease, box-shadow 0.25s ease;
}
.btn-ultra-dark:hover {
    transform: translateY(-2px) scale(1.02);
    border-color: rgba(247, 183, 51, 0.8);
    background: rgba(26, 32, 48, 0.95);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(247, 183, 51, 0.25);
    color: #ffffff !important;
}

.avatar-circle-sm {
    width: 24px;
    height: 24px;
    font-size: 0.72rem;
    background: #11141c;
    color: #f7b733;
    font-weight: 800;
    display: inline-grid;
    place-items: center;
    border-radius: 50%;
}

/* HERO SECTION */
.landing-hero {
    position: relative;
    padding: clamp(3.5rem, 7vw, 6.5rem) 0 4rem;
    overflow: hidden;
}
.hero-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(70px);
    pointer-events: none;
    z-index: 1;
}
.orb-1 {
    width: 650px;
    height: 650px;
    background: radial-gradient(circle, rgba(247,183,51,0.18) 0%, transparent 70%);
    top: -150px;
    right: -100px;
}
.orb-2 {
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, rgba(45,212,167,0.12) 0%, transparent 70%);
    bottom: -150px;
    left: -100px;
}

.hero-tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(247,183,51,0.1);
    border: 1px solid rgba(247,183,51,0.3);
    padding: 0.35rem 0.9rem;
    border-radius: 99px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--brand);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.hero-tag-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--brand);
    box-shadow: 0 0 6px var(--brand);
}

.hero-main-heading {
    font-size: clamp(2.3rem, 5vw, 3.8rem);
    line-height: 1.06;
    letter-spacing: -0.03em;
    margin-bottom: 1.2rem;
    font-weight: 900;
    color: #ffffff;
}
.hero-gradient-text {
    background: linear-gradient(120deg, #ffde6b 0%, #f59e0b 60%, #fc4a1a 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: inline-block;
}

.hero-lead-p {
    font-size: 1.08rem;
    line-height: 1.6;
    max-width: 36rem;
    margin-bottom: 1.8rem;
}

.hero-metrics-bar {
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding-top: 1.4rem;
}
.metric-val {
    font-size: 1.45rem;
    font-family: var(--font-display);
    line-height: 1.1;
}
.metric-divider {
    width: 1px;
    background: rgba(255, 255, 255, 0.08);
}

/* 3D INTERACTIVE CARD IN HERO */
.hero-3d-interactive-card {
    background: linear-gradient(145deg, rgba(23,28,40,0.92) 0%, rgba(13,16,24,0.96) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(247,183,51,0.35);
    box-shadow: 0 25px 60px -15px rgba(0,0,0,0.8), 0 0 35px -5px rgba(247,183,51,0.25);
    border-radius: 20px;
    padding: 1.6rem;
    position: relative;
    transform: perspective(1200px) rotateY(-6deg) rotateX(4deg) rotateZ(-1deg);
    transform-style: preserve-3d;
    transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.45s ease;
    will-change: transform;
}
.hero-3d-interactive-card:hover {
    transform: perspective(1200px) rotateY(0deg) rotateX(0deg) rotateZ(0deg) translateY(-8px) scale(1.02);
    box-shadow: 0 35px 80px -15px rgba(0, 0, 0, 0.9), 0 0 45px -5px rgba(247, 183, 51, 0.45) !important;
}

.student-avatar-orb {
    width: 36px;
    height: 36px;
    background: var(--grad-brand);
    color: #12141a;
    font-weight: 800;
    font-size: 0.9rem;
    display: inline-grid;
    place-items: center;
    border-radius: 50%;
}
.sub-glass-pill {
    display: block;
    background: rgba(255, 255, 255, 0.035);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 0.85rem;
    transition: all 0.25s ease;
}
.sub-glass-pill:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(247, 183, 51, 0.35);
}

.net-balance-pill {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, rgba(247,183,51,0.12) 0%, rgba(45,212,167,0.1) 100%);
    border: 1px solid rgba(247,183,51,0.3);
    border-radius: 14px;
    padding: 1.1rem;
    transition: all 0.25s ease;
}
.net-balance-pill:hover {
    border-color: #f7b733;
    box-shadow: 0 8px 25px rgba(247, 183, 51, 0.2);
}

.ai-alert-banner {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: rgba(247,183,51,0.06);
    border: 1px solid rgba(247,183,51,0.25);
    border-radius: 12px;
    padding: 0.85rem;
    transition: all 0.25s ease;
}
.ai-alert-banner:hover {
    background: rgba(247,183,51,0.1);
    border-color: rgba(247,183,51,0.4);
}
.ai-status-dot {
    font-size: 0.85rem;
    color: var(--success);
    margin-top: 2px;
}

.card-3d-footer-tag {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.7rem;
    color: var(--text-faint);
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px dashed rgba(255, 255, 255, 0.08);
}
.glow-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #2dd4a7;
    box-shadow: 0 0 6px #2dd4a7;
}

/* UNIVERSITY STRIP: LUXURY INFINITE MARQUEE TICKER */
.uni-marquee-strip {
    border-top: 1px solid rgba(255, 255, 255, 0.07);
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
    padding: 2.2rem 0;
    background: radial-gradient(circle at 50% 50%, rgba(245, 158, 11, 0.04) 0%, rgba(14, 18, 27, 0.7) 100%);
    position: relative;
    overflow: hidden;
}
.uni-marquee-viewport {
    width: 100%;
    overflow: hidden;
    position: relative;
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}
.uni-marquee-track {
    display: flex;
    width: max-content;
    animation: marqueeContinuous 34s linear infinite;
    will-change: transform;
}
.uni-marquee-track:hover {
    animation-play-state: paused;
}
.uni-marquee-group {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding-right: 1.25rem;
    flex-shrink: 0;
}
.uni-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    background: rgba(18, 23, 34, 0.85);
    border: 1px solid rgba(245, 158, 11, 0.22);
    border-radius: 99px;
    padding: 0.55rem 1.25rem;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.4);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    white-space: nowrap;
}
.uni-badge-pill:hover {
    transform: translateY(-3px) scale(1.05);
    border-color: rgba(245, 158, 11, 0.75);
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.28), 0 0 15px rgba(245, 158, 11, 0.35);
    background: rgba(26, 32, 48, 0.95);
}
.uni-dot {
    font-size: 0.65rem;
    line-height: 1;
}
.uni-dot.gold { color: #f59e0b; text-shadow: 0 0 8px rgba(245, 158, 11, 0.8); }
.uni-dot.emerald { color: #10b981; text-shadow: 0 0 8px rgba(16, 185, 129, 0.8); }
.uni-dot.cyan { color: #06b6d4; text-shadow: 0 0 8px rgba(6, 182, 212, 0.8); }
.uni-name {
    font-family: var(--font-display, 'Urbanist', sans-serif);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    color: #f1f5f9;
}

@keyframes marqueeContinuous {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* CASE STUDY SECTION */
.case-card-green {
    border-left: 4px solid var(--success) !important;
    background: rgba(18, 22, 32, 0.75) !important;
    backdrop-filter: blur(14px);
    transition: all 0.3s ease;
}
.case-card-green:hover {
    border-color: var(--success);
    box-shadow: 0 15px 40px rgba(0,0,0,0.6), 0 0 25px rgba(45,212,167,0.2) !important;
}
.case-card-red {
    border-left: 4px solid var(--danger) !important;
    background: rgba(18, 22, 32, 0.75) !important;
    backdrop-filter: blur(14px);
    transition: all 0.3s ease;
}
.case-card-red:hover {
    border-color: var(--danger);
    box-shadow: 0 15px 40px rgba(0,0,0,0.6), 0 0 25px rgba(251,113,133,0.2) !important;
}
.case-row {
    border-radius: 10px;
    padding: 0.65rem 0.9rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
}
.result-glow-banner {
    background: linear-gradient(135deg, rgba(247,183,51,0.1) 0%, rgba(45,212,167,0.1) 100%) !important;
    border: 2px dashed rgba(247,183,51,0.45) !important;
    border-radius: 20px;
    transition: all 0.3s ease;
}
.result-glow-banner:hover {
    border-color: #f7b733 !important;
    box-shadow: 0 20px 50px rgba(0,0,0,0.7), 0 0 35px rgba(247,183,51,0.3) !important;
}
.result-badge-label {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--brand);
    letter-spacing: 0.1em;
}

/* BENTO FEATURES */
.bento-card {
    background: rgba(18, 22, 32, 0.75) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 18px;
    padding: 1.8rem;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.bento-card:hover {
    border-color: rgba(247, 183, 51, 0.5) !important;
    box-shadow: 0 20px 50px rgba(0,0,0,0.7), 0 0 30px rgba(247, 183, 51, 0.25) !important;
    transform: translateY(-4px);
}
.bento-wide {
    background: linear-gradient(145deg, rgba(24,28,40,0.85) 0%, rgba(14,18,26,0.95) 100%) !important;
}
.bento-badge-tag {
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    color: var(--brand);
}
.ai-chip {
    font-size: 0.7rem;
    font-weight: 900;
    background: var(--brand);
    color: #11141c;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
}

/* HOVER 3D LIFT */
.hover-3d {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
}
.hover-3d:hover {
    transform: translateY(-4px);
}

/* ROPE MECHANISM */
.rope-container {
    position: fixed;
    top: 0;
    right: 32px;
    z-index: 10001;
    cursor: pointer;
    user-select: none;
    -webkit-user-select: none;
    transform-origin: top center;
    animation: ropeIdleSway 2.8s ease-in-out infinite;
    transition: filter 0.25s ease;
}
.rope-container:hover {
    filter: drop-shadow(0 0 16px rgba(247, 183, 51, 0.85));
    animation: ropeHoverSway 1.3s ease-in-out infinite;
}
.rope-container.rope-pulled {
    animation: ropeSnap 0.65s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards !important;
}

.rope-bracket {
    width: 28px;
    height: 9px;
    margin: 0 auto;
    background: linear-gradient(180deg, #593f0f 0%, #2b1d04 100%);
    border-radius: 0 0 5px 5px;
    border: 1px solid #7d5917;
    border-top: none;
    box-shadow: 0 3px 6px rgba(0,0,0,0.5);
}
.rope-cord {
    width: 6px;
    height: 92px;
    margin: 0 auto;
    background: repeating-linear-gradient(
        45deg,
        #f7b733,
        #f7b733 5px,
        #c68b1a 5px,
        #c68b1a 10px,
        #79510a 10px,
        #79510a 15px
    );
    border-radius: 3px;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.4), inset -1px 0 2px rgba(0,0,0,0.3);
    position: relative;
    transform-origin: top center;
}
.rope-handle {
    position: absolute;
    bottom: -32px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.rope-ring {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #fff2b0 0%, #f7b733 55%, #b5740a 100%);
    border: 2px solid #ffe279;
    box-shadow: 0 4px 16px rgba(247, 183, 51, 0.6), inset 0 2px 4px rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.rope-ring-inner {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: rgba(14, 17, 24, 0.95);
    border: 2px solid rgba(247, 183, 51, 0.5);
}
.rope-container:hover .rope-ring {
    transform: scale(1.15);
}
.rope-label {
    background: rgba(18, 22, 32, 0.94);
    color: #f7b733;
    border: 1px solid rgba(247, 183, 51, 0.5);
    padding: 0.22rem 0.55rem;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 800;
    white-space: nowrap;
    letter-spacing: 0.02em;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.6);
}
.rope-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #2dd4a7;
    box-shadow: 0 0 6px #2dd4a7;
    animation: ropePulse 1.4s infinite;
}

@keyframes ropeIdleSway {
    0% { transform: rotate(0deg); }
    22% { transform: rotate(6.8deg) skewX(1.2deg); }
    48% { transform: rotate(-6.2deg) skewX(-1.2deg); }
    72% { transform: rotate(4.2deg) skewX(0.7deg); }
    88% { transform: rotate(-2.4deg) skewX(-0.4deg); }
    100% { transform: rotate(0deg); }
}
@keyframes ropeHoverSway {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(10.5deg) skewX(2deg); }
    50% { transform: rotate(-9.8deg) skewX(-2deg); }
    75% { transform: rotate(6.5deg) skewX(1.2deg); }
    100% { transform: rotate(0deg); }
}
@keyframes ropeSnap {
    0% { transform: translateY(0) rotate(0deg); }
    32% { transform: translateY(44px) scaleY(1.24) rotate(-5deg); }
    58% { transform: translateY(-12px) scaleY(0.92) rotate(7deg); }
    76% { transform: translateY(6px) scaleY(1.04) rotate(-3deg); }
    90% { transform: translateY(-2px) scaleY(0.98) rotate(1deg); }
    100% { transform: translateY(0) scaleY(1) rotate(0deg); }
}
@keyframes ropePulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.7); }
}

@media (max-width: 768px) {
    .rope-container { right: 14px; top: 0; }
    .rope-cord { height: 66px; }
}

/* DRAWERS & BACKDROP */
.tools-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(7, 9, 14, 0.72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 10000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.35s ease;
}
.tools-backdrop.is-open {
    opacity: 1;
    pointer-events: auto;
}

.tools-drawer {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 360px;
    max-width: calc(100vw - 36px);
    background: linear-gradient(180deg, #131722 0%, #0c0f16 100%);
    box-shadow: 0 15px 50px rgba(0,0,0,0.8);
    z-index: 10002;
    display: flex;
    flex-direction: column;
    transition: transform 0.42s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
}
.tools-drawer-left {
    left: 0;
    border-right: 1px solid rgba(247, 183, 51, 0.3);
    transform: translateX(-100%);
}
.tools-drawer-left.is-open {
    transform: translateX(0);
}
.tools-drawer-right {
    right: 0;
    border-left: 1px solid rgba(45, 212, 167, 0.3);
    transform: translateX(100%);
}
.tools-drawer-right.is-open {
    transform: translateX(0);
}

.tools-drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem 0.9rem;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.tools-header-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--text);
}
.tools-header-sub {
    margin: 0;
    font-size: 0.72rem;
    color: var(--text-faint);
}
.tools-close-btn {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: var(--text);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}
.tools-close-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border-color: #ef4444;
}

.tools-notice-bar {
    background: rgba(247, 183, 51, 0.08);
    border-bottom: 1px dashed rgba(247, 183, 51, 0.2);
    padding: 0.5rem 1.25rem;
    font-size: 0.72rem;
    color: #f7b733;
}
.tools-drawer-content {
    flex: 1;
    overflow-y: auto;
    padding: 1rem 1.15rem;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.tool-preview-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 0.85rem 1rem;
    cursor: pointer;
    transition: all 0.25s ease;
}
.tool-preview-card:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(247, 183, 51, 0.4);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}
.tool-card-title {
    font-size: 0.88rem;
    color: var(--text);
}
.tool-badge {
    background: rgba(247, 183, 51, 0.15);
    color: #f7b733;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: 6px;
    border: 1px solid rgba(247, 183, 51, 0.3);
}
.tool-badge-green {
    background: rgba(45, 212, 167, 0.15);
    color: #2dd4a7;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: 6px;
    border: 1px solid rgba(45, 212, 167, 0.3);
}
.tool-card-desc {
    margin: 0.2rem 0 0.5rem;
    font-size: 0.74rem;
    color: var(--text-muted);
    line-height: 1.4;
}
.tool-mock-box {
    background: rgba(0, 0, 0, 0.28);
    border: 1px dashed rgba(255,255,255,0.08);
    border-radius: 8px;
    padding: 0.5rem 0.7rem;
}
.tool-mock-action {
    font-size: 0.7rem;
    color: #2dd4a7;
    font-weight: 700;
    margin-top: 0.3rem;
    text-align: center;
    background: rgba(45,212,167,0.08);
    padding: 0.25rem 0.4rem;
    border-radius: 4px;
}
.tool-pill-btn {
    background: rgba(247,183,51,0.12);
    border: 1px solid rgba(247,183,51,0.3);
    color: #f7b733;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.2rem 0.45rem;
    border-radius: 6px;
}

@media (max-width: 900px) {
    #hero-grid, #case-grid, #bento-grid, #calc-grid {
        grid-template-columns: 1fr !important;
    }
    #bento-grid > * {
        grid-column: 1 / -1 !important;
    }
    .hero-3d-interactive-card {
        transform: none !important;
    }
}
</style>

<!-- ================= 3D WEBGL ENGINE & SCROLL TRIGGERS SCRIPT ================= -->
<script>
// ================= 1. THREE.JS 3D SCENE & PARTICLES =================
let scene, camera, renderer;
let coinGroup, coinMesh, outerRing, innerRing, particles;
let mouseX = 0, mouseY = 0;
let targetRotX = 0, targetRotY = 0;
let spinVelocityY = 0, spinVelocityX = 0;
let isDragging = false, lastMouseX = 0, lastMouseY = 0;

function initThreeScene() {
    const canvas = document.getElementById('three-hero-canvas');
    if (!canvas || typeof THREE === 'undefined') return;

    // Scene
    scene = new THREE.Scene();

    // Camera
    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 18;

    // Renderer
    renderer = new THREE.WebGLRenderer({
        canvas: canvas,
        alpha: true,
        antialias: true
    });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    // Lights
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.85);
    scene.add(ambientLight);

    const goldPoint = new THREE.PointLight(0xf7b733, 3.5, 60);
    goldPoint.position.set(12, 10, 15);
    scene.add(goldPoint);

    const cyanPoint = new THREE.PointLight(0x2dd4a7, 2.8, 50);
    cyanPoint.position.set(-14, -8, 12);
    scene.add(cyanPoint);

    const rimLight = new THREE.DirectionalLight(0xfff0b3, 1.2);
    rimLight.position.set(0, 15, -6);
    scene.add(rimLight);

    // (Background 3D starlight particles provide atmosphere)

    // (Clean background without particles)

    // Event Listeners
    window.addEventListener('resize', onWindowResize);
    document.addEventListener('mousemove', onMouseMove);

    // Canvas Drag Physics
    canvas.addEventListener('mousedown', (e) => {
        isDragging = true;
        lastMouseX = e.clientX;
        lastMouseY = e.clientY;
    });
    window.addEventListener('mouseup', () => { isDragging = false; });
    canvas.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const dx = e.clientX - lastMouseX;
        const dy = e.clientY - lastMouseY;
        spinVelocityY = dx * 0.008;
        spinVelocityX = dy * 0.008;
        lastMouseX = e.clientX;
        lastMouseY = e.clientY;
    });

    // Touch
    canvas.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) {
            isDragging = true;
            lastMouseX = e.touches[0].clientX;
            lastMouseY = e.touches[0].clientY;
        }
    }, { passive: true });
    window.addEventListener('touchend', () => { isDragging = false; });
    canvas.addEventListener('touchmove', (e) => {
        if (!isDragging || e.touches.length !== 1) return;
        const dx = e.touches[0].clientX - lastMouseX;
        const dy = e.touches[0].clientY - lastMouseY;
        spinVelocityY = dx * 0.008;
        spinVelocityX = dy * 0.008;
        lastMouseX = e.touches[0].clientX;
        lastMouseY = e.touches[0].clientY;
    }, { passive: true });

    renderLoop();
}

function onWindowResize() {
    if (!camera || !renderer) return;
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);

    if (coinGroup) {
        if (window.innerWidth > 900) {
            coinGroup.position.set(4.8, 0.3, 0);
        } else {
            coinGroup.position.set(0, 1.6, -2);
        }
    }
}

function onMouseMove(e) {
    mouseX = (e.clientX / window.innerWidth) * 2 - 1;
    mouseY = -(e.clientY / window.innerHeight) * 2 + 1;

    targetRotY = mouseX * 0.4;
    targetRotX = -mouseY * 0.3;
}

let animClock = typeof THREE !== 'undefined' ? new THREE.Clock() : null;

function renderLoop() {
    requestAnimationFrame(renderLoop);
    const t = animClock ? animClock.getElapsedTime() : 0;

    if (coinGroup) {
        // Continuous rotation + user drag spin
        coinGroup.rotation.y += 0.007 + spinVelocityY;
        coinGroup.rotation.x += spinVelocityX;

        // Inertia friction
        spinVelocityX *= 0.92;
        spinVelocityY *= 0.92;

        // Smooth mouse tilt
        coinGroup.rotation.y += (targetRotY - coinGroup.rotation.y * 0.08) * 0.04;
        coinGroup.rotation.x += (targetRotX - coinGroup.rotation.x * 0.08) * 0.04;

        // Gentle float
        coinGroup.position.y += Math.sin(t * 1.5) * 0.0025;

        // Gyroscopic Rings
        if (outerRing) {
            outerRing.rotation.x += 0.012;
            outerRing.rotation.y += 0.015;
        }
        if (innerRing) {
            innerRing.rotation.x -= 0.016;
            innerRing.rotation.z += 0.01;
        }
    }

    if (particles) {
        particles.rotation.y = t * 0.018;
        particles.rotation.x = Math.sin(t * 0.015) * 0.04;
    }

    if (renderer && scene && camera) {
        renderer.render(scene, camera);
    }
}

// ================= 2. SCROLL TRIGGER ENGINE & 3D CHOREOGRAPHY =================
function initScrollEngine() {
    const laser = document.getElementById('scroll-progress-laser');
    const rollingCoin = document.getElementById('scroll-rolling-coin');
    const rollingFace = rollingCoin ? rollingCoin.querySelector('.rolling-coin-face') : null;
    const hudSection = document.getElementById('hud-section-label');
    const hudStatus = document.getElementById('hud-status-label');

    const sections = [
        { id: 'section-hero', name: '01 // OVERVIEW', status: 'LEDGER SYNCED' },
        { id: 'section-case-study', name: '02 // ALI SCENARIO', status: '50/30/20 DISCIPLINE' },
        { id: 'section-features', name: '03 // ARCHITECTURE', status: 'AI RADAR 99.4%' },
        { id: 'section-calculator', name: '04 // PLANNER', status: 'ALLOCATION ACTIVE' },
        { id: 'section-testimonials', name: '05 // VOICES', status: 'CAMPUS VERIFIED' }
    ];

    function updateProgress() {
        const st = window.scrollY || document.documentElement.scrollTop;
        const dh = document.documentElement.scrollHeight - window.innerHeight;
        const progress = Math.min(1, Math.max(0, st / (dh || 1)));

        // Update Laser Width
        if (laser) {
            laser.style.width = (progress * 100) + '%';
        }

        // Update Rolling Coin: clamped within viewport [14px, window.innerWidth - 14px]
        if (rollingCoin) {
            const trackW = window.innerWidth || document.documentElement.clientWidth;
            const coinRadius = 14;
            const posX = coinRadius + progress * (trackW - coinRadius * 2);
            rollingCoin.style.left = posX + 'px';
            if (rollingFace) {
                const rollDeg = (st * 1.8) % 360;
                rollingFace.style.transform = 'rotate(' + rollDeg + 'deg)';
            }
        }

        // Section Telemetry
        const scrollMid = st + window.innerHeight * 0.4;
        let activeSec = sections[0];
        sections.forEach(s => {
            const el = document.getElementById(s.id);
            if (el && el.offsetTop <= scrollMid) {
                activeSec = s;
            }
        });
        if (hudSection) hudSection.textContent = activeSec.name;
        if (hudStatus) hudStatus.textContent = activeSec.status;
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress(); // Run once immediately on load!
}
function initMagneticButtons() {
    const btns = document.querySelectorAll('.btn-magnetic');
    btns.forEach(b => {
        b.addEventListener('mousemove', function (e) {
            const r = b.getBoundingClientRect();
            const x = e.clientX - r.left - r.width / 2;
            const y = e.clientY - r.top - r.height / 2;
            b.style.transform = `translate(${x * 0.22}px, ${y * 0.22}px) scale(1.03)`;
        });
        b.addEventListener('mouseleave', function () {
            b.style.transform = '';
        });
    });
}

// ================= 4. INTERACTIVE 50/30/20 CALCULATOR =================
function initCalculator() {
    const slider = document.getElementById('calc-slider');
    const displayIncome = document.getElementById('calc-display-income');
    const essentials = document.getElementById('calc-essentials');
    const lifestyle = document.getElementById('calc-lifestyle');
    const savings = document.getElementById('calc-savings');
    const yearly = document.getElementById('calc-yearly');
    const barEss = document.getElementById('bar-essentials');
    const barLife = document.getElementById('bar-lifestyle');
    const barSave = document.getElementById('bar-savings');

    if (slider) {
        slider.addEventListener('input', function () {
            const val = parseFloat(this.value);
            if (displayIncome) displayIncome.textContent = 'Rs. ' + val.toLocaleString('en-US');

            const essVal = Math.round(val * 0.50);
            const lifeVal = Math.round(val * 0.30);
            const saveVal = Math.round(val * 0.20);
            const yearVal = saveVal * 12;

            if (essentials) essentials.textContent = 'Rs. ' + essVal.toLocaleString('en-US');
            if (lifestyle) lifestyle.textContent = 'Rs. ' + lifeVal.toLocaleString('en-US');
            if (savings) savings.textContent = 'Rs. ' + saveVal.toLocaleString('en-US');
            if (yearly) yearly.textContent = 'Rs. ' + yearVal.toLocaleString('en-US') + ' / year';

            // Spin 3D Coin slightly on slider move
            if (window.coin3D) {
                window.coin3D.rotation.y += 0.06;
            }
        });
    }
}

// ================= 5. HANGING ROPE MECHANISM & DRAWERS =================
let isToolsOpen = false;

function toggleToolsDrawers() {
    const rope = document.getElementById('rope-pull-mechanism');
    if (rope) {
        rope.classList.remove('rope-pulled');
        void rope.offsetWidth;
        rope.classList.add('rope-pulled');
    }

    if (isToolsOpen) {
        closeToolsDrawers();
    } else {
        openToolsDrawers();
    }
}

function openToolsDrawers() {
    isToolsOpen = true;
    document.getElementById('tools-drawer-left')?.classList.add('is-open');
    document.getElementById('tools-drawer-right')?.classList.add('is-open');
    document.getElementById('tools-backdrop')?.classList.add('is-open');
    const label = document.getElementById('rope-label-text');
    if (label) label.textContent = 'Close ✕';
}

function closeToolsDrawers() {
    isToolsOpen = false;
    document.getElementById('tools-drawer-left')?.classList.remove('is-open');
    document.getElementById('tools-drawer-right')?.classList.remove('is-open');
    document.getElementById('tools-backdrop')?.classList.remove('is-open');
    const label = document.getElementById('rope-label-text');
    if (label) label.textContent = 'Tools';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && isToolsOpen) {
        closeToolsDrawers();
    }
});


// ================= 3D CARD INTERACTIVE PERSPECTIVE TILT =================
function init3DCardTilt() {
    const cards = document.querySelectorAll('.card, .feature-card, .testimonial-card, .hover-3d');
    cards.forEach(card => {
        card.style.transformStyle = 'preserve-3d';
        card.style.transition = 'transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.35s ease';

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Maximum tilt angle of 14 degrees
            const rotateX = ((centerY - y) / centerY) * 14;
            const rotateY = ((x - centerX) / centerX) * 14;

            card.style.transform = 'perspective(1000px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg) scale3d(1.03, 1.03, 1.03)';
            card.style.boxShadow = '0 24px 48px rgba(0, 0, 0, 0.65), 0 0 25px rgba(245, 158, 11, 0.25)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            card.style.boxShadow = '';
        });
    });
}

// ================= SCROLL TRIGGERED CONTENT REVEAL ANIMATIONS =================
function initScrollRevealAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // 1. University Strip
    gsap.from('.uni-marquee-strip', {
        scrollTrigger: {
            trigger: '.uni-marquee-strip',
            start: 'top 85%'
        },
        opacity: 0,
        y: 40,
        duration: 0.8,
        ease: 'power3.out'
    });

    // 2. Ali's Scenario Cards: Real-Time 3D Rotation on Scroll ("scroll karne par ghumna")
    // Left: Income Card 3D Rotation & Swivel
    gsap.fromTo('.case-card-green',
        {
            rotationY: -30,
            rotationX: 20,
            rotationZ: -4,
            z: -100,
            y: 80,
            opacity: 0.25
        },
        {
            scrollTrigger: {
                trigger: '#section-case-study',
                start: 'top 95%',
                end: 'bottom 25%',
                scrub: 1.2
            },
            rotationY: 22,
            rotationX: -16,
            rotationZ: 4,
            z: 40,
            y: -30,
            opacity: 1,
            ease: 'none'
        }
    );

    // Right: Expenses Card 3D Rotation (Opposite complementary rotation)
    gsap.fromTo('.case-card-red',
        {
            rotationY: 30,
            rotationX: 20,
            rotationZ: 4,
            z: -100,
            y: 80,
            opacity: 0.25
        },
        {
            scrollTrigger: {
                trigger: '#section-case-study',
                start: 'top 95%',
                end: 'bottom 25%',
                scrub: 1.2
            },
            rotationY: -22,
            rotationX: -16,
            rotationZ: -4,
            z: 40,
            y: -30,
            opacity: 1,
            ease: 'none'
        }
    );

    // The Result Net Savings Banner 3D Wave Tilt
    gsap.fromTo('.result-glow-banner',
        {
            rotationX: 26,
            y: 70,
            scale: 0.92,
            opacity: 0.3
        },
        {
            scrollTrigger: {
                trigger: '.result-glow-banner',
                start: 'top 98%',
                end: 'bottom 40%',
                scrub: 1
            },
            rotationX: -6,
            y: -10,
            scale: 1,
            opacity: 1,
            ease: 'none'
        }
    );

    // 3. Feature Cards (Staggered 3D slide-in)
    gsap.from('.feature-card', {
        scrollTrigger: {
            trigger: '#section-features',
            start: 'top 80%'
        },
        opacity: 0,
        y: 60,
        rotationX: 16,
        stagger: 0.15,
        duration: 0.9,
        ease: 'power3.out'
    });

    // 4. Budget Calculator Card
    gsap.from('#section-calculator .card, #section-calculator .calc-container', {
        scrollTrigger: {
            trigger: '#section-calculator',
            start: 'top 80%'
        },
        opacity: 0,
        y: 50,
        duration: 0.8,
        ease: 'power3.out'
    });

    // 5. Testimonial Cards
    gsap.from('.testimonial-card', {
        scrollTrigger: {
            trigger: '#section-testimonials',
            start: 'top 80%'
        },
        opacity: 0,
        y: 50,
        rotationY: -10,
        stagger: 0.15,
        duration: 0.9,
        ease: 'power3.out'
    });
}

// ================= HERO BACKGROUND VAULT VIDEO (CONTINUOUS PLAY + SCROLL ACCELERATION) =================
function initHeroBackgroundVaultVideoScroller() {
    const video = document.getElementById('hero-vault-bg-video');
    if (!video) return;

    const baseRate = 0.95;
    let targetRate = baseRate;
    let currentRate = baseRate;
    let lastScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
    let lastScrollTime = performance.now();

    video.muted = true;
    video.loop = true;

    // Start video playback automatically
    const startPlay = () => {
        const p = video.play();
        if (p !== undefined) {
            p.catch(() => {
                // Autoplay policy fallback: start on first user interaction
                const resume = () => {
                    video.play().catch(() => {});
                    window.removeEventListener('click', resume);
                    window.removeEventListener('scroll', resume);
                    window.removeEventListener('keydown', resume);
                };
                window.addEventListener('click', resume, { once: true });
                window.addEventListener('scroll', resume, { once: true });
                window.addEventListener('keydown', resume, { once: true });
            });
        }
    };

    video.addEventListener('loadedmetadata', startPlay);
    startPlay();

    // Speed boost on scroll activity
    function boostSpeed(amount) {
        targetRate = Math.min(targetRate + amount, 3.8);
    }

    // Scroll listener: calculate scroll velocity and accelerate video
    window.addEventListener('scroll', () => {
        const now = performance.now();
        const currentY = window.pageYOffset || document.documentElement.scrollTop || 0;
        const dt = Math.max(now - lastScrollTime, 12);
        const dy = Math.abs(currentY - lastScrollY);
        const velocity = (dy / dt) * 12;

        lastScrollY = currentY;
        lastScrollTime = now;

        const boost = Math.min(Math.max(velocity * 0.14, 0.45), 2.8);
        boostSpeed(boost);
    }, { passive: true });

    // Wheel listener: instant speed boost on mouse wheel roll
    window.addEventListener('wheel', (e) => {
        const boost = Math.min(Math.abs(e.deltaY) * 0.009, 1.6);
        boostSpeed(boost);
    }, { passive: true });

    // Touch listener for mobile devices
    window.addEventListener('touchmove', () => {
        boostSpeed(0.8);
    }, { passive: true });

    // 60fps smooth deceleration loop back to normal speed
    function updateRateLoop() {
        // Smoothly decay targetRate back towards baseRate
        targetRate += (baseRate - targetRate) * 0.045;

        // Smoothly interpolate currentRate
        currentRate += (targetRate - currentRate) * 0.12;

        try {
            video.playbackRate = Math.max(0.5, Math.min(currentRate, 4.0));
        } catch(e) {}

        requestAnimationFrame(updateRateLoop);
    }
    requestAnimationFrame(updateRateLoop);
}

// ================= COCKPIT CARD HOVER COIN EMISSION ("zada nahi thora") =================
function initCockpitCardCoinHover() {
    const card = document.getElementById('vault-cockpit-card');
    if (!card) return;

    let host = card.querySelector('.coin-particle-host');
    if (!host) {
        host = document.createElement('div');
        host.className = 'coin-particle-host';
        card.appendChild(host);
    }

    let lastSpawnTime = 0;
    const activeCoins = [];

    card.addEventListener('mousemove', (e) => {
        const now = performance.now();
        // Throttled to ~170ms so it's gentle ("zada nahi thora")
        if (now - lastSpawnTime < 170) return;
        lastSpawnTime = now;

        const rect = card.getBoundingClientRect();
        const x = (e.clientX - rect.left) + 30;
        const y = (e.clientY - rect.top) + 30;

        spawnCoin(x, y);
    });

    function spawnCoin(startX, startY) {
        if (activeCoins.length > 7) return; // Keep total active coins small and tasteful

        const el = document.createElement('div');
        el.className = 'gold-coin-particle';
        el.textContent = 'Rs';
        host.appendChild(el);

        const coin = {
            el: el,
            x: startX + (Math.random() - 0.5) * 16,
            y: startY + (Math.random() - 0.5) * 8,
            vx: (Math.random() - 0.5) * 2.0,
            vy: -2.4 - Math.random() * 1.8, // subtle upward leap
            rotX: Math.random() * 360,
            rotY: Math.random() * 360,
            rotZ: Math.random() * 360,
            rotSpeedX: (Math.random() - 0.5) * 12,
            rotSpeedY: (Math.random() - 0.5) * 16,
            rotSpeedZ: (Math.random() - 0.5) * 8,
            scale: 0.85 + Math.random() * 0.3,
            opacity: 1,
            life: 0,
            maxLife: 50 + Math.random() * 15
        };

        activeCoins.push(coin);
    }

    function animateCoins() {
        for (let i = activeCoins.length - 1; i >= 0; i--) {
            const c = activeCoins[i];
            c.life++;

            // Gentle gravity
            c.vy += 0.22;
            c.x += c.vx;
            c.y += c.vy;

            c.rotX += c.rotSpeedX;
            c.rotY += c.rotSpeedY;
            c.rotZ += c.rotSpeedZ;

            // Fade out near end of life
            if (c.life > c.maxLife - 18) {
                c.opacity = Math.max(0, (c.maxLife - c.life) / 18);
            }

            c.el.style.transform = `translate3d(${c.x}px, ${c.y}px, 0) scale(${c.scale}) rotateX(${c.rotX}deg) rotateY(${c.rotY}deg) rotateZ(${c.rotZ}deg)`;
            c.el.style.opacity = c.opacity.toString();

            if (c.life >= c.maxLife) {
                if (c.el.parentNode) c.el.parentNode.removeChild(c.el);
                activeCoins.splice(i, 1);
            }
        }

        requestAnimationFrame(animateCoins);
    }
    requestAnimationFrame(animateCoins);
}

// ================= BOOTSTRAP =================
document.addEventListener('DOMContentLoaded', function () {
    initThreeScene();
    initHeroBackgroundVaultVideoScroller();
    initCockpitCardCoinHover();
    initScrollEngine();
    initMagneticButtons();
    initCalculator();
    init3DCardTilt();
    initScrollRevealAnimations();

    // GSAP Stagger Entrance for Hero Elements
    if (typeof gsap !== 'undefined') {
        gsap.from('.hero-entrance-anim > *', {
            y: 35,
            opacity: 0,
            duration: 0.9,
            stagger: 0.12,
            ease: 'power3.out'
        });

        
    }
});
</script>

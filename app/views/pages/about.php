<?php
declare(strict_types=1);
?>
<!-- ================= PUBLIC HEADER ================= -->
<header style="position:sticky;top:0;z-index:90;backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);background:rgba(14,17,23,0.92);border-bottom:1px solid var(--border);">
    <div class="container" style="min-height:72px;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:0.6rem 1rem;">
        <a href="<?= Helper::url('') ?>" class="flex items-center gap-1" style="text-decoration:none;flex-shrink:0;">
            <div class="brand-badge-box site-brand-mark"><span class="brand-badge-initials">CN</span><span class="brand-live-dot"></span></div>
            <div>
                <div class="brand-name" style="color:var(--text);font-size:1.15rem;font-weight:800;letter-spacing:-0.02em;"><?= Helper::e(APP_NAME) ?></div>
                <div class="brand-tag" style="font-size:.68rem;color:var(--brand);font-weight:600;">STUDENT FINTECH</div>
            </div>
        </a>

        <!-- Center: Desktop Pill Navigation -->
        <div class="nav-center-links">
            <a href="<?= Helper::url('') ?>" class="nav-link-pill">Home</a>
            <a href="<?= Helper::url('') ?>#features" class="nav-link-pill">Features</a>
            <a href="<?= Helper::url('about') ?>" class="nav-link-pill active">About</a>
            <a href="<?= Helper::url('faq') ?>" class="nav-link-pill">FAQ</a>
            <a href="<?= Helper::url('contact') ?>" class="nav-link-pill">Contact</a>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-1" style="flex-shrink:0;">
            <div class="dropdown settings-dropdown">
                <button type="button" class="btn btn-ghost btn-sm flex items-center gap-1" data-dropdown-trigger style="border-color:rgba(247,183,51,0.3);color:var(--text);font-weight:600;" title="Theme & Sound Settings">
                    <?= Helper::icon('gear', '14') ?>
                    <span>Settings</span>
                </button>
                <div class="dropdown-menu" style="min-width:280px;padding:1rem;border-radius:16px;background:var(--bg-soft);border:1px solid var(--border-strong);box-shadow:var(--shadow-lg);">
                    <div class="bold small mb-2 flex items-center gap-1" style="color:var(--brand);">
                        <?= Helper::icon('gear', '14') ?>
                        <span>Display Preferences</span>
                    </div>
                    <div class="mb-3">
                        <label class="faint small mb-1" style="display:block;">Display Mode</label>
                        <div class="flex gap-1">
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="dark" style="font-size:.78rem;padding:.4rem .2rem;">Dark</button>
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="light" style="font-size:.78rem;padding:.4rem .2rem;">Light</button>
                            <button type="button" class="btn btn-ghost btn-sm flex-1" data-set-theme="eye-care" style="font-size:.78rem;padding:.4rem .2rem;" title="Warm amber tint to filter blue light for night study">Eye Care</button>
                        </div>
                    </div>
                    <div class="mb-3" style="border-top:1px dashed var(--border);padding-top:.7rem;">
                        <div class="flex between items-center mb-1">
                            <span class="small bold">Sound Effects</span>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="toggleCoinSound()" data-sound-status style="font-size:.75rem;padding:.2rem .6rem;">Sound ON</button>
                        </div>
                    </div>
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

            <?php if (Helper::isLoggedIn()): ?>
                <a href="<?= Helper::url('dashboard') ?>" class="btn btn-primary btn-sm" style="font-weight:700;">Dashboard ➜</a>
            <?php else: ?>
                <a href="<?= Helper::url('login') ?>" class="btn btn-ghost btn-sm hide-mobile">Log in</a>
                <a href="<?= Helper::url('register') ?>" class="btn btn-primary btn-sm" style="font-weight:700;box-shadow:0 4px 15px -3px rgba(247,183,51,0.4);">Get Started</a>
            <?php endif; ?>

            <!-- Mobile Only Menu Dropdown (Strictly Hidden on Desktop) -->
            <div class="dropdown mobile-nav-toggle">
                <button type="button" class="btn btn-ghost btn-sm" data-dropdown-trigger style="padding:.45rem .6rem;" title="Navigation Menu">
                    <?= Helper::icon('list', '18') ?>
                </button>
                <div class="dropdown-menu" style="min-width:200px;padding:.6rem;border-radius:14px;background:var(--bg-soft);border:1px solid var(--border-strong);box-shadow:var(--shadow-lg);">
                    <a href="<?= Helper::url('') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Home</a>
                    <a href="<?= Helper::url('') ?>#features" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Features</a>
                    <a href="<?= Helper::url('about') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">About Us</a>
                    <a href="<?= Helper::url('faq') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">FAQ</a>
                    <a href="<?= Helper::url('contact') ?>" class="dropdown-item" style="padding:.55rem .8rem;border-radius:8px;font-weight:600;">Contact</a>
                    <div class="dropdown-sep"></div>
                    <?php if (Helper::isLoggedIn()): ?>
                        <a href="<?= Helper::url('dashboard') ?>" class="dropdown-item bold" style="color:var(--brand);">Open Dashboard ➜</a>
                    <?php else: ?>
                        <a href="<?= Helper::url('login') ?>" class="dropdown-item">Log in</a>
                        <a href="<?= Helper::url('register') ?>" class="dropdown-item bold" style="color:var(--brand);">Get Started Free ➜</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<main style="padding: 3.5rem 0 5rem;">
    <div class="container" style="max-width: 960px;">

        <!-- Badge & Headline -->
        <div class="center mb-4">
            <div class="inline-flex items-center gap-1 mb-2 animate-up" style="background:rgba(247,183,51,0.1);border:1px solid rgba(247,183,51,0.3);padding:.4rem 1rem;border-radius:var(--radius-full);">
                <?= Helper::icon('sparkle', '14') ?>
                <span style="font-size:.78rem;font-weight:700;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;">Our Mission &amp; Story</span>
            </div>
            <h1 style="font-size:clamp(2.2rem,4.5vw,3.2rem);font-weight:800;letter-spacing:-0.03em;margin-bottom:1rem;">
                Built by Students, <br>
                <span style="background:linear-gradient(120deg, #ffde6b 0%, #f59e0b 60%, #fc4a1a 100%);-webkit-background-clip:text;background-clip:text;color:transparent;">
                    For University Realities.
                </span>
            </h1>
            <p class="muted" style="font-size:1.1rem;max-width:38rem;margin:0 auto;line-height:1.6;">
                Every university student in Pakistan knows the feeling: pocket money arrives on the 1st, and by the 20th, nobody knows where it vanished. Coin_Nex is here to change that permanently.
            </p>
        </div>

        <!-- The Problem & Inspiration -->
        <div class="card mb-4 card-hover" style="background:linear-gradient(145deg, rgba(23,28,40,0.92) 0%, rgba(13,16,24,0.98) 100%);border:1px solid rgba(247,183,51,0.25);border-radius:18px;padding:2.2rem;">
            <div class="flex items-center gap-2 mb-3">
                <div style="color:var(--brand);display:flex;align-items:center;"><?= Helper::icon('bulb', '28') ?></div>
                <div>
                    <h2 style="font-size:1.35rem;font-weight:800;margin:0;">The Campus Financial Dilemma</h2>
                    <div class="faint small">Why traditional banking &amp; budgeting apps fail students</div>
                </div>
            </div>
            <p class="muted" style="line-height:1.7;margin-bottom:1.2rem;">
                General finance apps are designed for salaried professionals with mortgages, stock portfolios, and credit card rewards. They don't understand the daily cash rhythm of a university student:
            </p>
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:1rem;margin-bottom:1rem;">
                <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.1rem;">
                    <div style="color:var(--brand);margin-bottom:.4rem;"><?= Helper::icon('expense', '22') ?></div>
                    <div class="bold" style="color:var(--text);margin-bottom:.3rem;">Hostel &amp; Dhaba Food</div>
                    <div class="faint small" style="line-height:1.5;">Mess dues, evening chai sessions, and midnight snacks quietly eat 40% of allowance.</div>
                </div>
                <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.1rem;">
                    <div style="color:var(--brand);margin-bottom:.4rem;"><?= Helper::icon('swap', '22') ?></div>
                    <div class="bold" style="color:var(--text);margin-bottom:.3rem;">Daily University Commute</div>
                    <div class="faint small" style="line-height:1.5;">Bus passes, petrol for bike/car, Rickshaw/Bykea surges right before exams.</div>
                </div>
                <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.1rem;">
                    <div style="color:var(--brand);margin-bottom:.4rem;"><?= Helper::icon('chart', '22') ?></div>
                    <div class="bold" style="color:var(--text);margin-bottom:.3rem;">Academic Photocopies &amp; Projects</div>
                    <div class="faint small" style="line-height:1.5;">Final year design projects, stationery, printing slides, and lab manuals.</div>
                </div>
            </div>
            <p class="muted" style="line-height:1.7;margin:0;">
                Coin_Nex bridges this gap. We empower students like <strong style="color:var(--brand);">Ali &amp; Umar</strong> to record cash in 5 seconds, watch real-time balance calculations, and understand exactly when it's safe to spend.
            </p>
        </div>

        <!-- 3 Core Tenets Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1.5rem;margin-bottom:3rem;">
            
            <a href="<?= Helper::url('register') ?>" class="card card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.6rem;text-decoration:none;display:block;">
                <div class="stat-ico text-brand" style="margin-bottom:.9rem;"><?= Helper::icon('shield', '22') ?></div>
                <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:.6rem;color:var(--text);">Zero Bank Credentials</h3>
                <p class="faint small" style="line-height:1.6;margin:0;">
                    We will never ask you to link your bank account, debit card, or OTP. Coin_Nex works seamlessly as a private cashflow ledger that you control 100%.
                </p>
            </a>

            <a href="<?= Helper::url('register') ?>" class="card card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.6rem;text-decoration:none;display:block;">
                <div class="stat-ico text-success" style="margin-bottom:.9rem;"><?= Helper::icon('target', '22') ?></div>
                <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:.6rem;color:var(--text);">The 50/30/20 Student Rule</h3>
                <p class="faint small" style="line-height:1.6;margin:0;">
                    Coin_Nex adapts the gold standard: 50% for Needs (Mess, Hostel, Transport), 30% for Wants (Outings, Streaming), and 20% for Emergency Savings.
                </p>
            </a>

            <a href="<?= Helper::url('register') ?>" class="card card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.6rem;text-decoration:none;display:block;">
                <div class="stat-ico text-brand" style="margin-bottom:.9rem;"><?= Helper::icon('brain', '22') ?></div>
                <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:.6rem;color:var(--text);">BudgetBee AI Companion</h3>
                <p class="faint small" style="line-height:1.6;margin:0;">
                    BudgetBee provides friendly contextual nudges: "Hey Ali! You spent Rs. 12,000 on Food this month. If you save Rs. 750/week, you will have Rs. 3,000 extra for semester books!"
                </p>
            </a>

        </div>

        <!-- Call to Action Banner -->
        <div class="card center card-hover" style="background:linear-gradient(135deg, rgba(247,183,51,0.15) 0%, rgba(45,212,167,0.08) 100%);border:1px solid rgba(247,183,51,0.3);border-radius:20px;padding:2.8rem 2rem;">
            <h2 style="font-size:1.8rem;font-weight:800;margin-bottom:.8rem;">Ready to take charge of your campus cash?</h2>
            <p class="muted" style="max-width:32rem;margin:0 auto 1.6rem;">
                Create your student account in under 30 seconds. No credit card, no complex setup.
            </p>
            <div class="flex center gap-2 wrap">
                <a href="<?= Helper::url('register') ?>" class="btn btn-primary btn-lg" style="font-weight:800;box-shadow:0 8px 25px -4px rgba(247,183,51,0.45);">
                    Get Started Free ➜
                </a>
                <a href="<?= Helper::url('contact') ?>" class="btn btn-dark btn-lg">
                    Have Questions? Contact Us
                </a>
            </div>
        </div>

    </div>
</main>

<!-- ================= FOOTER ================= -->
<footer style="border-top:1px solid var(--border);padding:2.5rem 0;background:rgba(12,15,22,0.9);">
    <div class="container flex between wrap gap-3 items-center">
        <div class="flex items-center gap-1">
            <div class="brand-badge-box site-brand-mark site-brand-mark-sm"><span class="brand-badge-initials">CN</span></div>
            <div>
                <span class="bold" style="color:var(--text);font-size:1rem;"><?= Helper::e(APP_NAME) ?></span>
                <span class="faint small"> · <?= Helper::e(APP_TAGLINE) ?></span>
            </div>
        </div>

        <div class="flex gap-2 wrap faint small">
            <a href="<?= Helper::url('') ?>" class="link">Home</a>
            <span>·</span>
            <a href="<?= Helper::url('about') ?>" class="link bold" style="color:var(--brand);">About Us</a>
            <span>·</span>
            <a href="<?= Helper::url('faq') ?>" class="link">FAQ</a>
            <span>·</span>
            <a href="<?= Helper::url('contact') ?>" class="link">Contact Support</a>
            <span>·</span>
            <a href="<?= Helper::url('login') ?>" class="link">Log in</a>
            <span>·</span>
            <a href="<?= Helper::url('register') ?>" class="link">Register</a>
        </div>

        <div class="faint small">
            © 2026 Coin_Nex. Built for Pakistani University Students.
        </div>
    </div>
</footer>

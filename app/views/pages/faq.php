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
            <a href="<?= Helper::url('about') ?>" class="nav-link-pill">About</a>
            <a href="<?= Helper::url('faq') ?>" class="nav-link-pill active">FAQ</a>
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
    <div class="container" style="max-width: 900px;">

        <!-- Headline & Search -->
        <div class="center mb-4">
            <div class="inline-flex items-center gap-1 mb-2 animate-up" style="background:rgba(247,183,51,0.1);border:1px solid rgba(247,183,51,0.3);padding:.4rem 1rem;border-radius:var(--radius-full);">
                <?= Helper::icon('sparkle', '14') ?>
                <span style="font-size:.78rem;font-weight:700;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;">Help Center</span>
            </div>
            <h1 style="font-size:clamp(2.2rem,4.5vw,3.2rem);font-weight:800;letter-spacing:-0.03em;margin-bottom:1rem;">
                Frequently Asked <br>
                <span style="background:linear-gradient(120deg, #ffde6b 0%, #f59e0b 60%, #fc4a1a 100%);-webkit-background-clip:text;background-clip:text;color:transparent;">
                    Questions
                </span>
            </h1>
            <p class="muted" style="font-size:1.05rem;max-width:34rem;margin:0 auto 1.8rem;line-height:1.6;">
                Everything you need to know about managing student cashflow, privacy, and BudgetBee AI on Coin_Nex.
            </p>

            <!-- Instant Search Input -->
            <div style="max-width:540px;margin:0 auto;position:relative;">
                <input type="text" id="faq-search" placeholder="Type keywords e.g. 'bank', 'safe to spend', 'food'…" class="form-input" style="padding:.9rem 1.2rem;padding-left:2.8rem;border-radius:14px;background:var(--surface);border:1px solid var(--border);font-size:.95rem;box-shadow:0 6px 20px -5px rgba(0,0,0,0.4);">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;display:flex;align-items:center;"><?= Helper::icon('search', '16') ?></span>
            </div>
        </div>

        <!-- FAQ Items List -->
        <div id="faq-list" style="display:flex;flex-direction:column;gap:1rem;">

            <div class="card faq-item card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:1.4rem;cursor:pointer;transition:border-color 0.2s;" onclick="toggleFaq(this)">
                <div class="flex between items-center">
                    <h3 style="font-size:1.08rem;font-weight:700;margin:0;color:var(--text);">Do I need to connect my Pakistani bank account or Easypaisa / JazzCash?</h3>
                    <span class="faq-chevron" style="font-size:1.2rem;color:var(--brand);transition:transform .2s;">▾</span>
                </div>
                <div class="faq-answer" style="display:none;margin-top:.85rem;padding-top:.85rem;border-top:1px solid var(--border-soft);color:var(--text-muted);line-height:1.65;font-size:.92rem;">
                    <strong>No, absolutely not!</strong> Coin_Nex was designed specifically with a privacy-first mindset. You never connect bank credentials, debit cards, or OTPs. You simply enter amounts as you spend cash or digital wallet balance (e.g. "Rs. 350 for Canteen Lunch") in just 5 seconds.
                </div>
            </div>

            <div class="card faq-item card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:1.4rem;cursor:pointer;transition:border-color 0.2s;" onclick="toggleFaq(this)">
                <div class="flex between items-center">
                    <h3 style="font-size:1.08rem;font-weight:700;margin:0;color:var(--text);">What is the "Safe-to-Spend Today" feature on my student dashboard?</h3>
                    <span class="faq-chevron" style="font-size:1.2rem;color:var(--brand);transition:transform .2s;">▾</span>
                </div>
                <div class="faq-answer" style="display:none;margin-top:.85rem;padding-top:.85rem;border-top:1px solid var(--border-soft);color:var(--text-muted);line-height:1.65;font-size:.92rem;">
                    "Safe-to-Spend Today" divides your remaining month's balance by the number of days left in the current month. If you have Rs. 9,000 left and 10 days left in September, your Safe-to-Spend is <strong>Rs. 900 / day</strong>. If you stay under this number, you will never go broke before your next allowance!
                </div>
            </div>

            <div class="card faq-item card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:1.4rem;cursor:pointer;transition:border-color 0.2s;" onclick="toggleFaq(this)">
                <div class="flex between items-center">
                    <h3 style="font-size:1.08rem;font-weight:700;margin:0;color:var(--text);">How does Coin_Nex help Ali in the September 2026 example?</h3>
                    <span class="faq-chevron" style="font-size:1.2rem;color:var(--brand);transition:transform .2s;">▾</span>
                </div>
                <div class="faq-answer" style="display:none;margin-top:.85rem;padding-top:.85rem;border-top:1px solid var(--border-soft);color:var(--text-muted);line-height:1.65;font-size:.92rem;">
                    Ali earns Rs. 30,000 family allowance + Rs. 10,000 part-time job + Rs. 5,000 gift = <strong>Rs. 45,000</strong> total income. His expenses across Food (Rs. 12k), Transport (Rs. 5k), Hostel (Rs. 10k), Academics (Rs. 3k), Entertainment (Rs. 4k), Subscriptions (Rs. 2k) total <strong>Rs. 36,000</strong>. Coin_Nex automatically flags Food as his highest spending category (33% of outflow) and gives actionable advice to save Rs. 750/week, keeping his savings at Rs. 9,000.
                </div>
            </div>

            <div class="card faq-item card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:1.4rem;cursor:pointer;transition:border-color 0.2s;" onclick="toggleFaq(this)">
                <div class="flex between items-center">
                    <h3 style="font-size:1.08rem;font-weight:700;margin:0;color:var(--text);">Can I export my monthly expense statement as a printable PDF?</h3>
                    <span class="faq-chevron" style="font-size:1.2rem;color:var(--brand);transition:transform .2s;">▾</span>
                </div>
                <div class="faq-answer" style="display:none;margin-top:.85rem;padding-top:.85rem;border-top:1px solid var(--border-soft);color:var(--text-muted);line-height:1.65;font-size:.92rem;">
                    Yes! Visit the <strong>Reports</strong> tab in your student panel and click "Print / Export PDF". Coin_Nex generates a clean, university-grade monthly statement with category breakdowns, percentage distributions, and cashflow charts formatted perfectly for printing or sharing with parents/sponsors.
                </div>
            </div>

            <div class="card faq-item card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:1.4rem;cursor:pointer;transition:border-color 0.2s;" onclick="toggleFaq(this)">
                <div class="flex between items-center">
                    <h3 style="font-size:1.08rem;font-weight:700;margin:0;color:var(--text);">How do I customize categories for my university hostel or semester?</h3>
                    <span class="faq-chevron" style="font-size:1.2rem;color:var(--brand);transition:transform .2s;">▾</span>
                </div>
                <div class="faq-answer" style="display:none;margin-top:.85rem;padding-top:.85rem;border-top:1px solid var(--border-soft);color:var(--text-muted);line-height:1.65;font-size:.92rem;">
                    You can head to the <strong>Categories</strong> screen at any time to add custom expense or income categories (like "Hostel Laundry", "Chai Dhaba", "Gym Pass", or "Freelance Gig") with custom icons and budget caps.
                </div>
            </div>

            <div class="card faq-item card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:1.4rem;cursor:pointer;transition:border-color 0.2s;" onclick="toggleFaq(this)">
                <div class="flex between items-center">
                    <h3 style="font-size:1.08rem;font-weight:700;margin:0;color:var(--text);">Is Coin_Nex completely free for Pakistani university students?</h3>
                    <span class="faq-chevron" style="font-size:1.2rem;color:var(--brand);transition:transform .2s;">▾</span>
                </div>
                <div class="faq-answer" style="display:none;margin-top:.85rem;padding-top:.85rem;border-top:1px solid var(--border-soft);color:var(--text-muted);line-height:1.65;font-size:.92rem;">
                    Yes, 100% free! All core budgeting tools, category tracking, sound effects, BudgetBee AI insights, and PDF reports are available to every registered student without subscription fees or hidden paywalls.
                </div>
            </div>

        </div>

        <!-- Help Callout -->
        <a href="<?= Helper::url('contact') ?>" class="card mt-4 flex between wrap items-center gap-2 card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.5rem 1.8rem;text-decoration:none;display:flex;">
            <div>
                <div class="bold" style="color:var(--text);font-size:1.05rem;">Still have a question or need assistance?</div>
                <div class="faint small">Our campus student support team is ready to assist you.</div>
            </div>
            <span class="btn btn-primary" style="font-weight:700;">
                Contact Student Support ➜
            </span>
        </a>

    </div>
</main>

<script>
function toggleFaq(card) {
    const ans = card.querySelector('.faq-answer');
    const chev = card.querySelector('.faq-chevron');
    if (!ans) return;
    const isShowing = ans.style.display === 'block';
    ans.style.display = isShowing ? 'none' : 'block';
    if (chev) {
        chev.style.transform = isShowing ? 'rotate(0deg)' : 'rotate(180deg)';
    }
}

// Live Search Filter
document.getElementById('faq-search').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    const items = document.querySelectorAll('.faq-item');
    items.forEach(it => {
        const text = it.textContent.toLowerCase();
        if (!q || text.includes(q)) {
            it.style.display = 'block';
        } else {
            it.style.display = 'none';
        }
    });
});
</script>

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
            <a href="<?= Helper::url('about') ?>" class="link">About Us</a>
            <span>·</span>
            <a href="<?= Helper::url('faq') ?>" class="link bold" style="color:var(--brand);">FAQ</a>
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

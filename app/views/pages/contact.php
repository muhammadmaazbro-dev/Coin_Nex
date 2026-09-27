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
            <a href="<?= Helper::url('faq') ?>" class="nav-link-pill">FAQ</a>
            <a href="<?= Helper::url('contact') ?>" class="nav-link-pill active">Contact</a>
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
                            <span class="small bold">Audio Effects</span>
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
    <div class="container" style="max-width: 980px;">

        <!-- Headline -->
        <div class="center mb-4">
            <div class="inline-flex items-center gap-1 mb-2 animate-up" style="background:rgba(247,183,51,0.1);border:1px solid rgba(247,183,51,0.3);padding:.4rem 1rem;border-radius:var(--radius-full);">
                <?= Helper::icon('sparkle', '14') ?>
                <span style="font-size:.78rem;font-weight:700;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;">We're Here For You</span>
            </div>
            <h1 style="font-size:clamp(2.2rem,4.5vw,3.2rem);font-weight:800;letter-spacing:-0.03em;margin-bottom:1rem;">
                Get in Touch With <br>
                <span style="background:linear-gradient(120deg, #ffde6b 0%, #f59e0b 60%, #fc4a1a 100%);-webkit-background-clip:text;background-clip:text;color:transparent;">
                    Coin_Nex Support
                </span>
            </h1>
            <p class="muted" style="font-size:1.05rem;max-width:36rem;margin:0 auto;line-height:1.6;">
                Have a question about budgeting? Found a bug? Or want to bring Coin_Nex to your university club or student society? Send us a message!
            </p>
        </div>

        <!-- 2-Column Grid: Form & Info Cards -->
        <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:2.5rem;align-items:start;" id="contact-grid">

            <!-- Contact Form -->
            <div class="contact-form-shell">
                <div class="contact-form-glow"></div>
                <div class="contact-form-inner">
                    <div class="contact-form-head">
                        <div class="contact-form-icon">
                            <?= Helper::icon('mail', '21') ?>
                        </div>
                        <div>
                            <span class="contact-kicker">Support Center</span>
                            <h2>Send a Message</h2>
                            <p>Tell us what you need. Our student advisory team typically responds within 24 hours.</p>
                        </div>
                    </div>

                    <form method="POST" action="<?= Helper::url('contact') ?>" class="premium-contact-form">
                        <?= Helper::csrfField() ?>

                        <div class="contact-form-grid">
                            <div class="contact-field">
                                <label for="c-name">Full Name <span>*</span></label>
                                <div class="contact-input-wrap">
                                    <span class="contact-input-icon"><?= Helper::icon('user', '18') ?></span>
                                    <input type="text" id="c-name" name="name" class="contact-input" placeholder="Ali Ahmed" required value="<?= Helper::e(Helper::currentUser()['name'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="contact-field">
                                <label for="c-email">Email Address <span>*</span></label>
                                <div class="contact-input-wrap">
                                    <span class="contact-input-icon"><?= Helper::icon('mail', '18') ?></span>
                                    <input type="email" id="c-email" name="email" class="contact-input" placeholder="ali@example.com" required value="<?= Helper::e(Helper::currentUser()['email'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="contact-field">
                            <label for="c-topic">Topic / Category</label>
                            <div class="contact-input-wrap select-wrap">
                                <span class="contact-input-icon"><?= Helper::icon('tags', '18') ?></span>
                                <select id="c-topic" name="topic" class="contact-input contact-select">
                                    <option value="General Inquiry">General Inquiry / Question</option>
                                    <option value="Feature Suggestion">Feature Suggestion / Idea</option>
                                    <option value="Bug Report">Bug or Issue Report</option>
                                    <option value="University Society Partnership">University Society / Student Club</option>
                                    <option value="Hostel Budget Advice">Hostel / Living Budget Help</option>
                                </select>
                            </div>
                        </div>

                        <div class="contact-field">
                            <div class="contact-label-row">
                                <label for="c-msg">Your Message <span>*</span></label>
                                <small>Keep it clear &amp; concise</small>
                            </div>
                            <div class="contact-textarea-wrap">
                                <span class="contact-input-icon contact-textarea-icon"><?= Helper::icon('clipboard', '18') ?></span>
                                <textarea id="c-msg" name="message" class="contact-textarea" rows="5" maxlength="1000" placeholder="How can we assist you with Coin_Nex?" required></textarea>
                                <span class="contact-counter"><span id="contact-count">0</span>/1000</span>
                            </div>
                        </div>

                        <div class="contact-form-footer">
                            <div class="contact-security">
                                <span class="security-dot"><?= Helper::icon('shield', '15') ?></span>
                                <span>Your message is securely submitted.</span>
                            </div>
                            <button type="submit" class="contact-submit">
                                <span>Send Message</span>
                                <span class="submit-arrow">➜</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Direct Contact & Channels -->
            <div style="display:flex;flex-direction:column;gap:1.4rem;">
                
                <div class="card card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.6rem;">
                    <div class="flex items-center gap-1 mb-2">
                        <span style="color:var(--brand);display:flex;align-items:center;"><?= Helper::icon('bell', '20') ?></span>
                        <div class="bold" style="font-size:1.05rem;">Direct Email Support</div>
                    </div>
                    <p class="faint small" style="margin-bottom:.8rem;line-height:1.5;">
                        Have an urgent inquiry or want to share feedback directly with the founding engineers?
                    </p>
                    <a href="mailto:support@coin_nex.pk" class="link bold" style="color:var(--brand);font-size:.95rem;">
                        support@coin_nex.pk ➜
                    </a>
                </div>

                <div class="card card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.6rem;">
                    <div class="flex items-center gap-1 mb-2">
                        <span style="color:var(--success);display:flex;align-items:center;"><?= Helper::icon('shield', '20') ?></span>
                        <div class="bold" style="font-size:1.05rem;">Student Community Hub</div>
                    </div>
                    <p class="faint small" style="margin-bottom:.8rem;line-height:1.5;">
                        Connect with fellow university students across FAST, NUST, LUMS, COMSATS, UET, and IBA discussing saving tips and student discounts.
                    </p>
                    <span class="badge badge-success" style="font-weight:700;">● Active 24/7 Community</span>
                </div>

                <a href="<?= Helper::url('faq') ?>" class="card card-hover" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.6rem;text-decoration:none;display:block;">
                    <div class="flex items-center gap-1 mb-2">
                        <span style="color:var(--brand);display:flex;align-items:center;"><?= Helper::icon('bulb', '20') ?></span>
                        <div class="bold" style="font-size:1.05rem;color:var(--text);">Need Instant Answers?</div>
                    </div>
                    <p class="faint small" style="margin-bottom:.8rem;line-height:1.5;">
                        Check our FAQ section covering safe-to-spend, 50/30/20 student budgeting, and PDF exports.
                    </p>
                    <span class="btn btn-ghost btn-sm">Browse FAQ ➜</span>
                </a>

            </div>

        </div>

    </div>
</main>

<style>
/* ================= PREMIUM CONTACT FORM ================= */
.contact-form-shell {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(247,183,51,.22);
    border-radius: 24px;
    background:
        radial-gradient(circle at 10% 0%, rgba(247,183,51,.10), transparent 28%),
        linear-gradient(145deg, rgba(24,30,43,.96), rgba(10,14,22,.98));
    box-shadow: 0 24px 70px -35px rgba(0,0,0,.9), inset 0 1px 0 rgba(255,255,255,.035);
}

.contact-form-shell::before {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,.035) 45%, transparent 60%);
    transform: translateX(-100%);
    transition: transform .9s ease;
}

.contact-form-shell:hover::before { transform: translateX(100%); }

.contact-form-glow {
    position: absolute;
    width: 190px;
    height: 190px;
    top: -100px;
    right: -70px;
    border-radius: 50%;
    background: rgba(247,183,51,.16);
    filter: blur(55px);
    pointer-events: none;
}

.contact-form-inner { position: relative; z-index: 1; padding: 2rem; }

.contact-form-head {
    display: flex;
    align-items: flex-start;
    gap: .9rem;
    margin-bottom: 1.7rem;
}

.contact-form-icon {
    width: 46px;
    height: 46px;
    flex: 0 0 46px;
    display: grid;
    place-items: center;
    border-radius: 14px;
    color: var(--brand);
    background: linear-gradient(145deg, rgba(247,183,51,.18), rgba(247,183,51,.06));
    border: 1px solid rgba(247,183,51,.24);
    box-shadow: 0 10px 28px -15px rgba(247,183,51,.7);
}

.contact-kicker {
    display: block;
    margin-bottom: .22rem;
    color: var(--brand);
    font-size: .68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .14em;
}

.contact-form-head h2 { margin: 0 0 .3rem; font-size: 1.35rem; font-weight: 850; letter-spacing: -.025em; }
.contact-form-head p { margin: 0; color: var(--text-faint); font-size: .82rem; line-height: 1.55; }

.contact-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.contact-field { margin-bottom: 1rem; }
.contact-field label, .contact-label-row label {
    display: block;
    margin: 0 0 .45rem;
    color: var(--text);
    font-size: .76rem;
    font-weight: 750;
    letter-spacing: .01em;
}
.contact-field label span, .contact-label-row label span { color: #fb7185; }

.contact-input-wrap, .contact-textarea-wrap { position: relative; }

.contact-input, .contact-textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid rgba(148,163,184,.16);
    border-radius: 13px;
    outline: none;
    color: var(--text);
    background: rgba(9,13,21,.72);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.025), 0 8px 25px -22px rgba(0,0,0,.9);
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease, background .2s ease;
}

.contact-input { height: 50px; padding: .75rem .9rem .75rem 2.75rem; font: inherit; font-size: .84rem; }
.contact-textarea { min-height: 132px; padding: .85rem 3.8rem .85rem 2.75rem; resize: vertical; font: inherit; font-size: .84rem; line-height: 1.55; }
.contact-input::placeholder, .contact-textarea::placeholder { color: rgba(148,163,184,.48); }

.contact-input:hover, .contact-textarea:hover { border-color: rgba(247,183,51,.22); background: rgba(10,15,24,.9); }
.contact-input:focus, .contact-textarea:focus {
    border-color: rgba(247,183,51,.72);
    background: rgba(12,17,27,.96);
    box-shadow: 0 0 0 4px rgba(247,183,51,.08), 0 14px 35px -25px rgba(247,183,51,.6);
    transform: translateY(-1px);
}

.contact-input-icon {
    position: absolute;
    left: .95rem;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    color: var(--text-faint);
    pointer-events: none;
    transition: color .2s ease;
    z-index: 2;
}
.contact-input-wrap:focus-within .contact-input-icon { color: var(--brand); }
.contact-textarea-icon { top: 1rem; transform: none; }

.contact-select { appearance: none; cursor: pointer; padding-right: 2.7rem; }
.select-wrap::after {
    content: "⌄";
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-54%);
    color: var(--text-faint);
    font-size: 1rem;
    pointer-events: none;
}
.contact-select option { background: #111722; color: #fff; }

.contact-label-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.contact-label-row small { color: var(--text-faint); font-size: .68rem; }
.contact-counter { position: absolute; right: .85rem; bottom: .7rem; color: var(--text-faint); font-size: .65rem; pointer-events: none; }

.contact-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding-top: .35rem;
}

.contact-security { display: flex; align-items: center; gap: .45rem; color: var(--text-faint); font-size: .68rem; }
.security-dot { display: flex; color: var(--success); }

.contact-submit {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .75rem;
    min-width: 175px;
    border: 0;
    border-radius: 13px;
    padding: .85rem 1.25rem;
    cursor: pointer;
    color: #171105;
    background: linear-gradient(135deg, #ffdc70 0%, #f7b733 48%, #f59e0b 100%);
    box-shadow: 0 16px 35px -16px rgba(247,183,51,.72);
    font: inherit;
    font-size: .82rem;
    font-weight: 850;
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
}
.contact-submit::before {
    content: "";
    position: absolute;
    inset: 0 auto 0 -65%;
    width: 42%;
    transform: skewX(-20deg);
    background: rgba(255,255,255,.28);
    transition: left .55s ease;
}
.contact-submit:hover { transform: translateY(-2px); filter: brightness(1.04); box-shadow: 0 20px 45px -15px rgba(247,183,51,.8); }
.contact-submit:hover::before { left: 125%; }
.contact-submit:active { transform: translateY(0); }
.submit-arrow { font-size: 1rem; transition: transform .2s ease; }
.contact-submit:hover .submit-arrow { transform: translateX(4px); }

@media (max-width: 820px) {
    #contact-grid { grid-template-columns: 1fr !important; }
}

@media (max-width: 600px) {
    .contact-form-inner { padding: 1.25rem; }
    .contact-form-grid { grid-template-columns: 1fr; gap: 0; }
    .contact-form-head { gap: .7rem; }
    .contact-form-icon { width: 42px; height: 42px; flex-basis: 42px; }
    .contact-form-footer { align-items: stretch; flex-direction: column; }
    .contact-submit { width: 100%; }
}
</style>

<script>
(function () {
    const message = document.getElementById('c-msg');
    const counter = document.getElementById('contact-count');
    if (!message || !counter) return;
    const updateCount = () => { counter.textContent = message.value.length; };
    message.addEventListener('input', updateCount);
    updateCount();
})();
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
            <a href="<?= Helper::url('faq') ?>" class="link">FAQ</a>
            <span>·</span>
            <a href="<?= Helper::url('contact') ?>" class="link bold" style="color:var(--brand);">Contact Support</a>
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

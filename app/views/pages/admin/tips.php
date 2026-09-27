<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('admin') ?>">Admin</a><span class="sep">/</span><span class="current">Tips Engine Pool</span></div>
        <h1><?= Helper::icon('bulb', '26') ?> Saving Tips Pool Management</h1>
        <p class="muted small">Inject curated student saving advice and control the recommendation ranking engine.</p>
    </div>
    <div class="flex gap-1 wrap">
        <a href="<?= Helper::url('admin') ?>" class="btn btn-ghost btn-sm"><?= Helper::icon('chevron-left', '15') ?> Admin Dashboard</a>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.4rem;">
    <!-- Tips List -->
    <div class="card animate-up">
        <div class="card-title"><?= Helper::icon('list', '18') ?> Active Tips (<?= count($tips) ?>)</div>
        <?php if (empty($tips)): ?>
            <div class="text-center muted" style="padding:2.6rem;">No tips found. Add your first tip using the form.</div>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:.9rem;">
            <?php foreach ($tips as $ti): ?>
            <?php
                $score = (float) $ti['rank_score'];
                $potential = $score >= 85 ? 'High' : ($score >= 70 ? 'Medium' : 'Low');
                $badgeClass = $score >= 85 ? 'badge-danger' : ($score >= 70 ? 'badge-warning' : 'badge-info');
            ?>
            <div class="glass" style="padding:1rem 1.2rem;">
                <div class="flex between items-center mb-1">
                    <div class="flex items-center gap-1">
                        <?php if ($ti['category_name']): ?>
                        <span class="badge badge-brand"><?= Helper::e($ti['category_icon'] ?? '📌') ?> <?= Helper::e($ti['category_name']) ?></span>
                        <?php else: ?>
                        <span class="badge">General</span>
                        <?php endif; ?>
                        <span class="badge <?= $badgeClass ?>"><?= $potential ?> Potential (<?= (int)$score ?>)</span>
                    </div>
                    <form method="post" action="<?= Helper::url('admin/tipDelete/' . (int) $ti['tip_id']) ?>" class="inline" onsubmit="return confirm('Remove this tip?');">
                        <?= Helper::csrfField() ?>
                        <button type="submit" class="btn btn-danger btn-sm" title="Remove tip"><?= Helper::icon('trash', '13') ?></button>
                    </form>
                </div>
                <div class="small" style="line-height:1.5;margin-top:.4rem;"><?= Helper::e($ti['tip_text']) ?></div>
                <div class="faint small mt-1">Student: <?= Helper::e($ti['user_name'] ?? 'All Students') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Inject New Tip Form -->
    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title"><?= Helper::icon('plus', '18') ?> Inject New Tip to Students</div>
        <form method="post" action="<?= Helper::url('admin/tipAdd') ?>">
            <?= Helper::csrfField() ?>
            <div class="field">
                <label>Target Expense Category (Optional)</label>
                <select name="category_id" class="select">
                    <option value="0">General Student Financial Advice</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int) $cat['category_id'] ?>"><?= Helper::e($cat['icon']) ?> <?= Helper::e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label>Tip Text / Advice</label>
                <textarea name="tip_text" class="input" rows="4" placeholder="e.g. Try preparing meals at home 2-3 additional times per week to save Rs. 750/week." required minlength="10" style="resize:vertical;"></textarea>
            </div>

            <div class="field">
                <label>Rank Score (Impact Potential)</label>
                <select name="rank_score" class="select">
                    <option value="95">95 - High Saving Potential (Top Priority)</option>
                    <option value="85">85 - High Saving Potential</option>
                    <option value="75" selected>75 - Medium Saving Potential</option>
                    <option value="65">65 - Low Saving Potential</option>
                </select>
                <div class="form-hint">Higher scores appear at the top of the student dashboard.</div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3"><?= Helper::icon('bulb', '16') ?> Add Tip to Student Pools</button>
        </form>
    </div>
</div>

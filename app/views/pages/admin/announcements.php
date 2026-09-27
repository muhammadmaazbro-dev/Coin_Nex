<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('admin') ?>">Admin</a><span class="sep">/</span><span class="current">Announcements</span></div>
        <h1><?= Helper::icon('bell', '26') ?> System Announcements</h1>
        <p class="muted small">Broadcast campus updates, financial tips, or maintenance alerts to student dashboards.</p>
    </div>
    <div class="flex gap-1 wrap">
        <a href="<?= Helper::url('admin') ?>" class="btn btn-ghost btn-sm"><?= Helper::icon('chevron-left', '15') ?> Admin Dashboard</a>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.4rem;">
    <!-- Active Announcements -->
    <div class="card animate-up">
        <div class="card-title"><?= Helper::icon('list', '18') ?> Broadcast History (<?= count($announcements) ?>)</div>
        <?php if (empty($announcements)): ?>
            <div class="text-center muted" style="padding:2.6rem;">No announcements active. Post your first announcement below.</div>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <?php foreach ($announcements as $a): ?>
            <div class="glass" style="padding:1.1rem 1.3rem;border-left:4px solid var(--brand);">
                <div class="flex between items-center mb-1">
                    <div class="bold" style="font-size:1.05rem;"><?= Helper::e($a['title']) ?></div>
                    <form method="post" action="<?= Helper::url('admin/announcementDelete/' . (int) $a['announcement_id']) ?>" class="inline" onsubmit="return confirm('Delete this announcement?');">
                        <?= Helper::csrfField() ?>
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Announcement"><?= Helper::icon('trash', '14') ?></button>
                    </form>
                </div>
                <div class="small text-muted" style="line-height:1.5;margin-bottom:.6rem;"><?= nl2br(Helper::e($a['body'])) ?></div>
                <div class="faint small"><?= Helper::icon('clock', '12') ?> Broadcasted <?= Helper::timeAgo($a['created_at']) ?> (<?= Helper::formatDate($a['created_at']) ?>)</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Create Announcement Form -->
    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title"><?= Helper::icon('plus', '18') ?> Post New Announcement</div>
        <form method="post" action="<?= Helper::url('admin/announcementAdd') ?>">
            <?= Helper::csrfField() ?>
            <div class="field">
                <label>Announcement Headline</label>
                <input type="text" name="title" class="input" placeholder="e.g. 📢 Monthly Report Feature Released" required minlength="3" maxlength="150">
            </div>

            <div class="field">
                <label>Message Content</label>
                <textarea name="body" class="input" rows="5" placeholder="Write message to students..." required minlength="5" style="resize:vertical;"></textarea>
                <div class="form-hint">This notice will appear in the top banner on student dashboards.</div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3"><?= Helper::icon('bell', '16') ?> Broadcast to All Students</button>
        </form>
    </div>
</div>

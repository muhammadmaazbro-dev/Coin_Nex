<?php
declare(strict_types=1);
$icons = ['budget' => Helper::icon('target', '20'), 'info' => Helper::icon('info', '20'), 'flag' => Helper::icon('alert', '20')];
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><span class="current">Notifications</span></div>
        <h1><?= Helper::icon('bell', '26') ?> Notifications</h1>
        <p class="muted small"><?= $unreadCount ?> unread · <?= count($list) ?> total</p>
    </div>
    <form method="post" action="<?= Helper::url('notifications/markAllRead') ?>">
        <?= Helper::csrfField() ?>
        <button class="btn btn-dark btn-sm" type="submit" <?= $unreadCount === 0 ? 'disabled style="opacity:.45;"' : '' ?>><?= Helper::icon('check', '15') ?> Mark all read</button>
    </form>
</div>

<div class="card">
    <?php if (empty($list)): ?>
        <div class="text-center" style="padding:2.4rem 1rem;">
            <div class="no-data-ico"><?= Helper::icon('check', '40') ?></div>
            <p class="bold">You are all caught up</p>
            <p class="faint small">New alerts about budgets, flags and system updates will appear here.</p>
        </div>
    <?php else: ?>
        <?php foreach ($list as $n): ?>
        <div class="flex between items-center gap-2 notif-row <?= $n['is_read'] ? '' : 'unread' ?>" style="padding:.8rem 1rem;border-bottom:1px dashed var(--border);">
            <div class="flex items-center gap-2" style="gap:.7rem;min-width:0;flex:1;">
                <span style="display:grid;place-items:center;flex:none;"><?= $icons[$n['type']] ?? Helper::icon('bell', '20') ?></span>
                <div style="min-width:0;">
                    <div class="small" style="font-weight:<?= $n['is_read'] ? '400' : '700' ?>;"><?= Helper::e($n['message']) ?></div>
                    <div class="faint" style="font-size:.74rem;"><?= Helper::timeAgo($n['created_at']) ?></div>
                </div>
            </div>
            <div class="flex items-center" style="gap:.3rem;">
                <?php if ($n['link'] && filter_var(Helper::url($n['link']), FILTER_VALIDATE_URL) !== false): ?>
                <a href="<?= Helper::url($n['link']) ?>" class="btn btn-dark btn-sm">View <?= Helper::icon('chevron-right', '13') ?></a>
                <?php endif; ?>
                <?php if (!$n['is_read']): ?>
                <form method="post" action="<?= Helper::url('notifications/markRead?id=' . (int) $n['notification_id']) ?>">
                    <?= Helper::csrfField() ?>
                    <button class="icon-btn" title="Mark read"><?= Helper::icon('check', '16') ?></button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
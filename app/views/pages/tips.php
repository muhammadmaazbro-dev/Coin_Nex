<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="cc-breadcrumbs">
            <a href="<?= Helper::url('dashboard') ?>" class="crumb-link">Dashboard</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-current">Personalized Saving Tips</span>
        </div>
        <h1 style="margin-top:.3rem;"><?= Helper::icon('bulb', '26') ?> Personalized Saving Tips</h1>
        <p class="muted small">Intelligent saving recommendations calculated from your actual spending compared to student benchmarks and your historical averages.</p>
    </div>
    <form method="post" action="<?= Helper::url('tips/refresh') ?>">
        <?= Helper::csrfField() ?>
        <button type="submit" class="btn btn-primary btn-sm"><?= Helper::icon('refresh', '15') ?> Regenerate Tips</button>
    </form>
</div>

<div class="flex gap-1 wrap mb-3">
    <a href="<?= Helper::url('tips') ?>" class="btn <?= $filter === 'active' ? 'btn-primary' : 'btn-ghost' ?> btn-sm">
        <?= Helper::icon('list', '15') ?> Active Tips
    </a>
    <a href="<?= Helper::url('tips') ?>?view=bookmarks" class="btn <?= $filter === 'bookmarks' ? 'btn-primary' : 'btn-ghost' ?> btn-sm">
        <?= Helper::icon('bookmark', '15') ?> Saved &amp; Bookmarked
    </a>
    <a href="<?= Helper::url('tips') ?>?view=dismissed" class="btn <?= $filter === 'dismissed' ? 'btn-primary' : 'btn-ghost' ?> btn-sm">
        <?= Helper::icon('trash', '15') ?> Dismissed
    </a>
</div>

<?php if (empty($tips)): ?>
<div class="card text-center animate-up" style="padding:3rem 1.5rem;">
    <div style="font-size:3rem;margin-bottom:.5rem;">💡</div>
    <h2 style="font-size:1.15rem;">No Tips Found</h2>
    <p class="muted small" style="max-width:28rem;margin:0 auto 1.2rem;">
        <?= $filter === 'bookmarks' ? 'You have no saved tips. Click the bookmark icon on any active tip to save it.' : ($filter === 'dismissed' ? 'No dismissed tips in archive.' : 'Log your income and expenses to unlock tailored advice.') ?>
    </p>
    <?php if ($filter !== 'active'): ?>
        <a href="<?= Helper::url('tips') ?>" class="btn btn-ghost btn-sm">Back to Active Tips</a>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="stagger" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(310px,1fr));gap:1.1rem;">
    <?php foreach ($tips as $tip): ?>
    <?php
        $cat = $tipCategories[$tip['tip_id']] ?? null;
        $score = (float) $tip['rank_score'];
        $potential = $score >= 85 ? 'High Saving Potential' : ($score >= 70 ? 'Medium Saving Potential' : 'Helpful Habit');
        $badgeCls = $score >= 85 ? 'badge-danger' : ($score >= 70 ? 'badge-warning' : 'badge-brand');
    ?>
    <div class="card card-hover animate-up flex flex-column between" style="border-top:3px solid <?= $score >= 85 ? 'var(--danger)' : ($score >= 70 ? 'var(--warning)' : 'var(--brand)') ?>;">
        <div>
            <div class="flex between items-center mb-2">
                <div class="flex items-center gap-1">
                    <span class="badge <?= $badgeCls ?>"><?= $potential ?></span>
                    <?php if ($tip['is_pinned']): ?>
                        <span class="badge badge-brand" title="Pinned to top">📌 Pinned</span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-1">
                    <!-- Pin Toggle -->
                    <form method="post" action="<?= Helper::url('tips/pin/' . (int) $tip['tip_id']) ?>?view=<?= Helper::e($filter) ?>" class="inline">
                        <?= Helper::csrfField() ?>
                        <button class="icon-btn" title="<?= $tip['is_pinned'] ? 'Unpin tip' : 'Pin tip' ?>" style="<?= $tip['is_pinned'] ? 'color:var(--brand);' : '' ?>">
                            <?= Helper::icon('star', '15') ?>
                        </button>
                    </form>

                    <!-- Bookmark Toggle -->
                    <form method="post" action="<?= Helper::url('tips/bookmark/' . (int) $tip['tip_id']) ?>?view=<?= Helper::e($filter) ?>" class="inline">
                        <?= Helper::csrfField() ?>
                        <button class="icon-btn" title="<?= $tip['bookmarked'] ? 'Remove bookmark' : 'Bookmark tip' ?>" style="<?= $tip['bookmarked'] ? 'color:var(--brand);' : '' ?>">
                            <?= Helper::icon('bookmark', '15') ?>
                        </button>
                    </form>

                    <!-- Dismiss / Restore -->
                    <?php if ($tip['is_dismissed']): ?>
                    <form method="post" action="<?= Helper::url('tips/restore/' . (int) $tip['tip_id']) ?>?view=<?= Helper::e($filter) ?>" class="inline">
                        <?= Helper::csrfField() ?>
                        <button class="icon-btn" title="Restore tip"><?= Helper::icon('reply', '15') ?></button>
                    </form>
                    <?php else: ?>
                    <form method="post" action="<?= Helper::url('tips/dismiss/' . (int) $tip['tip_id']) ?>?view=<?= Helper::e($filter) ?>" class="inline" onsubmit="return confirm('Dismiss this tip?');">
                        <?= Helper::csrfField() ?>
                        <button class="icon-btn" title="Dismiss tip" style="color:var(--danger);"><?= Helper::icon('x', '15') ?></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            <p style="font-size:.95rem;line-height:1.55;color:var(--text);margin-bottom:.8rem;"><?= Helper::e($tip['tip_text']) ?></p>
        </div>

    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
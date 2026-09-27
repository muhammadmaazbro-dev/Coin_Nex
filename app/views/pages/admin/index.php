<?php
declare(strict_types=1);
$labels = array_reverse(array_column($txnsByMonth, 'ym'));
$incomeS = array_reverse(array_column($txnsByMonth, 'income'));
$expenseS = array_reverse(array_column($txnsByMonth, 'expense'));
$signupL = array_reverse(array_column($signupsByMonth, 'ym'));
$signupN = array_reverse(array_column($signupsByMonth, 'n'));
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><span class="current">Admin Control Panel</span></div>
        <h1><?= Helper::icon('shield', '26') ?> Admin Control Panel</h1>
        <p class="muted small">Platform overview, system categories, broadcasts, tips and student accounts.</p>
    </div>
    <div class="flex gap-1 wrap">
        <form method="post" action="<?= Helper::url('admin/cleanupTrash') ?>">
            <?= Helper::csrfField() ?>
            <button class="btn btn-dark btn-sm" type="submit"><?= Helper::icon('trash', '15') ?> Purge old notifications</button>
        </form>
    </div>
</div>

<!-- Admin Quick Navigation Rails -->
<div class="stagger" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:1rem;margin-bottom:1.4rem;">
    <a href="<?= Helper::url('admin/users') ?>" class="card card-hover flex items-center gap-1" style="padding:.9rem 1.1rem;text-decoration:none;">
        <span class="avatar-circle" style="background:rgba(247,183,51,.15);color:var(--brand);"><?= Helper::icon('user', '20') ?></span>
        <div>
            <div class="bold" style="color:var(--text);font-size:.92rem;">Manage Users</div>
            <div class="faint small"><?= $totalUsers ?> total accounts</div>
        </div>
    </a>
    <a href="<?= Helper::url('admin/categories') ?>" class="card card-hover flex items-center gap-1" style="padding:.9rem 1.1rem;text-decoration:none;">
        <span class="avatar-circle" style="background:rgba(45,212,167,.15);color:var(--success);"><?= Helper::icon('tags', '20') ?></span>
        <div>
            <div class="bold" style="color:var(--text);font-size:.92rem;">Default Categories</div>
            <div class="faint small"><?= $totalCategories ?> system groups</div>
        </div>
    </a>
    <a href="<?= Helper::url('admin/announcements') ?>" class="card card-hover flex items-center gap-1" style="padding:.9rem 1.1rem;text-decoration:none;">
        <span class="avatar-circle" style="background:rgba(96,185,249,.15);color:var(--info);"><?= Helper::icon('bell', '20') ?></span>
        <div>
            <div class="bold" style="color:var(--text);font-size:.92rem;">Announcements</div>
            <div class="faint small"><?= $announcementCount ?> active notices</div>
        </div>
    </a>
    <a href="<?= Helper::url('admin/tips') ?>" class="card card-hover flex items-center gap-1" style="padding:.9rem 1.1rem;text-decoration:none;">
        <span class="avatar-circle" style="background:rgba(251,113,133,.15);color:var(--danger);"><?= Helper::icon('bulb', '20') ?></span>
        <div>
            <div class="bold" style="color:var(--text);font-size:.92rem;">Saving Tips Pool</div>
            <div class="faint small"><?= $totalTips ?> curated tips</div>
        </div>
    </a>
</div>

<!-- Primary Stats Grid -->
<div class="grid-cards stagger mb-3">
    <a href="<?= Helper::url('admin/users') ?>" class="card stat-card card-hover animate-up" style="text-decoration:none;">
        <div class="stat-ico text-brand"><?= Helper::icon('user', '22') ?></div>
        <div class="stat-value"><?= number_format($totalUsers) ?></div>
        <div class="stat-label">Total Registered Users</div>
        <div class="faint small"><?= $activeUsers ?> active · <b style="color:var(--brand);"><?= $newUsersToday ?> new today</b></div>
    </a>
    <a href="<?= Helper::url('admin/users') ?>" class="card stat-card card-hover animate-up" style="animation-delay:.03s;text-decoration:none;">
        <div class="stat-ico text-danger"><?= Helper::icon('expense', '22') ?></div>
        <div class="stat-value"><?= Helper::money($totalSpend) ?></div>
        <div class="stat-label">Platform Total Spending</div>
        <div class="faint small"><?= number_format($expenseTxnCount) ?> expense entries logged</div>
    </a>
    <a href="<?= Helper::url('admin/users') ?>" class="card stat-card card-hover animate-up" style="animation-delay:.06s;text-decoration:none;">
        <div class="stat-ico text-success"><?= Helper::icon('income', '22') ?></div>
        <div class="stat-value"><?= Helper::money($totalIncome) ?></div>
        <div class="stat-label">Platform Total Income</div>
        <div class="faint small"><?= number_format($incomeTxnCount) ?> income entries logged</div>
    </a>
    <a href="<?= Helper::url('admin/categories') ?>" class="card stat-card card-hover animate-up" style="animation-delay:.09s;text-decoration:none;">
        <div class="stat-ico text-info"><?= Helper::icon('tags', '22') ?></div>
        <div class="stat-value" style="font-size:1.45rem;overflow:hidden;text-overflow:ellipsis;"><?= Helper::e($mostUsedCategory) ?></div>
        <div class="stat-label">Most Used Category</div>
        <div class="faint small"><?= number_format($totalTxns) ?> total transactions overall</div>
    </a>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.1rem;margin-bottom:1.4rem;">
    <div class="card animate-up">
        <div class="card-title"><?= Helper::icon('chart', '18') ?> Platform Cashflow · Last 6 Months</div>
        <div style="height:250px;"><canvas id="adminChart"></canvas></div>
    </div>
    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('user', '18') ?> Student Growth</span>
            <a href="<?= Helper::url('admin/users') ?>" class="btn btn-ghost btn-sm">Users →</a>
        </div>
        <?php if ($signupsByMonth): ?>
        <div class="faint small mb-2"><?= date('F Y', strtotime($signupL[0])) ?>: <?= $signupN[0] ?> new registration<?= (int) $signupN[0] === 1 ? '' : 's' ?></div>
        <div style="height:190px;"><canvas id="signupChart"></canvas></div>
        <?php else: ?>
        <p class="small muted">No signups recorded.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Transactions and Top Users -->
<div class="stagger" style="display:grid;grid-template-columns:1.2fr 1fr;gap:1.1rem;">
    <div class="card animate-up">
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('swap', '18') ?> Realtime Activity Stream</span>
            <span class="badge badge-brand"><?= count($recentTxns) ?> recent</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Student</th><th>Description</th><th>Type</th><th style="text-align:right;">Amount</th></tr></thead>
                <tbody>
                <?php foreach ($recentTxns as $rt): ?>
                <tr>
                    <td><div class="bold small"><?= Helper::e($rt['user_name']) ?></div></td>
                    <td class="muted small"><?= Helper::e($rt['category_icon']) ?> <?= Helper::e($rt['description'] ?: $rt['category_name']) ?></td>
                    <td><span class="badge <?= $rt['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>"><?= ucfirst($rt['type']) ?></span></td>
                    <td class="small bold" style="text-align:right;color:<?= $rt['type'] === 'income' ? 'var(--success)' : 'var(--danger)' ?>;"><?= Helper::money((float) $rt['amount']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title"><?= Helper::icon('tags', '18') ?> Top Expense Categories</div>
        <?php if (empty($topCategories)): ?>
            <p class="small muted">No category data yet.</p>
        <?php else: $catMax = max(array_map(static fn ($c) => (float) $c['total'], $topCategories)); ?>
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            <?php foreach ($topCategories as $tc): ?>
            <div>
                <div class="flex between items-center small" style="margin-bottom:.35rem;">
                    <span class="bold"><?= Helper::e($tc['icon']) ?> <?= Helper::e($tc['name']) ?></span>
                    <span class="small muted"><?= (int) $tc['n'] ?> entries · <b><?= Helper::money((float) $tc['total']) ?></b></span>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width:<?= round((float) $tc['total'] / $catMax * 100) ?>%;background:var(--brand);"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
    const tickCol = isDark ? 'rgba(255,255,255,.55)' : 'rgba(0,0,0,.6)';
    const gridCol = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.08)';

    new Chart(document.getElementById('adminChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                { label: 'Income', data: <?= json_encode(array_map('floatval', $incomeS)) ?>, borderColor: '#2dd4a7', backgroundColor: 'rgba(45,212,167,.12)', tension: .4, fill: true, borderWidth: 2 },
                { label: 'Expense', data: <?= json_encode(array_map('floatval', $expenseS)) ?>, borderColor: '#fb7185', backgroundColor: 'rgba(251,113,133,.10)', tension: .4, fill: true, borderWidth: 2 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: tickCol } } },
            scales: { x: { grid: { color: gridCol }, ticks: { color: tickCol } }, y: { grid: { color: gridCol }, ticks: { color: tickCol }, beginAtZero: true } }
        }
    });

    <?php if ($signupsByMonth): ?>
    new Chart(document.getElementById('signupChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($signupL) ?>,
            datasets: [{ label: 'New users', data: <?= json_encode($signupN) ?>, backgroundColor: 'rgba(247,183,51,.75)', borderRadius: 6 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { grid: { display: false }, ticks: { color: tickCol } }, y: { grid: { color: gridCol }, ticks: { color: tickCol }, beginAtZero: true, precision: 0 } }
        }
    });
    <?php endif; ?>
})();
</script>
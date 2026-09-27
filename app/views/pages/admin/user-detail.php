<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('admin') ?>">Admin</a><span class="sep">/</span><span class="current">Users</span><span class="sep">/</span><span class="current"><?= Helper::e($profile['name']) ?></span></div>
        <h1><?= Helper::e($profile['name']) ?> <?= Helper::icon('user', '24') ?></h1>
        <p class="muted small"><?= Helper::e($profile['email']) ?></p>
    </div>
    <a href="<?= Helper::url('admin/users') ?>" class="btn btn-dark btn-sm"><?= Helper::icon('chevron-left', '16') ?> All users</a>
</div>

<div class="grid-cards stagger">
    <div class="card stat-card animate-up">
        <div class="stat-ico"><?= Helper::icon('income', '22') ?></div>
        <div class="stat-value"><?= Helper::money($monthIncome) ?></div>
        <div class="stat-label">Income this month</div>
    </div>
    <div class="card stat-card animate-up" style="animation-delay:.03s;">
        <div class="stat-ico"><?= Helper::icon('expense', '22') ?></div>
        <div class="stat-value"><?= Helper::money($monthExpense) ?></div>
        <div class="stat-label">Spent this month</div>
    </div>
    <div class="card stat-card animate-up" style="animation-delay:.06s;">
        <div class="stat-ico"><?= Helper::icon('wallet', '22') ?></div>
        <div class="stat-value"><?= Helper::money($allTime) ?></div>
        <div class="stat-label">All-time spending</div>
    </div>
    <div class="card stat-card animate-up" style="animation-delay:.09s;">
        <div class="stat-ico"><?= Helper::icon('target', '22') ?></div>
        <div class="stat-value"><?= $budgetCount + $recurringCount ?></div>
        <div class="stat-label">Budgets + recurring</div>
        <div class="faint small"><?= $budgetCount ?> budgets · <?= $recurringCount ?> recurring rules</div>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1.3fr 1fr;gap:1.1rem;">
    <div class="card animate-up">
        <div class="card-title"><?= Helper::icon('swap', '18') ?> Recent transactions</div>
        <?php if (empty($recentTxns)): ?>
        <p class="small muted">No transactions yet.</p>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Date</th><th>Description</th><th>Category</th><th>Amount</th></tr></thead>
                <tbody>
                    <?php foreach ($recentTxns as $t): ?>
                    <tr>
                        <td class="small muted"><?= date('d M Y', strtotime($t['transaction_date'])) ?></td>
                        <td class="small"><?= Helper::e($t['description']) ?></td>
                        <td class="small"><?= Helper::e($t['category_icon']) ?> <?= Helper::e($t['category_name']) ?></td>
                        <td class="small <?= $t['type'] === 'income' ? '' : '' ?>" style="color:<?= $t['type'] === 'income' ? 'var(--ok,#16a34a)' : '#ef4444' ?>">
                            <?= $t['type'] === 'income' ? '+' : '−' ?><?= Helper::money((float) $t['amount']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div>
        <div class="card animate-up">
            <div class="card-title"><?= Helper::icon('info', '18') ?> Account details</div>
            <div class="row-grid">
                <div><div class="faint small">Role</div><div class="small bold"><?= Helper::e($profile['role']) ?></div></div>
                <div><div class="faint small">Status</div><div class="small bold"><?= $profile['is_active'] ? 'Active' : 'Deactivated' ?></div></div>
                <div><div class="faint small">Academic year</div><div class="small bold"><?= Helper::e($profile['academic_year'] ?: '—') ?></div></div>
                <div><div class="faint small">Monthly allowance</div><div class="small bold"><?= Helper::money((float) $profile['monthly_allowance_baseline']) ?></div></div>
                <div><div class="faint small">Savings goal</div><div class="small bold"><?= Helper::money((float) $profile['savings_goal']) ?></div></div>
                <div><div class="faint small">Notifications</div><div class="small bold"><?= $notifCount ?></div></div>
                <div style="grid-column:1/-1;"><div class="faint small">Last login</div><div class="small bold"><?= Helper::e($profile['last_login'] ?? '—') ?></div></div>
                <div style="grid-column:1/-1;"><div class="faint small">Joined</div><div class="small bold"><?= Helper::e($profile['created_at'] ?? '—') ?></div></div>
            </div>
        </div>
        <div class="card mt-2 animate-up">
            <div class="card-title"><?= Helper::icon('gear', '18') ?> Moderation</div>
            <div class="flex wrap gap-1" style="margin-top:.4rem;">
                <form method="post" action="<?= Helper::url('admin/toggleActive/' . (int) $profile['user_id']) ?>">
                    <?= Helper::csrfField() ?>
                    <button class="btn btn-dark btn-sm" type="submit"><?= $profile['is_active'] ? Helper::icon('x', '15') . ' Deactivate' : Helper::icon('check', '15') . ' Activate' ?></button>
                </form>
                <form method="post" action="<?= Helper::url('admin/toggleRole/' . (int) $profile['user_id']) ?>">
                    <?= Helper::csrfField() ?>
                    <button class="btn btn-dark btn-sm" type="submit"><?= Helper::icon('swap', '16') ?> <?= $profile['role'] === 'admin' ? 'Demote to student' : 'Promote to admin' ?></button>
                </form>
                <?php if ($profile['role'] !== 'admin'): ?>
                <form method="post" action="<?= Helper::url('admin/deleteUser/' . (int) $profile['user_id']) ?>" onsubmit="return confirm('Delete this user and ALL their data? This cannot be undone.');">
                    <?= Helper::csrfField() ?>
                    <button class="btn btn-danger btn-sm" type="submit"><?= Helper::icon('trash', '15') ?> Delete user</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
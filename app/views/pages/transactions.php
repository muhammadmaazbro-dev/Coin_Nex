<?php
declare(strict_types=1);
$netIncome = 0.0;
$netExpense = 0.0;
foreach ($transactions as $t) {
    if ($t['type'] === 'income') { $netIncome += (float) $t['amount']; } else { $netExpense += (float) $t['amount']; }
}
$filteredMonth = $filters['month'] !== '' ? date('F Y', strtotime($filters['month'] . '-01')) : 'All time';
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('dashboard') ?>">Dashboard</a><span class="sep">/</span><span class="current">Transactions</span></div>
        <h1><?= Helper::icon('swap', '26') ?> Transactions</h1>
        <p class="muted small"><?= (int) $total ?> record(s) · <?= Helper::e($filteredMonth) ?></p>
    </div>
    <div class="flex gap-1 wrap">
        <a href="<?= Helper::url('transactions/import') ?>" class="btn btn-ghost btn-sm"><?= Helper::icon('upload', '16') ?> Import CSV</a>
        <a href="<?= Helper::url('transactions/add') ?>" class="btn btn-primary btn-sm"><?= Helper::icon('plus', '16') ?> Add Transaction</a>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.1rem;margin-bottom:1.4rem;">
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-success"><?= Helper::icon('income', '20') ?></div>
        <div class="stat-label">In</div>
        <div class="stat-value text-success" style="font-size:1.3rem;"><?= Helper::money($netIncome) ?></div>
    </div>
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-danger"><?= Helper::icon('expense', '20') ?></div>
        <div class="stat-label">Out</div>
        <div class="stat-value text-danger" style="font-size:1.3rem;"><?= Helper::money($netExpense) ?></div>
    </div>
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-brand"><?= Helper::icon('balance', '20') ?></div>
        <div class="stat-label">Net</div>
        <div class="stat-value" style="font-size:1.3rem;color:<?= $netIncome - $netExpense >= 0 ? 'var(--success)' : 'var(--danger)' ?>;"><?= Helper::money($netIncome - $netExpense) ?></div>
    </div>
</div>

<!-- Filters -->
<form method="get" action="<?= Helper::url('transactions') ?>" class="glass" style="padding:1.1rem 1.2rem;margin-bottom:1.4rem;">
    <div class="flex gap-1 wrap items-center" style="flex-wrap:wrap;">
        <select name="type" class="select" style="width:auto;min-width:140px;" onchange="this.form.submit()">
            <option value="">All types</option>
            <option value="income" <?= $filters['type'] === 'income' ? 'selected' : '' ?>>Income</option>
            <option value="expense" <?= $filters['type'] === 'expense' ? 'selected' : '' ?>>Expense</option>
        </select>
        <select name="category_id" class="select" style="width:auto;min-width:160px;" onchange="this.form.submit()">
            <option value="0">All categories</option>
            <?php foreach (array_merge($incomeCategories, $expenseCategories) as $cat): ?>
            <option value="<?= (int) $cat['category_id'] ?>" <?= (int) $filters['category_id'] === (int) $cat['category_id'] ? 'selected' : '' ?>><?= Helper::e($cat['icon']) ?> <?= Helper::e($cat['name']) ?> (<?= Helper::e($cat['type']) ?>)</option>
            <?php endforeach; ?>
        </select>
        <input class="input" type="month" name="month" value="<?= Helper::e($filters['month']) ?>" onchange="this.form.submit()" style="width:auto;">
        <div class="input-group" style="flex:1;min-width:180px;">
            <input class="input" type="search" name="q" value="<?= Helper::e($filters['search']) ?>" placeholder="Search description or category…">
            <button type="submit" class="btn btn-dark"><?= Helper::icon('search', '16') ?></button>
        </div>
        <a href="<?= Helper::url('transactions') ?>" class="btn btn-ghost btn-sm">Clear</a>
    </div>
</form>

<!-- Table -->
<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th>Date</th>
                <th>Amount</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($transactions)): ?>
            <tr><td colspan="5" class="text-center" style="padding:2.4rem;">
                <div class="no-data-ico"><?= Helper::icon('clipboard', '40') ?></div>
                <div class="bold">No transactions yet</div>
                <div class="faint small" style="margin-bottom:1rem;">Add your first income or expense to start the magic.</div>
                <a href="<?= Helper::url('transactions/add') ?>" class="btn btn-primary btn-sm"><?= Helper::icon('plus', '16') ?> Add Transaction</a>
            </td></tr>
        <?php else: foreach ($transactions as $t): ?>
            <tr>
                <td>
                    <span class="flex items-center gap-1" style="min-width:0;"><span style="font-size:1.1rem;flex:none;"><?= Helper::e($t['category_icon']) ?></span>
                    <span class="bold"><?= Helper::e($t['category_name']) ?></span>
                    <?php if ($t['is_recurring']): ?><span class="badge badge-info" style="font-size:.62rem;"><?= Helper::icon('refresh', '11') ?> <?= Helper::e(ucfirst($t['recurrence'])) ?></span><?php endif; ?>
                    <?php if ($t['ai_suggested_category'] && $t['ai_suggested_category'] == $t['category_id']): ?><span class="badge badge-brand" style="font-size:.62rem;" title="AI suggested this category"><?= Helper::icon('brain', '11') ?></span><?php endif; ?>
                    <?php if ($t['is_duplicate_flag']): ?><span class="badge badge-warning" style="font-size:.62rem;" title="Possible duplicate: same amount & description within 30 days"><?= Helper::icon('duplicate', '11') ?> Dup</span><?php endif; ?>
                    </span>
                </td>
                <td class="muted"><?= Helper::e($t['description'] ?: '—') ?></td>
                <td class="muted"><?= Helper::formatDate($t['transaction_date']) ?></td>
                <td class="<?= $t['type'] === 'income' ? 'amount-income' : 'amount-expense' ?>"><?= $t['type'] === 'income' ? '+' : '−' ?><?= Helper::money((float) $t['amount']) ?></td>
                <td>
                    <div class="flex gap-1" style="justify-content:flex-end;">
                        <a href="<?= Helper::url('transactions/edit/' . (int) $t['transaction_id']) ?>" class="icon-btn" title="Edit"><?= Helper::icon('edit', '17') ?></a>
                        <form method="post" action="<?= Helper::url('transactions/delete/' . (int) $t['transaction_id']) ?>" onsubmit="return confirm('Delete this transaction?');">
                            <?= Helper::csrfField() ?>
                            <button class="icon-btn" title="Delete" style="color:var(--danger);"><?= Helper::icon('trash', '17') ?></button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
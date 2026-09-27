<?php
declare(strict_types=1);
$monthLabel = date('F Y', strtotime($from));
$selectedCat = (int) Helper::get('category_id', 0);
$selectedType = (string) Helper::get('type', '');
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="cc-breadcrumbs">
            <a href="<?= Helper::url('dashboard') ?>" class="crumb-link">Dashboard</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-current">Financial Reports</span>
        </div>
        <h1 style="margin-top:.3rem;"><?= Helper::icon('chart', '26') ?> Student Financial Reports</h1>
        <p class="muted small">Analyze your cashflow, category share, weekly burn rate, and download official PDF/CSV summaries.</p>
    </div>
    <div class="flex gap-1 wrap">
        <a href="<?= Helper::url('reports/exportCsv') ?>?month=<?= Helper::e($month) ?>&category_id=<?= $selectedCat ?>&type=<?= Helper::e($selectedType) ?>" class="btn btn-ghost btn-sm">
            <?= Helper::icon('download', '15') ?> Export CSV
        </a>
        <a href="<?= Helper::url('reports/exportPdf') ?>?month=<?= Helper::e($month) ?>&category_id=<?= $selectedCat ?>&type=<?= Helper::e($selectedType) ?>" class="btn btn-primary btn-sm" style="font-weight:700;">
            <?= Helper::icon('clipboard', '15') ?> Download PDF Report
        </a>
    </div>
</div>

<!-- Comprehensive Report Filters -->
<form method="get" action="<?= Helper::url('reports') ?>" class="card mb-3" style="padding:1.1rem 1.3rem;">
    <div class="bold small mb-2 flex items-center gap-1" style="color:var(--brand);">
        <?= Helper::icon('filter', '15') ?> Filter Financial Summary
    </div>
    <div class="flex gap-1 wrap items-end">
        <div class="field" style="margin:0;min-width:140px;">
            <label class="small muted">Report Month</label>
            <input type="month" name="month" class="input" value="<?= Helper::e($month) ?>">
        </div>

        <div class="field" style="margin:0;min-width:160px;">
            <label class="small muted">Category</label>
            <select name="category_id" class="select">
                <option value="0">All Categories</option>
                <?php foreach ($userCategories as $c): ?>
                <option value="<?= (int) $c['category_id'] ?>" <?= $selectedCat === (int)$c['category_id'] ? 'selected' : '' ?>>
                    <?= Helper::e($c['icon']) ?> <?= Helper::e($c['name']) ?> (<?= ucfirst($c['type']) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field" style="margin:0;min-width:130px;">
            <label class="small muted">Transaction Type</label>
            <select name="type" class="select">
                <option value="">All Types</option>
                <option value="expense" <?= $selectedType === 'expense' ? 'selected' : '' ?>>Expense Only</option>
                <option value="income" <?= $selectedType === 'income' ? 'selected' : '' ?>>Income Only</option>
            </select>
        </div>

        <div class="field" style="margin:0;min-width:130px;">
            <label class="small muted">From Date (Optional)</label>
            <input type="date" name="from_date" class="input" value="<?= Helper::e(Helper::get('from_date', '')) ?>">
        </div>

        <div class="field" style="margin:0;min-width:130px;">
            <label class="small muted">To Date (Optional)</label>
            <input type="date" name="to_date" class="input" value="<?= Helper::e(Helper::get('to_date', '')) ?>">
        </div>

        <div class="flex gap-1" style="margin-left:auto;">
            <a href="<?= Helper::url('reports') ?>" class="btn btn-ghost btn-sm">Reset</a>
            <button type="submit" class="btn btn-primary btn-sm"><?= Helper::icon('search', '14') ?> Apply Filters</button>
        </div>
    </div>
</form>

<!-- 4 Summary Stat Cards -->
<div class="stagger" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:1.1rem;margin-bottom:1.4rem;">
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-success"><?= Helper::icon('income', '20') ?></div>
        <div class="stat-label">Total Income</div>
        <div class="stat-value text-success"><?= Helper::money($income) ?></div>
        <div class="faint small mt-1"><?= $selectedType === 'expense' ? 'Filtered out' : 'Total money received' ?></div>
    </div>
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-danger"><?= Helper::icon('expense', '20') ?></div>
        <div class="stat-label">Total Expenses</div>
        <div class="stat-value text-danger"><?= Helper::money($expense) ?></div>
        <div class="faint small mt-1"><?= $selectedType === 'income' ? 'Filtered out' : 'Total spent' ?></div>
    </div>
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-brand"><?= Helper::icon('wallet', '20') ?></div>
        <div class="stat-label">Net Balance</div>
        <div class="stat-value" style="color:<?= $balance >= 0 ? 'var(--brand)' : 'var(--danger)' ?>;"><?= Helper::money($balance) ?></div>
        <div class="faint small mt-1"><?= $savingsRate ?>% savings rate</div>
    </div>
    <div class="card card-pad-sm stat-card">
        <div class="stat-ico text-info"><?= Helper::icon('swap', '20') ?></div>
        <div class="stat-label">Transactions</div>
        <div class="stat-value text-info"><?= (int) $count ?></div>
        <div class="faint small mt-1">Logged in selected period</div>
    </div>
</div>

<!-- 6-Month Income vs Expense Trend Chart (SRS Requirement) -->
<div class="card mb-3 animate-up">
    <div class="card-title flex between items-center">
        <span><?= Helper::icon('chart', '18') ?> Last 6 Months Cashflow Comparison</span>
        <span class="faint small">Income vs Expense trends</span>
    </div>
    <div style="height:240px;"><canvas id="sixMonthChart"></canvas></div>
</div>

<!-- Category Spending Breakdown Table -->
<div class="card mb-3 animate-up">
    <div class="card-title"><?= Helper::icon('tags', '18') ?> Spending by Category · <?= Helper::e($monthLabel) ?></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th style="text-align:right;">Count</th>
                    <th style="text-align:right;">Total Amount</th>
                    <th style="text-align:right;width:30%;">Category Share</th>
                </tr>
            </thead>
            <tbody>
            <?php $catTotal = array_sum(array_map(static fn ($c) => (float) $c['total'], $byCategory)); ?>
            <?php if ($catTotal <= 0): ?>
                <tr><td colspan="4" class="text-center muted" style="padding:2rem;">No expense transactions matching criteria in <?= Helper::e($monthLabel) ?>.</td></tr>
            <?php else: foreach ($byCategory as $c): ?>
                <?php if ((float) $c['total'] <= 0) continue; ?>
                <?php $sharePct = round((float) $c['total'] / $catTotal * 100); ?>
                <tr>
                    <td>
                        <span class="flex items-center gap-1">
                            <span class="avatar-circle" style="width:30px;height:30px;font-size:1rem;flex:none;"><?= Helper::e($c['icon']) ?></span>
                            <span class="bold"><?= Helper::e($c['name']) ?></span>
                        </span>
                    </td>
                    <td class="muted" style="text-align:right;"><?= (int) $c['n'] ?></td>
                    <td style="text-align:right;" class="bold text-danger"><?= Helper::money((float) $c['total']) ?></td>
                    <td style="text-align:right;">
                        <div class="share-bar">
                            <div class="share-fill" style="width:<?= $sharePct ?>%;background:var(--brand);"></div>
                            <span class="small bold muted"><?= $sharePct ?>%</span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Month-relative Weekly Summary & Daily Summary (SRS Requirement) -->
<div class="stagger" style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.4rem;">
    <!-- Weekly Summary (Week 1, Week 2, Week 3, Week 4) -->
    <div class="card animate-up">
        <div class="card-title"><?= Helper::icon('calendar', '18') ?> Weekly Summary · <?= Helper::e($monthLabel) ?></div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Week Interval</th>
                        <th style="text-align:right;">Income</th>
                        <th style="text-align:right;">Expense</th>
                        <th style="text-align:right;">Net</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($weekly as $w): ?>
                    <tr>
                        <td class="bold"><?= Helper::e($w['label']) ?></td>
                        <td class="text-success" style="text-align:right;"><?= (float) $w['inc'] > 0 ? '+' : '' ?><?= Helper::money((float) $w['inc']) ?></td>
                        <td class="text-danger" style="text-align:right;"><?= (float) $w['exp'] > 0 ? '−' : '' ?><?= Helper::money((float) $w['exp']) ?></td>
                        <td style="text-align:right;color:<?= (float) $w['net'] >= 0 ? 'var(--success)' : 'var(--danger)' ?>;" class="bold">
                            <?= Helper::money((float) $w['net']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title"><?= Helper::icon('clock', '18') ?> Daily Log · <?= Helper::e($monthLabel) ?></div>
        <div class="table-wrap" style="max-height:280px;overflow-y:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align:right;">In</th>
                        <th style="text-align:right;">Out</th>
                        <th style="text-align:right;">Day Net</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($daily)): ?>
                    <tr><td colspan="4" class="text-center muted" style="padding:1.6rem;">No activity logged.</td></tr>
                <?php else: foreach ($daily as $d): $dayNet = (float) $d['inc'] - (float) $d['exp']; ?>
                    <tr>
                        <td class="muted small"><?= date('D, j M', strtotime($d['d'])) ?></td>
                        <td class="text-success small" style="text-align:right;"><?= (float) $d['inc'] > 0 ? '+' . Helper::money((float) $d['inc']) : '—' ?></td>
                        <td class="text-danger small" style="text-align:right;"><?= (float) $d['exp'] > 0 ? '−' . Helper::money((float) $d['exp']) : '—' ?></td>
                        <td style="text-align:right;color:<?= $dayNet >= 0 ? 'var(--success)' : 'var(--danger)' ?>;" class="small bold">
                            <?= Helper::money($dayNet) ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Biggest Transactions Table -->
<div class="card animate-up">
    <div class="card-title"><?= Helper::icon('star', '18') ?> Largest Transactions · <?= Helper::e($monthLabel) ?></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($topTxns)): ?>
                <tr><td colspan="4" class="text-center muted" style="padding:2rem;">No transactions found.</td></tr>
            <?php else: foreach ($topTxns as $t): ?>
                <tr>
                    <td>
                        <span class="flex items-center gap-1">
                            <span class="avatar-circle" style="width:28px;height:28px;font-size:.9rem;flex:none;"><?= Helper::e($t['category_icon']) ?></span>
                            <span class="bold"><?= Helper::e($t['category_name']) ?></span>
                        </span>
                    </td>
                    <td class="muted"><?= Helper::e($t['description'] ?: '—') ?></td>
                    <td class="muted small"><?= Helper::formatDate($t['transaction_date']) ?></td>
                    <td class="bold <?= $t['type'] === 'income' ? 'text-success' : 'text-danger' ?>" style="text-align:right;">
                        <?= $t['type'] === 'income' ? '+' : '−' ?><?= Helper::money((float) $t['amount']) ?>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
(function () {
    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
    const gridCol = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.08)';
    const tickCol = isDark ? 'rgba(255,255,255,.55)' : 'rgba(0,0,0,.6)';

    // 6 Month Trend Chart
    const ctx = document.getElementById('sixMonthChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($sixMonths['labels']) ?>,
                datasets: [
                    { label: 'Income', data: <?= json_encode($sixMonths['income']) ?>, backgroundColor: 'rgba(45,212,167,.75)', borderRadius: 5 },
                    { label: 'Expense', data: <?= json_encode($sixMonths['expense']) ?>, backgroundColor: 'rgba(251,113,133,.75)', borderRadius: 5 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: tickCol } } },
                scales: {
                    x: { grid: { color: gridCol }, ticks: { color: tickCol } },
                    y: { grid: { color: gridCol }, ticks: { color: tickCol }, beginAtZero: true }
                }
            }
        });
    }
})();
</script>
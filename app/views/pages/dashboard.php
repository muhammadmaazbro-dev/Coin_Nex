<?php
declare(strict_types=1);
$greeting = (int) date('G') < 12 ? 'Good morning' : ((int) date('G') < 17 ? 'Good afternoon' : 'Good evening');
$chartColors = ['#f59e0b', '#34d399', '#38bdf8', '#f472b6', '#a78bfa', '#fb7185', '#22d3ee', '#4ade80', '#eab308'];
$monthLabel = date('F Y');
?>

<!-- Breadcrumbs & Greeting -->
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="cc-breadcrumbs">
            <span class="crumb-current"><?= Helper::icon('home', '14') ?> Dashboard Overview</span>
        </div>
        <h1 style="margin-top:.3rem;letter-spacing:-0.02em;"><?= Helper::e($greeting) ?>, <?= Helper::e($user['name']) ?>!</h1>
        <p class="muted small" style="margin:0;">Here is your complete student cashflow and savings picture for <b><?= Helper::e($monthLabel) ?></b>.</p>
    </div>
    <!-- Quick Add Buttons -->
    <div class="flex gap-1 wrap items-center">
        <a href="<?= Helper::url('transactions/add') ?>?type=income" class="btn btn-primary btn-sm" style="background:#2dd4a7;border-color:#2dd4a7;color:#0b0d12;font-weight:700;">
            <?= Helper::icon('income', '16') ?> + Add Income
        </a>
        <a href="<?= Helper::url('transactions/add') ?>?type=expense" class="btn btn-primary btn-sm" style="font-weight:700;">
            <?= Helper::icon('expense', '16') ?> + Add Expense
        </a>
        <a href="<?= Helper::url('insights') ?>" class="btn btn-dark btn-sm">
            <?= Helper::icon('brain', '16') ?> AI Insights
        </a>
    </div>
</div>

<!-- System Announcements Banner -->
<?php if (!empty($announcements)): ?>
<?php foreach ($announcements as $an): ?>
<div class="alert alert-brand animate-up mb-3" style="display:flex;align-items:flex-start;gap:.85rem;background:rgba(247,183,51,.10);border:1px solid rgba(247,183,51,.3);border-radius:var(--radius);">
    <span style="display:flex;align-items:center;color:var(--brand);margin-top:2px;"><?= Helper::icon('bell', '20') ?></span>
    <div style="flex:1;">
        <div class="bold" style="color:var(--brand);font-size:.95rem;"><?= Helper::e($an['title']) ?></div>
        <div class="small" style="line-height:1.45;color:var(--text);"><?= nl2br(Helper::e($an['body'])) ?></div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Safe-to-Spend Today Daily Allowance Dial (Student Favorite) -->
<div class="safe-spend-card mb-3 animate-up flex between items-center wrap gap-2">
    <div class="flex items-center gap-2">
        <div style="display:flex;align-items:center;color:#2dd4a7;filter:drop-shadow(0 2px 8px rgba(45,212,167,0.4));">
            <?= Helper::icon('shield', '36') ?>
        </div>
        <div>
            <div class="flex items-center gap-1">
                <span class="badge badge-success" style="font-size:.72rem;letter-spacing:.05em;">● LIVE BUDGET DIAL</span>
                <span class="faint small">Safe-to-Spend Today</span>
            </div>
            <div class="safe-spend-val">
                <span data-count-up="<?= (float)$safeDailySpend ?>" data-count-prefix="Rs. "><?= Helper::money($safeDailySpend) ?></span>
                <span style="font-size:.85rem;font-weight:500;color:var(--text-muted);">/ day</span>
            </div>
            <div class="faint small" style="margin-top:2px;">
                Calculated for remaining <b><?= (int)$daysLeft ?> days</b> in <?= Helper::e($monthLabel) ?>. Spend less today and tomorrow's allowance grows!
            </div>
        </div>
    </div>
    <div class="flex items-center gap-1">
        <a href="<?= Helper::url('budgets') ?>" class="btn btn-ghost btn-sm" style="border-color:rgba(247,183,51,0.3);color:var(--brand);font-weight:600;">
            View Budgets →
        </a>
        <a href="<?= Helper::url('transactions/add') ?>?type=expense" class="btn btn-primary btn-sm" style="font-weight:700;">
            + Log Expense
        </a>
    </div>
</div>

<!-- 4 Key Stat Cards (Income, Expense, Balance, Savings) -->
<div class="stagger" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:1.1rem;margin-bottom:1.4rem;">
    <!-- Income -->
    <a href="<?= Helper::url('transactions') ?>?type=income" class="card stat-card card-hover animate-up" style="text-decoration:none;">
        <div class="stat-ico text-success"><?= Helper::icon('income', '22') ?></div>
        <div class="stat-label">Total Income · <?= date('M Y') ?></div>
        <div class="stat-value text-success" data-count-up="<?= (float)$income ?>" data-count-prefix="Rs. "><?= Helper::money($income) ?></div>
        <div class="stat-footer mt-1 faint small">Family, part-time &amp; gifts</div>
    </a>

    <!-- Expenses -->
    <a href="<?= Helper::url('transactions') ?>?type=expense" class="card stat-card card-hover animate-up" style="animation-delay:.04s;text-decoration:none;">
        <div class="stat-ico text-danger"><?= Helper::icon('expense', '22') ?></div>
        <div class="stat-label">Total Expenses · <?= date('M Y') ?></div>
        <div class="stat-value text-danger" data-count-up="<?= (float)$expense ?>" data-count-prefix="Rs. "><?= Helper::money($expense) ?></div>
        <div class="stat-footer mt-1 faint small">≈ <?= Helper::money($dailyAvg) ?> daily burn rate</div>
    </a>

    <!-- Remaining Balance -->
    <a href="<?= Helper::url('transactions') ?>" class="card stat-card card-hover animate-up" style="animation-delay:.08s;text-decoration:none;">
        <div class="stat-ico text-brand"><?= Helper::icon('wallet', '22') ?></div>
        <div class="stat-label">Remaining Balance</div>
        <div class="stat-value" style="color:<?= $balance >= 0 ? 'var(--brand)' : 'var(--danger)' ?>;" data-count-up="<?= (float)$balance ?>" data-count-prefix="Rs. "><?= Helper::money($balance) ?></div>
        <div class="stat-footer mt-1 faint small">Income minus total expenses</div>
    </a>

    <!-- Savings -->
    <a href="<?= Helper::url('budgets') ?>" class="card stat-card card-hover animate-up" style="animation-delay:.12s;text-decoration:none;">
        <div class="stat-ico text-success"><?= Helper::icon('target', '22') ?></div>
        <div class="stat-label">Monthly Savings</div>
        <div class="stat-value text-success" data-count-up="<?= (float)$savings ?>" data-count-prefix="Rs. "><?= Helper::money($savings) ?></div>
        <div class="stat-footer mt-1"><span class="badge badge-success"><?= $savingsRate ?>% saved</span> <?= $savingsRate >= 20 ? 'Target achieved' : 'Aim for 20%' ?></div>
    </a>
</div>

<!-- Highlighted AI Spending Alert & Insight Card -->
<?php if ($topCategoryInsight): ?>
<div class="card animate-up mb-3" style="border:1px solid rgba(247,183,51,.25);background:linear-gradient(135deg, rgba(247,183,51,.04) 0%, rgba(16,19,27,.6) 100%);">
    <div class="flex between items-center wrap gap-2">
        <div class="flex items-center gap-1">
            <span style="font-size:2rem;line-height:1;"><?= Helper::e($topCategoryInsight['category_icon']) ?></span>
            <div>
                <div class="bold flex items-center gap-1" style="font-size:1.05rem;color:var(--brand);">
                    <?= Helper::icon('alert', '18') ?> <?= Helper::e($topCategoryInsight['category_name']) ?> is your highest spending category this month.
                </div>
                <div class="small text-muted" style="margin-top:.2rem;">
                    You spent <b><?= Helper::money((float) $topCategoryInsight['current_spend']) ?></b> on <?= Helper::e($topCategoryInsight['category_name']) ?> in <?= Helper::e($monthLabel) ?>.
                    <?php if ($topCategoryInsight['is_above_avg']): ?>
                        Your historical average is <b><?= Helper::money((float) $topCategoryInsight['hist_avg']) ?></b> (+<?= (int) $topCategoryInsight['pct_growth'] ?>% increase).
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-1">
            <div class="glass glass-soft" style="padding:.5rem .9rem;border-radius:12px;">
                <span class="faint small">Smart Suggestion:</span>
                <div class="bold small" style="color:var(--success);">Reduce <?= Helper::e($topCategoryInsight['category_name']) ?> by <?= Helper::money((float) $topCategoryInsight['weekly_saving']) ?> / week</div>
            </div>
            <a href="<?= Helper::url('budgets') ?>" class="btn btn-primary btn-sm" style="white-space:nowrap;">Set Budget Cap</a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Charts Row -->
<div class="stagger" style="display:grid;grid-template-columns:1.6fr 1fr;gap:1.1rem;margin-bottom:1.1rem;">
    <!-- 6-Month Income vs Expense -->
    <div class="card animate-up">
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('chart', '18') ?> Last 6 Months · Income vs Expenses</span>
            <span class="faint small">Apr – Sep 2026</span>
        </div>
        <div style="height:230px;"><canvas id="trendChart"></canvas></div>
    </div>

    <!-- Category Donut -->
    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title"><?= Helper::icon('tags', '18') ?> Where It Went · <?= Helper::e($monthLabel) ?></div>
        <?php if (empty($byCategory)): ?>
            <div class="text-center muted" style="padding:2.5rem;">No expenses logged for this month.</div>
        <?php else: ?>
        <div style="height:160px;margin-bottom:.8rem;"><canvas id="catChart"></canvas></div>
        <div style="max-height:160px;overflow-y:auto;padding-right:.3rem;">
            <?php foreach ($byCategory as $i => $cat): ?>
            <div class="flex between items-center" style="padding:.35rem 0;border-bottom:1px dashed var(--border);">
                <span class="flex items-center gap-1" style="min-width:0;">
                    <span style="width:10px;height:10px;border-radius:3px;background:<?= $chartColors[$i % count($chartColors)] ?>;flex:none;"></span>
                    <span class="small bold" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= Helper::e($cat['icon']) ?> <?= Helper::e($cat['name']) ?></span>
                </span>
                <span class="small bold"><?= Helper::money((float) $cat['total']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Budgets vs Actual Progress -->
<?php if (!empty($budgets)): ?>
<div class="card mb-3 animate-up">
    <div class="card-title flex between items-center">
        <span><?= Helper::icon('target', '18') ?> Category Budgets Progress · <?= Helper::e($monthLabel) ?></span>
        <a href="<?= Helper::url('budgets') ?>" class="btn btn-ghost btn-sm">Manage Budgets →</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:1.2rem;">
        <?php foreach ($budgets as $b): ?>
        <?php
            $isOver = $b['pct'] >= 100;
            $isWarn = $b['pct'] >= (float)$b['alert_threshold'];
            $fillColor = $isOver ? 'var(--danger)' : ($isWarn ? 'var(--warning)' : 'var(--success)');
        ?>
        <a href="<?= Helper::url('budgets') ?>" class="glass card-hover" style="display:block;text-decoration:none;padding:.9rem 1rem;border-radius:14px;">
            <div class="flex between items-center small mb-1">
                <span class="bold" style="color:var(--text);"><?= Helper::e($b['category_icon']) ?> <?= Helper::e($b['category_name']) ?></span>
                <span class="bold" style="color:<?= $fillColor ?>;"><?= Helper::money((float) $b['spent']) ?> / <?= Helper::money((float) $b['limit_amount']) ?></span>
            </div>
            <div class="progress-bar" style="height:8px;">
                <div class="progress-fill" style="width:<?= min(100, (int) $b['pct']) ?>%;background:<?= $fillColor ?>;"></div>
            </div>
            <div class="flex between faint small mt-1" style="font-size:.74rem;">
                <span><?= (int) $b['pct'] ?>% used</span>
                <span><?= $isOver ? Helper::icon('alert', '13') . ' Exceeded' : Helper::money(max(0, (float)$b['limit_amount'] - (float)$b['spent'])) . ' remaining' ?></span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Lower Row: Saving Tips, Forecast & Activity Stream -->
<div class="stagger" style="display:grid;grid-template-columns:1.2fr 1fr;gap:1.1rem;">
    <!-- Saving Tips Engine -->
    <div class="card animate-up">
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('bulb', '18') ?> Personalized Saving Tips</span>
            <a href="<?= Helper::url('tips') ?>" class="small link">View All Tips →</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            <?php if (empty($tips)): ?>
                <p class="muted small text-center" style="padding:1.5rem;">Add a few expenses to generate personalized tips.</p>
            <?php else: foreach ($tips as $ti): ?>
            <?php
                $score = (float) $ti['rank_score'];
                $potential = $score >= 85 ? 'High Potential' : ($score >= 70 ? 'Medium Potential' : 'Tip');
                $badgeCls = $score >= 85 ? 'badge-danger' : ($score >= 70 ? 'badge-warning' : 'badge-brand');
            ?>
            <div class="glass" style="padding:.85rem 1rem;border-radius:12px;">
                <div class="flex between items-center gap-1 mb-1">
                    <span class="badge <?= $badgeCls ?>"><?= $potential ?></span>
                    <form method="post" action="<?= Helper::url('tips/bookmark/' . (int) $ti['tip_id']) ?>" class="inline">
                        <?= Helper::csrfField() ?>
                        <button class="btn btn-ghost btn-sm" style="padding:.15rem .45rem;" title="Bookmark this tip">
                            <?= Helper::icon('bookmark', '13') ?> <?= $ti['bookmarked'] ? 'Saved' : 'Save' ?>
                        </button>
                    </form>
                </div>
                <div class="small text-muted" style="line-height:1.45;"><?= Helper::e($ti['tip_text']) ?></div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Forecast & Recent Transactions -->
    <div class="card animate-up" style="animation-delay:.05s;">
        <!-- Forecast Card -->
        <a href="<?= Helper::url('insights') ?>" class="glass mb-3 card-hover" style="display:block;text-decoration:none;padding:.9rem 1.1rem;border-radius:14px;border:1px dashed var(--border-strong);">
            <div class="flex items-center gap-1 mb-1">
                <span class="stat-ico text-brand" style="margin:0;"><?= Helper::icon('sparkle', '18') ?></span>
                <span class="bold small" style="color:var(--brand);">Next Month Spending Forecast</span>
            </div>
            <div class="stat-value" style="font-size:1.4rem;color:var(--text);"><?= Helper::money((float) $forecast) ?></div>
            <div class="faint small" style="margin-top:.2rem;">Based on your recent 3-month spending trends. Tap for AI breakdown →</div>
        </a>

        <!-- Recent Activity -->
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('clock', '18') ?> Recent Transactions</span>
            <a href="<?= Helper::url('transactions') ?>" class="small link">View list →</a>
        </div>
        <div class="activity-list">
            <?php if (empty($recent)): ?>
                <div class="text-center muted small" style="padding:1rem;">No transactions yet.</div>
            <?php else: foreach (array_slice($recent, 0, 5) as $r): ?>
            <a href="<?= Helper::url('transactions') ?>" class="flex between items-center card-hover" style="display:flex;text-decoration:none;padding:.45rem .3rem;border-bottom:1px dashed var(--border);border-radius:8px;">
                <div class="flex items-center gap-1" style="min-width:0;">
                    <span class="avatar-circle" style="width:30px;height:30px;font-size:.9rem;flex:none;"><?= Helper::e($r['category_icon']) ?></span>
                    <div style="min-width:0;">
                        <div class="small bold" style="color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= Helper::e($r['description'] ?: $r['category_name']) ?></div>
                        <div class="faint" style="font-size:.72rem;"><?= Helper::formatDate($r['transaction_date']) ?></div>
                    </div>
                </div>
                <span class="small bold <?= $r['type'] === 'income' ? 'text-success' : 'text-danger' ?>">
                    <?= $r['type'] === 'income' ? '+' : '−' ?><?= Helper::money((float) $r['amount']) ?>
                </span>
            </a>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<script>
(function () {
    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
    const gridCol = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.08)';
    const tickCol = isDark ? 'rgba(255,255,255,.55)' : 'rgba(0,0,0,.6)';

    // Trend chart
    const trend = document.getElementById('trendChart');
    if (trend) {
        new Chart(trend, {
            type: 'line',
            data: {
                labels: <?= json_encode($series['labels']) ?>,
                datasets: [
                    { label: 'Income', data: <?= json_encode($series['income']) ?>, borderColor: '#2dd4a7', backgroundColor: 'rgba(45,212,167,.12)', fill: true, tension: .4, borderWidth: 2.5, pointRadius: 3 },
                    { label: 'Expense', data: <?= json_encode($series['expense']) ?>, borderColor: '#fb7185', backgroundColor: 'rgba(251,113,133,.10)', fill: true, tension: .4, borderWidth: 2.5, pointRadius: 3 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: tickCol, usePointStyle: true } } },
                scales: {
                    x: { grid: { color: gridCol }, ticks: { color: tickCol } },
                    y: { grid: { color: gridCol }, ticks: { color: tickCol }, beginAtZero: true }
                }
            }
        });
    }

    // Category donut
    const cat = document.getElementById('catChart');
    const catData = <?= json_encode(array_column($byCategory, 'total')) ?>;
    const catNames = <?= json_encode(array_map(static fn ($c) => $c['name'], $byCategory)) ?>;
    if (cat && catData.length) {
        new Chart(cat, {
            type: 'doughnut',
            data: {
                labels: catNames,
                datasets: [{ data: catData, backgroundColor: <?= json_encode(array_slice($chartColors, 0, count($byCategory))) ?>, borderWidth: 2, borderColor: isDark ? '#10131b' : '#fff' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });
    }
})();
</script>
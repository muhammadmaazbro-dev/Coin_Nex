<?php
declare(strict_types=1);
$monthWithDash = date('Y-m', strtotime($month));
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('dashboard') ?>">Dashboard</a><span class="sep">/</span><span class="current">Budgets</span></div>
        <h1><?= Helper::icon('target', '26') ?> Budgets</h1>
        <p class="muted small">Keep each category in check — get notified before you overspend.</p>
    </div>
</div>

<div class="glass" style="padding:1.1rem 1.2rem;margin-bottom:1.4rem;">
    <div class="flex gap-1 wrap items-center">
        <label class="small muted">Month</label>
        <input type="month" name="month" class="input" value="<?= Helper::e($month) ?>" style="width:auto;" onchange="location.href='<?= Helper::url('budgets') ?>?month='+this.value">
        <span class="small muted" style="margin-left:auto;">Total budget <b><?= Helper::money((float) $totalBudget) ?></b> · Spent <b style="color:var(--danger);"><?= Helper::money((float) $totalSpent) ?></b> (<?= (int) $avgPct ?>%)</span>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.1rem;">
    <?php if (empty($budgets)): ?>
    <div class="card text-center" style="grid-column:1/-1;padding:2.4rem;">
        <div class="no-data-ico"><?= Helper::icon('target', '44') ?></div>
        <h2 style="font-size:1.1rem;">No budgets set for <?= date('F Y', strtotime($monthWithDash . '-01')) ?></h2>
        <p class="muted small">Use the form below to set a limit for a category.</p>
    </div>
    <?php endif; ?>

    <?php foreach ($budgets as $b): ?>
    <?php
        $limit = (float) $b['limit_amount'];
        $spent = (float) $b['spent'];
        $pct = $limit > 0 ? $spent / $limit * 100 : 0;
        $barColor = $pct >= 100 ? 'var(--danger)' : ($pct >= (float) $b['alert_threshold'] ? 'var(--warning)' : 'var(--brand)');
    ?>
    <div class="card card-hover">
        <div class="flex between items-center" style="margin-bottom:.6rem;">
            <span class="flex items-center gap-1 bold"><?= Helper::e($b['category_icon']) ?> <?= Helper::e($b['category_name']) ?></span>
            <span class="badge <?= $pct >= 100 ? 'badge-danger' : ($pct >= (float) $b['alert_threshold'] ? 'badge-warning' : 'badge-info') ?>"><?= (int) round($pct) ?>%</span>
        </div>
        <div class="progress-bar"><div class="progress-fill" style="width:<?= min(100, $pct) ?>%;background:<?= $barColor ?>;"></div></div>
        <div class="flex between faint small" style="margin:.4rem 0 .1rem;">
            <span><?= Helper::money($spent) ?> spent</span>
            <span><?= Helper::money($limit) ?> limit</span>
        </div>
        <div class="small muted" style="margin-bottom:.8rem;">Alert at <?= (float) $b['alert_threshold'] ?>% · <?= Helper::money(max(0, $limit - $spent)) ?> left</div>

        <form method="post" action="<?= Helper::url('budgets/update/' . (int) $b['budget_id']) ?>?month=<?= Helper::e($month) ?>" class="flex" style="gap:.5rem;">
            <?= Helper::csrfField() ?>
            <input type="number" step="0.01" min="0.01" name="limit_amount" class="input" value="<?= Helper::e($limit) ?>" placeholder="Limit" title="Budget limit">
            <select name="alert_threshold" class="select" style="width:auto;padding:.45rem 1.6rem .45rem .7rem;" title="Alert threshold">
                <?php foreach ([60, 70, 75, 80, 85, 90] as $at): ?>
                <option value="<?= $at ?>" <?= (int) $b['alert_threshold'] === $at ? 'selected' : '' ?>>Alarm <?= $at ?>%</option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-dark btn-sm" title="Save changes"><?= Helper::icon('edit', '15') ?></button>
            <a href="#" data-budget-delete="<?= (int) $b['budget_id'] ?>" data-month="<?= Helper::e($month) ?>" class="btn btn-danger btn-sm" style="text-decoration:none;"><?= Helper::icon('trash', '15') ?></a>
        </form>
    </div>
    <?php endforeach; ?>
</div>

<div class="card mt-3">
    <div class="card-title"><?= Helper::icon('plus', '18') ?> Set a new budget</div>
    <form method="post" action="<?= Helper::url('budgets/create') ?>" class="flex gap-1 wrap items-end">
        <?= Helper::csrfField() ?>
        <div class="field" style="margin:0;">
            <label>Category</label>
            <select name="category_id" class="select" required>
                <option value="">Choose…</option>
                <?php foreach ($expenseCategories as $c): ?>
                <option value="<?= (int) $c['category_id'] ?>"><?= Helper::e($c['icon']) ?> <?= Helper::e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field" style="margin:0;width:130px;">
            <label>Month</label>
            <input type="month" name="month" class="input" value="<?= Helper::e($month) ?>" required>
        </div>
        <div class="field" style="margin:0;width:130px;">
            <label>Limit (<?= Helper::e(DEFAULT_CURRENCY_SYMBOL) ?>)</label>
            <input type="number" step="0.01" min="0.01" name="limit_amount" class="input" placeholder="100.00" required>
        </div>
        <div class="field" style="margin:0;width:130px;">
            <label>Alert at (%)</label>
            <input type="number" step="1" min="10" max="100" name="alert_threshold" class="input" value="80" placeholder="80">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save budget</button>
    </form>
</div>

<script>
document.querySelectorAll('[data-budget-delete]').forEach(function (el) {
    el.addEventListener('click', function (e) {
        e.preventDefault();
        if (!confirm('Remove this budget?')) return;
        const id = el.dataset.budgetDelete, month = el.dataset.month;
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '<?= Helper::url('budgets') ?>' + '/delete/' + id + '?month=' + month;
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'csrf_token'; input.value = window.CSRF_TOKEN;
        form.appendChild(input);
        document.body.appendChild(form); form.submit();
    });
});
</script>
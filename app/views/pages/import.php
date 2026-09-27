<?php
declare(strict_types=1);
$hasPreview = !empty($preview);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="cc-breadcrumbs">
            <a href="<?= Helper::url('transactions') ?>" class="crumb-link">Transactions</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-current">Import CSV</span>
        </div>
        <h1 style="margin-top:.3rem;"><?= Helper::icon('upload', '26') ?> Import Transactions from CSV</h1>
        <p class="muted small">Upload bank/cash transaction statements. The built-in AI will automatically categorize entries for your review.</p>
    </div>
    <a href="<?= Helper::url('transactions/template') ?>" class="btn btn-ghost btn-sm"><?= Helper::icon('download', '15') ?> Download CSV Template</a>
</div>

<?php if (!$hasPreview): ?>
<div class="card text-center animate-up" style="padding:2.6rem 1.4rem;">
    <div style="margin-bottom:.6rem;color:var(--brand);"><?= Helper::icon('upload', '44') ?></div>
    <h2 style="font-size:1.2rem;">Upload your CSV File</h2>
    <p class="muted small" style="max-width:34rem;margin:0 auto 1.2rem;">
        Expected columns: <code>date, type, amount, description, category</code>.<br>
        Supported types: <strong>income</strong> or <strong>expense</strong>.<br>
        Category can be left blank — the AI auto-classifies items like McDonald's, Uber, Netflix, etc.
    </p>

    <form method="post" action="<?= Helper::url('transactions/import') ?>" enctype="multipart/form-data" id="csv-form" style="max-width:480px;margin:0 auto;">
        <?= Helper::csrfField() ?>
        <label class="glass" style="display:block;padding:2.2rem;border-style:dashed;border-width:2px;border-color:var(--brand);cursor:pointer;border-radius:16px;" for="csv_file">
            <div style="margin-bottom:.5rem;color:var(--brand);"><?= Helper::icon('clipboard', '40') ?></div>
            <div class="bold" id="file-name" style="font-size:1.05rem;">Choose a CSV file…</div>
            <div class="faint small mt-1">or drag &amp; drop your statement here</div>
        </label>
        <input class="input" type="file" name="csv_file" id="csv_file" accept=".csv,text/csv" style="display:none;" required>
        <button type="submit" class="btn btn-primary btn-block mt-3" id="csv-submit" style="font-weight:700;">
            Preview &amp; AI Categorize <?= Helper::icon('chevron-right', '15') ?>
        </button>
    </form>
</div>

<script>
document.getElementById('csv_file').addEventListener('change', function () {
    const name = this.files[0] ? this.files[0].name : 'Choose a CSV file…';
    document.getElementById('file-name').textContent = name;
});
document.getElementById('csv-form').addEventListener('submit', function () {
    document.getElementById('csv-submit').disabled = true;
    document.getElementById('csv-submit').textContent = 'Analyzing and categorizing…';
});
</script>

<?php else: ?>
<div class="card animate-up">
    <div class="card-title flex between items-center">
        <span><?= Helper::icon('eye', '18') ?> CSV Preview &amp; Review (<?= count($preview) ?> row<?= count($preview) === 1 ? '' : 's' ?>)</span>
        <span class="badge badge-brand">AI Auto-Categorized</span>
    </div>
    <p class="muted small mb-2">Review AI categorized records below. You can adjust the assigned category for any row before confirming import.</p>

    <form method="post" action="<?= Helper::url('transactions/importConfirm') ?>">
        <?= Helper::csrfField() ?>
        <div class="table-wrap" style="max-height:450px;overflow-y:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th style="text-align:right;">Amount</th>
                        <th style="min-width:200px;">Assigned Category</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($preview as $idx => $row): ?>
                    <tr>
                        <td class="muted small"><?= Helper::formatDate($row['transaction_date']) ?></td>
                        <td><span class="badge <?= $row['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>"><?= ucfirst($row['type']) ?></span></td>
                        <td class="bold small"><?= Helper::e($row['description'] ?: '—') ?></td>
                        <td class="bold <?= $row['type'] === 'income' ? 'text-success' : 'text-danger' ?>" style="text-align:right;">
                            <?= Helper::money((float) $row['amount']) ?>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <select name="category_override[<?= $idx ?>]" class="select" style="padding:.35rem .6rem;font-size:.85rem;height:auto;">
                                    <?php foreach ($allCategories as $c): ?>
                                    <?php if ($c['type'] === $row['type']): ?>
                                    <option value="<?= (int) $c['category_id'] ?>" <?= (int)$c['category_id'] === (int)$row['category_id'] ? 'selected' : '' ?>>
                                        <?= Helper::e($c['icon']) ?> <?= Helper::e($c['name']) ?>
                                    </option>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($row['ai_suggested_category'])): ?>
                                    <span class="badge badge-brand" title="Suggested by AI classifier" style="flex:none;font-size:.7rem;">AI 🤖</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex gap-1 mt-3" style="justify-content:flex-end;">
            <a href="<?= Helper::url('transactions/import') ?>" class="btn btn-ghost"><?= Helper::icon('chevron-left', '15') ?> Discard &amp; Re-upload</a>
            <button type="submit" class="btn btn-primary" style="font-weight:700;">
                Confirm &amp; Import <?= count($preview) ?> Transactions <?= Helper::icon('chevron-right', '15') ?>
            </button>
        </div>
    </form>
</div>
<?php endif; ?>
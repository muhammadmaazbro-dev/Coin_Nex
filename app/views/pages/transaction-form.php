<?php
declare(strict_types=1);
$isEdit = $editing !== null && !empty($editing['transaction_id']);
$t = $editing ?? [
    'type' => 'expense',
    'category_id' => 0,
    'amount' => '',
    'description' => '',
    'transaction_date' => date('Y-m-d'),
    'is_recurring' => 0,
    'recurrence' => 'none',
];
$formAction = $isEdit ? Helper::url('transactions/update/' . (int) $t['transaction_id']) : Helper::url('transactions/create');
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="cc-breadcrumbs">
            <a href="<?= Helper::url('transactions') ?>" class="crumb-link">Transactions</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-current"><?= $isEdit ? 'Edit Transaction' : 'Add Transaction' ?></span>
        </div>
        <h1 style="margin-top:.3rem;"><?= $isEdit ? Helper::icon('edit', '24') . ' Edit Transaction' : Helper::icon('plus', '24') . ' Add ' . ucfirst($t['type']) ?></h1>
        <p class="muted small"><?= $isEdit ? 'Update transaction details below.' : 'Real-time AI categorization auto-identifies categories and checks for duplicates &amp; anomalies.' ?></p>
    </div>
    <a href="<?= Helper::url('transactions') ?>" class="btn btn-ghost btn-sm"><?= Helper::icon('chevron-left', '15') ?> Back to list</a>
</div>

<div class="card animate-up" style="max-width:680px;">
    <form method="post" action="<?= $formAction ?>" id="txn-form">
        <?= Helper::csrfField() ?>

        <!-- Type Selection Pills -->
        <div class="field mb-3">
            <label class="bold small mb-1" style="display:block;">Transaction Type</label>
            <div class="pill-select" id="type-pills">
                <label class="pill <?= $t['type'] === 'expense' ? 'active' : '' ?>">
                    <input type="radio" name="type" value="expense" <?= $t['type'] === 'expense' ? 'checked' : '' ?>>
                    <?= Helper::icon('expense', '15') ?> Expense
                </label>
                <label class="pill <?= $t['type'] === 'income' ? 'active' : '' ?>">
                    <input type="radio" name="type" value="income" <?= $t['type'] === 'income' ? 'checked' : '' ?>>
                    <?= Helper::icon('income', '15') ?> Income
                </label>
            </div>
        </div>

        <!-- Description with AI suggestion -->
        <div class="field mb-3">
            <label for="description" class="bold small mb-1" style="display:block;">
                Description
                <span class="form-hint faint">(e.g. McDonald's burger, Uber ride to campus, Family allowance)</span>
            </label>
            <input class="input" type="text" id="description" name="description" value="<?= Helper::e($t['description']) ?>" placeholder="What was this for?" autocomplete="off" required>

            <!-- Interactive AI Suggestion Banner with [Accept] and [Change] -->
            <div id="ai-suggestion-box" class="glass glass-soft mt-2" style="display:none;padding:.75rem 1rem;border-radius:12px;border:1px solid rgba(247,183,51,.35);background:rgba(247,183,51,.06);">
                <div class="flex between items-center wrap gap-1">
                    <div class="flex items-center gap-1">
                        <span style="font-size:1.2rem;">🤖</span>
                        <span class="small">AI Suggestion: <b id="ai-suggested-name" style="color:var(--brand);">Food</b></span>
                    </div>
                    <div class="flex gap-1 items-center">
                        <button type="button" class="btn btn-primary btn-sm" id="ai-accept-btn" style="padding:.25rem .7rem;font-size:.8rem;">
                            <?= Helper::icon('check', '13') ?> Accept
                        </button>
                        <button type="button" class="btn btn-ghost btn-sm" id="ai-change-btn" style="padding:.25rem .7rem;font-size:.8rem;">
                            Change
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Dropdown -->
        <div class="field mb-3">
            <label for="category-select" class="bold small mb-1" style="display:block;">
                Category <span id="category-badge"></span>
            </label>
            <select class="select" id="category-select" name="category_id" required>
                <option value="" disabled <?= (int)$t['category_id'] === 0 ? 'selected' : '' ?>>Select category…</option>
                <optgroup label="Expense Categories" id="expense-optgroup">
                    <?php foreach ($expenseCategories as $cat): ?>
                    <option value="<?= (int) $cat['category_id'] ?>" data-type="expense" data-icon="<?= Helper::e($cat['icon']) ?>" <?= (int) $t['category_id'] === (int) $cat['category_id'] ? 'selected' : '' ?>>
                        <?= Helper::e($cat['icon']) ?> <?= Helper::e($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="Income Categories" id="income-optgroup">
                    <?php foreach ($incomeCategories as $cat): ?>
                    <option value="<?= (int) $cat['category_id'] ?>" data-type="income" data-icon="<?= Helper::e($cat['icon']) ?>" <?= (int) $t['category_id'] === (int) $cat['category_id'] ? 'selected' : '' ?>>
                        <?= Helper::e($cat['icon']) ?> <?= Helper::e($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>

        <!-- Amount and Date in 2 columns -->
        <div class="grid-2-cols gap-2 mb-3">
            <div class="field" style="margin:0;">
                <label for="amount" class="bold small mb-1" style="display:block;">Amount (<?= Helper::e(DEFAULT_CURRENCY_SYMBOL) ?>)</label>
                <div class="input-affix">
                    <span class="affix"><?= Helper::e(DEFAULT_CURRENCY_SYMBOL) ?></span>
                    <input class="input" type="number" step="0.01" min="0.01" id="amount" name="amount" value="<?= Helper::e($t['amount']) ?>" placeholder="0.00" required>
                </div>
            </div>
            <div class="field" style="margin:0;">
                <label for="transaction_date" class="bold small mb-1" style="display:block;">Transaction Date</label>
                <input class="input" type="date" id="transaction_date" name="transaction_date" value="<?= Helper::e($t['transaction_date']) ?>" required>
            </div>
        </div>

        <!-- Anomaly Warning Alert Box -->
        <div id="anomaly-alert-box" class="alert alert-brand mb-3" style="display:none;background:rgba(251,113,133,.12);border:1px solid rgba(251,113,133,.4);border-radius:12px;">
            <?= Helper::icon('alert', '20') ?>
            <div style="flex:1;">
                <div class="bold" style="color:var(--danger);font-size:.9rem;" id="anomaly-msg">Unusually large transaction detected!</div>
                <label class="flex items-center gap-1 mt-1 small" style="cursor:pointer;">
                    <input type="checkbox" id="anomaly-confirm-check">
                    <span class="bold">Yes, this amount is correct.</span>
                </label>
            </div>
        </div>

        <!-- Duplicate Warning Alert Box -->
        <div id="duplicate-alert-box" class="alert alert-brand mb-3" style="display:none;background:rgba(247,183,51,.12);border:1px solid rgba(247,183,51,.4);border-radius:12px;">
            <?= Helper::icon('alert', '20') ?>
            <div style="flex:1;">
                <div class="small" id="duplicate-msg" style="color:var(--text);font-weight:600;">Possible duplicate transaction detected.</div>
            </div>
        </div>

        <!-- Recurring Option -->
        <div class="glass" style="padding:.9rem 1.1rem;margin-bottom:1.4rem;border-radius:12px;">
            <label class="flex items-center gap-1" style="cursor:pointer;margin:0;">
                <span class="switch"><input type="checkbox" id="is_recurring" name="is_recurring" value="1" <?= $t['is_recurring'] ? 'checked' : '' ?>> <span class="slider"></span></span>
                <span class="bold small"><?= Helper::icon('refresh', '14') ?> Set as Recurring Transaction</span>
            </label>
            <div class="flex items-center gap-1" style="margin-top:.7rem;padding-left:2.4rem;<?= $t['is_recurring'] ? '' : 'display:none;' ?>" id="recurrence-box">
                <span class="small muted">Repeats</span>
                <select class="select" name="recurrence" id="recurrence" style="width:auto;padding:.4rem 2rem .4rem .8rem;">
                    <option value="monthly" <?= $t['recurrence'] === 'monthly' || $t['recurrence'] === 'none' ? 'selected' : '' ?>>Every Month (e.g. Allowance, Rent)</option>
                    <option value="weekly" <?= $t['recurrence'] === 'weekly' ? 'selected' : '' ?>>Every Week</option>
                </select>
            </div>
        </div>

        <div class="flex gap-1" style="justify-content:flex-end;">
            <a href="<?= Helper::url('transactions') ?>" class="btn btn-ghost">Cancel</a>
            <button type="submit" id="submit-btn" class="btn btn-primary" style="font-weight:700;">
                <?= $isEdit ? 'Save Changes' : ($t['type'] === 'income' ? '+ Save Income' : '+ Save Expense') ?>
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    const typePills = document.querySelectorAll('#type-pills input');
    const catSelect = document.getElementById('category-select');
    const descInput = document.getElementById('description');
    const amountInput = document.getElementById('amount');
    const dateInput = document.getElementById('transaction_date');
    const aiBox = document.getElementById('ai-suggestion-box');
    const aiName = document.getElementById('ai-suggested-name');
    const aiAcceptBtn = document.getElementById('ai-accept-btn');
    const aiChangeBtn = document.getElementById('ai-change-btn');
    const anomalyBox = document.getElementById('anomaly-alert-box');
    const anomalyMsg = document.getElementById('anomaly-msg');
    const anomalyCheck = document.getElementById('anomaly-confirm-check');
    const duplicateBox = document.getElementById('duplicate-alert-box');
    const duplicateMsg = document.getElementById('duplicate-msg');
    const recCheck = document.getElementById('is_recurring');
    const recBox = document.getElementById('recurrence-box');
    const submitBtn = document.getElementById('submit-btn');

    let currentAiMatch = null;
    let anomalyPending = false;

    function getSelectedType() {
        const checked = Array.from(typePills).find(r => r.checked);
        return checked ? checked.value : 'expense';
    }

    function syncTypeOptions() {
        const type = getSelectedType();
        Array.from(catSelect.options).forEach(opt => {
            if (opt.value === '') return;
            const match = opt.dataset.type === type;
            opt.disabled = !match;
            opt.hidden = !match;
        });
        const cur = catSelect.selectedOptions[0];
        if (!cur || cur.disabled) {
            catSelect.value = '';
        }
        submitBtn.textContent = <?= json_encode($isEdit ? 'Save Changes' : '') ?> || (type === 'income' ? '+ Save Income' : '+ Save Expense');
    }

    typePills.forEach(p => p.addEventListener('change', () => {
        syncTypeOptions();
        fireSuggest();
        fireCheck();
    }));
    syncTypeOptions();

    // Debounced AI category suggest
    function fireSuggest() {
        const q = descInput.value.trim();
        const type = getSelectedType();
        if (q.length < 2) {
            aiBox.style.display = 'none';
            return;
        }
        fetch('<?= Helper::url('transactions/suggest') ?>?q=' + encodeURIComponent(q) + '&type=' + type)
            .then(r => r.json())
            .then(d => {
                if (d.match) {
                    currentAiMatch = d.match;
                    aiName.textContent = d.match.icon + ' ' + d.match.name;
                    aiBox.style.display = 'block';
                } else if (d.suggestion) {
                    currentAiMatch = null;
                    aiName.textContent = d.suggestion;
                    aiBox.style.display = 'block';
                } else {
                    aiBox.style.display = 'none';
                }
            })
            .catch(() => {});
    }

    aiAcceptBtn.addEventListener('click', () => {
        if (currentAiMatch) {
            catSelect.value = currentAiMatch.id;
        }
        aiBox.style.display = 'none';
        fireCheck();
    });

    aiChangeBtn.addEventListener('click', () => {
        catSelect.focus();
        aiBox.style.display = 'none';
    });

    // Real-time anomaly & duplicate detection check
    function fireCheck() {
        const amount = parseFloat(amountInput.value) || 0;
        const catId = parseInt(catSelect.value) || 0;
        const date = dateInput.value;
        const type = getSelectedType();
        const excludeId = <?= (int)($t['transaction_id'] ?? 0) ?>;

        if (amount <= 0) {
            anomalyBox.style.display = 'none';
            duplicateBox.style.display = 'none';
            anomalyPending = false;
            return;
        }

        fetch('<?= Helper::url('transactions/check') ?>?amount=' + amount + '&category_id=' + catId + '&date=' + encodeURIComponent(date) + '&type=' + type + '&exclude_id=' + excludeId)
            .then(r => r.json())
            .then(d => {
                // Anomaly warning
                if (d.anomaly && d.anomaly.is_anomaly) {
                    anomalyMsg.textContent = d.anomaly.message;
                    anomalyBox.style.display = 'flex';
                    anomalyPending = true;
                } else {
                    anomalyBox.style.display = 'none';
                    anomalyPending = false;
                }

                // Duplicate warning
                if (d.duplicate && d.duplicate.is_duplicate) {
                    duplicateMsg.textContent = d.duplicate.message;
                    duplicateBox.style.display = 'flex';
                } else {
                    duplicateBox.style.display = 'none';
                }
            })
            .catch(() => {});
    }

    descInput.addEventListener('input', debounce(fireSuggest, 300));
    amountInput.addEventListener('input', debounce(fireCheck, 400));
    catSelect.addEventListener('change', fireCheck);
    dateInput.addEventListener('change', fireCheck);

    document.getElementById('txn-form').addEventListener('submit', function (e) {
        if (anomalyPending && !anomalyCheck.checked) {
            e.preventDefault();
            anomalyBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            alert('⚠️ Please confirm the unusually large transaction amount before saving.');
        }
    });

    recCheck.addEventListener('change', () => {
        recBox.style.display = recCheck.checked ? 'flex' : 'none';
    });

    function debounce(fn, ms) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), ms);
        };
    }
})();
</script>
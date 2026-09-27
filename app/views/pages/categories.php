<?php
declare(strict_types=1);
$emojis = ['💵','💼','🎓','🎁','💰','🍔','🚌','🏠','📚','📺','🎬','🧾','🛒','☕','🎮','📱','🚕','🏥','✈️','👟','📷','🎧','💊','🐾'];
function renderCategoryCard(array $group, string $type): void {
    $tint = $type === 'income' ? 'rgba(45,212,167,.25)' : 'rgba(251,113,133,.25)';
    $label = $type === 'income' ? 'Income' : 'Expense';
    $labelIcon = $type === 'income' ? Helper::icon('income', '15') : Helper::icon('expense', '15');
?>
    <div class="card" style="border-color:<?= $tint ?>;">
        <div class="card-title"><?= $labelIcon ?> <?= $label ?> categories <span class="badge badge-faint"><?= count($group) ?></span></div>
        <div class="flex" style="flex-direction:column;gap:.6rem;">
            <?php foreach ($group as $cat): ?>
            <div class="flex items-center between gap-1" style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:.6rem .9rem;">
                <div class="flex items-center gap-1">
                    <span style="font-size:1.15rem;"><?= Helper::e($cat['icon']) ?></span>
                    <span class="bold" style="font-size:.92rem;"><?= Helper::e($cat['name']) ?></span>
                    <?php if ($cat['is_default']): ?>
                        <span class="badge badge-info" style="font-size:.65rem;">SYSTEM</span>
                    <?php else: ?>
                        <span class="badge badge-brand" style="font-size:.65rem;">MY CATEGORY</span>
                    <?php endif; ?>
                </div>
                <?php if (!$cat['is_default']): ?>
                <div class="flex gap-1">
                    <button class="icon-btn" title="Edit" data-edit-cat data-id="<?= (int) $cat['category_id'] ?>" data-name="<?= Helper::e($cat['name']) ?>" data-icon="<?= Helper::e($cat['icon']) ?>" data-type="<?= Helper::e($cat['type']) ?>"><?= Helper::icon('edit', '17') ?></button>
                    <form method="post" action="<?= Helper::url('categories/delete/' . (int) $cat['category_id']) ?>" onsubmit="return confirm('Delete category &quot;<?= Helper::e($cat['name']) ?>&quot;?');">
                        <?= Helper::csrfField() ?>
                        <button class="icon-btn" title="Delete" style="color:var(--danger);"><?= Helper::icon('trash', '17') ?></button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="form-hint mt-2">System categories are locked and shared with all students. Create your own below.</div>
    </div>
<?php } ?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('dashboard') ?>">Dashboard</a><span class="sep">/</span><span class="current">Categories</span></div>
        <h1><?= Helper::icon('tags', '26') ?> Category Manager</h1>
        <p class="muted small">Organize your money with system defaults and your own custom categories.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" data-add-category><?= Helper::icon('plus', '15') ?> Add Category</button>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;" id="category-grid">
    <?php
    renderCategoryCard($income, 'income');
    renderCategoryCard($expense, 'expense');
    ?>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal-mask" id="category-modal" hidden aria-hidden="true">
    <div class="modal">
        <div class="modal-head">
            <h3 id="category-modal-title">Add Category</h3>
            <button class="modal-close" data-modal-close><?= Helper::icon('x', '17') ?></button>
        </div>
        <form method="post" action="<?= Helper::url('categories/add') ?>" id="category-form">
            <?= Helper::csrfField() ?>
            <input type="hidden" name="type" id="category-type" value="expense">
            <div class="pill-select" style="margin-bottom:1.1rem;">
                <label class="pill"><input type="radio" name="type-pill" value="expense" checked> <?= Helper::icon('expense', '13') ?> Expense</label>
                <label class="pill"><input type="radio" name="type-pill" value="income"> <?= Helper::icon('income', '13') ?> Income</label>
            </div>
            <div class="field">
                <label for="category-name">Category name</label>
                <input class="input" type="text" id="category-name" name="name" placeholder="e.g. Coffee runs" required minlength="2" maxlength="50">
            </div>
            <div class="field">
                <label>Icon</label>
                <input type="hidden" name="icon" id="category-icon" value="📌" required>
                <div class="flex wrap gap-1" id="emoji-picker">
                    <?php foreach ($emojis as $ei): ?>
                    <button type="button" class="emoji-opt" data-emoji="<?= Helper::e($ei) ?>" style="border:1px solid var(--border);background:var(--surface);border-radius:10px;width:40px;height:40px;font-size:1.2rem;cursor:pointer;transition:all .15s;"><?= Helper::e($ei) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="flex gap-1 mt-2" style="justify-content:flex-end;">
                <button type="button" class="btn btn-ghost" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Category modal is fully hidden until Add/Edit is clicked. */
#category-modal[hidden] { display: none !important; }
#category-modal:not([hidden]) { display: flex; }
#category-grid { grid-template-columns: 1fr 1fr; }
@media (max-width: 900px) { #category-grid { grid-template-columns: 1fr; } }
</style>

<script>
(function () {
    'use strict';

    const modal = document.getElementById('category-modal');
    const form = document.getElementById('category-form');
    const nameInput = document.getElementById('category-name');
    const typeInput = document.getElementById('category-type');
    const iconInput = document.getElementById('category-icon');
    const picker = document.getElementById('emoji-picker');
    const title = document.getElementById('category-modal-title');

    // Category controls are self-contained so they work even if another
    // global script fails or is unavailable.
    if (!modal || !form || !nameInput || !typeInput || !iconInput || !picker) return;

    const addAction = <?= json_encode(Helper::url('categories/add')) ?>;
    const editBase = <?= json_encode(Helper::url('categories/edit/')) ?>;

    function closeCategoryModal() {
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        // Keep the modal out of the page entirely when it is not in use.
        setTimeout(function () {
            if (!modal.classList.contains('show')) {
                modal.hidden = true;
            }
        }, 320);
    }

    function setType(type) {
        const safeType = type === 'income' ? 'income' : 'expense';
        typeInput.value = safeType;

        form.querySelectorAll('input[name="type-pill"]').forEach(function (input) {
            const checked = input.value === safeType;
            input.checked = checked;
            const pill = input.closest('.pill');
            if (pill) pill.classList.toggle('active', checked);
        });
    }

    function selectIcon(icon) {
        const selectedIcon = icon || '📌';
        iconInput.value = selectedIcon;

        picker.querySelectorAll('.emoji-opt').forEach(function (button) {
            const selected = button.dataset.emoji === selectedIcon;
            button.classList.toggle('selected', selected);
            button.style.borderColor = selected ? 'var(--brand)' : 'var(--border)';
            button.style.background = selected ? 'rgba(247,183,51,.15)' : 'var(--surface)';
            button.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });
    }

    function prepareSubmitButton() {
        const submit = form.querySelector('button[type="submit"]');
        if (!submit) return;
        submit.disabled = false;
        submit.textContent = 'Save Category';
    }

    function resetForm() {
        form.reset();
        form.action = addAction;
        form.dataset.submitting = '';
        if (title) title.textContent = 'Add Category';
        prepareSubmitButton();
        setType('expense');
        selectIcon('📌');
    }

    function openCategoryModal() {
        resetForm();
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        // Wait one frame so the CSS transition always starts from the hidden state.
        requestAnimationFrame(function () {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            setTimeout(function () { nameInput.focus(); }, 60);
        });
    }

    function openEditModal(button) {
        form.reset();
        form.dataset.submitting = '';
        prepareSubmitButton();
        form.action = editBase + encodeURIComponent(button.dataset.id || '');
        if (title) title.textContent = 'Edit Category';
        nameInput.value = button.dataset.name || '';
        setType(button.dataset.type || 'expense');
        selectIcon(button.dataset.icon || '📌');
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        requestAnimationFrame(function () {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            setTimeout(function () {
                nameInput.focus();
                nameInput.select();
            }, 60);
        });
    }

    // IMPORTANT: the Add/Edit modal must never appear automatically on page load.
    // It is opened only after the user clicks Add Category or Edit.
    modal.classList.remove('show');
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    // Add Category.
    document.querySelectorAll('[data-add-category]').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            openCategoryModal();
        });
    });

    // Edit Category.
    document.querySelectorAll('[data-edit-cat]').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            openEditModal(button);
        });
    });

    // Income / Expense selector.
    form.querySelectorAll('input[name="type-pill"]').forEach(function (input) {
        input.addEventListener('change', function () {
            setType(input.value);
        });
    });

    // Every icon is a real button. Delegation makes the whole icon picker reliable.
    picker.addEventListener('click', function (event) {
        const button = event.target.closest('.emoji-opt');
        if (!button || !picker.contains(button)) return;
        event.preventDefault();
        event.stopPropagation();
        selectIcon(button.dataset.emoji);
    });

    // X button, Cancel button, and backdrop.
    modal.addEventListener('click', function (event) {
        const closeButton = event.target.closest('[data-modal-close]');
        if (closeButton && modal.contains(closeButton)) {
            event.preventDefault();
            closeCategoryModal();
            return;
        }
        if (event.target === modal) {
            closeCategoryModal();
        }
    });

    // Escape key.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('show')) {
            event.preventDefault();
            closeCategoryModal();
        }
    });

    // Normal PHP form POST. No fetch/AJAX dependency here.
    form.addEventListener('submit', function (event) {
        nameInput.value = nameInput.value.trim();

        const selectedType = form.querySelector('input[name="type-pill"]:checked');
        typeInput.value = selectedType ? selectedType.value : 'expense';
        if (!iconInput.value) iconInput.value = '📌';

        // Let native HTML validation handle an invalid/empty name.
        if (!form.checkValidity()) return;

        // Prevent accidental double submissions only after validation passes.
        if (form.dataset.submitting === '1') {
            event.preventDefault();
            return;
        }

        form.dataset.submitting = '1';
        const submit = form.querySelector('button[type="submit"]');
        if (submit) {
            submit.disabled = true;
            submit.textContent = 'Saving…';
        }
    });

    // Compatibility for any existing markup or browser console testing.
    window.openCategoryModal = openCategoryModal;
    window.closeCategoryModal = closeCategoryModal;
    window.setCatType = setType;

    setType(typeInput.value || 'expense');
    selectIcon(iconInput.value || '📌');
})();
</script>
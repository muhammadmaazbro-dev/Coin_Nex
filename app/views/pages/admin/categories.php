<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('admin') ?>">Admin</a><span class="sep">/</span><span class="current">Default Categories</span></div>
        <h1><?= Helper::icon('tags', '26') ?> Default Categories Management</h1>
        <p class="muted small">Control platform default income and expense categories available to all students.</p>
    </div>
    <div class="flex gap-1 wrap">
        <a href="<?= Helper::url('admin') ?>" class="btn btn-ghost btn-sm"><?= Helper::icon('chevron-left', '15') ?> Admin Dashboard</a>
    </div>
</div>

<div class="stagger" style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.4rem;">
    <!-- Categories List -->
    <div class="card animate-up">
        <div class="card-title"><?= Helper::icon('list', '18') ?> Platform Categories (<?= count($defaultCategories) ?>)</div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Type</th>
                        <th style="text-align:right;">Usage Count</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($defaultCategories)): ?>
                    <tr><td colspan="4" class="text-center muted" style="padding:2rem;">No default categories found.</td></tr>
                <?php else: foreach ($defaultCategories as $cat): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-1">
                                <span class="avatar-circle" style="width:34px;height:34px;font-size:1.1rem;flex:none;"><?= Helper::e($cat['icon']) ?></span>
                                <span class="bold"><?= Helper::e($cat['name']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?= $cat['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>"><?= ucfirst($cat['type']) ?></span>
                        </td>
                        <td style="text-align:right;" class="muted">
                            <?= (int) $cat['usage_count'] ?> txns
                        </td>
                        <td style="text-align:right;">
                            <button type="button" class="btn btn-ghost btn-sm" onclick="openEditModal(<?= (int)$cat['category_id'] ?>, '<?= Helper::e(addslashes($cat['name'])) ?>', '<?= Helper::e(addslashes($cat['icon'])) ?>')">
                                <?= Helper::icon('edit', '14') ?>
                            </button>
                            <?php if ((int)$cat['usage_count'] === 0): ?>
                            <form method="post" action="<?= Helper::url('admin/categoryDelete/' . (int) $cat['category_id']) ?>" class="inline" onsubmit="return confirm('Delete this system category?');">
                                <?= Helper::csrfField() ?>
                                <button type="submit" class="btn btn-danger btn-sm"><?= Helper::icon('trash', '14') ?></button>
                            </form>
                            <?php else: ?>
                            <span class="badge" title="Cannot delete category in use" style="opacity:.6;"><?= Helper::icon('lock', '12') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Category Form -->
    <div class="card animate-up" style="animation-delay:.05s;">
        <div class="card-title"><?= Helper::icon('plus', '18') ?> Add Default Category</div>
        <form method="post" action="<?= Helper::url('admin/categoryAdd') ?>">
            <?= Helper::csrfField() ?>
            <div class="field">
                <label>Category Type</label>
                <div class="pill-select">
                    <label class="pill active"><input type="radio" name="type" value="expense" checked> Expense</label>
                    <label class="pill"><input type="radio" name="type" value="income"> Income</label>
                </div>
            </div>

            <div class="field">
                <label>Category Name</label>
                <input type="text" name="name" class="input" placeholder="e.g. Tuition, Gym, Freelancing" required minlength="2" maxlength="50">
            </div>

            <div class="field">
                <label>Emoji / Icon</label>
                <div class="flex gap-1 items-center">
                    <input type="text" name="icon" id="new-icon-input" class="input" value="📌" style="width:70px;text-align:center;font-size:1.4rem;" required maxlength="10">
                    <div class="flex gap-1 wrap">
                        <?php foreach (['🍔','🚌','🏠','📚','📺','🎬','🧾','💰','💼','🎓','🎁','✨','🛒','🏋️','🏥','✈️'] as $em): ?>
                        <button type="button" class="btn btn-ghost btn-sm" style="padding:.2rem .4rem;font-size:1.1rem;" onclick="document.getElementById('new-icon-input').value='<?= $em ?>'"><?= $em ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3"><?= Helper::icon('plus', '16') ?> Create System Category</button>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="cat-edit-modal" class="modal-backdrop" style="display:none;">
    <div class="modal card animate-up" style="max-width:440px;">
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('edit', '18') ?> Edit Category</span>
            <button type="button" class="icon-btn" onclick="closeEditModal()"><?= Helper::icon('x', '16') ?></button>
        </div>
        <form method="post" id="cat-edit-form" action="">
            <?= Helper::csrfField() ?>
            <div class="field">
                <label>Category Name</label>
                <input type="text" name="name" id="modal-cat-name" class="input" required minlength="2" maxlength="50">
            </div>
            <div class="field">
                <label>Emoji / Icon</label>
                <input type="text" name="icon" id="modal-cat-icon" class="input" style="width:70px;text-align:center;font-size:1.4rem;" required maxlength="10">
            </div>
            <div class="flex gap-1 mt-3" style="justify-content:flex-end;">
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, icon) {
    document.getElementById('cat-edit-form').action = '<?= Helper::url('admin/categoryEdit/') ?>' + id;
    document.getElementById('modal-cat-name').value = name;
    document.getElementById('modal-cat-icon').value = icon;
    document.getElementById('cat-edit-modal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('cat-edit-modal').style.display = 'none';
}
</script>

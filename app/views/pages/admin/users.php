<?php
declare(strict_types=1);
?>
<div class="flex between wrap gap-2 items-center mb-3">
    <div>
        <div class="breadcrumbs"><a href="<?= Helper::url('admin') ?>">Admin</a><span class="sep">/</span><span class="current">Users</span></div>
        <h1><?= Helper::icon('user', '26') ?> User Management</h1>
        <p class="muted small">Search, inspect, reset passwords and moderate accounts.</p>
    </div>
    <form method="get" action="<?= Helper::url('admin/users') ?>" class="flex gap-1 items-center">
        <input type="search" name="q" class="input" placeholder="Search name or email…" value="<?= Helper::e(Helper::get('q', '')) ?>">
        <button class="btn btn-primary btn-sm" type="submit"><?= Helper::icon('search', '14') ?> Search</button>
    </form>
</div>

<div class="card animate-up">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th><th>Role</th><th>Status</th><th>Transactions</th><th>Income</th><th>Spending</th><th>Last login</th><th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr><td colspan="8" class="text-center muted small" style="padding:2rem;">No users found.</td></tr>
                <?php endif; ?>
                <?php foreach ($rows as $u): ?>
                <?php $t = $totals[$u['user_id']] ?? ['txns' => 0, 'income' => 0.0, 'expense' => 0.0]; ?>
                <tr>
                    <td>
                        <div class="bold"><?= Helper::e($u['name']) ?> <?= (int) $u['user_id'] === (int) Helper::currentUser()['user_id'] ? '<span class="faint small">(you)</span>' : '' ?></div>
                        <div class="faint small"><?= Helper::e($u['email']) ?></div>
                    </td>
                    <td>
                        <div class="flex items-center" style="gap:.4rem;">
                            <span class="badge <?= $u['role'] === 'admin' ? 'badge-brand' : '' ?>"><?= Helper::e($u['role']) ?></span>
                            <?php if ($u['role'] !== 'admin' || (int) $u['user_id'] === (int) Helper::currentUser()['user_id']): ?>
                            <form method="post" action="<?= Helper::url('admin/toggleRole/' . (int) $u['user_id']) ?>">
                                <?= Helper::csrfField() ?>
                                <button class="btn btn-ghost btn-sm" type="submit" title="Toggle role" style="padding:.2rem .4rem;"><?= Helper::icon('swap', '13') ?></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($u['role'] !== 'admin'): ?>
                        <form method="post" action="<?= Helper::url('admin/toggleActive/' . (int) $u['user_id']) ?>">
                            <?= Helper::csrfField() ?>
                            <button type="submit" class="badge-btn <?= $u['is_active'] ? 'badge-ok' : 'badge-danger' ?>" title="Click to toggle"><?= $u['is_active'] ? 'Active' : 'Disabled' ?></button>
                        </form>
                        <?php else: ?>
                        <span class="badge <?= $u['is_active'] ? 'badge-ok' : 'badge-danger' ?>"><?= $u['is_active'] ? 'Active' : 'Disabled' ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="small"><?= number_format((int) $t['txns']) ?></td>
                    <td class="small text-success"><?= Helper::money((float) $t['income']) ?></td>
                    <td class="small text-danger"><?= Helper::money((float) $t['expense']) ?></td>
                    <td class="small muted"><?= $u['last_login'] ? date('d M Y', strtotime($u['last_login'])) : '—' ?></td>
                    <td class="small" style="text-align:right;white-space:nowrap;">
                        <a href="<?= Helper::url('admin/user/' . (int) $u['user_id']) ?>" class="btn btn-ghost btn-sm" title="View Detail"><?= Helper::icon('eye', '14') ?> View</a>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="openResetModal(<?= (int)$u['user_id'] ?>, '<?= Helper::e(addslashes($u['name'])) ?>')" title="Reset Password">
                            <?= Helper::icon('lock', '14') ?> Reset
                        </button>
                        <?php if ($u['role'] !== 'admin'): ?>
                        <form method="post" action="<?= Helper::url('admin/deleteUser/' . (int) $u['user_id']) ?>" class="inline" onsubmit="return confirm('Delete this user and ALL their data?');">
                            <?= Helper::csrfField() ?>
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Account"><?= Helper::icon('trash', '14') ?></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<p class="faint small mt-2">Deactivating a user blocks their login. Admin password reset instantly updates their login credentials.</p>

<!-- Reset Password Modal -->
<div id="reset-pwd-modal" class="modal-backdrop" style="display:none;">
    <div class="modal card animate-up" style="max-width:420px;">
        <div class="card-title flex between items-center">
            <span><?= Helper::icon('lock', '18') ?> Reset User Password</span>
            <button type="button" class="icon-btn" onclick="closeResetModal()"><?= Helper::icon('x', '16') ?></button>
        </div>
        <form method="post" id="reset-pwd-form" action="">
            <?= Helper::csrfField() ?>
            <p class="small muted mb-2">Setting a new password for <b id="reset-user-name">User</b>.</p>
            <div class="field">
                <label>New Temporary Password</label>
                <input type="text" name="new_password" class="input" value="Student@123" required minlength="6">
            </div>
            <div class="flex gap-1 mt-3" style="justify-content:flex-end;">
                <button type="button" class="btn btn-ghost" onclick="closeResetModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script>
function openResetModal(userId, name) {
    document.getElementById('reset-pwd-form').action = '<?= Helper::url('admin/resetPassword/') ?>' + userId;
    document.getElementById('reset-user-name').textContent = name;
    document.getElementById('reset-pwd-modal').style.display = 'flex';
}
function closeResetModal() {
    document.getElementById('reset-pwd-modal').style.display = 'none';
}
</script>

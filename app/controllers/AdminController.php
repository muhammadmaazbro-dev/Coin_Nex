<?php
declare(strict_types=1);

final class AdminController
{
    public function index(): void
    {
        Helper::requireAdmin();
        $userId = Helper::userId();

        $totalUsers       = (int) Database::scalar('SELECT COUNT(*) FROM users');
        $activeUsers      = (int) Database::scalar('SELECT COUNT(*) FROM users WHERE is_active = 1');
        $studentCount     = (int) Database::scalar('SELECT COUNT(*) FROM users WHERE role = "student"');
        $adminCount       = (int) Database::scalar('SELECT COUNT(*) FROM users WHERE role = "admin"');
        $newUsersToday    = (int) Database::scalar('SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()');

        $totalTxns        = (int) Database::scalar('SELECT COUNT(*) FROM transactions');
        $totalSpend       = (float) Database::scalar('SELECT COALESCE(SUM(amount),0) FROM transactions WHERE type = "expense"');
        $totalIncome      = (float) Database::scalar('SELECT COALESCE(SUM(amount),0) FROM transactions WHERE type = "income"');
        $incomeTxnCount   = (int) Database::scalar('SELECT COUNT(*) FROM transactions WHERE type = "income"');
        $expenseTxnCount  = (int) Database::scalar('SELECT COUNT(*) FROM transactions WHERE type = "expense"');

        $totalCategories  = (int) Database::scalar('SELECT COUNT(*) FROM categories');
        $totalBudgets     = (int) Database::scalar('SELECT COUNT(*) FROM budgets');
        $totalTips        = (int) Database::scalar('SELECT COUNT(*) FROM tips');
        $announcementCount = (int) Database::scalar('SELECT COUNT(*) FROM announcements');

        $signupsByMonth = Database::fetchAll(
            'SELECT DATE_FORMAT(created_at, "%Y-%m") AS ym, COUNT(*) AS n
               FROM users
              GROUP BY ym ORDER BY ym DESC LIMIT 6'
        );

        $txnsByMonth = Database::fetchAll(
            'SELECT DATE_FORMAT(transaction_date, "%Y-%m") AS ym,
                    SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) AS income,
                    SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) AS expense
               FROM transactions
              GROUP BY ym ORDER BY ym DESC LIMIT 6'
        );

        $topCategories = Database::fetchAll(
            'SELECT c.name, c.icon, COUNT(t.transaction_id) AS n, COALESCE(SUM(t.amount),0) AS total
               FROM categories c JOIN transactions t ON t.category_id = c.category_id AND t.type = "expense"
              GROUP BY c.category_id ORDER BY total DESC LIMIT 6'
        );

        $mostUsedCategory = !empty($topCategories) ? $topCategories[0]['name'] : 'Food';

        $recentUsers = Database::fetchAll(
            'SELECT user_id, name, email, role, is_active, last_login, created_at
               FROM users ORDER BY created_at DESC LIMIT 8'
        );

        $recentTxns = Database::fetchAll(
            'SELECT t.*, u.name AS user_name, c.name AS category_name, c.icon AS category_icon
               FROM transactions t
               JOIN users u ON u.user_id = t.user_id
               JOIN categories c ON c.category_id = t.category_id
              ORDER BY t.created_at DESC LIMIT 8'
        );

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        $view = 'pages/admin/index';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function users($search = ''): void
    {
        Helper::requireAdmin();
        $userId = Helper::userId();
        $search = trim(preg_replace('/[^a-zA-Z0-9@ ._\-]/', '', (string) Helper::get('q', $search)));

        if ($search !== '') {
            $rows = Database::fetchAll(
                'SELECT user_id, name, email, role, is_active, last_login, created_at
                   FROM users
                  WHERE name LIKE ? OR email LIKE ?
                  ORDER BY created_at DESC',
                ['%' . $search . '%', '%' . $search . '%']
            );
        } else {
            $rows = User::recent(200);
        }

        $totals = [];
        foreach ($rows as $row) {
            $totals[$row['user_id']] = [
                'txns'    => (int) Database::scalar('SELECT COUNT(*) FROM transactions WHERE user_id = ?', [$row['user_id']]),
                'income'  => (float) Database::scalar('SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "income"', [$row['user_id']]),
                'expense' => (float) Database::scalar('SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "expense"', [$row['user_id']]),
            ];
        }

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        $view = 'pages/admin/users';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function user($id = 0): void
    {
        Helper::requireAdmin();
        $userId = (int) $id;
        if ($userId <= 0) {
            Helper::abort('Invalid user', 404);
        }
        $profile = User::findById($userId);
        if (!$profile) {
            Helper::abort('User not found', 404);
        }
        $user = Helper::currentUser();

        $recentTxns = Database::fetchAll(
            'SELECT t.*, c.name AS category_name, c.icon AS category_icon
               FROM transactions t JOIN categories c ON c.category_id = t.category_id
              WHERE t.user_id = ?
              ORDER BY t.transaction_date DESC, t.transaction_id DESC LIMIT 20',
            [$userId]
        );
        $month = date('Y-m');
        $monthIncome = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "income" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $month]
        );
        $monthExpense = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "expense" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $month]
        );
        $allTime = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "expense"',
            [$userId]
        );
        $budgetCount = (int) Database::scalar('SELECT COUNT(*) FROM budgets WHERE user_id = ?', [$userId]);
        $recurringCount = (int) Database::scalar(
            'SELECT COUNT(*) FROM transactions WHERE user_id = ? AND is_recurring = 1',
            [$userId]
        );

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [(int) $user['user_id']]
        );

        $view = 'pages/admin/user-detail';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function resetPassword($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $userId = (int) $id;
        $target = User::findById($userId);
        if (!$target) {
            Helper::abort('User not found', 404);
        }

        $newPass = trim((string) Helper::post('new_password', 'Student@123'));
        if (strlen($newPass) < 6) {
            Helper::error('Password must be at least 6 characters.');
            Helper::redirect('admin/users');
        }

        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        Database::run('UPDATE users SET password_hash = ? WHERE user_id = ?', [$hash, $userId]);
        Helper::success("Password for {$target['name']} ({$target['email']}) reset to: $newPass");
        Helper::redirect('admin/users');
    }

    public function toggleActive($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $userId = (int) $id;
        $target = User::findById($userId);
        if (!$target) {
            Helper::abort('User not found', 404);
        }
        if ($target['role'] === 'admin') {
            Helper::error('Admins cannot be deactivated.');
            Helper::redirect('admin/users');
        }
        if (Helper::currentUser()['user_id'] === $userId) {
            Helper::error('You cannot deactivate yourself.');
            Helper::redirect('admin/users');
        }
        $newState = $target['is_active'] ? 0 : 1;
        User::update($userId, ['is_active' => $newState]);
        Helper::success($newState ? 'Account activated.' : 'Account deactivated.');
        Helper::redirect('admin/users');
    }

    public function toggleRole($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $userId = (int) $id;
        $target = User::findById($userId);
        if (!$target) {
            Helper::abort('User not found', 404);
        }
        if ($target['role'] === 'admin') {
            if (Helper::currentUser()['user_id'] !== $userId) {
                Helper::error('Only admins can demote themselves.');
                Helper::redirect('admin/users');
            }
            $newRole = 'student';
        } else {
            $newRole = 'admin';
        }
        Database::run('UPDATE users SET role = ? WHERE user_id = ?', [$newRole, $userId]);
        Helper::success('Role updated.');
        Helper::redirect('admin/users');
    }

    public function deleteUser($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $userId = (int) $id;
        $target = User::findById($userId);
        if (!$target) {
            Helper::abort('User not found', 404);
        }
        if ($userId === Helper::currentUser()['user_id']) {
            Helper::error('You cannot delete your own account.');
            Helper::redirect('admin/users');
        }
        if ($target['role'] === 'admin') {
            Helper::error('Admins must be demoted to student before deletion.');
            Helper::redirect('admin/users');
        }

        $tables = ['notifications', 'insights', 'tips', 'budgets', 'password_resets', 'transactions', 'user_category_rules', 'activity_logs'];
        foreach ($tables as $table) {
            try {
                Database::run("DELETE FROM `$table` WHERE user_id = ?", [$userId]);
            } catch (\Throwable) {}
        }
        Database::run('DELETE FROM users WHERE user_id = ?', [$userId]);
        Helper::success('User permanently deleted along with their data.');
        Helper::redirect('admin/users');
    }

    // -------------------------------------------------------------
    // ADMIN CATEGORIES MANAGEMENT
    // -------------------------------------------------------------
    public function categories(): void
    {
        Helper::requireAdmin();
        $userId = Helper::userId();

        $defaultCategories = Database::fetchAll(
            'SELECT c.*, COUNT(t.transaction_id) AS usage_count
               FROM categories c
               LEFT JOIN transactions t ON t.category_id = c.category_id
              WHERE c.is_default = 1
              GROUP BY c.category_id
              ORDER BY c.type DESC, c.name ASC'
        );

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        $view = 'pages/admin/categories';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function categoryAdd(): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $name = trim((string) Helper::post('name'));
        $type = (string) Helper::post('type');
        $icon = trim((string) Helper::post('icon')) ?: '📌';

        if ($type !== 'income' && $type !== 'expense') {
            Helper::error('Invalid category type.');
            Helper::redirect('admin/categories');
        }
        if (mb_strlen($name) < 2 || mb_strlen($name) > 50) {
            Helper::error('Category name must be 2–50 characters.');
            Helper::redirect('admin/categories');
        }

        Database::run(
            'INSERT INTO categories (name, type, icon, is_default, user_id) VALUES (?, ?, ?, 1, NULL)',
            [$name, $type, $icon]
        );
        Helper::success("System category '$name' added successfully.");
        Helper::redirect('admin/categories');
    }

    public function categoryEdit($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;

        $cat = Database::fetch('SELECT * FROM categories WHERE category_id = ? AND is_default = 1', [$id]);
        if (!$cat) {
            Helper::error('Category not found.');
            Helper::redirect('admin/categories');
        }

        $name = trim((string) Helper::post('name'));
        $icon = trim((string) Helper::post('icon')) ?: '📌';

        Database::run(
            'UPDATE categories SET name = ?, icon = ? WHERE category_id = ?',
            [$name, $icon, $id]
        );
        Helper::success("System category '$name' updated.");
        Helper::redirect('admin/categories');
    }

    public function categoryDelete($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;

        $usage = (int) Database::scalar('SELECT COUNT(*) FROM transactions WHERE category_id = ?', [$id]);
        if ($usage > 0) {
            Helper::error("Cannot delete category — it is used in $usage transaction(s).");
            Helper::redirect('admin/categories');
        }

        Database::run('DELETE FROM categories WHERE category_id = ? AND is_default = 1', [$id]);
        Helper::success('System category deleted.');
        Helper::redirect('admin/categories');
    }

    // -------------------------------------------------------------
    // ANNOUNCEMENTS MANAGEMENT
    // -------------------------------------------------------------
    public function announcements(): void
    {
        Helper::requireAdmin();
        $userId = Helper::userId();

        $announcements = Database::fetchAll(
            'SELECT * FROM announcements ORDER BY created_at DESC'
        );

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        $view = 'pages/admin/announcements';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function announcementAdd(): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $title = trim((string) Helper::post('title'));
        $body = trim((string) Helper::post('body'));

        if (mb_strlen($title) < 3 || mb_strlen($body) < 5) {
            Helper::error('Please provide a valid announcement title and message.');
            Helper::redirect('admin/announcements');
        }

        Database::run(
            'INSERT INTO announcements (title, body, created_at) VALUES (?, ?, NOW())',
            [$title, $body]
        );

        // Also broadcast an in-app notification to all students
        $students = Database::fetchAll('SELECT user_id FROM users WHERE role = "student"');
        foreach ($students as $st) {
            Database::run(
                'INSERT INTO notifications (user_id, message, type, related_type, notif_key, link) VALUES (?, ?, "info", "announcement", ?, "dashboard")',
                [$st['user_id'], '📢 Announcement: ' . $title, 'announcement-' . time()]
            );
        }

        Helper::success('Announcement broadcasted to all students successfully.');
        Helper::redirect('admin/announcements');
    }

    public function announcementDelete($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;

        Database::run('DELETE FROM announcements WHERE announcement_id = ?', [$id]);
        Helper::success('Announcement removed.');
        Helper::redirect('admin/announcements');
    }

    // -------------------------------------------------------------
    // TIPS MANAGEMENT
    // -------------------------------------------------------------
    public function tips(): void
    {
        Helper::requireAdmin();
        $userId = Helper::userId();

        $tips = Database::fetchAll(
            'SELECT t.*, c.name AS category_name, c.icon AS category_icon, u.name AS user_name
               FROM tips t
               LEFT JOIN categories c ON c.category_id = t.category_id
               LEFT JOIN users u ON u.user_id = t.user_id
              ORDER BY t.created_at DESC LIMIT 50'
        );

        $categories = Database::fetchAll('SELECT * FROM categories WHERE is_default = 1 AND type = "expense"');

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        $view = 'pages/admin/tips';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function tipAdd(): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $text = trim((string) Helper::post('tip_text'));
        $catId = (int) Helper::post('category_id', 0);
        $score = (float) Helper::post('rank_score', 80.0);

        if (mb_strlen($text) < 10) {
            Helper::error('Tip content must be at least 10 characters.');
            Helper::redirect('admin/tips');
        }

        // Add to active students
        $students = Database::fetchAll('SELECT user_id FROM users WHERE role = "student"');
        foreach ($students as $st) {
            Database::run(
                'INSERT INTO tips (user_id, category_id, tip_text, rank_score, is_pinned, is_dismissed, bookmarked) VALUES (?, ?, ?, ?, 0, 0, 0)',
                [$st['user_id'], $catId > 0 ? $catId : null, $text, $score]
            );
        }

        Helper::success('Saving tip added to student tips pool.');
        Helper::redirect('admin/tips');
    }

    public function tipDelete($id = 0): never
    {
        Helper::requireAdmin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;

        Database::run('DELETE FROM tips WHERE tip_id = ?', [$id]);
        Helper::success('Tip removed.');
        Helper::redirect('admin/tips');
    }
}

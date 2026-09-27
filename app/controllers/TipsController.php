<?php
declare(strict_types=1);

final class TipsController
{
    public function index(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $month = date('Y-m');
        $filter = (string) Helper::get('view', 'active');
        if (!in_array($filter, ['active', 'bookmarks', 'dismissed'], true)) {
            $filter = 'active';
        }

        $lastGenerated = (string) Database::scalar(
            "SELECT MAX(DATE_FORMAT(created_at, '%Y-%m')) FROM tips WHERE user_id = ?",
            [$userId]
        );
        if ($lastGenerated != $month) {
            Tips::clearAll($userId);
            Tips::generateForUser($userId, $month);
        }

        $tips = Tips::allForUser($userId, $filter);
        $tipCategories = [];
        foreach ($tips as $tip) {
            $tipCategories[$tip['tip_id']] = $tip['category_id'] ? Category::findById((int) $tip['category_id']) : null;
        }

        $unreadNotifications = self::unreadCount();
        $view = 'pages/tips';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function refresh(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Tips::clearAll(Helper::userId());
        Tips::generateForUser(Helper::userId(), date('Y-m'));
        Helper::success('Tips regenerated based on your latest spending.');
        Helper::redirect('tips');
    }

    public function bookmark($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Tips::bookmark(Helper::userId(), (int) $id);
        Helper::redirect('tips?view=' . rawurlencode((string) Helper::get('view', 'active')));
    }

    public function pin($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Tips::togglePin(Helper::userId(), (int) $id);
        Helper::redirect('tips?view=' . rawurlencode((string) Helper::get('view', 'active')));
    }

    public function dismiss($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Tips::dismiss(Helper::userId(), (int) $id);
        Helper::redirect('tips?view=active');
    }

    public function restore($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Tips::restore(Helper::userId(), (int) $id);
        Helper::redirect('tips?view=dismissed');
    }

    private static function unreadCount(): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
    }
}
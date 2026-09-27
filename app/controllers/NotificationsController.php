<?php
declare(strict_types=1);

final class NotificationsController
{
    public function index(): void
    {
        Helper::requireLogin();
        $list = Database::fetchAll(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY is_read ASC, created_at DESC',
            [Helper::userId()]
        );
        $unreadCount = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
        $view = 'pages/notifications';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function markAllRead(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Database::run('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0', [Helper::userId()]);
        Helper::success('All notifications marked as read.');
        Helper::redirect('notifications');
    }

    public function markRead(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Database::run(
            'UPDATE notifications SET is_read = 1 WHERE user_id = ? AND notification_id = ?',
            [Helper::userId(), (int) Helper::get('id', 0)]
        );
        Helper::json(['ok' => true]);
    }

    public function fetch(): never
    {
        Helper::requireLogin();
        $list = Database::fetchAll(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 8',
            [Helper::userId()]
        );
        $icons = [
            'budget' => '🎯',
            'info'   => '💬',
            'flag'   => '🚩',
        ];
        $out = array_map(static function (array $n) use ($icons): array {
            return [
                'link'    => Helper::url($n['link'] ?? 'notifications'),
                'icon'    => $icons[$n['type']] ?? '🔔',
                'message' => $n['message'],
                'when'    => Helper::timeAgo($n['created_at']),
            ];
        }, $list);
        Helper::json(['list' => $out]);
    }
}
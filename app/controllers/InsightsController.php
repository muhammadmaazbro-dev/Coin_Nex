<?php
declare(strict_types=1);

final class InsightsController
{
    public function index(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $month = date('Y-m');

        Insights::generateForUser($userId, $month);
        $latest = Insights::latest($userId);
        $history = Insights::byUser($userId);
        $aiWarning = false;

        $unreadNotifications = self::unreadCount();
        $view = 'pages/insights';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function pin($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        Insights::togglePin(Helper::userId(), (int) $id);
        Helper::success('Insight pin toggled.');
        Helper::redirect('insights');
    }

    public function chat(): never
    {
        Helper::requireLogin();
        $question = trim((string) Helper::get('q', ''));
        if ($question === '') {
            Helper::json(['ok' => false, 'reply' => 'Say something first. 😊'], 400);
        }
        $answer = Insights::chat($question, Helper::userId());
        Helper::json([
            'ok'     => true,
            'reply'  => $answer['answer'] ?? 'Sorry, I did not get that. Try a money question.',
            'quick'  => $answer['quick'] ?? [],
        ]);
    }

    private static function unreadCount(): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
    }
}
<?php
declare(strict_types=1);

final class BudgetsController
{
    public function index(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $month = (string) Helper::get('month', date('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }
        Budget::applyAlerts($userId, $month);

        $budgets = Budget::byUserMonth($userId, $month);
        $incomeCategories = Category::byTypeForUser($userId, 'income');
        $expenseCategories = Category::byTypeForUser($userId, 'expense');

        $spendByCategory = [];
        foreach ($budgets as $b) {
            $spendByCategory[$b['category_id']] = (float) $b['spent'];
        }

        $totalBudget = array_sum(array_map(static fn ($b) => (float) $b['limit_amount'], $budgets));
        $totalSpent = array_sum($spendByCategory);
        $avgPct = $totalBudget > 0 ? (int) round($totalSpent / $totalBudget * 100) : 0;

        $unreadNotifications = self::unreadCount();
        $view = 'pages/budgets';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function create(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $userId = Helper::userId();
        $month = (string) Helper::post('month', date('Y-m'));
        $categoryId = (int) Helper::post('category_id', 0);
        $limit = (float) Helper::post('limit_amount', 0);
        $threshold = (float) Helper::post('alert_threshold', 80);

        if ($limit <= 0) {
            Helper::error('Budget limit must be greater than zero.');
            Helper::redirect('budgets?month=' . rawurlencode($month));
        }
        if ($categoryId <= 0 || Category::findById($categoryId) === null) {
            Helper::error('Please choose a valid expense category.');
            Helper::redirect('budgets?month=' . rawurlencode($month));
        }
        $threshold = Helper::clamp($threshold, 10, 100);

        if (!Budget::create($userId, $categoryId, $month, $limit, $threshold)) {
            Helper::error('A budget for that category already exists in this month.');
        } else {
            Helper::success('Budget set for ' . date('F Y', strtotime($month . '-01')) . '.');
        }
        Helper::redirect('budgets?month=' . rawurlencode($month));
    }

    public function update($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $userId = Helper::userId();
        $id = (int) $id;
        $limit = (float) Helper::post('limit_amount', 0);
        $threshold = (float) Helper::post('alert_threshold', 80);
        $month = (string) Helper::get('month', date('Y-m'));

        if ($limit <= 0) {
            Helper::error('Budget limit must be greater than zero.');
            Helper::redirect('budgets?month=' . rawurlencode($month));
        }
        $threshold = Helper::clamp($threshold, 10, 100);
        Budget::update($userId, $id, $limit, $threshold);
        Helper::success('Budget updated.');
        Helper::redirect('budgets?month=' . rawurlencode($month));
    }

    public function delete($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $month = (string) Helper::get('month', date('Y-m'));
        Budget::deleteOwned(Helper::userId(), (int) $id);
        Helper::success('Budget removed.');
        Helper::redirect('budgets?month=' . rawurlencode($month));
    }

    private static function unreadCount(): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
    }
}
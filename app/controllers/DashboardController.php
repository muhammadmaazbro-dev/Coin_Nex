<?php
declare(strict_types=1);

final class DashboardController
{
    public function index(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $user = Helper::currentUser();
        $month = date('Y-m');

        // Log recent view activity
        try {
            $lastAct = Database::scalar(
                'SELECT created_at FROM activity_logs WHERE user_id = ? AND entity_type = "dashboard" ORDER BY log_id DESC LIMIT 1',
                [$userId]
            );
            if (!$lastAct || (time() - strtotime((string)$lastAct)) > 1800) {
                Database::run(
                    'INSERT INTO activity_logs (user_id, action_type, entity_type, description) VALUES (?, "view", "dashboard", "Visited student dashboard")',
                    [$userId]
                );
            }
        } catch (\Throwable) {}

        $income  = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "income" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $month]
        );
        $expense = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "expense" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $month]
        );
        $balance = Helper::to2($income - $expense);
        $savings = max(0.0, $balance);
        $savingsRate = $income > 0 ? round(($income - $expense) / $income * 100) : 0;

        $series = $this->monthlySeries($userId);
        $byCategory = $this->expenseByCategory($userId, $month);
        $recent = Database::fetchAll(
            'SELECT t.*, c.name AS category_name, c.icon AS category_icon
               FROM transactions t JOIN categories c ON c.category_id = t.category_id
              WHERE t.user_id = ? ORDER BY t.transaction_date DESC, t.transaction_id DESC LIMIT 6',
            [$userId]
        );

        $topCategory = null;
        $topCategoryInsight = null;
        if (!empty($byCategory)) {
            usort($byCategory, static fn ($a, $b) => $b['total'] <=> $a['total']);
            $topCategory = $byCategory[0];

            // Calculate historical average for top category over preceding months
            $currentMonthStart = $month . '-01';
            $histAvg = (float) Database::scalar(
                'SELECT COALESCE(AVG(monthly_sum), 0) FROM (
                    SELECT SUM(amount) AS monthly_sum
                      FROM transactions
                     WHERE user_id = ? AND category_id = ? AND type = "expense"
                       AND transaction_date < ?
                     GROUP BY DATE_FORMAT(transaction_date, "%Y-%m")
                ) t',
                [$userId, $topCategory['category_id'], $currentMonthStart]
            );

            $currentSpend = (float) $topCategory['total'];
            if ($histAvg > 0) {
                $diff = $currentSpend - $histAvg;
                $pctGrowth = round(($diff / $histAvg) * 100);
                $weeklySaving = max(100.0, round(abs($diff) / 4 / 50) * 50); // rounded to nearest Rs. 50

                $topCategoryInsight = [
                    'category_name' => $topCategory['name'],
                    'category_icon' => $topCategory['icon'],
                    'current_spend' => $currentSpend,
                    'hist_avg'      => round($histAvg, 0),
                    'diff'          => $diff,
                    'pct_growth'    => $pctGrowth,
                    'weekly_saving' => $weeklySaving,
                    'is_above_avg'  => $diff > 0,
                ];
            } else {
                $topCategoryInsight = [
                    'category_name' => $topCategory['name'],
                    'category_icon' => $topCategory['icon'],
                    'current_spend' => $currentSpend,
                    'hist_avg'      => $currentSpend,
                    'diff'          => 0,
                    'pct_growth'    => 0,
                    'weekly_saving' => max(250.0, round($currentSpend * 0.15 / 4 / 50) * 50),
                    'is_above_avg'  => false,
                ];
            }
        }

        $daysElapsed = max(1, (int) date('j'));
        $dailyAvg = Helper::to2($expense / $daysElapsed);

        $biggest = Database::fetch(
            'SELECT t.amount, t.description, c.name AS category_name, c.icon AS category_icon
               FROM transactions t JOIN categories c ON c.category_id = t.category_id
              WHERE t.user_id = ? AND t.type = "expense" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?
              ORDER BY t.amount DESC LIMIT 1',
            [$userId, $month]
        );

        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        // Active Budgets
        $budgets = Database::fetchAll(
            'SELECT b.*, c.name AS category_name, c.icon AS category_icon
               FROM budgets b JOIN categories c ON c.category_id = b.category_id
              WHERE b.user_id = ? AND DATE_FORMAT(b.month,"%Y-%m") = ?
              ORDER BY b.limit_amount DESC',
            [$userId, date('Y-m')]
        );
        foreach ($budgets as $i => $budget) {
            $spent = (float) Database::scalar(
                'SELECT COALESCE(SUM(amount),0) FROM transactions
                  WHERE user_id = ? AND category_id = ? AND type = "expense"
                    AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
                [$userId, $budget['category_id'], date('Y-m')]
            );
            $budgets[$i]['spent'] = $spent;
            $budgets[$i]['pct'] = (float) $budget['limit_amount'] > 0
                ? (int) round($spent / (float) $budget['limit_amount'] * 100) : 0;
        }

        $insight = Insights::generateForUser($userId, $month);
        Budget::applyAlerts($userId, date('Y-m'));

        // System Announcements
        $announcements = Database::fetchAll(
            'SELECT * FROM announcements ORDER BY created_at DESC LIMIT 2'
        );

        // Personalized saving tips
        $tips = Database::fetchAll(
            'SELECT tip_id, tip_text, rank_score, bookmarked
               FROM tips
              WHERE user_id = ? AND is_dismissed = 0
              ORDER BY CASE WHEN bookmarked = 1 THEN 0 ELSE 1 END, rank_score DESC
              LIMIT 3',
            [$userId]
        );

        // Spending Forecast for upcoming month
        $recentExpenses = array_filter(array_slice($series['expense'], -3), static fn ($v) => $v > 0);
        $forecast = !empty($recentExpenses)
            ? round(array_sum($recentExpenses) / count($recentExpenses))
            : $expense;

        // Activity Logs
        $activities = Database::fetchAll(
            'SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 5',
            [$userId]
        );

        $daysInMonth = (int) date('t', strtotime($month . '-01'));
        $currentDay = (int) date('j');
        $daysLeft = max(1, $daysInMonth - $currentDay + 1);
        $safeDailySpend = max(0.0, round($balance / $daysLeft, 0));

        $view = 'pages/dashboard';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    private function monthlySeries(int $userId): array
    {
        $labels = [];
        $income = [];
        $expense = [];
        $firstDay = new DateTime(date('Y-m-01'));
        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $firstDay)->modify("-$i months");
            $labels[] = $date->format('M');
            $prefix = $date->format('Y-m');
            $income[] = (float) Database::scalar(
                'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "income" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
                [$userId, $prefix]
            );
            $expense[] = (float) Database::scalar(
                'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE user_id = ? AND type = "expense" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
                [$userId, $prefix]
            );
        }
        return compact('labels', 'income', 'expense');
    }

    private function expenseByCategory(int $userId, string $month): array
    {
        return Database::fetchAll(
            'SELECT c.category_id, c.name, c.icon, COALESCE(SUM(t.amount),0) AS total
               FROM categories c
               LEFT JOIN transactions t ON t.category_id = c.category_id
                    AND t.user_id = ? AND t.type = "expense" AND DATE_FORMAT(t.transaction_date,"%Y-%m") = ?
              WHERE c.type = "expense"
              GROUP BY c.category_id
             HAVING total > 0
              ORDER BY total DESC',
            [$userId, $month]
        );
    }
}
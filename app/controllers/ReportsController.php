<?php
declare(strict_types=1);

final class ReportsController
{
    public function index(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();

        $month = (string) Helper::get('month', date('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $filterCategory = (int) Helper::get('category_id', 0);
        $filterType = (string) Helper::get('type', '');
        $customFrom = (string) Helper::get('from_date', '');
        $customTo = (string) Helper::get('to_date', '');

        if ($customFrom !== '' && strtotime($customFrom) && $customTo !== '' && strtotime($customTo)) {
            $from = $customFrom;
            $to = $customTo;
        } else {
            $from = $month . '-01';
            $to = date('Y-m-t', strtotime($from));
        }

        // Log activity
        try {
            Database::run(
                'INSERT INTO activity_logs (user_id, action_type, entity_type, description) VALUES (?, "view", "report", ?)',
                [$userId, 'Viewed financial report for ' . date('F Y', strtotime($from))]
            );
        } catch (\Throwable) {}

        // Base query conditions
        $whereSql = 'WHERE t.user_id = ? AND t.transaction_date BETWEEN ? AND ?';
        $params = [$userId, $from, $to];

        if ($filterCategory > 0) {
            $whereSql .= ' AND t.category_id = ?';
            $params[] = $filterCategory;
        }
        if ($filterType === 'income' || $filterType === 'expense') {
            $whereSql .= ' AND t.type = ?';
            $params[] = $filterType;
        }

        $income = (float) Database::scalar(
            "SELECT COALESCE(SUM(t.amount),0) FROM transactions t $whereSql AND t.type='income'",
            $params
        );
        $expense = (float) Database::scalar(
            "SELECT COALESCE(SUM(t.amount),0) FROM transactions t $whereSql AND t.type='expense'",
            $params
        );
        $balance = Helper::to2($income - $expense);
        $savings = max(0.0, $balance);
        $savingsRate = $income > 0 ? round(($income - $expense) / $income * 100) : 0;
        $count = (int) Database::scalar(
            "SELECT COUNT(*) FROM transactions t $whereSql",
            $params
        );

        // Spending by category
        $byCategory = Database::fetchAll(
            'SELECT c.name, c.icon, c.type, COALESCE(SUM(t.amount),0) AS total, COUNT(t.transaction_id) AS n
               FROM categories c
               LEFT JOIN transactions t ON t.category_id = c.category_id AND t.user_id = ? AND t.transaction_date BETWEEN ? AND ?
              WHERE c.type = "expense" ' . ($filterCategory > 0 ? 'AND c.category_id = ' . $filterCategory : '') . '
              GROUP BY c.category_id
             HAVING total > 0
              ORDER BY total DESC',
            [$userId, $from, $to]
        );

        // Top transactions
        $topTxns = Database::fetchAll(
            "SELECT t.*, c.name AS category_name, c.icon AS category_icon
               FROM transactions t JOIN categories c ON c.category_id = t.category_id
              $whereSql
              ORDER BY t.amount DESC LIMIT 10",
            $params
        );

        // Months list for dropdown
        $months = Database::fetchAll(
            "SELECT DATE_FORMAT(transaction_date,'%Y-%m') AS m,
                    COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END),0) AS inc,
                    COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END),0) AS exp
               FROM transactions WHERE user_id=?
              GROUP BY m ORDER BY m DESC LIMIT 12",
            [$userId]
        );

        // 6-Month comparative chart dataset
        $sixMonths = $this->getSixMonthSeries($userId, $month);

        // Daily breakdown
        $daily = Database::fetchAll(
            "SELECT t.transaction_date AS d,
                    COALESCE(SUM(CASE WHEN t.type='income' THEN t.amount ELSE 0 END),0) AS inc,
                    COALESCE(SUM(CASE WHEN t.type='expense' THEN t.amount ELSE 0 END),0) AS exp
               FROM transactions t $whereSql
              GROUP BY d ORDER BY d ASC",
            $params
        );

        // Month-relative Weekly Summary (Week 1: 1-7, Week 2: 8-14, Week 3: 15-21, Week 4: 22-end)
        $weeklyBuckets = [
            1 => ['label' => 'Week 1 (Days 1–7)', 'inc' => 0.0, 'exp' => 0.0],
            2 => ['label' => 'Week 2 (Days 8–14)', 'inc' => 0.0, 'exp' => 0.0],
            3 => ['label' => 'Week 3 (Days 15–21)', 'inc' => 0.0, 'exp' => 0.0],
            4 => ['label' => 'Week 4 (Days 22–End)', 'inc' => 0.0, 'exp' => 0.0],
        ];

        foreach ($daily as $row) {
            $dayNum = (int) date('j', strtotime($row['d']));
            $wKey = $dayNum <= 7 ? 1 : ($dayNum <= 14 ? 2 : ($dayNum <= 21 ? 3 : 4));
            $weeklyBuckets[$wKey]['inc'] += (float) $row['inc'];
            $weeklyBuckets[$wKey]['exp'] += (float) $row['exp'];
        }

        $weekly = [];
        foreach ($weeklyBuckets as $k => $v) {
            $weekly[] = [
                'week'  => $k,
                'label' => $v['label'],
                'inc'   => $v['inc'],
                'exp'   => $v['exp'],
                'net'   => $v['inc'] - $v['exp'],
            ];
        }

        $userCategories = Category::allForUser($userId);
        $unreadNotifications = self::unreadCount();
        $view = 'pages/reports';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function exportCsv(): never
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $month = (string) Helper::get('month', date('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $filterCategory = (int) Helper::get('category_id', 0);
        $filterType = (string) Helper::get('type', '');
        $from = $month . '-01';
        $to = date('Y-m-t', strtotime($from));

        $whereSql = 'WHERE t.user_id = ? AND t.transaction_date BETWEEN ? AND ?';
        $params = [$userId, $from, $to];
        if ($filterCategory > 0) {
            $whereSql .= ' AND t.category_id = ?';
            $params[] = $filterCategory;
        }
        if ($filterType === 'income' || $filterType === 'expense') {
            $whereSql .= ' AND t.type = ?';
            $params[] = $filterType;
        }

        $rows = Database::fetchAll(
            "SELECT t.transaction_date, t.type, t.amount, t.description, c.name AS category
               FROM transactions t JOIN categories c ON c.category_id=t.category_id
              $whereSql
              ORDER BY t.transaction_date DESC",
            $params
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="coin_nex-report-' . $month . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Date', 'Type', 'Amount (PKR)', 'Category', 'Description']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['transaction_date'], ucfirst($r['type']), $r['amount'], $r['category'], $r['description']]);
        }
        fclose($out);
        exit;
    }

    public function exportPdf(): never
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $user = Helper::currentUser();
        $month = (string) Helper::get('month', date('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }
        $from = $month . '-01';
        $to = date('Y-m-t', strtotime($from));
        $monthLabel = date('F Y', strtotime($from));

        $filterCategory = (int) Helper::get('category_id', 0);
        $filterType = (string) Helper::get('type', '');

        $whereSql = 'WHERE t.user_id = ? AND t.transaction_date BETWEEN ? AND ?';
        $params = [$userId, $from, $to];
        if ($filterCategory > 0) {
            $whereSql .= ' AND t.category_id = ?';
            $params[] = $filterCategory;
        }
        if ($filterType === 'income' || $filterType === 'expense') {
            $whereSql .= ' AND t.type = ?';
            $params[] = $filterType;
        }

        $rows = Database::fetchAll(
            "SELECT t.transaction_date, t.type, t.amount, t.description, c.name AS category
               FROM transactions t JOIN categories c ON c.category_id=t.category_id
              $whereSql
              ORDER BY t.transaction_date ASC",
            $params
        );

        $pdf = new Pdf('Coin_Nex - ' . $monthLabel . ' Financial Report', 'Generated for ' . ($user['name'] ?? 'Student') . ' on ' . date('F j, Y'));
        $y = 740;
        $pdf->headerBlock($y);
        $y -= 50;

        $pdf->text(APP_TAGLINE . ' | Student Financial Report', 11, false, 1, $y - 14);

        $totals = ['income' => 0.0, 'expense' => 0.0];
        foreach ($rows as $r) {
            $totals[$r['type']] += (float) $r['amount'];
        }
        $net = $totals['income'] - $totals['expense'];
        $summary = 'Total Income: ' . Helper::money($totals['income'])
            . '    Total Expense: ' . Helper::money($totals['expense'])
            . '    Net Balance: ' . Helper::money($net)
            . '    Transactions: ' . count($rows);
        $pdf->text($summary, 10, true, 1, $y - 36);
        $y -= 8;

        $pdf->text('Expense Breakdown by Category', 12, true, 0, $y - 58);
        $catRows = Database::fetchAll(
            'SELECT c.name, COALESCE(SUM(t.amount),0) AS total, COUNT(t.transaction_id) AS n
               FROM categories c
               LEFT JOIN transactions t ON t.category_id = c.category_id AND t.user_id = ? AND t.transaction_date BETWEEN ? AND ?
              WHERE c.type = "expense"
              GROUP BY c.category_id HAVING total > 0
              ORDER BY total DESC',
            [$userId, $from, $to]
        );
        $catData = [];
        foreach ($catRows as $c) {
            $catData[] = [$c['name'], Helper::money((float) $c['total']), (string) $c['n']];
        }
        $y = $pdf->table(['Category', 'Amount', 'Count'], $catData, [260, 130, 80], $y - 72, [0, 2, 2]);

        $y -= 25;
        $pdf->text('Transactions Record', 12, true, 0, $y);
        $y -= 16;
        $txRows = [];
        foreach ($rows as $r) {
            $txRows[] = [
                $r['transaction_date'],
                ucfirst($r['type']),
                $r['description'] ?: $r['category'],
                Helper::money((float) $r['amount']),
            ];
        }
        $y = $pdf->table(['Date', 'Type', 'Description', 'Amount'], $txRows, [95, 60, 205, 110], $y, [0, 0, 0, 2]);

        $pdf->footerBlock(1, 1);
        $pdf->output('coin_nex-report-' . $month . '.pdf');
    }

    private function getSixMonthSeries(int $userId, string $currentMonth): array
    {
        $labels = [];
        $income = [];
        $expense = [];
        $firstDay = new DateTime($currentMonth . '-01');

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

    private static function unreadCount(): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
    }
}
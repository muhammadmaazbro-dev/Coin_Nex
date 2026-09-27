<?php
declare(strict_types=1);

final class Budget
{
    public static function byUserMonth(int $userId, string $month): array
    {
        $rows = Database::fetchAll(
            'SELECT b.*, c.name AS category_name, c.icon AS category_icon
               FROM budgets b JOIN categories c ON c.category_id = b.category_id
              WHERE b.user_id = ? AND DATE_FORMAT(b.month,"%Y-%m") = ?
              ORDER BY b.category_id ASC',
            [$userId, $month]
        );
        foreach ($rows as $i => $row) {
            $rows[$i]['spent'] = (float) Database::scalar(
                'SELECT COALESCE(SUM(amount),0) FROM transactions
                  WHERE user_id = ? AND category_id = ? AND type = "expense"
                    AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
                [$userId, $row['category_id'], $month]
            );
        }
        return $rows;
    }

    public static function exists(int $userId, int $categoryId, string $month): bool
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM budgets WHERE user_id = ? AND category_id = ? AND DATE_FORMAT(month,"%Y-%m") = ?',
            [$userId, $categoryId, $month]
        ) > 0;
    }

    public static function create(int $userId, int $categoryId, string $month, float $limit, float $threshold)
    {
        if (self::exists($userId, $categoryId, $month)) {
            return false;
        }
        return Database::run(
            'INSERT INTO budgets (user_id, category_id, month, limit_amount, alert_threshold)
             VALUES (?, ?, ?, ?, ?)',
            [$userId, $categoryId, $month . '-01', $limit, $threshold]
        );
    }

    public static function update(int $userId, int $budgetId, float $limit, float $threshold)
    {
        return Database::run(
            'UPDATE budgets SET limit_amount = ?, alert_threshold = ? WHERE budget_id = ? AND user_id = ?',
            [$limit, $threshold, $budgetId, $userId]
        );
    }

    public static function deleteOwned(int $userId, int $budgetId)
    {
        return Database::run(
            'DELETE FROM budgets WHERE budget_id = ? AND user_id = ?',
            [$budgetId, $userId]
        );
    }

    public static function applyAlerts(int $userId, string $month): int
    {
        $budgets = self::byUserMonth($userId, $month);
        $made = 0;
        foreach ($budgets as $budget) {
            $limit = (float) $budget['limit_amount'];
            if ($limit <= 0) {
                continue;
            }
            $threshold = (float) $budget['alert_threshold'];
            $spent = (float) $budget['spent'];
            $pct = Helper::to2($spent / $limit * 100);

            $uid = (int) $budget['user_id'];
            $budgetId = (int) $budget['budget_id'];
            $already = (int) Database::scalar(
                'SELECT COUNT(*) FROM notifications
                  WHERE user_id = ? AND related_type = "budget" AND related_id = ? AND notif_key = ?',
                [$uid, $budgetId, $month . '|' . (int) floor($pct / 25) * 25]
            );

            if ($already > 0) {
                continue;
            }

            if ($pct >= 100) {
                self::notify($uid, $budgetId, $month, (int) floor($pct / 25) * 25,
                    $budget['category_icon'] . ' Budget exceeded! "' . $budget['category_name'] . '" crossed its ' . Helper::money($limit) . ' limit.');
                $made++;
            } elseif ($pct >= $threshold && $spent > 0) {
                self::notify($uid, $budgetId, $month, (int) floor($pct / 25) * 25,
                    $budget['category_icon'] . ' Budget alert: "' . $budget['category_name'] . '" is at ' . Helper::money($spent) . ' (' . (int) $pct . '% of ' . Helper::money($limit) . ').');
                $made++;
            }
        }
        return $made;
    }

    private static function notify(int $userId, int $budgetId, string $month, int $bucket, string $message): void
    {
        Database::run(
            'INSERT INTO notifications (user_id, type, message, related_type, related_id, notif_key, is_read, created_at)
             VALUES (?, "budget", ?, "budget", ?, ?, 0, NOW())',
            [$userId, $message, $budgetId, $month . '|' . $bucket]
        );
    }
}

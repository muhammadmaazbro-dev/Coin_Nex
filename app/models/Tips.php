<?php
declare(strict_types=1);

final class Tips
{
    public static function generateForUser(int $userId, string $month): array
    {
        $monthKey = $month . '-01';
        $monthlyIncome = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions
              WHERE user_id = ? AND type = "income" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $month]
        );
        $monthlyExpense = (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions
              WHERE user_id = ? AND type = "expense" AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $month]
        );

        $spend = [];
        foreach (Database::fetchAll(
            'SELECT t.category_id, c.name, c.icon,
                    COALESCE(SUM(t.amount),0) AS total
               FROM transactions t JOIN categories c ON c.category_id = t.category_id
              WHERE t.user_id = ? AND t.type = "expense" AND DATE_FORMAT(t.transaction_date,"%Y-%m") = ?
              GROUP BY t.category_id',
            [$userId, $month]
        ) as $row) {
            $spend[$row['name']] = (float) $row['total'];
        }

        $tips = [];
        $rank = 90;

        $add = static function (string $text, ?int $catId = null, int $impact = 0) use (&$tips, &$rank, $userId): void {
            $tips[] = [
                'user_id'     => $userId,
                'category_id' => $catId,
                'tip_text'    => $text,
                'rank_score'  => $impact > 0 ? $rank : (int) round($rank - 10),
                'is_pinned'   => 0,
            ];
            $rank -= 6;
        };

        foreach ($spend as $name => $total) {
            $categoryId = null;
            foreach (Database::fetchAll('SELECT category_id FROM categories WHERE name = ? AND is_default = 1', [$name]) as $c) {
                $categoryId = (int) $c['category_id'];
            }
            $pct = $monthlyIncome > 0 ? (int) round($total / $monthlyIncome * 100) : 0;

            if ($name === 'Food' && $total > ($monthlyIncome * 0.25)) {
                $add('Food is ' . Helper::money($total) . ' (' . $pct . '% of income). Cooking a few meals at home can save ~' . Helper::money($total * 0.2) . ' next month.', $categoryId, 1);
            }
            if ($name === 'Subscriptions' && $total > 0) {
                $add('You spent ' . Helper::money($total) . ' on subscriptions. Audit them monthly — one unused plan can save you real money.', $categoryId, 1);
            }
            if ($name === 'Entertainment' && $total > ($monthlyIncome * 0.12)) {
                $add('Entertainment (' . Helper::money($total) . ') is high. Try free campus events — easy to halve it.', $categoryId, 1);
            }
            if ($name === 'Transport' && $total > ($monthlyIncome * 0.10)) {
                $add('Transport bill is ' . Helper::money($total) . '. A monthly commute pass beats single tickets.', $categoryId, 1);
            }
            if ($name === 'Hostel/Rent' && $total > ($monthlyIncome * 0.35)) {
                $add('Housing takes ' . Helper::money($total) . '. Budget rule: keep it under 30% of income.', $categoryId, 1);
            }
            if ($name === 'Academics' && $total > ($monthlyIncome * 0.22)) {
                $add('Academic costs are ' . Helper::money($total) . '. Hunt second-hand books and old stationery.', $categoryId, 1);
            }
        }

        if ($monthlyExpense <= 0 && $monthlyIncome > 0) {
            $add('Great — no spending recorded this month. Keep it up (or start logging so tips get personal).');
        } elseif ($monthlyIncome > 0 && $monthlyIncome - $monthlyExpense < $monthlyIncome * 0.2) {
            $add('Your savings rate is under 20%. Aim to save a fifth of every rupee that comes in.', null, 1);
        }

$upto = $rank;
        if ($monthlyIncome > 0 && $upto < 70) {
            $add('Rule of thumb: save at least 20% before spending. Pay yourself first.');
        }
        if (count($spend) === 0) {
            $add('Add a few transactions and the tip engine will tailor advice to your spending.');
            $add('Try the 50/30/20 rule: needs 50%, wants 30%, savings 20%.');
        }

        $generic = [
            'Review weekly, not monthly, so small leaks get caught early.',
            'Use the student discount everywhere you can — cinema, public transport, software.',
            'Set a strict mobile & data limit; it is a silent budget killer.',
            'Keep 3 days of meal money aside in cash for emergencies.',
        ];
        $currentCount = count($tips);
        if ($currentCount < 3) {
            shuffle($generic);
            foreach (array_slice($generic, 0, 3 - $currentCount) as $g) {
                $add($g);
            }
        }

        foreach ($tips as $tip) {
            $exists = (int) Database::scalar(
                'SELECT COUNT(*) FROM tips WHERE user_id = ? AND tip_text = ?',
                [$userId, $tip['tip_text']]
            );
            if ($exists === 0) {
                Database::run(
                    'INSERT INTO tips (user_id, category_id, tip_text, rank_score, is_pinned, is_dismissed, bookmarked, created_at)
                     VALUES (?, ?, ?, ?, 0, 0, 0, NOW())',
                    [$tip['user_id'], $tip['category_id'], $tip['tip_text'], $tip['rank_score']]
                );
            }
        }

        return self::allForUser($userId, 'active');
    }

    public static function allForUser(int $userId, string $filter = 'active'): array
    {
        $where = ['user_id = ?'];
        $params = [$userId];
        if ($filter === 'active') {
            $where[] = 'is_dismissed = 0';
        } elseif ($filter === 'bookmarks') {
            $where[] = 'bookmarked = 1';
        } elseif ($filter === 'dismissed') {
            $where[] = 'is_dismissed = 1';
        }
        return Database::fetchAll(
            'SELECT * FROM tips WHERE ' . implode(' AND ', $where) . ' ORDER BY is_pinned DESC, rank_score DESC, tip_id DESC',
            $params
        );
    }

    public static function bookmark(int $userId, int $tipId)
    {
        return Database::run(
            'UPDATE tips SET bookmarked = 1 - bookmarked WHERE tip_id = ? AND user_id = ?',
            [$tipId, $userId]
        );
    }

    public static function dismiss(int $userId, int $tipId)
    {
        return Database::run(
            'UPDATE tips SET is_dismissed = 1 WHERE tip_id = ? AND user_id = ?',
            [$tipId, $userId]
        );
    }

    public static function restore(int $userId, int $tipId)
    {
        return Database::run(
            'UPDATE tips SET is_dismissed = 0 WHERE tip_id = ? AND user_id = ?',
            [$tipId, $userId]
        );
    }

    public static function togglePin(int $userId, int $tipId)
    {
        return Database::run(
            'UPDATE tips SET is_pinned = 1 - is_pinned WHERE tip_id = ? AND user_id = ?',
            [$tipId, $userId]
        );
    }

    public static function potentialLevel(float $score): string
    {
        if ($score >= 85) return 'High';
        if ($score >= 70) return 'Medium';
        return 'Low';
    }

    public static function clearAll(int $userId)
    {
        return Database::run('DELETE FROM tips WHERE user_id = ?', [$userId]);
    }
}

<?php
declare(strict_types=1);

final class Insights
{
    public static function generateForUser(int $userId, string $month): array
    {
        $from = $month . '-01';
        $prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
        $prevFrom = $prevMonth . '-01';
        $prevTo = date('Y-m-t', strtotime($prevFrom));

        $income = (float) self::sum($userId, 'income', $from, $month);
        $expense = (float) self::sum($userId, 'expense', $from, $month);
        $prevIncome = (float) self::sum($userId, 'income', $prevFrom, $prevFrom, true);
        $prevExpense = (float) self::sum($userId, 'expense', $prevFrom, $prevFrom, true);

        $net = $income - $expense;
        $savingsRate = $income > 0 ? round($net / $income * 100) : 0;

        $byCategory = Database::fetchAll(
            'SELECT c.name AS category, c.icon, COALESCE(SUM(t.amount),0) AS total, COUNT(t.transaction_id) AS n
               FROM transactions t JOIN categories c ON c.category_id = t.category_id
              WHERE t.user_id = ? AND t.type = "expense" AND DATE_FORMAT(t.transaction_date,"%Y-%m") = ?
              GROUP BY c.category_id ORDER BY total DESC',
            [$userId, $month]
        );

        $summary = [];
        $summary[] = 'This month you earned ' . Helper::money($income) . ' and spent ' . Helper::money($expense)
            . ', leaving a net of ' . Helper::money($net) . '.';

        if ($income <= 0 && $expense <= 0) {
            $summary[] = 'No activity recorded yet — add your first transaction to unlock analysis.';
        } elseif ($savingsRate >= 30) {
            $summary[] = 'Your savings rate of ' . $savingsRate . '% is excellent — above the 20% rule. Keep it up!';
        } elseif ($savingsRate >= 20) {
            $summary[] = 'You saved ' . $savingsRate . '%, right on the 20% rule. ';
        } elseif ($savingsRate >= 0) {
            $summary[] = 'You saved ' . $savingsRate . '%. Try trimming your top expense category to reach 20%.';
        } else {
            $summary[] = 'You spent ' . abs($savingsRate) . '% more than you earned — consider a stricter budget next month.';
        }

        if ($prevIncome > 0 || $prevExpense > 0) {
            $deltaI = $prevIncome > 0 ? (int) round(($income - $prevIncome) / $prevIncome * 100) : 0;
            $deltaE = $prevExpense > 0 ? (int) round(($expense - $prevExpense) / $prevExpense * 100) : 0;
            $summary[] = 'Versus last month: income is ' . ($deltaI >= 0 ? 'up ' : 'down ') . abs($deltaI)
                . '%, spending is ' . ($deltaE >= 0 ? 'up ' : 'down ') . abs($deltaE) . '%.';
        }

        if (!empty($byCategory)) {
            $top = $byCategory[0];
            $summary[] = 'Top expense: ' . $top['icon'] . ' ' . $top['category'] . ' (' . Helper::money((float) $top['total']) . ', ' . (int) $top['n'] . ' purchase' . ((int) $top['n'] > 1 ? 's' : '') . ').';
        }

        $tip = self::suggestOne(compact('income', 'expense', 'net', 'savingsRate', 'byCategory'));

        $monthKey = $month . '-01';
        $existing = (int) Database::scalar('SELECT COUNT(*) FROM insights WHERE user_id = ? AND month = ?', [$userId, $monthKey]);
        if ($existing === 0) {
            Database::run(
                'INSERT INTO insights (user_id, month, summary_text, tip_text, generated_by, is_pinned, created_at)
                 VALUES (?, ?, ?, ?, "engine", 0, NOW())',
                [$userId, $monthKey, implode(' ', $summary), (string) $tip]
            );
        }
        return ['summary' => $summary, 'tip' => $tip];
    }

    public static function byUser(int $userId): array
    {
        return Database::fetchAll(
            'SELECT * FROM insights WHERE user_id = ? ORDER BY month DESC',
            [$userId]
        );
    }

    public static function latest(int $userId): ?array
    {
        return Database::fetch(
            'SELECT * FROM insights WHERE user_id = ? ORDER BY month DESC LIMIT 1',
            [$userId]
        );
    }

    public static function togglePin(int $userId, int $insightId): bool
    {
        Database::run(
            'UPDATE insights SET is_pinned = 1 - is_pinned WHERE insight_id = ? AND user_id = ?',
            [$insightId, $userId]
        );
        return true;
    }

    public static function chat(string $question, int $userId): ?array
    {
        $q = mb_strtolower(trim($question));
        if ($q === '') {
            return null;
        }
        $month = date('Y-m');
        $income = self::sum($userId, 'income', $month . '-01', $month);
        $expense = self::sum($userId, 'expense', $month . '-01', $month);
        $net = $income - $expense;
        $rate = $income > 0 ? (int) round($net / $income * 100) : 0;
        $txnCount = (int) Database::scalar('SELECT COUNT(*) FROM transactions WHERE user_id = ?', [$userId]);

        $hi = ['hi', 'hello', 'salam', 'hey', 'assalam', 'hola'];
        foreach ($hi as $h) {
            if ($q === $h || str_starts_with($q, $h . ' ')) {
                return ['answer' => 'Assalam-o-Alaikum! 👋 I am Bee, your budget buddy. Ask me about your spending, savings, or budgets.', 'quick' => ["How much did I spend this month?", "What is my savings rate?", "Give me 1 saving tip", "How much do I have left?"]];
            }
        }
        if ((str_contains($q, 'sav') || str_contains($q, 'saving')) && (str_contains($q, 'rate') || str_contains($q, 'how much'))) {
            return ['answer' => 'Your savings rate this month is ' . $rate . '% (' . Helper::money($net) . ' of ' . Helper::money($income) . ' income). The 20% rule says you should aim to keep at least 20%. ' . ($rate >= 20 ? 'You are doing great! 🎉' : 'A little trimming could push you over the line.'), 'quick' => ['Give me 1 saving tip', 'How much did I spend this month?']];
        }
        if (str_contains($q, 'spend') && (str_contains($q, 'how much') || str_contains($q, 'total'))) {
            return ['answer' => 'This month you spent ' . Helper::money($expense) . ' across ' . $txnCount . ' transaction' . ($txnCount === 1 ? '' : 's') . '. Your biggest budget risk is where the money flows the most — open Reports to see the split.', 'quick' => ['What is my savings rate?', 'How much do I have left?']];
        }
        if (str_contains($q, 'left') || str_contains($q, 'remaining') || str_contains($q, 'balance')) {
            $avgDay = $expense / max(1, (int) date('j'));
            return ['answer' => 'Balance this month is ' . Helper::money($net) . '. You are spending about ' . Helper::money(round($avgDay, 2)) . ' per day so far.', 'quick' => ['What is my savings rate?', 'Give me 1 saving tip']];
        }
        if (str_contains($q, 'tip') || str_contains($q, 'advice') || str_contains($q, 'idea')) {
            $tip = Tips::allForUser($userId, 'active');
            if ($tip) {
                return ['answer' => '💡 ' . $tip[0]['tip_text'], 'quick' => ['Another tip', 'How much do I have left?']];
            }
            return ['answer' => 'I would say: every time you log income, move 20% to savings before you spend it.', 'quick' => ['How much did I spend this month?']];
        }
        if (str_contains($q, 'budget') || str_contains($q, 'limit')) {
            $budgets = Database::fetchAll('SELECT COUNT(*) AS n FROM budgets WHERE user_id = ?', [$userId]);
            if ((int) $budgets[0]['n'] > 0) {
                return ['answer' => 'You have ' . (int) $budgets[0]['n'] . ' active budget' . ((int) $budgets[0]['n'] > 1 ? 's' : '') . '. You can review them on the Budgets page; I send you an alert when one crosses its threshold.', 'quick' => ['How much did I spend this month?', 'What is my savings rate?']];
            }
            return ['answer' => 'You have no budgets yet. Set one on the Budgets page 🎯 and I will nudge you before you overshoot a category.', 'quick' => ['Set a budget hint', 'How much did I spend this month?']];
        }
        if (str_contains($q, 'where') && (str_contains($q, 'go') || str_contains($q, 'money') || str_contains($q, 'spent'))) {
            $top = Database::fetch(
                'SELECT c.name, COALESCE(SUM(t.amount),0) AS total FROM transactions t
                  JOIN categories c ON c.category_id = t.category_id
                 WHERE t.user_id = ? AND t.type = "expense" AND DATE_FORMAT(t.transaction_date,"%Y-%m") = ?
                 GROUP BY c.category_id ORDER BY total DESC LIMIT 1',
                [$userId, $month]
            );
            return ['answer' => $top ? 'Most of September went to "' . $top['name'] . '" (' . Helper::money((float) $top['total']) . '). Check Reports 📊 for the whole picture.' : 'Add expenses first and I will tell you where your money goes.', 'quick' => ['Give me 1 saving tip', 'What is my savings rate?']];
        }
        if (str_contains($q, 'who') && (str_contains($q, 'you') || str_contains($q, 'bee'))) {
            return ['answer' => 'I am Bee, Coin_Nex\'s built-in budget assistant. I read your transaction history and answer money questions — all on your own laptop, no internet needed. 🐝', 'quick' => ['Help', 'What is my savings rate?']];
        }
        if (str_contains($q, 'help') || str_contains($q, 'what can')) {
            return ['answer' => 'Try asking: "How much did I spend this month?", "What is my savings rate?", "Where did my money go?", "Give me 1 saving tip", or "How much do I have left?".', 'quick' => ['What is my savings rate?', 'How much do I have left?', "Where did my money go?"]];
        }
        return ['answer' => 'Hmm, I am best with money questions, like spending, savings, budgets or tips. Try one of these 👇', 'quick' => ['How much did I spend this month?', 'What is my savings rate?', 'Give me 1 saving tip']];
    }

    private static function sum(int $userId, string $type, string $from, string $monthKey, bool $isPrev = false): float
    {
        if ($isPrev) {
            $param = $from . '-01';
            return (float) Database::scalar(
                'SELECT COALESCE(SUM(amount),0) FROM transactions
                  WHERE user_id = ? AND type = ? AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
                [$userId, $type, $param]
            );
        }
        return (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions
              WHERE user_id = ? AND type = ? AND DATE_FORMAT(transaction_date,"%Y-%m") = ?',
            [$userId, $type, $monthKey]
        );
    }

    private static function suggestOne(array $ctx): ?string
    {
        $income = (float) $ctx['income'];
        $expense = (float) $ctx['expense'];
        $savingsRate = (int) $ctx['savingsRate'];
        $byCategory = $ctx['byCategory'];
        if (empty($byCategory)) {
            return 'Start logging daily — even small records beat a month of guessing.';
        }
        $top = $byCategory[0];
        $topTotal = (float) $top['total'];
        if ($savingsRate >= 20) {
            return 'Repeat your current habit — cap top category "' . $top['category'] . '" at ' . Helper::money($topTotal) . '.';
        }
        return 'Target "' . $top['category'] . '" (' . Helper::money($topTotal) . '): a 15% cut frees ' . Helper::money(round($topTotal * 0.15, 2)) . '.';
    }
}
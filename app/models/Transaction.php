<?php
declare(strict_types=1);

final class Transaction
{
    public static function allForUser(int $userId, array $filters = []): array
    {
        [$where, $params] = self::buildWhere($userId, $filters);
        $limit = (int) ($filters['limit'] ?? 100);
        return Database::fetchAll(
            "SELECT t.*, c.name AS category_name, c.icon AS category_icon
               FROM transactions t
               JOIN categories c ON c.category_id = t.category_id
              $where
              ORDER BY t.transaction_date DESC, t.transaction_id DESC
              LIMIT $limit",
            $params
        );
    }

    public static function countAllForUser(int $userId, array $filters = []): int
    {
        [$where, $params] = self::buildWhere($userId, $filters);
        return (int) Database::scalar(
            "SELECT COUNT(*) FROM transactions t JOIN categories c ON c.category_id = t.category_id $where",
            $params
        );
    }

    public static function findOwned(int $id, int $userId): ?array
    {
        return Database::fetch(
            'SELECT t.*, c.name AS category_name, c.icon AS category_icon
               FROM transactions t
               JOIN categories c ON c.category_id = t.category_id
              WHERE t.transaction_id = ? AND t.user_id = ?
              LIMIT 1',
            [$id, $userId]
        );
    }

    public static function create(array $data): int
    {
        Database::run(
            'INSERT INTO transactions (user_id, category_id, type, amount, description, transaction_date, is_recurring, recurrence, ai_suggested_category, ai_override)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['user_id'],
                $data['category_id'],
                $data['type'],
                round((float) $data['amount'], 2),
                $data['description'] ?: null,
                $data['transaction_date'],
                $data['is_recurring'] ? 1 : 0,
                $data['recurrence'] ?? 'none',
                $data['ai_suggested_category'] ?? null,
                $data['ai_override'] ? 1 : 0,
            ]
        );
        return Database::lastId();
    }

    public static function updateTransaction(int $id, int $userId, array $data): void
    {
        Database::run(
            'UPDATE transactions
                SET category_id = ?, type = ?, amount = ?, description = ?, transaction_date = ?, is_recurring = ?, recurrence = ?
              WHERE transaction_id = ? AND user_id = ?',
            [
                $data['category_id'],
                $data['type'],
                round((float) $data['amount'], 2),
                $data['description'] ?: null,
                $data['transaction_date'],
                $data['is_recurring'] ? 1 : 0,
                $data['recurrence'] ?? 'none',
                $id,
                $userId,
            ]
        );
    }

    public static function deleteOwned(int $id, int $userId): void
    {
        Database::run('DELETE FROM transactions WHERE transaction_id = ? AND user_id = ?', [$id, $userId]);
    }

    public static function applyDueRecurring(int $userId): int
    {
        $rows = Database::fetchAll(
            'SELECT * FROM transactions
              WHERE user_id = ? AND is_recurring = 1 AND recurrence IN ("monthly","weekly")',
            [$userId]
        );
        $created = 0;
        foreach ($rows as $txn) {
            $existing = Database::fetchAll(
                'SELECT transaction_date FROM transactions
                  WHERE user_id = ? AND category_id = ? AND COALESCE(description,"") = ?
                  ORDER BY transaction_date ASC',
                [$userId, $txn['category_id'], $txn['description'] ?? '']
            );
            $covered = [];
            foreach ($existing as $ex) {
                $covered[self::periodKey(strtotime($ex['transaction_date']), $txn['recurrence'])][] = 1;
            }
            $anchor = strtotime($txn['transaction_date']);
            $todayTime = time();
            $step = $txn['recurrence'] === 'weekly' ? '+1 week' : '+1 month';
            $cursor = $anchor;
            $guard = 0;
            while ($guard++ < 500) {
                $cursor = strtotime($step, $cursor);
                if ($cursor === false || $cursor > $todayTime) {
                    break;
                }
                $key = self::periodKey($cursor, $txn['recurrence']);
                if (empty($covered[$key])) {
                    self::create([
                        'user_id'            => $userId,
                        'category_id'        => $txn['category_id'],
                        'type'               => $txn['type'],
                        'amount'             => $txn['amount'],
                        'description'        => $txn['description'],
                        'transaction_date'   => date('Y-m-d', $cursor),
                        'is_recurring'       => false,
                        'recurrence'         => 'none',
                        'ai_suggested_category' => null,
                        'ai_override'        => 0,
                    ]);
                    $covered[$key][] = 1;
                    $created++;
                }
            }
        }
        return $created;
    }

    public static function refreshDuplicateFlags(int $userId): void
    {
        Database::run('UPDATE transactions SET is_duplicate_flag = 0 WHERE user_id = ?', [$userId]);
        $rows = Database::fetchAll(
            'SELECT transaction_id, transaction_date, category_id, type, amount, description
               FROM transactions WHERE user_id = ?
               ORDER BY transaction_date ASC',
            [$userId]
        );
        $groups = [];
        foreach ($rows as $r) {
            $sig = strtolower(trim((string) ($r['description'] ?? '')));
            $sig = $r['type'] . '|' . (int) $r['category_id'] . '|' . round((float) $r['amount'], 2) . '|' . $sig;
            $groups[$sig][] = $r;
        }
        $flagged = [];
        foreach ($groups as $list) {
            $n = count($list);
            if ($n < 2) {
                continue;
            }
            for ($i = 0; $i < $n; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $dA = strtotime($list[$i]['transaction_date']);
                    $dB = strtotime($list[$j]['transaction_date']);
                    $diff = (int) round(abs($dA - $dB) / 86400);
                    if ($diff > 21 || in_array($diff, [7, 14, 21, 28, 29, 30, 31], true)) {
                        continue;
                    }
                    $flagged[(int) $list[$i]['transaction_id']] = 1;
                    $flagged[(int) $list[$j]['transaction_id']] = 1;
                }
            }
        }
        foreach (array_keys($flagged) as $id) {
            Database::run('UPDATE transactions SET is_duplicate_flag = 1 WHERE transaction_id = ? AND user_id = ?', [$id, $userId]);
        }
    }

    private static function periodKey(int $timestamp, string $recurrence): string
    {
        return $recurrence === 'weekly' ? date('Y-W', $timestamp) : date('Y-m', $timestamp);
    }

    public static function totals(int $userId, ?string $type = null, ?string $from = null, ?string $to = null): float
    {
        $where = ['user_id = ?'];
        $params = [$userId];
        if ($type !== null) {
            $where[] = 'type = ?';
            $params[] = $type;
        }
        if ($from !== null) {
            $where[] = 'transaction_date >= ?';
            $params[] = $from;
        }
        if ($to !== null) {
            $where[] = 'transaction_date <= ?';
            $params[] = $to;
        }
        return (float) Database::scalar(
            'SELECT COALESCE(SUM(amount),0) FROM transactions WHERE ' . implode(' AND ', $where),
            $params
        );
    }

    private static function buildWhere(int $userId, array $filters): array
    {
        $where = ['t.user_id = ?'];
        $params = [$userId];

        if (!empty($filters['type'])) {
            $where[] = 't.type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['category_id'])) {
            $where[] = 't.category_id = ?';
            $params[] = (int) $filters['category_id'];
        }
        if (!empty($filters['month'])) {
            $where[] = 'DATE_FORMAT(t.transaction_date, "%Y-%m") = ?';
            $params[] = $filters['month'];
        } elseif (!empty($filters['from']) && !empty($filters['to'])) {
            $where[] = 't.transaction_date BETWEEN ? AND ?';
            $params[] = $filters['from'];
            $params[] = $filters['to'];
        } elseif (!empty($filters['from'])) {
            $where[] = 't.transaction_date >= ?';
            $params[] = $filters['from'];
        } elseif (!empty($filters['to'])) {
            $where[] = 't.transaction_date <= ?';
            $params[] = $filters['to'];
        }
        if (!empty($filters['search'])) {
            $where[] = '(t.description LIKE ? OR c.name LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        return ['WHERE ' . implode(' AND ', $where), $params];
    }
}
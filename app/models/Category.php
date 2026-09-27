<?php
declare(strict_types=1);

final class Category
{
    public static function allForUser(int $userId): array
    {
        return Database::fetchAll(
            'SELECT * FROM categories
              WHERE is_default = 1 OR user_id = ?
              ORDER BY type DESC, is_default DESC, category_id ASC',
            [$userId]
        );
    }

    public static function byTypeForUser(int $userId, string $type): array
    {
        return Database::fetchAll(
            'SELECT * FROM categories
              WHERE (is_default = 1 OR user_id = ?) AND type = ?
              ORDER BY is_default DESC, category_id ASC',
            [$userId, $type]
        );
    }

    public static function findOwned(int $id, int $userId): ?array
    {
        return Database::fetch(
            'SELECT * FROM categories WHERE category_id = ? AND user_id = ? LIMIT 1',
            [$id, $userId]
        );
    }

    public static function create(int $userId, string $name, string $type, string $icon): int
    {
        Database::run(
            'INSERT INTO categories (name, type, icon, is_default, user_id) VALUES (?, ?, ?, 0, ?)',
            [$name, $type, $icon, $userId]
        );
        return Database::lastId();
    }

    public static function update(int $id, int $userId, string $name, string $icon): void
    {
        Database::run(
            'UPDATE categories SET name = ?, icon = ? WHERE category_id = ? AND user_id = ?',
            [$name, $icon, $id, $userId]
        );
    }

    public static function delete(int $id, int $userId): bool
    {
        $hasTransactions = (int) Database::scalar(
            'SELECT COUNT(*) FROM transactions WHERE category_id = ?',
            [$id]
        );
        if ($hasTransactions > 0) {
            return false;
        }
        Database::run('DELETE FROM categories WHERE category_id = ? AND user_id = ?', [$id, $userId]);
        return true;
    }

    public static function nameTakenForUser(int $userId, string $name, string $type, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM categories WHERE (is_default = 1 OR user_id = ?) AND name = ? AND type = ?';
        $params = [$userId, $name, $type];
        if ($exceptId !== null) {
            $sql .= ' AND category_id != ?';
            $params[] = $exceptId;
        }
        return (int) Database::scalar($sql, $params) > 0;
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch('SELECT * FROM categories WHERE category_id = ? LIMIT 1', [$id]);
    }

    public static function usageCount(): array
    {
        return Database::fetchAll(
            'SELECT c.category_id, c.name, c.icon, c.type, COUNT(t.transaction_id) AS total
               FROM categories c
               LEFT JOIN transactions t ON t.category_id = c.category_id
              GROUP BY c.category_id
              ORDER BY total DESC'
        );
    }
}
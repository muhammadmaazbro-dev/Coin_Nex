<?php
declare(strict_types=1);

final class User
{
    public static function findByEmail(string $email): ?array
    {
        return Database::fetch('SELECT * FROM users WHERE email = ? LIMIT 1', [strtolower(trim($email))]);
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch('SELECT * FROM users WHERE user_id = ? LIMIT 1', [$id]);
    }

    public static function findByResetToken(string $tokenHash): ?array
    {
        return Database::fetch(
            'SELECT u.*, pr.reset_id AS active_reset_id
               FROM password_resets pr
               JOIN users u ON u.user_id = pr.user_id
              WHERE pr.token_hash = ? AND pr.used = 0 AND pr.expires_at > NOW()
              LIMIT 1',
            [$tokenHash]
        );
    }

    public static function create(array $data): int
    {
        Database::run(
            'INSERT INTO users (name, email, password_hash, role, academic_year, monthly_allowance_baseline, savings_goal)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                trim($data['name']),
                strtolower(trim($data['email'])),
                $data['password_hash'],
                $data['role'] ?? 'student',
                $data['academic_year'] ?? null,
                $data['monthly_allowance_baseline'] ?? 0,
                $data['savings_goal'] ?? 0,
            ]
        );
        return Database::lastId();
    }

    public static function setLastLogin(int $id): void
    {
        Database::run('UPDATE users SET last_login = NOW() WHERE user_id = ?', [$id]);
    }

    public static function update(int $id, array $fields): void
    {
        $allowed = ['name', 'academic_year', 'monthly_allowance_baseline', 'savings_goal', 'currency', 'theme', 'font_scale', 'password_hash', 'is_active'];
        $sets = [];
        $params = [];
        foreach ($fields as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $sets[] = "`$key` = ?";
                $params[] = $value;
            }
        }
        if (!$sets) {
            return;
        }
        $params[] = $id;
        Database::run('UPDATE users SET ' . implode(', ', $sets) . ' WHERE user_id = ?', $params);
    }

    public static function createResetToken(int $id): string
    {
        $token = Helper::randomToken();
        Database::run(
            'INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))',
            [$id, hash('sha256', $token)]
        );
        return $token;
    }

    public static function markResetUsed(int $resetId): void
    {
        Database::run('UPDATE password_resets SET used = 1 WHERE reset_id = ?', [$resetId]);
    }

    public static function setPassword(int $id, string $hash): void
    {
        Database::run('UPDATE users SET password_hash = ? WHERE user_id = ?', [$hash, $id]);
    }

    public static function countByRole(string $role): int
    {
        return (int) Database::scalar('SELECT COUNT(*) FROM users WHERE role = ?', [$role]);
    }

    public static function recent(int $limit = 8): array
    {
        return Database::fetchAll('SELECT user_id, name, email, role, is_active, last_login, created_at FROM users ORDER BY user_id DESC LIMIT ' . (int) $limit);
    }

    public static function saveProfile(int $id, array $data): void
    {
        self::update($id, [
            'name'                        => trim($data['name'] ?? ''),
            'academic_year'               => $data['academic_year'] ?? null,
            'monthly_allowance_baseline'  => (float)($data['monthly_allowance_baseline'] ?? 0),
            'savings_goal'                => (float)($data['savings_goal'] ?? 0),
        ]);
    }
}
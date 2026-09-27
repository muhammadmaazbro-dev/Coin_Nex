<?php
declare(strict_types=1);

final class AIClassifier
{
    private const EXPENSE_KEYWORDS = [
        'Food'           => [
            'cafe', 'café', 'restaurant', 'food', 'lunch', 'dinner', 'breakfast', 'pizza', 'burger',
            'grocer', 'grocery', 'snack', 'coffee', 'tea', 'chai', 'meal', 'tiffin', 'canteen',
            'delivery', 'eat', 'kfc', 'mcdonald', 'subway', 'bakery', 'fruit', 'biryani', 'karahi',
            'shawarma', 'roll', 'paratha', 'daal', 'samosa', 'juices', 'bread', 'milk', 'eggs',
            'dawat', 'coke', 'pepsi', 'cheezious', 'optp', 'hardees', 'broadway'
        ],
        'Transport'      => [
            'bus', 'train', 'metro', 'uber', 'cab', 'taxi', 'fuel', 'petrol', 'gas', 'parking',
            'ticket', 'transport', 'ride', 'bike', 'auto', 'rickshaw', 'airport', 'bykea',
            'indrive', 'careem', 'speedo', 'orange train', 'railway', 'toll', 'van'
        ],
        'Hostel/Rent'    => [
            'hostel', 'rent', 'dorm', 'room', 'deposit', 'electricity', 'utility', 'utilities',
            'wifi bill', 'pg rent', 'maintenance', 'landlord', 'warden', 'mess bill', 'geyser', 'ups'
        ],
        'Academics'      => [
            'book', 'tuition', 'fee', 'course', 'stationery', 'pen', 'notebook', 'semester',
            'exam', 'admission', 'copies', 'printing', 'lab', 'workshop', 'seminar', 'binder',
            'assignment', 'calculator', 'project', 'paper', 'library', 'quiz', 'course pack'
        ],
        'Subscriptions'  => [
            'netflix', 'spotify', 'prime', 'subscription', 'youtube', 'disney', 'app', 'icloud',
            'google one', 'gym app', 'music', 'package', 'bundle', 'jazz', 'telenor', 'zong', 'ufone',
            'data package', 'call package', 'streaming', 'software'
        ],
        'Entertainment'  => [
            'movie', 'cinema', 'party', 'concert', 'game', 'gaming', 'fun', 'outing', 'hangout',
            'weekend trip', 'adventure', 'bowling', 'arcade', 'cue', 'snooker', 'steam', 'playstation',
            'tour', 'picnic', 'treat', 'celebration', 'match', 'cricket'
        ],
        'Miscellaneous'  => [
            'misc', 'other', 'stuff', 'random', 'purchase', 'shopping', 'gift', 'clothes',
            'shoes', 'daraz', 'laundry', 'medicine', 'doctor', 'barber', 'haircut'
        ],
    ];

    private const INCOME_KEYWORDS = [
        'Allowance'      => ['allowance', 'pocket', 'monthly', 'from home', 'family', 'dad', 'mom', 'transfer', 'budget', 'abu', 'ammi', 'parents'],
        'Part-time Job'  => ['salary', 'job', 'work', 'shift', 'payroll', 'wages', 'freelance', 'part time', 'parttime', 'design', 'gig', 'internship', 'tutoring', 'tutor', 'fiverr', 'upwork', 'client'],
        'Scholarship'    => ['scholarship', 'grant', 'merit', 'fund', 'award', 'stipend', 'financial aid', 'hec', 'fee concession'],
        'Gift'           => ['gift', 'birthday', 'present', 'eidi', 'reward', 'congrats', 'prize', 'treat'],
        'Other Income'   => ['refund', 'cashback', 'sold', 'sale', 'bonus', 'commission', 'profit', 'interest', 'investment'],
    ];

    /**
     * Intelligent category suggest: checks user's learned rules first,
     * then historical transactions, then built-in keywords.
     */
    public static function suggest(string $text, string $type, ?int $userId = null): ?string
    {
        $haystack = mb_strtolower(trim($text));
        if ($haystack === '') {
            return null;
        }

        // 1. User learned rules (Correction learning engine)
        if ($userId !== null && $userId > 0) {
            $learned = self::matchLearnedRule($userId, $haystack, $type);
            if ($learned !== null) {
                return $learned;
            }

            // 2. User's previous transaction history
            $historyCat = self::matchFromHistory($userId, $haystack, $type);
            if ($historyCat !== null) {
                return $historyCat;
            }
        }

        // 3. Keyword dictionary scoring
        $table = $type === 'income' ? self::INCOME_KEYWORDS : self::EXPENSE_KEYWORDS;
        $bestScore = 0;
        $best = null;

        foreach ($table as $category => $keywords) {
            $score = 0;
            foreach ($keywords as $kw) {
                if (mb_strpos($haystack, mb_strtolower($kw)) !== false) {
                    $score += mb_strlen($kw);
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $category;
            }
        }

        return $best;
    }

    private static function matchLearnedRule(int $userId, string $haystack, string $type): ?string
    {
        try {
            $rules = Database::fetchAll(
                'SELECT r.keyword, c.name FROM user_category_rules r
                 JOIN categories c ON c.category_id = r.category_id
                 WHERE r.user_id = ? AND r.type = ? ORDER BY LENGTH(r.keyword) DESC',
                [$userId, $type]
            );
            foreach ($rules as $r) {
                if (mb_strpos($haystack, mb_strtolower($r['keyword'])) !== false) {
                    return $r['name'];
                }
            }
        } catch (\Throwable) {}
        return null;
    }

    private static function matchFromHistory(int $userId, string $haystack, string $type): ?string
    {
        try {
            $past = Database::fetch(
                'SELECT c.name FROM transactions t
                 JOIN categories c ON c.category_id = t.category_id
                 WHERE t.user_id = ? AND t.type = ? AND LOWER(t.description) = ?
                 ORDER BY t.transaction_id DESC LIMIT 1',
                [$userId, $type, $haystack]
            );
            if ($past && !empty($past['name'])) {
                return $past['name'];
            }
        } catch (\Throwable) {}
        return null;
    }

    /**
     * AI correction learning: Save when user chooses/modifies a category.
     */
    public static function learnCorrection(int $userId, string $text, int $categoryId, string $type): void
    {
        $text = mb_strtolower(trim($text));
        if ($text === '' || mb_strlen($text) < 3) {
            return;
        }

        // Extract key words (e.g. "gaming store" -> "gaming")
        $words = preg_split('/[\s,\.\-_]+/', $text);
        $candidate = '';
        foreach ($words as $w) {
            if (mb_strlen($w) >= 3 && !in_array($w, ['the', 'and', 'for', 'with', 'from', 'this', 'that'], true)) {
                $candidate = $w;
                break;
            }
        }
        $keyword = $candidate !== '' ? $candidate : $text;

        try {
            $existing = Database::fetch(
                'SELECT rule_id FROM user_category_rules WHERE user_id = ? AND keyword = ? AND type = ? LIMIT 1',
                [$userId, $keyword, $type]
            );
            if ($existing) {
                Database::run(
                    'UPDATE user_category_rules SET category_id = ? WHERE rule_id = ?',
                    [$categoryId, (int) $existing['rule_id']]
                );
            } else {
                Database::run(
                    'INSERT INTO user_category_rules (user_id, keyword, category_id, type) VALUES (?, ?, ?, ?)',
                    [$userId, $keyword, $categoryId, $type]
                );
            }
        } catch (\Throwable) {}
    }

    /**
     * Check for Unusually Large Transaction
     */
    public static function checkAnomaly(int $userId, float $amount, string $type): array
    {
        if ($type !== 'expense' || $amount <= 0) {
            return ['is_anomaly' => false, 'message' => ''];
        }

        $avg = (float) Database::scalar(
            'SELECT COALESCE(AVG(amount), 0) FROM transactions WHERE user_id = ? AND type = "expense"',
            [$userId]
        );

        $threshold = max(10000.0, $avg * 3.0);
        if ($avg > 0 && $amount >= $threshold) {
            return [
                'is_anomaly' => true,
                'avg'        => round($avg, 2),
                'amount'     => $amount,
                'message'    => '⚠️ Unusually large transaction: ' . Helper::money($amount) . ' (Your average expense is ' . Helper::money($avg) . '). Please confirm this amount.',
            ];
        }

        // Hard threshold for students if no history yet
        if ($avg <= 0 && $amount >= 20000.0) {
            return [
                'is_anomaly' => true,
                'avg'        => 0.0,
                'amount'     => $amount,
                'message'    => '⚠️ Unusually large transaction: ' . Helper::money($amount) . '. Is this amount correct?',
            ];
        }

        return ['is_anomaly' => false, 'message' => ''];
    }

    /**
     * Duplicate detection: Warns if identical amount, category, and date already exists
     */
    public static function checkDuplicate(int $userId, float $amount, int $categoryId, string $date, string $type, ?int $excludeId = null): array
    {
        $sql = 'SELECT t.*, c.name AS cat_name FROM transactions t
                JOIN categories c ON c.category_id = t.category_id
                WHERE t.user_id = ? AND t.amount = ? AND t.category_id = ?
                  AND t.transaction_date = ? AND t.type = ?';
        $params = [$userId, $amount, $categoryId, $date, $type];

        if ($excludeId !== null && $excludeId > 0) {
            $sql .= ' AND t.transaction_id != ?';
            $params[] = $excludeId;
        }
        $sql .= ' LIMIT 1';

        $found = Database::fetch($sql, $params);
        if ($found) {
            return [
                'is_duplicate' => true,
                'existing'     => $found,
                'message'      => '⚠️ Duplicate warning: A transaction of ' . Helper::money($amount) . ' for "' . Helper::e($found['cat_name']) . '" already exists on ' . Helper::formatDate($date) . '.',
            ];
        }

        return ['is_duplicate' => false, 'message' => ''];
    }

    public static function defaultCategoryName(string $type): string
    {
        return $type === 'income' ? 'Other Income' : 'Miscellaneous';
    }

    public static function matchingCategory(array $userCategories, string $text, string $type, ?int $userId = null): ?array
    {
        $haystack = mb_strtolower(trim($text));
        foreach ($userCategories as $cat) {
            if ($cat['type'] !== $type) continue;
            if (mb_strtolower($cat['name']) === $haystack) {
                return $cat;
            }
            if (mb_strpos($haystack, mb_strtolower($cat['name'])) !== false) {
                return $cat;
            }
        }

        $suggested = self::suggest($text, $type, $userId);
        if ($suggested !== null) {
            foreach ($userCategories as $cat) {
                if ($cat['type'] === $type && mb_strtolower($cat['name']) === mb_strtolower($suggested)) {
                    return $cat;
                }
            }
        }

        $fallback = self::defaultCategoryName($type);
        foreach ($userCategories as $cat) {
            if ($cat['type'] === $type && mb_strtolower($cat['name']) === mb_strtolower($fallback)) {
                return $cat;
            }
        }
        return null;
    }
}
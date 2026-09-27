<?php
declare(strict_types=1);

final class TransactionsController
{
    public function index(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();

        $createdRecurring = Transaction::applyDueRecurring($userId);
        if ($createdRecurring > 0) {
            Helper::info("Auto-applied $createdRecurring recurring transaction(s) for this period.");
        }

        $filters = [
            'type'        => (string) Helper::get('type', ''),
            'category_id' => (int) Helper::get('category_id', 0),
            'month'       => (string) Helper::get('month', date('Y-m')),
            'search'      => trim((string) Helper::get('q', '')),
            'limit'       => 400,
        ];

        $total = Transaction::countAllForUser($userId, $filters);
        $transactions = Transaction::allForUser($userId, $filters);
        $incomeCategories = Category::byTypeForUser($userId, 'income');
        $expenseCategories = Category::byTypeForUser($userId, 'expense');
        $unreadNotifications = self::unreadCount();

        $view = 'pages/transactions';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function add(): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $incomeCategories = Category::byTypeForUser($userId, 'income');
        $expenseCategories = Category::byTypeForUser($userId, 'expense');
        $presetType = (string) Helper::get('type', 'expense');
        if (!in_array($presetType, ['income', 'expense'], true)) {
            $presetType = 'expense';
        }
        $editing = [
            'type' => $presetType,
            'category_id' => 0,
            'amount' => '',
            'description' => '',
            'transaction_date' => date('Y-m-d'),
            'is_recurring' => 0,
            'recurrence' => 'none',
        ];
        $unreadNotifications = self::unreadCount();

        $view = 'pages/transaction-form';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function create(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $this->validateAndSave(null);
    }

    public function edit($id = 0): void
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $id = (int) $id;
        $editing = Transaction::findOwned($id, $userId);
        if ($editing === null) {
            Helper::error('Transaction not found.');
            Helper::redirect('transactions');
        }
        $incomeCategories = Category::byTypeForUser($userId, 'income');
        $expenseCategories = Category::byTypeForUser($userId, 'expense');
        $unreadNotifications = self::unreadCount();

        $view = 'pages/transaction-form';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function update($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $this->validateAndSave((int) $id);
    }

    public function delete($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;
        $userId = Helper::userId();
        $txn = Transaction::findOwned($id, $userId);
        if ($txn === null) {
            Helper::error('Transaction not found.');
            Helper::redirect('transactions');
        }

        try {
            Database::run(
                'INSERT INTO activity_logs (user_id, action_type, entity_type, entity_id, description) VALUES (?, "delete", "transaction", ?, ?)',
                [$userId, $id, 'Deleted ' . ucfirst($txn['type']) . ': ' . ($txn['description'] ?: 'Transaction') . ' (' . Helper::money((float) $txn['amount']) . ')']
            );
        } catch (\Throwable) {}

        Transaction::deleteOwned($id, $userId);
        Transaction::refreshDuplicateFlags($userId);
        Helper::success('Transaction deleted.');
        Helper::redirect('transactions');
    }

    public function check(): never
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $amount = (float) Helper::get('amount', 0);
        $categoryId = (int) Helper::get('category_id', 0);
        $date = (string) Helper::get('date', date('Y-m-d'));
        $type = (string) Helper::get('type', 'expense');
        $excludeId = (int) Helper::get('exclude_id', 0);

        $anomaly = AIClassifier::checkAnomaly($userId, $amount, $type);
        $duplicate = $categoryId > 0 ? AIClassifier::checkDuplicate($userId, $amount, $categoryId, $date, $type, $excludeId ?: null) : ['is_duplicate' => false, 'message' => ''];

        Helper::json([
            'ok'        => true,
            'anomaly'   => $anomaly,
            'duplicate' => $duplicate,
        ]);
    }

    public function suggest(): never
    {
        Helper::requireLogin();
        $userId = Helper::userId();
        $text = trim((string) Helper::get('q', ''));
        $type = (string) Helper::get('type', 'expense');
        if ($text === '') {
            Helper::json(['ok' => true, 'suggestion' => null, 'match' => null]);
        }
        $suggestion = AIClassifier::suggest($text, $type === 'income' ? 'income' : 'expense', $userId);
        $categories = Category::byTypeForUser($userId, $type === 'income' ? 'income' : 'expense');
        $match = null;
        foreach ($categories as $cat) {
            if (mb_strtolower($cat['name']) === mb_strtolower((string) $suggestion)) {
                $match = ['id' => (int) $cat['category_id'], 'name' => $cat['name'], 'icon' => $cat['icon']];
                break;
            }
        }
        Helper::json(['ok' => true, 'suggestion' => $suggestion, 'match' => $match]);
    }

    public function import(): void
    {
        Helper::requireLogin();
        $preview = Session::get('csv_preview', []);

        if (Helper::isPost() && !empty($_FILES['csv_file']['tmp_name'])) {
            Helper::csrfVerify();
            $preview = $this->parseCsv($_FILES['csv_file']['tmp_name']);
            if (empty($preview)) {
                Helper::error('No valid rows found in the CSV file. Download the template and try again.');
            } else {
                Session::set('csv_preview', $preview);
            }
        }

        $allCategories = Category::allForUser(Helper::userId());
        $unreadNotifications = self::unreadCount();
        $view = 'pages/import';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function importConfirm(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $rows = Session::get('csv_preview', []);
        $userCategories = Helper::post('category_override', []);
        $saved = 0;
        $userId = Helper::userId();

        foreach ($rows as $idx => $row) {
            $goodDate = is_string($row['transaction_date'] ?? null) && strtotime($row['transaction_date']) !== false;
            if (!$goodDate || !isset($row['amount']) || $row['amount'] <= 0) {
                continue;
            }

            $catId = isset($userCategories[$idx]) && (int)$userCategories[$idx] > 0
                ? (int)$userCategories[$idx]
                : (int)$row['category_id'];

            Transaction::create([
                'user_id'          => $userId,
                'category_id'      => $catId,
                'type'             => $row['type'],
                'amount'           => $row['amount'],
                'description'      => $row['description'],
                'transaction_date' => $row['transaction_date'],
                'is_recurring'     => false,
                'recurrence'       => 'none',
                'ai_suggested_category' => $row['ai_suggested_category'] ?? null,
                'ai_override'      => ($catId !== (int)$row['category_id']) ? 1 : 0,
            ]);

            if (!empty($row['description'])) {
                AIClassifier::learnCorrection($userId, (string)$row['description'], $catId, $row['type']);
            }
            $saved++;
        }

        Session::delete('csv_preview');
        Transaction::refreshDuplicateFlags($userId);

        try {
            Database::run(
                'INSERT INTO activity_logs (user_id, action_type, entity_type, description) VALUES (?, "create", "csv_import", ?)',
                [$userId, "Imported $saved transactions from CSV"]
            );
        } catch (\Throwable) {}

        Helper::success("Imported $saved transaction(s) successfully from CSV.");
        Helper::redirect('transactions');
    }

    public function template(): never
    {
        Helper::requireLogin();
        $csv = "date,type,amount,description,category\n";
        $csv .= "2026-09-01,income,30000.00,Monthly allowance,Allowance\n";
        $csv .= "2026-09-03,expense,500.00,Campus cafe lunch,Food\n";
        $csv .= "2026-09-05,expense,800.00,Uber ride to university,Transport\n";
        $csv .= "2026-09-10,expense,800.00,Netflix subscription,Subscriptions\n";
        $header = 'attachment; filename="coin_nex-template.csv"';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: ' . $header);
        echo $csv;
        exit;
    }

    private function validateAndSave(?int $id): void
    {
        $userId = Helper::userId();
        $type  = (string) Helper::post('type');
        $amount = (float) Helper::post('amount', 0);
        $categoryId = (int) Helper::post('category_id', 0);
        $description = trim((string) Helper::post('description'));
        $date = (string) Helper::post('transaction_date', date('Y-m-d'));
        $recurring = (bool) Helper::post('is_recurring', false);
        $recurrence = (string) Helper::post('recurrence', 'none');

        if ($type !== 'income' && $type !== 'expense') {
            Helper::error('Invalid transaction type.');
            Helper::redirect($id ? 'transactions/edit/' . $id : 'transactions/add');
        }
        if ($amount <= 0) {
            Helper::error('Amount must be greater than zero.');
            Helper::redirect($id ? 'transactions/edit/' . $id : 'transactions/add');
        }
        if ($categoryId <= 0) {
            Helper::error('Please choose a category.');
            Helper::redirect($id ? 'transactions/edit/' . $id : 'transactions/add');
        }
        $category = Category::findById($categoryId);
        if ($category === null || $category['type'] !== $type) {
            Helper::error('Category does not match transaction type.');
            Helper::redirect($id ? 'transactions/edit/' . $id : 'transactions/add');
        }
        if (!strtotime($date)) {
            Helper::error('Please enter a valid date.');
            Helper::redirect($id ? 'transactions/edit/' . $id : 'transactions/add');
        }
        if ($recurring && !in_array($recurrence, ['weekly', 'monthly'], true)) {
            $recurrence = 'monthly';
        }

        $data = [
            'user_id'          => $userId,
            'category_id'      => $categoryId,
            'type'             => $type,
            'amount'           => $amount,
            'description'      => $description,
            'transaction_date' => date('Y-m-d', strtotime($date)),
            'is_recurring'     => $recurring,
            'recurrence'       => $recurring ? $recurrence : 'none',
        ];

        if ($id === null) {
            $suggestion = AIClassifier::suggest($description, $type, $userId);
            $suggestedMatch = null;
            foreach (Category::byTypeForUser($userId, $type) as $cat) {
                if (mb_strtolower($cat['name']) === mb_strtolower((string) $suggestion)) {
                    $suggestedMatch = $cat;
                    break;
                }
            }
            $aiCatId = $suggestedMatch ? (int) $suggestedMatch['category_id'] : null;
            $data['ai_suggested_category'] = $aiCatId;
            $data['ai_override'] = ($aiCatId !== null && $aiCatId !== $categoryId) ? 1 : 0;

            // Learn correction for future AI suggestions
            if ($description !== '') {
                AIClassifier::learnCorrection($userId, $description, $categoryId, $type);
            }

            $newId = Transaction::create($data);
            Transaction::refreshDuplicateFlags($userId);

            try {
                Database::run(
                    'INSERT INTO activity_logs (user_id, action_type, entity_type, entity_id, description) VALUES (?, "create", "transaction", ?, ?)',
                    [$userId, $newId, 'Logged ' . ucfirst($type) . ': ' . ($description ?: $category['name']) . ' (' . Helper::money($amount) . ')']
                );
            } catch (\Throwable) {}

            Helper::success('Transaction saved!' . ($suggestion !== null ? ' AI matched category: ' . $suggestion : ''));
        } else {
            $existing = Transaction::findOwned($id, $userId);
            if ($existing === null) {
                Helper::error('Transaction not found.');
                Helper::redirect('transactions');
            }
            Transaction::updateTransaction($id, $userId, $data);
            Transaction::refreshDuplicateFlags($userId);

            if ($description !== '') {
                AIClassifier::learnCorrection($userId, $description, $categoryId, $type);
            }

            try {
                Database::run(
                    'INSERT INTO activity_logs (user_id, action_type, entity_type, entity_id, description) VALUES (?, "edit", "transaction", ?, ?)',
                    [$userId, $id, 'Edited ' . ucfirst($type) . ': ' . ($description ?: $category['name']) . ' (' . Helper::money($amount) . ')']
                );
            } catch (\Throwable) {}

            Helper::success('Transaction updated successfully.');
        }
        Helper::redirect('transactions');
    }

    private function parseCsv(string $file): array
    {
        $rows = [];
        $handle = fopen($file, 'r');
        if ($handle === false) {
            return $rows;
        }

        $header = ['date', 'type', 'amount', 'description', 'category'];
        $isFirst = true;
        while (($line = fgetcsv($handle, 0, ',')) !== false) {
            $line = array_map(static fn ($v) => trim((string) $v), $line ?: []);
            if (count(array_filter($line)) === 0) {
                continue;
            }
            if ($isFirst) {
                $firstLine = implode('~', $line);
                if (preg_match('/date|amount|description|type|category/i', $firstLine)) {
                    $isFirst = false;
                    continue;
                }
                $isFirst = false;
            }
            $date = str_replace(['/', '.'], '-', $line[0] ?? '');
            $ts = strtotime($date);
            if ($ts === false) {
                continue;
            }
            $date = date('Y-m-d', $ts);
            $typeRaw = mb_strtolower($line[1] ?? 'expense');
            $type = (($typeRaw === 'income' || $typeRaw === 'credit' || $typeRaw === 'in' || $typeRaw === '+')
                ? 'income' : 'expense');
            $amount = (float) str_replace(['$', 'Rs.', 'Rs', 'PKR', ','], '', $line[2] ?? '0');
            $description = $line[3] ?? '';
            $catName = $line[4] ?? '';

            $categories = Category::byTypeForUser(Helper::userId(), $type);
            $category = null;
            if ($catName !== '') {
                foreach ($categories as $cat) {
                    if (mb_strtolower($cat['name']) === mb_strtolower($catName)) {
                        $category = $cat;
                        break;
                    }
                }
            }
            $suggested = null;
            if ($category === null) {
                $category = AIClassifier::matchingCategory($categories, $description ?: $catName, $type, Helper::userId());
                $suggested = $category['category_id'] ?? null;
            }

            if ($category !== null && $amount > 0) {
                $rows[] = [
                    'transaction_date' => $date,
                    'type'             => $type,
                    'amount'           => round($amount, 2),
                    'description'      => $description,
                    'category_id'      => (int) $category['category_id'],
                    'category_name'    => $category['name'],
                    'category_icon'    => $category['icon'],
                    'ai_suggested_category' => $suggested,
                ];
            }
        }
        fclose($handle);
        return $rows;
    }

    private static function unreadCount(): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
    }
}
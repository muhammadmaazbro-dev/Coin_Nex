<?php
declare(strict_types=1);

final class CategoriesController
{
    public function index(): void
    {
        Helper::requireLogin();
        $categories = Category::allForUser(Helper::userId());
        $income = array_values(array_filter($categories, static fn ($c) => $c['type'] === 'income'));
        $expense = array_values(array_filter($categories, static fn ($c) => $c['type'] === 'expense'));
        $unreadNotifications = self::unreadCount();

        $view = 'pages/categories';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function add(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $name = trim((string) Helper::post('name'));
        $type = (string) Helper::post('type');
        $icon = trim((string) Helper::post('icon')) ?: '📌';

        if ($type !== 'income' && $type !== 'expense') {
            Helper::error('Invalid category type.');
            Helper::redirect('categories');
        }
        if (mb_strlen($name) < 2 || mb_strlen($name) > 50) {
            Helper::error('Category name must be 2–50 characters.');
            Helper::redirect('categories');
        }
        if (Category::nameTakenForUser(Helper::userId(), $name, $type)) {
            Helper::error('A category with that name already exists.');
            Helper::redirect('categories');
        }

        Category::create(Helper::userId(), $name, $type, $icon);
        Helper::success("Category '$name' created!");
        Helper::redirect('categories');
    }

    public function edit($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;
        $category = Category::findOwned($id, Helper::userId());
        if ($category === null) {
            Helper::error('Category not found or it is a system default.');
            Helper::redirect('categories');
        }

        $name = trim((string) Helper::post('name'));
        $icon = trim((string) Helper::post('icon')) ?: '📌';

        if (mb_strlen($name) < 2 || mb_strlen($name) > 50) {
            Helper::error('Category name must be 2–50 characters.');
            Helper::redirect('categories');
        }
        if (Category::nameTakenForUser(Helper::userId(), $name, $category['type'], $id)) {
            Helper::error('A category with that name already exists.');
            Helper::redirect('categories');
        }

        Category::update($id, Helper::userId(), $name, $icon);
        Helper::success("Category updated to '$name'.");
        Helper::redirect('categories');
    }

    public function delete($id = 0): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();
        $id = (int) $id;
        $category = Category::findOwned($id, Helper::userId());
        if ($category === null) {
            Helper::error('Category not found or it is a system default.');
            Helper::redirect('categories');
        }
        if (!Category::delete($id, Helper::userId())) {
            Helper::error("Cannot delete '$category[name]' — it is being used by transactions.");
            Helper::redirect('categories');
        }
        Helper::success("Category '$category[name]' deleted.");
        Helper::redirect('categories');
    }

    public function emoji(): never
    {
        Helper::requireLogin();
        Helper::json(['ok' => true]);
    }

    private static function unreadCount(): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );
    }
}
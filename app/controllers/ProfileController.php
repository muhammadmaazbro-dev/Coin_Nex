<?php
declare(strict_types=1);

final class ProfileController
{
    public function index(): void
    {
        Helper::requireLogin();
        $user = User::findById(Helper::userId());
        $unreadNotifications = (int) Database::scalar(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0',
            [Helper::userId()]
        );

        $view = 'pages/profile';
        require VIEWS_PATH . '/layouts/app-layout.php';
    }

    public function update(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $name = trim((string) Helper::post('name'));
        $year = trim((string) Helper::post('academic_year'));
        $allowance = (float) Helper::post('monthly_allowance_baseline', 0);
        $goal = (float) Helper::post('savings_goal', 0);

        if (mb_strlen($name) < 3) {
            Helper::error('Name must be at least 3 characters.');
            Helper::redirect('profile');
        }

        User::saveProfile(Helper::userId(), [
            'name' => $name,
            'academic_year' => $year ?: null,
            'monthly_allowance_baseline' => max(0, $allowance),
            'savings_goal' => max(0, $goal),
        ]);

        $fresh = User::findById(Helper::userId());
        Session::set('user', [
            'user_id'    => (int) $fresh['user_id'],
            'name'       => $fresh['name'],
            'email'      => $fresh['email'],
            'role'       => $fresh['role'],
            'theme'      => $fresh['theme'],
            'font_scale' => (float) $fresh['font_scale'],
            'currency'   => $fresh['currency'],
        ]);

        Helper::success('Profile updated successfully.');
        Helper::redirect('profile');
    }

    public function password(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $current = (string) Helper::post('current_password');
        $new     = (string) Helper::post('new_password');
        $confirm = (string) Helper::post('confirm_password');

        $user = User::findById(Helper::userId());

        if ($user === null || !password_verify($current, $user['password_hash'])) {
            Helper::error('Current password is incorrect.');
            Helper::redirect('profile');
        }
        if (mb_strlen($new) < 8) {
            Helper::error('New password must be at least 8 characters.');
            Helper::redirect('profile');
        }
        if ($new !== $confirm) {
            Helper::error('New passwords do not match.');
            Helper::redirect('profile');
        }

        User::setPassword(Helper::userId(), password_hash($new, PASSWORD_DEFAULT));
        Helper::success('Password changed successfully.');
        Helper::redirect('profile');
    }

    public function theme(): never
    {
        Helper::requireLogin();
        if (!Helper::isPost()) {
            Helper::abort('Invalid request', 405);
        }
        Helper::csrfVerify();

        $theme = (string) Helper::post('theme') === 'light' ? 'light' : 'dark';
        $fontScale = Helper::clamp((float) Helper::post('font_scale', 1.0), 0.85, 1.2);

        User::update(Helper::userId(), ['theme' => $theme, 'font_scale' => $fontScale]);
        $fresh = User::findById(Helper::userId());
        Session::set('user', [
            'user_id'    => (int) $fresh['user_id'],
            'name'       => $fresh['name'],
            'email'      => $fresh['email'],
            'role'       => $fresh['role'],
            'theme'      => $fresh['theme'],
            'font_scale' => (float) $fresh['font_scale'],
            'currency'   => $fresh['currency'],
        ]);

        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
            Helper::json(['ok' => true, 'theme' => $theme, 'font_scale' => $fontScale]);
        }

        Helper::success('Preferences saved.');
        Helper::redirect('profile');
    }
}
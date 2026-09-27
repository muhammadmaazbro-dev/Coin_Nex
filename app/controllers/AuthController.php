<?php
declare(strict_types=1);

final class AuthController
{
    public function register(): void
    {
        if (Helper::isLoggedIn()) {
            Helper::redirect('dashboard');
        }

        if (Helper::isPost()) {
            Helper::csrfVerify();
            $name  = trim((string) Helper::post('name'));
            $email = strtolower(trim((string) Helper::post('email')));
            $pass  = (string) Helper::post('password');
            $confirm = (string) Helper::post('password_confirm');
            $year  = trim((string) Helper::post('academic_year'));
            $errors = [];

            if (mb_strlen($name) < 3) {
                $errors[] = 'Name must be at least 3 characters.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            } elseif (User::findByEmail($email) !== null) {
                $errors[] = 'An account with this email already exists.';
            }
            if (mb_strlen($pass) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            } elseif ($pass !== $confirm) {
                $errors[] = 'Passwords do not match.';
            }

            if ($errors) {
                Helper::rememberOld(['name' => $name, 'email' => $email, 'academic_year' => $year]);
                foreach ($errors as $message) {
                    Helper::error($message);
                }
                Helper::redirect('register');
            }

            $userId = User::create([
                'name'          => $name,
                'email'         => $email,
                'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
                'academic_year' => $year ?: null,
            ]);

            $this->loginUser($userId);
            Helper::success('Welcome to ' . APP_NAME . '! Your account is ready. 🐝');
            Helper::redirect('dashboard');
        }

        Helper::clearOld();
        $view = 'auth/register';
        require VIEWS_PATH . '/layouts/auth-layout.php';
    }

    public function login(): void
    {
        if (Helper::isLoggedIn()) {
            Helper::redirect('dashboard');
        }

        if (Helper::isPost()) {
            Helper::csrfVerify();
            $email = strtolower(trim((string) Helper::post('email')));
            $pass  = (string) Helper::post('password');

            $user = User::findByEmail($email);

            if ($user === null || !password_verify($pass, $user['password_hash'])) {
                Helper::error('Invalid email or password.');
                Helper::redirect('login');
            }
            if (!$user['is_active']) {
                Helper::error('This account has been disabled. Please contact the administrator.');
                Helper::redirect('login');
            }

            $this->loginUser((int) $user['user_id']);
            Helper::success('Welcome back, ' . $user['name'] . '! 👋');
            Helper::redirect($user['role'] === 'admin' ? 'admin' : 'dashboard');
        }

        Helper::clearOld();
        $view = 'auth/login';
        require VIEWS_PATH . '/layouts/auth-layout.php';
    }

    public function logout(): void
    {
        Session::destroy();
        Session::start();
        Helper::redirect('');
    }

    public function forgotPassword(): void
    {
        if (Helper::isLoggedIn()) {
            Helper::redirect('dashboard');
        }

        if (Helper::isPost()) {
            Helper::csrfVerify();
            $email = strtolower(trim((string) Helper::post('email')));
            $user = User::findByEmail($email);

            if ($user !== null && $user['is_active']) {
                $token = User::createResetToken((int) $user['user_id']);
                $resetUrl = Helper::url('reset-password/' . $token);
                Helper::info('We have emailed you a secure password reset link. For this demo build, use the link below:');
                Helper::info('<a href="' . Helper::e($resetUrl) . '" style="color:var(--brand);font-weight:700;">' . Helper::e($resetUrl) . '</a>');
            } else {
                Helper::error('No account found with that email.');
            }
            Helper::redirect('forgot-password');
        }

        $view = 'auth/forgot';
        require VIEWS_PATH . '/layouts/auth-layout.php';
    }

    public function resetPassword(string $token = ''): void
    {
        if (Helper::isLoggedIn()) {
            Helper::redirect('dashboard');
        }

        $tokenHash = hash('sha256', $token);
        $user = User::findByResetToken($tokenHash);

        if ($user === null) {
            Helper::error('This reset link is invalid or has expired.');
            Helper::redirect('forgot-password');
        }

        if (Helper::isPost()) {
            Helper::csrfVerify();
            $pass = (string) Helper::post('password');
            $confirm = (string) Helper::post('password_confirm');

            if (mb_strlen($pass) < 8) {
                Helper::error('Password must be at least 8 characters long.');
            } elseif ($pass !== $confirm) {
                Helper::error('Passwords do not match.');
            } else {
                User::setPassword((int) $user['user_id'], password_hash($pass, PASSWORD_DEFAULT));
                User::markResetUsed((int) $user['active_reset_id']);
                Helper::success('Password updated! Login with your new password.');
                Helper::redirect('login');
            }
        }

        $view = 'auth/reset';
        require VIEWS_PATH . '/layouts/auth-layout.php';
    }

    private function loginUser(int $userId): void
    {
        Session::regenerate();
        $user = User::findById($userId);
        Session::set('user', [
            'user_id'   => (int) $user['user_id'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'theme'     => $user['theme'],
            'font_scale'=> (float) $user['font_scale'],
            'currency'  => $user['currency'],
        ]);
        User::setLastLogin($userId);
    }
}
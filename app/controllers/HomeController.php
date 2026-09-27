<?php
declare(strict_types=1);

final class HomeController
{
    public function index(): void
    {
        $liveUserData = null;
        if (Helper::isLoggedIn()) {
            $user = Helper::currentUser();
            $userId = (int) $user['user_id'];
            $month = date('Y-m');
            $from = $month . '-01';
            $to = date('Y-m-t', strtotime($from));
            $income = Transaction::totals($userId, 'income', $from, $to);
            $expense = Transaction::totals($userId, 'expense', $from, $to);
            $balance = $income - $expense;
            $savings = max(0, $balance);
            $liveUserData = [
                'name' => $user['name'],
                'first_name' => explode(' ', trim($user['name']))[0],
                'initial' => mb_strtoupper(mb_substr($user['name'] ?? 'U', 0, 1)),
                'email' => $user['email'],
                'month_label' => date('F Y'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance,
                'savings' => $savings,
                'savings_rate' => $income > 0 ? (int) round(($savings / $income) * 100) : 0,
            ];
        }

        $pageTitle = APP_NAME . ' — Student Expense & Budget Tracker';
        $view = 'pages/home';
        require VIEWS_PATH . '/layouts/guest-layout.php';
    }

    public function features(): void
    {
        $pageTitle = 'Features — ' . APP_NAME;
        $view = 'pages/features';
        require VIEWS_PATH . '/layouts/guest-layout.php';
    }

    public function about(): void
    {
        $pageTitle = 'About Us — ' . APP_NAME;
        $view = 'pages/about';
        require VIEWS_PATH . '/layouts/guest-layout.php';
    }

    public function faq(): void
    {
        $pageTitle = 'Frequently Asked Questions — ' . APP_NAME;
        $view = 'pages/faq';
        require VIEWS_PATH . '/layouts/guest-layout.php';
    }

    public function contact(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            Helper::validateCsrf();
            $name    = trim($_POST['name'] ?? '');
            $email   = trim($_POST['email'] ?? '');
            $topic   = trim($_POST['topic'] ?? 'General Inquiry');
            $message = trim($_POST['message'] ?? '');

            if ($name === '' || $email === '' || $message === '') {
                Session::flash('error', 'Please fill in all required fields (Name, Email, and Message).');
                Helper::redirect('contact');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Session::flash('error', 'Please provide a valid university or personal email address.');
                Helper::redirect('contact');
            }

            // In production, an email would be dispatched or stored in a feedback table
            Session::flash('success', 'Shukriya ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Your message has been received. Our student support team will reply within 24 hours.');
            Helper::redirect('contact');
        }

        $pageTitle = 'Contact Coin_Nex Support & Feedback';
        $view = 'pages/contact';
        require VIEWS_PATH . '/layouts/guest-layout.php';
    }
}
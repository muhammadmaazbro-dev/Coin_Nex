<?php
declare(strict_types=1);

define('APP_NAME', 'Coin_Nex');
define('APP_TAGLINE', 'NextGen BudgetBee');
define('APP_VERSION', '1.0.0');
// Auto-detect base URL dynamically so it works on any directory name (e.g. "coin_nex A", "coin_nex", or root)
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = dirname($scriptName);
if (str_ends_with($scriptDir, '/public')) {
    $detectedBase = substr($scriptDir, 0, -7);
} else {
    $detectedBase = $scriptDir;
}
$detectedBase = rtrim($detectedBase, '/\\');
if ($detectedBase === '.' || $detectedBase === '/') {
    $detectedBase = '';
}
define('BASE_URL', $detectedBase);
define('APP_TIMEZONE', 'Asia/Karachi');
define('DEFAULT_CURRENCY_SYMBOL', 'Rs.');
define('DEFAULT_CURRENCY_CODE', 'PKR');
define('SESSION_NAME', 'coin_nex_session');
define('SESSION_LIFETIME', 60 * 60 * 24 * 7);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'coin_nex');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOADS_PATH', ROOT_PATH . '/public/uploads');

date_default_timezone_set(APP_TIMEZONE);

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_only_cookies', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
}

spl_autoload_register(static function (string $class): void {
    $map = [
        APP_PATH . '/core',
        APP_PATH . '/controllers',
        APP_PATH . '/models',
    ];
    foreach ($map as $dir) {
        $file = $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});
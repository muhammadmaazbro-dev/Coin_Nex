<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

echo "=== Coin_Nex SMOKE TEST SUITE ===\n";

// 1. Check Database connection
try {
    $db = Database::pdo();
    echo "✓ Database connected successfully\n";
} catch (Throwable $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check User authentication / mock
$student = User::findByEmail('student@coin_nex.pk');
if ($student) {
    echo "✓ Student account found: {$student['name']} ({$student['email']})\n";
} else {
    echo "! Student account not found\n";
}

$admin = User::findByEmail('admin@coin_nex.pk');
if ($admin) {
    echo "✓ Admin account found: {$admin['name']} ({$admin['email']})\n";
} else {
    echo "! Admin account not found\n";
}

// 3. Scan modified view files for raw emojis
$viewsToScan = [
    'app/views/pages/home.php',
    'app/views/pages/dashboard.php',
    'app/views/pages/admin/index.php',
    'app/views/pages/about.php',
    'app/views/pages/faq.php',
    'app/views/pages/contact.php',
    'app/views/layouts/app-layout.php'
];

$disallowedEmojis = ['🪙', '🎉', '📢', '🛡️', '💸', '💰', '🏠', '📋', '🎯', '🐝', '💡', '📊', '🏷️', '🍔', '🚌', '📚', '🔒', '📐', '🎓', '❓', '📬', '🇵🇰'];

$emojiIssues = 0;
foreach ($viewsToScan as $v) {
    $path = __DIR__ . '/../' . $v;
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);

    foreach ($disallowedEmojis as $emoji) {
        // Exception: favicon svg or css data URIs if necessary, but in our views we want zero raw emojis
        if (str_contains($content, $emoji)) {
            // Check line number
            $lines = explode("\n", $content);
            foreach ($lines as $ln => $line) {
                if (str_contains($line, $emoji)) {
                    // Check if it's the favicon link in head
                    if (str_contains($line, 'rel="icon"')) continue;
                    echo "  ! Found disallowed emoji '{$emoji}' in {$v} at line " . ($ln + 1) . ": " . trim($line) . "\n";
                    $emojiIssues++;
                }
            }
        }
    }
}

if ($emojiIssues === 0) {
    echo "✓ Zero disallowed raw emojis in all scanned views!\n";
} else {
    echo "✗ Found {$emojiIssues} emoji occurrences.\n";
}

// 4. Test rendering of public views
ob_start();
try {
    $view = 'pages/home';
    $user = null;
    $announcements = [];
    $features = [];
    // We can test including the file
    include __DIR__ . '/../app/views/pages/home.php';
    ob_end_clean();
    echo "✓ home.php rendered without throwing\n";
} catch (Throwable $e) {
    ob_end_clean();
    echo "✗ home.php render error: " . $e->getMessage() . "\n";
}

echo "=== ALL SMOKE CHECKS COMPLETE ===\n";

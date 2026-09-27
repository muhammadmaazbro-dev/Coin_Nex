<?php
declare(strict_types=1);
$viewFile = VIEWS_PATH . '/' . $view . '.php';
$pageTitle ??= Helper::pageTitle();
$flashes = Session::flashGet();
?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= Helper::e(APP_NAME) ?> — smart spending tracker for students. Log income &amp; expenses, get savings tips, and review friendly monthly insights.">
    <title><?= Helper::e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Urbanist:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Helper::asset('css/style.css') . '?v=20260926-responsive' ?>">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐝</text></svg>">
    <script>window.CC_BASE = <?= json_encode(BASE_URL) ?>;</script>
    <script src="<?= Helper::asset('js/vendor/three.min.js') ?>"></script>
    <script src="<?= Helper::asset('js/vendor/gsap.min.js') ?>"></script>
    <script src="<?= Helper::asset('js/vendor/ScrollTrigger.min.js') ?>"></script>
</head>
<body class="guest-body">
    <div class="toast-wrap"></div>
    <?php if ($flashes): ?>
    <script>document.addEventListener('DOMContentLoaded', function () {
        const flash = <?= json_encode($flashes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        flash.forEach(f => toast(f.message, f.type));
    });</script>
    <?php endif; ?>

    <?php if (is_file($viewFile)) { require $viewFile; } else { Helper::abort("View not found: $view", 500); } ?>

    <!-- Unified Coin_Nex light theme: loaded after page markup to normalize inline page decoration. -->
    <link rel="stylesheet" href="<?= Helper::asset('css/light-theme-overrides.css') . '?v=20260927-light' ?>">

    <script src="<?= Helper::asset('js/vendor/chart.umd.js') ?>"></script>
    <script src="<?= Helper::asset('js/main.js') ?>"></script>
</body>
</html>
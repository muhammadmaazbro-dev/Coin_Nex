<?php
declare(strict_types=1);
$code = http_response_code() ?: 404;
if (!isset($title)) { $title = "Error $code"; }
if (!isset($message)) { $message = 'Something went wrong'; }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($title) ?> · <?= Helper::e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= Helper::asset('css/style.css') ?>">
</head>
<body class="error-page">
    <div class="error-card animate-up">
        <div class="error-code"><?= Helper::e($code) ?></div>
        <h1><?= Helper::e($title) ?></h1>
        <p><?= Helper::e($message) ?></p>
        <a href="<?= Helper::url('') ?>" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
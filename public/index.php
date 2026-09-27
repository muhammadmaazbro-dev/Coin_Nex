<?php
declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';

Session::start();

App::run();
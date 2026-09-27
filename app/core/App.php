<?php
declare(strict_types=1);

final class App
{
    private const ALIASES = [
        'login'            => ['AuthController', 'login'],
        'register'         => ['AuthController', 'register'],
        'logout'           => ['AuthController', 'logout'],
        'forgot-password'  => ['AuthController', 'forgotPassword'],
        'reset-password'   => ['AuthController', 'resetPassword'],
        'home'             => ['HomeController', 'index'],
        'features'         => ['HomeController', 'features'],
        'about'            => ['HomeController', 'about'],
        'contact'          => ['HomeController', 'contact'],
        'faq'              => ['HomeController', 'faq'],
    ];

    public static function run(): void
    {
        $path = Helper::basePathUri();
        $segments = $path === '/' ? [] : array_values(array_filter(explode('/', $path), static fn ($s) => $s !== ''));

        [$controllerClass, $methodSegment, $paramOffset] = self::resolve($segments);
        $params = array_slice($segments, $paramOffset);

        if ($controllerClass === '' || $methodSegment === '') {
            Helper::abort('Invalid route', 404);
        }
        $controllerFile  = APP_PATH . '/controllers/' . $controllerClass . '.php';

        if (!is_file($controllerFile)) {
            Helper::abort('Page not found', 404);
        }

        require $controllerFile;

        if (!class_exists($controllerClass)) {
            Helper::abort('Controller not found', 404);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodSegment)) {
            Helper::abort('Action not found', 404);
        }

        if (!is_callable([$controller, $methodSegment])) {
            Helper::abort('Action not callable', 403);
        }

        $controller->{$methodSegment}(...array_values($params));
    }

    private static function resolve(array $segments): array
    {
        $slug = $segments[0] ?? 'home';

        if (isset(self::ALIASES[$slug])) {
            [$controller, $method] = self::ALIASES[$slug];
            return [$controller, $method, 1];
        }

        $controller = preg_replace('/[^a-zA-Z0-9_]/', '', $slug);
        $method     = preg_replace('/[^a-zA-Z0-9_]/', '', $segments[1] ?? 'index');

        if ($controller === '') {
            return ['', '', 0];
        }

        return [ucfirst($controller) . 'Controller', $method, 2];
    }
}
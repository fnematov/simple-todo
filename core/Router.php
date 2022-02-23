<?php

use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

class Router
{
    public static function handle()
    {
        $route = Request::get('r');
        $params = Request::getAll();
        $controller = 'site';
        $action = 'index';

        if ($route) {
            unset($params['r']);
            $routeParams = explode('/', $route);
            if (isset($routeParams[0])) $controller = $routeParams[0];
            if (isset($routeParams[1])) $action = $routeParams[1];
        }
        $controllerName = ucfirst($controller) . 'Controller';
        if (class_exists($controllerName)) {
            $controller = new $controllerName;
            if (method_exists($controller, $action)) {
                call_user_func([$controller, $action], $params);
                die;
            }
        }
        http_response_code(404);
        include(__DIR__ . '/../views/error.php');
        die;
    }

    public static function to(string $name, array $params = []): string
    {
        $route = '?r=' . $name;

        foreach ($params as $key => $param) {
            $route .= "&$key=$param";
        }

        return $route;
    }

    #[Pure] public static function current(array $params = []): string
    {
        $route = Request::get('r');
        $hasQuestionLabel = false;
        if ($route) $hasQuestionLabel = true;

        $nextRoute = $hasQuestionLabel ? '?r=' . Request::get('r') : '';

        foreach ($params as $key => $value) {
            $nextRoute .= ($hasQuestionLabel ? "&" : "?");
            $nextRoute .= "$key=$value";
            $hasQuestionLabel = true;
        }

        return $nextRoute;
    }

    #[NoReturn] public static function redirect(string $name)
    {
        header("Location: ?r=" . $name);
        exit();
    }
}
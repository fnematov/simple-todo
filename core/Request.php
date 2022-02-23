<?php

use JetBrains\PhpStorm\Pure;

class Request
{
    public static function getMethod(): string
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        if ($method === "post" && isset($_POST['_method'])) {
            return strtolower($_POST['_method']);
        }

        return $method;
    }

    public static function get($name = null, $default = null): string|array|null
    {
        return isset($_GET[$name]) ? htmlspecialchars($_GET[$name]) : $default;
    }

    public static function getAll(): array
    {
        return $_GET;
    }

    public static function post($name = null, $default = null): string|array|null
    {
        return isset($_POST[$name]) ? htmlspecialchars($_POST[$name]) : $default;
    }

    #[Pure] public static function isPost(): bool
    {
        return self::getMethod() === 'post';
    }
}
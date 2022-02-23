<?php

use JetBrains\PhpStorm\NoReturn;

if (!function_exists('dd')) {
    #[NoReturn] function dd(...$args)
    {
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
            echo "<br>";
        }
        echo "</pre>";
        die;
    }
}
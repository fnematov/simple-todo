<?php

class Controller
{
    public function __construct()
    {
        if (session_id() == "") session_start();
    }

    public function render(string $view, array $data = [])
    {
        extract($data);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/' . $view . '.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
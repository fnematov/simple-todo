<?php

trait Authenticate
{
    public static function guest()
    {
        return !isset($_SESSION['user']);
    }

    public static function login(Model $user)
    {
        $_SESSION['user'] = $user->id;
    }

    public static function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
    }
}
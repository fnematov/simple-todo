<?php

class AuthController extends Controller
{
    public function login()
    {
        if (!User::guest()) Router::redirect('admin/index');

        $errors = [];
        if (Request::isPost()) {
            if (!Request::post('username') || !Request::post('password')) {
                $errors[] = "Username and password is required";
            } else {
                $user = User::query()->where('username', '=', Request::post('username'))->one();
                if (!$user || hash('sha256', Request::post('password')) !== $user->password) {
                    $errors[] = "Invalid username or password";
                } else {
                    User::login($user);
                    Router::redirect('admin/index');
                }

            }
        }

        $this->render('login', ['errors' => $errors]);
    }

    public function logout()
    {
        User::logout();
        Router::redirect('site/index');
    }
}
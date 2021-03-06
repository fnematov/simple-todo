<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Router;
use App\Models\ToDo;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (User::guest()) {
            Router::redirect('auth/login');
        }
    }

    public function index()
    {
        $todos = ToDo::query()
            ->orderBy('id')
            ->paginate(3, Request::get('page', 1));

        $this->render('admin/index', ['todos' => $todos]);
    }

    public function update()
    {
        $todo = ToDo::query()->where('id', '=', Request::get('id'))->one();
        if (!$todo) Router::redirect('error');

        if (Request::isPost()) {
            $todo->fill([
                'content' => Request::post('content'),
                'status' => Request::post('status') ?? 0
            ]);
            $todo->save();
            Router::redirect('admin/index');
        }

        $this->render('admin/update', ['todo' => $todo]);
    }
}
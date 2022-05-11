<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Router;
use App\Models\ToDo;

class SiteController extends Controller
{
    public function index()
    {
        $todos = ToDo::query()
            ->orderBy(Request::get('sort', 'id'), Request::get('direction', 'asc'))
            ->paginate(3, Request::get('page', 1));
        $this->render('index', [
            'todos' => $todos
        ]);
    }

    public function create()
    {
        if (Request::isPost()) {
            $model = new ToDo();
            $model->fill([
                'name' => Request::post('name'),
                'email' => Request::post('email'),
                'content' => Request::post('content'),
                'status' => 0
            ]);
            $model->save();
            Router::redirect("site/index");
        }

        $this->render('create');
    }
}
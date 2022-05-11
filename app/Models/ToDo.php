<?php

namespace App\Models;

use App\Core\Model;

class ToDo extends Model
{
    protected string $table = 'todos';

    public array $attributes = ['name', 'email', 'content', 'status'];
}
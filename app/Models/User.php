<?php

namespace App\Models;

use App\Core\Model;
use App\Traits\Authenticate;

class User extends Model
{
    use Authenticate;

    protected string $table = "users";
}
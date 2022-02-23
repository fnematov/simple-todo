<?php

class User extends Model
{
    use Authenticate;

    protected string $table = "users";
}
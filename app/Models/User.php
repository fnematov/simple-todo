<?php

class User extends Model
{
    use Authenticate;

    protected string $table = "users";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
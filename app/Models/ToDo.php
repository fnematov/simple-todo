<?php

class ToDo extends Model
{
    protected string $table = 'todos';

    public array $attributes = ['name', 'email', 'content', 'status'];
}
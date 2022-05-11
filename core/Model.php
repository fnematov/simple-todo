<?php

namespace App\Core;

class Model
{
    protected string $table = '';
    public bool $exists = false;
    public array $attributes = [];
    public string $primaryKey = 'id';

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function query(): Database
    {
        return (new static)->newQuery();
    }

    private function newQuery(): Database
    {
        return (new Database())->setModel($this);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function fill(array $data)
    {
        foreach ($data as $key => $datum) {
            $this->{$key} = $datum;
        }
    }

    public function instance(array $attributes = []): self
    {
        return new static($attributes);
    }

    public function save()
    {
        $query = $this->newQuery();
        if ($this->exists) $this->performUpdate($query);
        else $this->performInsert($query);
    }

    public function performUpdate(Database $query)
    {
        $values = $this->prepareValues();
        $query->update($values);
    }

    public function performInsert(Database $query)
    {
        $values = $this->prepareValues();
        $query->insert($values);
    }

    private function prepareValues(): array
    {
        $values = [];
        foreach ($this->attributes as $attribute) {
            $values[$attribute] = $this->{$attribute};
        }
        return $values;
    }
}
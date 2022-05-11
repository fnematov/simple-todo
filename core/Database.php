<?php

namespace App\Core;

use PDO;
use PDOException;
use stdClass;

class Database
{
    private PDO $connection;
    protected Model $model;
    private string $sql = '';
    private string $countSql = '';
    private string $tableName = '';
    private array $select = [];
    private array $where = [];
    private array $orderBy = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $queryParams = [];
    private bool $enablePagination = false;

    public function __construct()
    {
        $configFile = include(__DIR__ . '/../config/index.php');
        $config = $configFile['database'];
        try {
            $this->connection = new PDO(
                $config['driver'] . ":host=" . $config['host'] . ";port=" . $config["port"] . ";dbname=" . $config['dbname'],
                $config['username'],
                $config['password']
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    private function clearQuery()
    {
        $this->sql = '';
        $this->countSql = '';
        $this->tableName = '';
        $this->select = [];
        $this->where = [];
        $this->orderBy = [];
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;
        $this->tableName = $model->getTable();
        return $this;
    }

    public function newModel(array $attributes = []): Model
    {
        return $this->model->instance($attributes);
    }

    public function select(array $columns = ["*"]): self
    {
        $this->select = $columns;
        return $this;
    }

    public function where(string $column, string $condition = null, string $value = null): self
    {
        $this->where[] = [$column, $condition, $value];
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = [$column, $direction];
        return $this;
    }

    public function get()
    {
        return $this->buildSelectQuery()->execute();
    }

    public function paginate($perPage = 12, $page = 1)
    {
        $this->limit = $perPage;
        $this->offset = ($page - 1) * $perPage;
        $this->enablePagination = true;
        return $this->buildSelectQuery()->execute();
    }

    public function one()
    {
        return $this->buildSelectQuery()->execute(true);
    }

    public function insert(array $values)
    {
        $this->buildInsert($values);
        $this->execute();
    }

    public function update(array $values)
    {
        $this->buildUpdate($values);
        $this->execute();
    }

    private function execute(bool $single = false)
    {
        $pdo = $this->connection->prepare($this->sql);
        $pdo->setFetchMode(PDO::FETCH_ASSOC);
        $pdo->execute($this->queryParams);

        if ($single) $result = $pdo->fetch();
        else $result = $pdo->fetchAll();

        $totalItemsCount = null;
        if ($this->enablePagination) {
            $pdoCount = $this->connection->prepare($this->countSql);
            $pdoCount->execute();
            $totalItemsCount = $pdoCount->fetchColumn();
        }
        $this->clearQuery();
        return $this->convertToModel($result, $single, $totalItemsCount);
    }

    private function convertToModel($result, bool $single = false, $count = null)
    {
        if ($single) {
            return $result ? $this->newModel(array_merge($result, ['exists' => true])) : null;
        } else {
            $res = new stdClass();
            $res->items = [];
            foreach ($result as $item) {
                $res->items[] = $this->newModel($item);
            }
            if ($count > 0) {
                $res->total_pages = ceil($count / $this->limit);
                $res->total_items = $count;
                $res->current_page = $this->offset / $this->limit + 1;
            }
            return $res;
        }
    }

    private function buildSelectQuery(): self
    {
        $this->buildSelect();
        $this->buildWhere();
        $this->buildOrderBy();
        $this->buildLimit();
        $this->buildOffset();
        return $this;
    }

    private function buildLimit()
    {
        if ($this->limit != null) {
            $this->sql .= " LIMIT :limit";
            $this->queryParams[':limit'] = $this->limit;
        }
    }

    private function buildOffset()
    {
        if ($this->offset != null) {
            $this->sql .= " OFFSET :offset";
            $this->queryParams[':offset'] = $this->offset;
        }
    }

    private function buildInsert(array $values)
    {
        $this->sql = "INSERT INTO " . $this->tableName .
            " (" . implode(',', array_keys($values)) . ") VALUES " .
            " (:" . implode(",:", array_keys($values)) . ")";

        foreach ($values as $key => $value) {
            $this->queryParams[':' . $key] = $value;
        }
    }

    private function buildUpdate(array $values)
    {
        $this->sql = "UPDATE " . $this->tableName . " SET ";

        $count = 1;
        foreach ($values as $key => $value) {
            $this->sql .= ($key . " = :" . $key . ($count != count($values) ? ', ' : ''));
            $this->queryParams[':' . $key] = $value;
            $count++;
        }

        $this->sql .= (" WHERE " . $this->model->primaryKey . " = :" . $this->model->primaryKey);
        $this->queryParams[":" . $this->model->primaryKey] = $this->model->{$this->model->primaryKey};
    }

    private function buildSelect()
    {
        if (count($this->select) > 0) $this->sql = "SELECT " . implode(", ", $this->select) . " FROM " . $this->tableName;
        else $this->sql = "SELECT * FROM " . $this->tableName;

        $this->countSql = "SELECT COUNT(*) FROM " . $this->tableName;
    }

    private function buildWhere()
    {
        if (count($this->where) > 0) {
            $this->sql = $this->sql . " WHERE ";
            $this->countSql = $this->countSql . " WHERE ";
            foreach ($this->where as $key => $where) {
                if ($key > 0) $this->sql .= " AND ";
                if ($key > 0) $this->countSql .= " AND ";
                $this->sql .= ($where[0] . $where[1] . ":" . $where[0]);
                $this->countSql .= ($where[0] . $where[1] . ":" . $where[2]);
                $this->queryParams[":$where[0]"] = $where[2];
            }
        }
    }

    private function buildOrderBy()
    {
        if (count($this->orderBy) > 0) {
            $this->sql = $this->sql . " ORDER BY ";
            foreach ($this->orderBy as $key => $orderBy) {
                if ($key > 0) $this->sql .= ", ";
                $this->sql .= ($orderBy[0] . " " . $orderBy[1]);
            }
        }
    }
}
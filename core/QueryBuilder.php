<?php

trait QueryBuilder
{
    public $tableName = '';
    public $where = '';
    public $operator = '';
    public $selectField = '*';
    public $limit = '';
    public $orderBy = '';
    public $innerJoin = '';


    public function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function where($field, $compare, $value)
    {
        if (empty($this->where)) {
            $this->operator = 'WHERE ';
        } else {
            $this->operator = ' AND ';
        }
        $this->where .= "$this->operator {$field} {$compare} '$value'";

        return $this;
    }

    public function orWhere($field, $compare, $value)
    {
        if (empty($this->where)) {
            $this->operator = 'WHERE ';
        } else {
            $this->operator = ' OR ';
        }
        $this->where .= "$this->operator {$field} {$compare} '$value'";

        return $this;
    }

    public function whereLike($field, $value)
    {
        if (empty($this->where)) {
            $this->operator = 'WHERE ';
        } else {
            $this->operator = ' AND ';
        }
        $this->where .= "$this->operator {$field} LIKE '$value'";

        return $this;
    }

    public function select($field = '*')
    {
        $this->selectField = $field;

        return $this;
    }

    /**
     * Inner Join
     *
     * @param string $tableName Tên bảng
     * @param string $relationship Quan hệ
     */
    public function join($tableName, $relationship)
    {
        if (!empty($tableName) && !empty($relationship)) {
            $this->innerJoin = "INNER JOIN {$tableName} ON {$relationship} ";
        }

        return $this;
    }

    public function orderBy($field, $type = 'ASC')
    {
        if (!empty($field)) {
            if (empty($this->orderBy)) {
                $this->orderBy = "ORDER BY {$field} {$type}";
            } else {
                $this->orderBy .= ", {$field} {$type}";
            }
        }
        return $this;
    }

    public function limit($number, $offset = 0)
    {
        if (!empty($number)) {
            $this->limit = "LIMIT $number OFFSET $offset";
        }
        return $this;
    }

    public function get()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where $this->orderBy $this->limit";
        $query = $this->query($sqlQuery,[],true);

        // reset query
        $this->resetQuery();

        if (!empty($query)) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function first()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->where LIMIT 1";
        $query = $this->query($sqlQuery,[],true);

        // reset query
        $this->resetQuery();

        if (!empty($query)) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Insert data
     *
     * @param array $data Mảng dữ liệu
     */
    public function insert($data)
    {
        $tableName = $this->tableName;
        $insertStatus = $this->insertData($tableName, $data);
        return $insertStatus;
    }

    public function lastId()
    {
        return $this->getInsertId();
    }

    public function update($data)
    {
        $tableName = $this->tableName;
        $where = $this->where;
        $whereUpdate = str_replace('WHERE', '', $where);
        if (!empty($whereUpdate)) {
            $updateStatus = $this->updateData($tableName, $data, $whereUpdate);
            return $updateStatus;
        }
        return false;
    }

    public function delete()
    {
        $tableName = $this->tableName;
        $where = $this->where;
        $whereDelete = str_replace('WHERE', '', $where);
        if (!empty($whereDelete)) {
            $deleteStatus = $this->deleteData($tableName, $whereDelete);
            return $deleteStatus;
        }
        return false;
    }

    public function resetQuery()
    {
        // reset query
        $this->tableName = '';
        $this->where = '';
        $this->operator = '';
        $this->selectField = '*';
        $this->limit = '';
        $this->orderBy = '';
        $this->innerJoin = '';
    }
}
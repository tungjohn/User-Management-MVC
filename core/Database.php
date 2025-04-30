<?php
/**
 * Class Database
 * @package App\Database
 * Class này dùng để tương tác với csdl
 * Phương thức __construct trong Class này dùng để kết nối csdl
 * Class này sử dụng kết nối csdl từ class Connection
 */

class Database
{
    private $__conn;

    private $sql = null;

    use QueryBuilder;
    public function __construct()
    {
        global $db_config;
        $this->__conn = Connection::getInstance($db_config);
    }

    public function query($sql, $data = [], $statementStatus = false) {

        $this->sql = $sql;
        $query = false;

        try {
            $statement = $this->__conn->prepare($this->sql);

            if (empty($data)) {
                $query = $statement->execute();
            } else {
                $query = $statement->execute($data);
            }
        } catch (Exception $exception) {
            $this->getException($exception);
        }

        if ($statementStatus && $query) {
            return $statement;
        }

        return $query;
    }

    // báo lỗi
    public function getException($exception) {
        $mess = $exception->getMessage();
        App::$app->loadError('database', ['message' => $mess]);
        die(); // Dừng tất cả chương trình
    }

    private function fetch($sql) {
        $statement = $this->query($sql, [], true);
        if (is_object($statement)) {
            return $statement;
        }

        return false;
    }

    /**
     * Phương thức lấy tất cả bản ghi dựa vào câu lệnh sql
     */
    public function getRaw($sql) {
        $statement = $this->fetch($sql);

        if (!empty($statement)) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
    }

    /**
     * Phương thức lấy một bản ghi đầu tiên dựa vào câu lệnh sql
     */
    public function firstRaw($sql) {
        $statement = $this->fetch($sql);

        if (!empty($statement)) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    /**
     * Phương thức insert
     */

    public function insertData($table, $dataInsert) {
        $keyArr = array_keys($dataInsert);
        $fieldStr = implode(', ', $keyArr);
        $valueStr = ':'.implode(', :', $keyArr);
        $sql = "INSERT INTO $table ($fieldStr) VALUES ($valueStr)";

        return $this->query($sql, $dataInsert);
    }

    /**
     * Phương thức update
     */
    public function updateData($table, $dataUpdate, $condition = '') {
        $keyArr = array_keys($dataUpdate);
        $fieldStr = '';
        foreach ($keyArr as $key) {
            $fieldStr .= $key . ' = :' . $key . ', ';
        }
        $fieldStr = rtrim($fieldStr, ', ');
        if (!empty($condition)) {
            $sql = "UPDATE $table SET $fieldStr WHERE $condition";
        } else {
            $sql = "UPDATE $table SET $fieldStr";
        }

        return $this->query($sql, $dataUpdate);
    }

    /**
     * Phương thức delete
     */
    public function deleteData($table, $condition = '') {
        if (!empty($condition)) {
            $sql = "DELETE FROM $table WHERE $condition";
        } else {
            $sql = "DELETE FROM $table";
        }

        return $this->query($sql);
    }

    /**
     * Phương thức lấy số bản ghi của câu lệnh sql
     */

    public function getRows($sql) {
        $statement = $this->query($sql, [], true);
        if (!empty($statement)) {
            return $statement->rowCount();
        }

        return 0;
    }

    /**
     * Phương thức lấy id vừa insert
     */

    public function getInsertId() {
        return $this->__conn->lastInsertId();
    }

    /**
     * Phương thức lấy đối tượng PDO
     */
    public function getPDO() {
        return $this->__conn;
    }
}
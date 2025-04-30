<?php
/**
 * Base model Singleton
 * Kế thừa class Database
 *
 */
abstract class Model extends Database
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    abstract function tableFill();

    abstract function fieldFill();

    abstract function primaryKeyFill();

    // lấy toàn bộ dữ liệu
    public function all() {
        $tableName = $this->tableFill();
        $tableName = empty($tableName) ? 'users' : $tableName;
        
        $fieldSelect = $this->fieldFill();
        $fieldSelect = empty($fieldSelect) ? '*' : $fieldSelect;
        
        $sql = "SELECT $fieldSelect FROM $tableName";
        $data = $this->db->getRaw($sql);
        return $data;
    }

    // lấy dữ liệu đầu tiên
    public function first() {
        $tableName = $this->tableFill();
        $tableName = empty($tableName) ? 'users' : $tableName;

        $fieldSelect = $this->fieldFill();
        $fieldSelect = empty($fieldSelect) ? '*' : $fieldSelect;

        $sql = "SELECT $fieldSelect FROM $tableName";
        $data = $this->db->firstRaw($sql);
        return $data;
    }

    public function find($id) {
        $tableName = $this->tableFill();
        $tableName = empty($tableName) ? 'users' : $tableName;

        $fieldSelect = $this->fieldFill();
        $fieldSelect = empty($fieldSelect) ? '*' : $fieldSelect;

        $primaryKey = $this->primaryKeyFill();
        $primaryKey = empty($primaryKey) ? 'id' : $primaryKey;

        $sql = "SELECT $fieldSelect FROM $tableName WHERE $primaryKey = $id";
        $data = $this->db->firstRaw($sql);
        return $data;
    }
}
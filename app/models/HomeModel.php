<?php
/**
 * kế thừa từ class Model
 */
class HomeModel extends Model {

    public function tableFill() {
        return 'table';
    }

    public function fieldFill() {
        return 'field';
    }

    public function primaryKeyFill() {
        return 'id';
    }

    public function getInsert()
    {
        return $this->db->getInsertId();
    }
}
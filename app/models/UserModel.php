<?php
/**
 * UserModel Model
 */
class UserModel extends Model {

    public function tableFill() {
        return 'users';
    }

    public function fieldFill() {
        return '*';
    }

    public function primaryKeyFill() {
        return 'id';
    }

    public function getAllUser()
    {
        $data = $this->db->select('users.*, `groups`.name as group_name')
            ->table($this->tableFill())
            ->join('`groups`', 'users.group_id = `groups`.id')
            ->orderBy('users.id', 'DESC')
            ->get();
        return $data;
    }
}
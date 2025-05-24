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

    public function getListUser($condition = [])
    {
        $data = $this->db->select('users.*, `groups`.name as group_name')
            ->table($this->tableFill())
            ->join('`groups`', 'users.group_id = `groups`.id')
            ->orderBy('users.id', 'DESC');
        
        if (!empty($condition)) {
            if (isset($condition['group_id']) && !empty($condition['group_id'])) {
                $data->where('users.group_id', '=', $condition['group_id']);
            }
            if (isset($condition['status']) && !empty($condition['status'])) {
                $data->where('users.status', '=', $condition['status']);
            }
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $data->where(function($query) use ($condition) {
                    $query->where('users.name', 'LIKE', '%' . $condition['keyword'] . '%')
                          ->orWhere('users.email', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }
        }
        
        // phân trang
        $data = $data->paginate(5);
        return $data;
    }

    public function getGroups()
    {
        $groups = $this->db->select('id, name')
            ->table('`groups`')
            ->orderBy('id', 'DESC')
            ->get();
        return $groups;
    }

    public function getStatus()
    {
        $status = [
            1 => 'Kích hoạt',
            2 => 'Chưa kích hoạt',
        ];
        return $status;
    }
}
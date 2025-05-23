<?php
/** 
 * QueryBuilder Trait
*/
trait QueryBuilder
{
    public $tableName = '';
    public $where = '';
    public $operator = '';
    public $selectField = '*';
    public $limit = '';
    public $orderBy = '';
    public $innerJoin = '';
    public $openingParenthesis = false;

    public function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function where($field, $compare = null, $value = null)
    {
        // Logical Grouping
        // Nếu $field là một Closure, tức là một hàm ẩn danh
        if ($field instanceof Closure) {
            // Bắt đầu một nhóm điều kiện với toán tử AND
            $this->where .= ' AND (';
            // Đánh dấu mở ngoặc đơn để không thêm operator vào đầu
            $this->openingParenthesis = true;
            // Gọi hàm closure để thực hiện các điều kiện bên trong
            $field($this);
            // Đóng ngoặc đơn sau khi thực hiện các điều kiện
            $this->where .= ')';
            // Trả về đối tượng hiện tại để tiếp tục chuỗi phương thức
            return $this;
        }

        if (empty($this->where)) {
            $this->operator = 'WHERE ';
        } else {
            $this->operator = ' AND ';
            if ($this->openingParenthesis) {
                // Nếu đã mở ngoặc đơn thì không thêm operator vào đầu
                $this->operator = '';
                // Đặt lại biến mở ngoặc đơn để không ảnh hưởng đến các điều kiện tiếp theo
                $this->openingParenthesis = false;
            } 
        }
        $this->where .= "$this->operator {$field} {$compare} '$value'";

        return $this;
    }

    public function orWhere($field, $compare = null, $value = null)
    {
        // Logical Grouping
        // Nếu $field là một Closure, tức là một hàm ẩn danh
        if ($field instanceof Closure) {
            // Bắt đầu một nhóm điều kiện với toán tử OR
            $this->where .= ' OR (';
            // Đánh dấu mở ngoặc đơn để không thêm operator vào đầu
            $this->openingParenthesis = true;
            // Gọi hàm closure để thực hiện các điều kiện bên trong
            $field($this);
            // Đóng ngoặc đơn sau khi thực hiện các điều kiện
            $this->where .= ')';
            // Trả về đối tượng hiện tại để tiếp tục chuỗi phương thức
            return $this;
        }

        if (empty($this->where)) {
            $this->operator = 'WHERE ';
        } else {
            $this->operator = ' OR ';
            if ($this->openingParenthesis) {
                // Nếu đã mở ngoặc đơn thì không thêm operator vào đầu
                $this->operator = '';
                // Đặt lại biến mở ngoặc đơn để không ảnh hưởng đến các điều kiện tiếp theo
                $this->openingParenthesis = false;
            } 
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
        $sqlQuery = $this->toSql() . " $this->limit";
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
        $sqlQuery = $this->toSql() . " LIMIT 1";
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

    /**
     * Phân trang
     * @param int $perPage Số bản ghi trên mỗi trang
     * 
     */
    public function paginate($perPage = 10)
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);

        $offset = ($page - 1) * $perPage;
        $baseUrl = $this->getBaseUrl($page);
    
        $sqlQuery = $this->toSql() . " LIMIT $perPage OFFSET $offset";
        $data = $this->query($sqlQuery,[],true)->fetchAll(PDO::FETCH_ASSOC);

        // Đếm tổng số dòng
        $countSql = "SELECT COUNT(*) as total FROM ({$this->toSql()}) as sub";
        $total = $this->query($countSql,[],true)->fetchColumn();

        return new Paginator($data, $baseUrl, $total, $perPage, $page);
    }

    public function toSql()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where $this->orderBy";
        return $sqlQuery;
    }

    public function getBaseUrl($page)
    {
        $baseUrl = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $baseUrl = str_replace('&page=' . $page, '', $baseUrl);
        $baseUrl = str_replace('?page=' . $page, '', $baseUrl);

        return $baseUrl;
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
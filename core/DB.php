<?php
/**
 * Global query builder, hỗ trợ truy vấn database ở nhiều nơi
 */

class DB
{

    public $db;

    public function __construct() {
        $this->db = new Database();
    }
}
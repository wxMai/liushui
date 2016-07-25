<?php

class Basic_model extends CI_Model{

    #操作的数据表
    private $table = '';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取一条记录
     * @param Array $where 条件 key => value
     * @param boolean $field 要筛选的字段
     * @param array $join_list  array(
     *                  array{
     *                          'table' =>
     *                          'condition' => 'a.id = b.id',
     *                          'method' => 'inner|left|right'
     *                         })
     * @return Array
     */
    public function get_one($where = array(),$field = false,$join_list = array()){
        $field && $this->db->select($field);
        $this->db->from($this->table);
        $where && $this->db->where($where);
        $this->_join_table($join_list);
        $result = $this->db->get();
        return $result->first_row();
    }

    /**
     * 设置操作的数据表
     * @param string $table
     * @return $this
     */
    public function set_table($table = ''){
        $this->table = $table;
        return $this;
    }

    /**
     * 联合查询中的 join 表
     * @param array $join_list
     */
    private function _join_table($join_list = array()) {
        if ($join_list && is_array($join_list)) {
            foreach ($join_list as $join) {
                $this->db->join($join['table'], $join['condition'], $join['method']);
            }
        }
    }

    /**
     * 插入
     * @param array $data
     * @return bool
     */
    public function insert($data) {
        $this->check_table();
        $result = $this->db->insert($this->table, $data);
        return $result?$this->db->insert_id():false;
    }

    /**
     * 检查是否已经 set_table
     */
    public function check_table() {
        if (empty($this->table))
            exit('table is empty');
    }

    /**
     * @param bool $field
     * @param bool $where
     * @param $join_list
     */
    public function get_list($field = false,$where = false,$join_list = false){

        $this->check_table();
        $field && $this->db->select($field);
        $where && $this->db->where($where);
        if($join_list){
            $this->_join_table($join_list);
        }
        $query = $this->db->get($this->table);
        return $query->result('array');
    }

}
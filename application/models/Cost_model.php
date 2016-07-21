<?php

class Cost_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->model('basic_model');
    }

    /**
     * 添加花费信息
     * @param $costInfo
     * @param $user
     * @return mixed
     */
    public function add_cost($costInfo,$user){
        $data['UserId'] = $user;
        $data['UseName'] = isset($costInfo['UseName'])?$costInfo['UseName']:'';
        $data['num'] = floatval($costInfo['Money']);
        $data['time'] = isset($costInfo['time'])?$costInfo['time']:date('y-m-d H:i:s',time());
        $data['cost_type'] = isset($costInfo['type'])?$costInfo['type']:'默认';
        $data['note'] = isset($costInfo['note'])?$costInfo['note']:'';
        return $this->db->insert('cost', $data);
    }

    /**
     * 获取花费列表 - 年
     * @return mixed
     */
    public function get_cost_list(){
        $query = $this->db->query('SELECT SUM(num) as num,UseName,EXTRACT(month FROM time) as month FROM cost GROUP BY UseName,month ORDER BY month DESC');
        $result['list'] = $query->result('array');
        $query = $this->db->query('SELECT SUM(num) as num,UseName FROM cost GROUP BY UseName');
        $result['total'] = $query->result('array');
        return $result;
    }

    /**
     * 获取花费列表 - 月
     * @param int $month
     * @return mixed
     */
    public function get_month_list($month = 1){
        $query = $this->db->query('SELECT SUM(num) AS num,UseName,EXTRACT(day FROM time) as day FROM cost WHERE EXTRACT(month FROM time)='.$month.' GROUP BY UseName,day ORDER BY day DESC');
        $result['list'] = $query->result('array');
        $query = $this->db->query('SELECT SUM(num) AS num,UseName FROM cost WHERE EXTRACT(month FROM time)='.$month.' GROUP BY UseName');
        $result['total'] = $query->result('array');
        return $result;
    }

    /**
     * 获取花费列表 - 日
     * @param int $month
     * @param int $day
     * @return mixed
     */
    public function get_day_list($month = 1,$day = 1){
        $where = array(
            'EXTRACT(month FROM time) =' => $month,
            'EXTRACT(day FROM time) =' => $day
        );
        $join_list = array(
            array(
                'table' => 'user',
                'condition' => 'user.UserId = cost.UserId',
                'method' => 'INNER'
            )
        );
        return $this->basic_model->set_table('cost')->get_list(false,$where,$join_list);
    }

    /**
     * 获取用户名称列表
     * @return mixed
     */
    public function get_UserName_list(){
        $this->load->model('User_model');
        return $this->User_model->get_UserName_list();
    }

    /**
     * 添加花费类型
     * @param $data
     * @return mixed
     */
    public function add_cost_type($data){
        return $this->basic_model->set_table('costType')->insert($data);
    }

    /**
     * 获取花费类型列表
     * @return mixed
     */
    public function get_costType_list(){
        $field = false;
        $where = false;
        return $this->basic_model->set_table('costType')->get_list($field,$where);
    }

    

}
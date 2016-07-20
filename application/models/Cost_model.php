<?php

class Cost_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->model('basic_model');
    }

    public function add_cost($costInfo,$user){
        $data['UserId'] = $user;
        $data['UseName'] = isset($costInfo['UseName'])?$costInfo['UseName']:'';
        $data['num'] = intval($costInfo['Money']);
        $data['time'] = isset($costInfo['time'])?$costInfo['time']:date('y-m-d H:i:s',time());
        $data['cost_type'] = isset($costInfo['type'])?$costInfo['type']:'默认';
        $data['note'] = isset($costInfo['note'])?$costInfo['note']:'';
        return $this->db->insert('cost', $data);
    }

    public function get_cost_list(){
//        SELECT COUNT(num),UseName,EXTRACT(month FROM time) as month FROM cost GROUP BY UseName,month;
//        SELECT COUNT(num),EXTRACT(month FROM time) as month FROM cost GROUP BY month;

//        SELECT COUNT(num),UseName,EXTRACT(day FROM time) as day FROM cost WHERE EXTRACT(month FROM time)=7 GROUP BY UseName,day;
//        SELECT COUNT(num),EXTRACT(day FROM time) as day FROM cost WHERE EXTRACT(month FROM time)=7 GROUP BY day;

//        SELECT * FROM cost WHERE EXTRACT(day FROM time)=20;
//        SELECT COUNT(num),UseName FROM cost WHERE EXTRACT(day FROM time)=20 GROUP BY UseName;
    }
    
    public function get_UserName_list(){
        $this->load->model('User_model');
        return $this->User_model->get_UserName_list();
    }

    public function add_cost_type($data){
        return $this->basic_model->set_table('costType')->insert($data);
    }

    public function get_costType_list(){
        $field = false;
        $where = false;
        return $this->basic_model->set_table('costType')->get_list($field,$where);
    }

    

}
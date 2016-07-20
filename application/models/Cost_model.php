<?php

class Cost_model extends CI_Model{

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

    }
    
    public function get_UserName_list(){
        $this->load->model('User_model');
        return $this->User_model->get_UserName_list();
    }
    

}
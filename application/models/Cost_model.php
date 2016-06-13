<?php

class Cost_model extends CI_Model{

    public function add_cost($costInfo,$user){
        $data['user'] = $user;
        $data['money'] = intval($costInfo['money']);
        $data['time'] = isset($costInfo['time'])?$costInfo['time']:date('y-m-d H:i:s',time());
        $data['type'] = isset($costInfo['type'])?$costInfo['type']:'默认';
        $data['note'] = isset($costInfo['note'])?$costInfo['note']:'';
        return $this->db->insert('cost', $data);
    }

    public function get_cost_list(){

    }

}
<?php

class User_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->model('basic_model');
    }

    public function get_userInfo($userInfo = false){
        if(!$userInfo || !isset($userInfo['UserName']) || !isset($userInfo['password'])){
            return false;
        }
        $where = array(
            'UserName'=>$userInfo['UserName'],
            'password'=>md5($userInfo['password']),
            'is_audit'=>'Y'
        );
        $user = $this->basic_model->set_table('user')->get_one($where);
        return $user;
    }
    
    public function get_userInfo_by_id($UserId = false){
        if(!$UserId){
            return false;
        }
        $where = array('UserId'=>$UserId);
        return $this->basic_model->set_table('user')->get_one($where);
    }

    public function check_user_by_name($UserName = false){
        if(!$UserName){
            return false;
        }
        $where = array(
            'UserName' => $UserName
        );
        return $this->basic_model->set_table('user')->get_one($where);
    }

    public function register($data){
        if($data['password'] === $data['sure_password']){
            $insert_data = array(
                'UserName' => $data['UserName'],
                'password' => md5($data['password'])
            );
            return $this->basic_model->set_table('user')->insert($insert_data);
        }else{
            return false;
        }
    }

}
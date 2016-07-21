<?php

class User_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->model('basic_model');
    }

    /**
     * 获取用户信息 - 登录用
     * @param bool $userInfo
     * @return bool
     */
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

    /**
     * 根据用户ID获取用户信息 - 刷新用户信息用
     * @param bool $UserId
     * @return bool
     */
    public function get_userInfo_by_id($UserId = false){
        if(!$UserId){
            return false;
        }
        $where = array('UserId'=>$UserId);
        return $this->basic_model->set_table('user')->get_one($where);
    }

    /**
     * 检查用户名称是否存在
     * @param bool $UserName
     * @return bool
     */
    public function check_user_by_name($UserName = false){
        if(!$UserName){
            return false;
        }
        $where = array(
            'UserName' => $UserName
        );
        return $this->basic_model->set_table('user')->get_one($where);
    }

    /**
     * 注册 - 添加用户信息
     * @param $data
     * @return bool
     */
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

    /**
     * 获取用户名称列表
     * @return mixed
     */
    public function get_UserName_list(){
        $field = 'UserName';
        $where = array(
            'is_audit' => 'Y',
            //'is_superadmin' => 'N'
        );
        return $this->basic_model->set_table('user')->get_list($field,$where);
    }

}
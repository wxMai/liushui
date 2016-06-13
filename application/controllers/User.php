<?php

class User extends MY_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }
    
    public function index() {
        if(!$this->getData('userInfo')){
            $this->load->view('user/login');
            return;
        }
    }

    public function login() {
        if(!$this->input->post('count')||!$this->input->post('password')){
            exit('参数错误');
        }
        $userInfo = $this->User_model->get_userInfo($this->input->post());
        if($userInfo){
            $this->session->set_userdata('userInfo',$userInfo);
        }else{
            exit('账号或密码错误');
        }
    }

    
}

 
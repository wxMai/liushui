<?php

class User extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->check_login();
    }

    /**
     * 首页 - 登录
     */
    public function index()
    {
        $this->login();
    }

    /**
     * 登录 - 页面
     */
    public function login(){
        $this->load->view('user/login');
    }

    /**
     * 登录 - 接口
     */
    public function do_login()
    {
        if (!$this->input->post('UserName') || !$this->input->post('password')) {
            c_exit(0,'参数错误.');
        }
        $userInfo = $this->User_model->get_userInfo($this->input->post());
        if ($userInfo) {
            $this->refresh_userInfo($userInfo->UserId);
            c_exit(1,'登录成功');
        } else {
            c_exit(0,'密码错误.');
        }
    }

    /**
     * 注册 - 页面
     */
    public function register()
    {
        $this->load->view('user/register');
    }

    /**
     * 注册 - 接口
     */
    public function do_register()
    {
        if (!$this->input->post('sure_password') || !$this->input->post('UserName') || !$this->input->post('password')) {
            c_exit(0, '数据缺失');
        }
        $result = $this->User_model->register($this->input->post());
        if($result){
            //$this->refresh_userInfo($result); #注册后还要等待管理员审核
            c_exit(1,'注册成功');
        }else{
            c_exit(0,'注册操作失败');
        }
    }

    /**
     * 检查注册名称是否已存在 - 接口
     */
    public function check_register_name()
    {
        if ($this->User_model->check_user_by_name($this->input->get('UserName'))) {
            http_response_code(400);
        }else{
            http_response_code(200);
        }
    }

    /**
     * 检查登录名称是否存在 - 接口
     */
    public function check_login_name(){
        if ($this->User_model->check_user_by_name($this->input->get('UserName'))) {
            http_response_code(200);
        }else{
            http_response_code(400);
        }
    }

    /**
     * 检查登录状态
     */
    public function check_login(){
        if($this->getData('userInfo')){
            redirect(site_url('cost/index'));
        }
    }

    /**
     * 刷新用户信息
     * @param bool $UserId
     */
    public function refresh_userInfo($UserId = false){
        $UserId = $UserId?$UserId:$this->getData('userInfo')->UserId;
        $userInfo = $this->User_model->get_userInfo_by_id($UserId);
        if($userInfo){
            $this->session->set_userdata('userInfo',$userInfo);
            $this->setData('userInfo',$userInfo);
        }
    }

    /**
     * test用
     */
    public function test()
    {
        var_dump($this->getData('userInfo'));
        var_dump_it($this->session->userdata('userInfo'));
    }


}

 
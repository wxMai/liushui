<?php

class Cost extends MY_Controller{

    function __construct() {
        parent::__construct();
        $this->load->model('Cost_model');
        $this->check_login();
    }

    public function index() {
        if(!$this->getData('userInfo')){
            $this->load->view('user/login');
            return;
        }
        $this->setData('UserName_list',$this->Cost_model->get_UserName_list());
        $this->load->view('cost/add',$this->getData());
    }
    
    public function lists(){
        
    }

    public function add() {
        if(!$this->getData('userInfo')){
            $this->load->view('user/login');
            return;
        }
        if(!$this->input->post() || !$this->input->post('money')){
            $this->load->view('cost/add');
        }
        if($this->Cost_model->add_cost($this->input->post(),$this->getData('userInfo')->UserId)){
            exit('成功');
        }else{
            exit('失败');
        }
    }

    public function add_type(){
        
    }

    public function do_add_type(){

    }

    public function check_login(){
        if(!$this->getData('userInfo')){
            redirect(site_url(array('user/login')));
        }
    }


}

 
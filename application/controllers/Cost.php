<?php

class Cost extends MY_Controller{

    function __construct() {
        parent::__construct();
        $this->load->model('Cost_model');
    }

    public function index() {
        if(!$this->getData('userInfo')){
            $this->load->view('user/login');
            return;
        }
        $this->load->view('cost/add');
    }

    public function add() {
        if(!$this->getData('userInfo')){
            $this->load->view('user/login');
            return;
        }
        if(!$this->input->post() || !$this->input->post('money')){
            $this->load->view('cost/add');
        }
        if($this->Cost_model->add_cost($this->input->post(),$this->data['userInfo']->id)){
            exit('成功');
        }else{
            exit('失败');
        }
    }


}

 
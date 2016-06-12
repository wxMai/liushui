<?php

class MY_Controller extends CI_Controller {

    public $data;

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        
        $this->data['userInfo'] = $this->login_check();
    }

    private function login_check() {
        
//        return $this->session->has_userdata('userInfo');
    }

}

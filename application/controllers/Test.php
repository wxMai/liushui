<?php

class Test extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
    }

    public function test(){
        $this->load->view('user/test');
    }
    
    public function do_test(){
        c_exit(0,$_FILES);
    }




}


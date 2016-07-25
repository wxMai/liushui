<?php

class Test extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
    }

    public function test(){
        $test = new PHPExcel();
        var_dump_it($test);
    }


}


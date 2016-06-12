<?php

class User extends MY_Controller{
    
    function __construct() {
        parent::__construct();
    }
    
    public function index() {
//        $query = $this->db->query('SELECT * FROM my_table');
//        var_dump($query);
    }
    
    public function login() {
        if($this->input->post()){
            
        }
    }
    
}

 
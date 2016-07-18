<?php

class MY_Controller extends CI_Controller
{

    private $data;

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->helper('common_helper');
        
        $this->data['userInfo'] = $this->login_check();
    }

    private function login_check()
    {
        if ($this->session->has_userdata('userInfo')) {
            return $this->session->userdata('userInfo');
        }
        return false;
    }

    /**
     * @return data[]
     */
    public function getData($array = false)
    {
        $result = $this->data;
        if (is_array($array)) {
            foreach ($array as $item) {
                $result = isset($result[$item])?$result[$item]:false;
            }
        }elseif($array){
            $result = $this->data[$array];
        }
        return (isset($result)&&$result!='')?$result:false;
    }

    public function setData($value,$array = false){
        $result = &$this->data;
        if (is_array($array)) {
            foreach ($array as $item) {
                $result = &$result[$item];
            }
        }elseif($array){
            $result = &$this->data[$array];
        }
        $result = $value;
    }

}

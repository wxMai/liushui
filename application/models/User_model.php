<?php

class User_model extends CI_Model{

    public function get_userInfo($userInfo = false){
        if(!$userInfo || !isset($userInfo['count']) || !isset($userInfo['password'])){
            return false;
        }
        $user = $this->db->get_where('user',array('count'=>$userInfo['count'],'password'=>md5($userInfo['password'])))->result();
        return empty($user[0])?false:$user[0];
    }

}
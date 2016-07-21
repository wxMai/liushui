<?php

/**
 * only for testing, remove it later
 */
function var_dump_it($content, $exit = TRUE) {
    echo '<pre>';
    var_dump($content);
    echo '</pre>';
    if ($exit)
        die();
}

/**
 * 判断数组是否有该键值
 * @param type $where 检查的数组
 * @param type $key 键值
 * @param type $default_val 默认值
 * @return type
 */
function check_data($where = array(), $key = '', $default_val = '0') {
    if (empty($where) || !$key)
        return $default_val;
    return isset($where[$key]) && $where[$key] !== FALSE ? clean_data($where[$key]) : $default_val;
}

/**
 * htmlspecialchars() and trim()  addslash
 * @param str $str
 */
function clean_data($str = '') {
    return !is_array($str) && $str !== FALSE ? htmlspecialchars(trim($str)) : '';
}

/**
 *
 * /**
 *  退出并以json数据返回
 * @param int $status 0:通常是代表失败.
 * @param str $msg 返回的 msg,
 * @param array $arr 其它参数
 *  @param Boolean $to_interrupt 是否中断
 */
function c_exit($status = 0, $msg = "", $arr = array(), $to_interrupt = true) {
    $_msg = $msg ? $msg : (!$status ? '操作失败' : '操作成功');
    $_arr = array(
        'status' => $status,
        'msg' => $_msg
    );
    if ($arr && is_array($arr))
        $_arr = array_merge($_arr, $arr);
    if ($to_interrupt)
        exit(json_encode($_arr));
    else
        echo(json_encode($_arr));
}

function c_exit_echo($status = 0, $msg = "", $arr = array()) {
    header('Content-type:text/json');
    $_msg = $msg ? $msg : (!$status ? '操作失败' : '操作成功');
    $_arr = array(
        'status' => $status,
        'msg' => $_msg
    );
    if ($arr && is_array($arr))
        $_arr = array_merge($_arr, $arr);
    echo (json_encode($_arr));
}


//生成指定个数的随机数
function random($length) {
    $hash = '';
    $chars = '0123456789';
    $max = strlen($chars) - 1;
    PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 *
 * 更新用户session 中的 item
 * @param type $userId
 * @param type $item
 */
function update_session($userId = 0, $item = FALSE, $value = FALSE) {
    $ci = &get_instance();
    $userinfo = $ci->session->userdata('userinfo');
    $userinfo[$item] = $value;
    $ci->session->set_userdata('userinfo', $userinfo);
    return;
}


function set_seaslog($level = 'info', $message = FALSE , $module = FALSE) {
    if (method_exists('SeasLog', 'getBasePath') && $message) {
        SeasLog::setBasePath('Log');
//        SeasLog::setBasePath(base_url().'Log');
        $module && SeasLog::setLogger($module);
        SeasLog::log($level, $message);
    }
}

function get_seaslog($level = 'info',$date = FALSE ,$path = FALSE){
    if(method_exists('SeasLog', 'getBasePath')){
        $path = $path ? $path : '*';
        SeasLog::setBasePath($path);
        $date = $date ? $date : NULL;
        return SeasLog::analyzerDetail($level,$path,$date);
//        return SeasLog::analyzerCount();
    }
    return false;
}

function get_array_value($key,$arr,$default = 0){
    if(isset($arr[$key])){
        return $arr[$key];
    }else{
        return $default;
    }
}

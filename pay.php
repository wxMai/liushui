<?php

    function pay_pc($mixId = 0  )
    {
        //订单ID
        $mixId =  $mixId ? $mixId :  $this->session->userdata('tm_payOrderId' ) ;
        
        $orderInfo = $this->tmpayorder_model->get_basic_order($mixId);
        $this->_validate_pay_time($orderInfo);
        $this->_validate_pay($mixId, $orderInfo);
        
        
        $payment_id = 1; //支付类型
        $orderId = $orderInfo['OrderId'];
        $placeInfo = $this->tmpayorder_model->get_place_detail($orderInfo['PlaceProductId']);

        if ($orderId) {
            $this->load->library('ipayment', array($payment_id)); //加载管理支付类，自定义的
            $this->load->helper('string');
           
            //订单信息，将要发送的信息
            $order_data = array(
                'OrderId' => $orderId,
                'SortId' => $orderInfo['SortId'].'_'.random_string('alnum', 8),
                'Status' => $orderInfo['Status'],
                'TotalPrice' => $orderInfo['TotalPrice'],
                'Phone' => $orderInfo['Mobile'],
                'orderName' =>  $placeInfo['PlaceName'].'-'.$placeInfo['HallName'].'-预订' ,
                'UserId' =>     $orderInfo['UserId'],
                'note' =>   '特色场地预订' ,
                //同步返回
                'return_url' => site_url(array('tmpayment','callback',$payment_id )),
                //异步返回
                'notify_url' => site_url(array('tmpayment', 'asyncCallback', $payment_id ))
                
            );
            if ($order_data) {
                $paymentPlugin = $this->ipayment->getPaymentPlugin(); //支付接口类
                if (!is_object($paymentPlugin)) {
                    $msg = array('type' => 'fail', 'msg' => '支付方式不存在！');
                    print_r($msg);
                    exit;
                }
                //返回待请求参数数组
                $data['sendData'] = $paymentPlugin->packData($this->ipayment->getPaymentInfo($order_data)); //打包发送的数据

                $data['submitUrl'] = $paymentPlugin->submitUrl;
                $data['notify_url']  = '';
                $data['method'] = $paymentPlugin->method;
               
                $this->load->view('alipay/pay_form', $data);
            }
        }
    }
    
    /**
     * 手机支付功能，支付宝
     */
    function pay_mobile($SortId = 0) {
        $payment_id = 2; //支付类型：手机支付
        #mai
        $orderInfo = $this->tmpayorder_model->get_basic_order($SortId);
        $order_id = $orderInfo['OrderId'];
        $this->_validate_pay_time($orderInfo);
        $this->_validate_pay($SortId, $orderInfo);
        $placeInfo = $this->tmpayorder_model->get_place_detail($orderInfo['PlaceProductId']);
        #maiend

        if ($order_id) {
            $this->load->library('ipayment', array($payment_id)); //加载管理支付类
            //订单信息
            $order_data = array(
                'OrderId' => $order_id,
                'SortId' => $orderInfo['SortId'] . '_' . random_string('alnum', 8),
                'Status' => $orderInfo['Status'],
                'TotalPrice' => $orderInfo['TotalPrice'],
                'Phone' => $orderInfo['Mobile'],
                'Location' => $placeInfo['Province'] . ' - ' . $placeInfo['City'],
                'PlaceName' => $placeInfo['PlaceName'],
                'PeopleNumber' => $placeInfo['TotalPeople'],
                'orderName' => $placeInfo['PlaceName'] . '-' . $placeInfo['HallName'] . '-预订 -手机端',
                'UserId' => $orderInfo['UserId'],
                'return_url' => site_url(array('tm_payment', 'callback', $payment_id)),
                'notify_url' => site_url(array('tmp_ayment', 'asyncCallback', $payment_id)),
                'merchant_url' => site_url(array('tm_order', 'index', $placeInfo['SortId'])),
                'note' => '这是一条手机测试数据'
            );

            if ($order_data) {
                $paymentPlugin = $this->ipayment->getPaymentPlugin(); //接口类

                if (!is_object($paymentPlugin)) {
                    $msg = array('type' => 'fail', 'msg' => '支付方式不存在！');
                    print_r($msg);
                    exit;
                }
                //返回待请求参数数组
                $data['sendData'] = $paymentPlugin->packData($this->ipayment->getPaymentInfo($order_data)); //打包发送的数据
                $data['submitUrl'] = $paymentPlugin->submitUrl;
                $data['method'] = $paymentPlugin->method;

                $this->load->view('alipay/pay_form', $data);
            }
        } else {
            echo 'fail';
            exit($order_id . 'can`t null');
        }
    }
    
    /**
     * alipay支付操作后，回调
     * @param $payment_id支付ID (类型)
     * @return string
     */
    function callback($payment_id)
    {
        //订单号/
        $this->load->library('ipayment', array($payment_id)); //加载管理支付类
        $paymentPlugin = $this->ipayment->getPaymentPlugin(); //接口类
        if (!is_object($paymentPlugin)) {
            $msg = array('type' => 'fail', 'msg' => '支付方式不存在！');
            return;
        }
        //初始化参数
        $money = 0; //总金额
        $message = '支付失败'; //提示信息
        $orderNo = 0; //订单号
        $get_data = $this->input->get();
        //执行接口回调函数
        $return_result = $paymentPlugin->callback($get_data, $money, $message, $orderNo);
        if ($return_result == true && isset($get_data['out_trade_no']) ) {
            
            $SortId = preg_replace('/_.*/', '', $get_data['out_trade_no']);
            $orderInfo = $this->tmpayorder_model->get_basic_order($SortId);
             
            if( $this->tmpayorder_model->validate_callback($get_data['out_trade_no'])){#如果判断到异步已经更新了订单 
                #结束异步
                #$paymentPlugin->asyncStop();
                #跳转
                $this->_redirect_after_pay_action($orderInfo);
            }
            
            $this->after_pay_action($get_data,$orderInfo);
            $this->_redirect_after_pay_action($orderInfo);
                
        } else { //支付失败
            echo 'fail';
        }
        exit('error');
    }
    // 支付回调[异步]
    function asyncCallback($payment_id)
    {
        //从URL中获取支付方式
        $this->load->library('ipayment', array($payment_id)); //加载管理支付类
        $paymentPlugin = $this->ipayment->getPaymentPlugin(); //接口类
        if (!is_object($paymentPlugin)) {
            echo "fail";
        }
        //初始化参数
        $money = 0;
        $message = '支付失败';
        $orderNo = 0;
        $post_data = $this->input->post();
        //执行接口回调函数
        $return = $paymentPlugin->asyncCallback($post_data, $money, $message, $orderNo);
        //支付成功
        if ($return == true) {
            #判断订单是否已更新
            $SortId = preg_replace('/_.*/', '', $post_data['out_trade_no']);
            $orderInfo = $this->tmpayorder_model->get_basic_order($SortId);
            
            if( !$this->tmpayorder_model->validate_callback($post_data['out_trade_no'])){#如果判断没有支付记录则更新
                $this->after_pay_action($post_data,$orderInfo);   
            }
            //$r = $this->after_pay_action($post_data,$orderInfo);
            #如没更新一系列操作
           
            $paymentPlugin->asyncStop(true);
        } else {
            $paymentPlugin->asyncStop(false);
        }
        $this->_redirect_after_pay_action($orderInfo);
    }
    ?>





<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-28
 * Time: 下午2:20
 * 支付管理类
 */
class ipayment
{
    private $ci_obj = null;
    private $payment_config = array();
    private $payment_id; //支付类型
    private $paymentPlugin;

    /**构造函数
     * @param $ci_arr [0]支付类型
     */
    function __construct($payment_type)
    {
        $this->ci_obj =& get_instance();
        if (is_numeric($payment_type[0])) $this->payment_id = (int)$payment_type[0];
        else exit('参数有误');

        $this->payment_config = $this->_get_paymentPlugin_config(); //加载支付类型配置信息
        if ($this->payment_config == false) exit('支付类型不存在！');

        $this->ci_obj->load->library('paymentplugin'); //加载支付基类
        unset($this->ci_obj->paymentplugin); //只为导入，不需要实例化
    }

    /**
     * 获取支付接口对象
     * @return object
     */
    public function getPaymentPlugin()
    {
        if ($this->paymentPlugin) return $this->paymentPlugin;
        $class_name = 'pay_' . $this->payment_config['class_name'];
        define('PAY_IN', 'ipayment'); //入口安全标识
        //config为配置信息，paymentid为配置类型
        $this->ci_obj->load->library($class_name, array('config' => $this->payment_config, 'payment_id' => $this->payment_id));
        if (!is_object($this->ci_obj->$class_name)) return null;
        $this->paymentPlugin = $this->ci_obj->$class_name;
        return $this->paymentPlugin;
    }

    public function get_payment_config()
    {
        return $this->payment_config;
    }

    //统一数据处理
    function getPaymentInfo($order_data)
    {
        $payment_data = $order_data;
        //当前客户端ＩＰ
        $payment_data['HJ_Client_ip'] = $this->ci_obj->input->ip_address();
        //获取订单信息
        $payment_data ['HJ_Subject'] = $order_data['orderName']; //商品名称
        $payment_data ['HJ_Total_fee'] = $order_data['TotalPrice']; //付款总额
        $payment_data ['HJ_Out_trade_no'] = $order_data['SortId']; //订单号
        $payment_data ['HJ_Body'] = isset($order_data['note']) ? $order_data['note'] : ""; //订单描述
        // $payment_data ['HJ_OrderId'] = $order_data['OrderId']; //订单id
        unset($payment_data['orderName'], $payment_data['TotalPrice'], $payment_data["SortId"], $payment_data["note"]);
        return $payment_data;
    }

    /**
     * 加载支付接口的配置信息  todo:此部分会存放在数据库
     * @param $type
     * @return bool|array
     */
    private function  _get_paymentPlugin_config()
    {
        $pay_config = array
        (
            //可扩展多个 key为支付类型
            1 => array( //网页即时到账支付方式
                'class_name' => 'alipay_direct', //libraries中的类
                'partner' => '2088911258129774',
                'key' => 'gqao5fq152c2h8rv2dmaliclm4yg15vn', //安全检验码，以数字和字母组成的32位字符
                'seller_email' => 'mail@aihuiju.com', //收款支付宝账号
                'sign_type' => 'MD5', //签名方式 不需修改
                'input_charset' => 'utf-8', //字符编码格式
                'cacert' => getcwd() . DIRECTORY_SEPARATOR . APPPATH . 'config/key/cacert.pem',
                'transport' => 'http', //访问模式
            ),
            2 => array( //手机页面即时到账支付
                'class_name' => 'alipay_mobile_direct', //libraries中的类
                'partner' => '2088911258129774', //合作身份者id
                'key' => 'gqao5fq152c2h8rv2dmaliclm4yg15vn',
                'seller_email' => 'mail@aihuiju.com', //收款支付宝账号
                'sign_type' => '0001',
                'private_key_path' => getcwd() . DIRECTORY_SEPARATOR . APPPATH . 'config/key/rsa_private_key.pem',
                'ali_public_key_path' => getcwd() . DIRECTORY_SEPARATOR . APPPATH . 'config/key/alipay_public_key.pem',
                'cacert' => getcwd() . DIRECTORY_SEPARATOR . APPPATH . 'config/key/cacert.pem',
                'input_charset' => 'utf-8',
                'transport' => 'http',
                'return_format' => 'xml',
                'v' => '2.0',
            ),
        );
        if (array_key_exists($this->payment_id, $pay_config)) return $pay_config[$this->payment_id];
        return false;
    }
}



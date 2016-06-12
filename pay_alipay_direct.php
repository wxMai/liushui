<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-26
 * Time: 下午3:37
 */
if (!defined('PAY_IN') || PAY_IN != 'ipayment') exit('非法操作'); //入口安全认证
class pay_alipay_direct extends PaymentPlugin
{
    public $name = '支付宝[即时到帐]'; //支付插件名称
    public $submitUrl = 'https://mapi.alipay.com/gateway.do?_input_charset=utf-8'; //提交地址
    /**
     * HTTPS|http形式消息验证地址
     */
    private $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    private $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    /**  //打包发送的数据
     * @param $payment
     * @return array
     */
    public function packData($payment)
    {
        $return = array();
        //基本参数
        $return["service"] = isset($payment["service"]) ? $payment["service"] : "create_direct_pay_by_user";
        $return["partner"] = isset($payment["partner"]) ? $payment["partner"] : trim($this->_config['partner']);
        $return["seller_id"] = isset($payment["seller_id"]) ? $payment["seller_id"] : trim($this->_config['partner']);
        $return["seller_email"] = isset($payment["seller_email"]) ? $payment["seller_email"] : trim($this->_config['seller_email']);
        $return["_input_charset"] = isset($payment["_input_charset"]) ? $payment["_input_charset"] : trim(strtolower($this->_config['input_charset']));
        $return["notify_url"] = isset($payment["notify_url"]) ? $payment["notify_url"] : $this->asyncCallbackUrl; //异步回调参数
        $return["return_url"] = isset($payment["return_url"]) ? $payment["return_url"] : $this->callbackUrl; //页面回调

        $return["error_notify_url"] = isset($payment["error_notify_url"]) ? $payment["error_notify_url"] : ''; //请求出错时的通知页面路径,记录出现提示报错

        //业务参数
        $return["subject"] = isset($payment["subject"]) ? $payment["subject"] : $payment["HJ_Subject"]; //订单名称
        $return["out_trade_no"] = isset($payment["out_trade_no"]) ? $payment["out_trade_no"] : $payment['HJ_Out_trade_no']; //订单号
        $return["body"] = isset($payment["body"]) ? $payment["body"] : $payment['HJ_Body']; //订单描述 对一笔交易的具体描述信息
        $return["extra_common_param"] = isset($payment["extra_common_param"]) ? $payment["extra_common_param"] : "extra_common_param_bic"; //公用回传参数.如果用户请求时传递了该参数，则返回给商户时会回传该参数。
        $return["total_fee"] = isset($payment["total_fee"]) ? $payment["total_fee"] : $payment['HJ_Total_fee']; //交易金额
        $return["exter_invoke_ip"] = isset($payment["exter_invoke_ip"]) ? $payment["exter_invoke_ip"] : $payment["HJ_Client_ip"]; //客户端的IP地址

        $return["payment_type"] = isset($payment["payment_type"]) ? $payment["payment_type"] : 1; //1：为购买商品
        $return["show_url"] = isset($payment["show_url"]) ? $payment["show_url"] : "http://www.aihuiju.com"; //商品展示地址  收银台页面上，商品展示的超链接

        // $return["seller_account_name"]=trim($this->_config['seller_email']);//卖家别名支付宝账号 seller_id>seller_account_name>seller_email
        // $return["it_b_pay"]="1";//超时时间
        // $return["qr_pay_mode"]=1;//扫码支付：扫码支付的方式
        //   $return["paymethod"]="";//信用支付||余额支付
        //     $return["need_ctu_check"]='Y';//
        //   $return["anti_phishing_key"]='';//防钓鱼时间戳

        return $this->buildRequestPara($return);
    }

    //同步处理,页面回调
    public function callback($callbackData, &$money, &$message, &$orderNo)
    {
        try {
            /**
             * $callbackData=array(
             * 'body' => '套餐意向金1000测试金',//订单描述
             * 'buyer_email' => '1411980609@qq.com',//客户支付账号
             * 'buyer_id' => '2088802487896400',//客户支付账号id
             * 'exterface' => 'create_direct_pay_by_user',//支付接口
             * 'extra_common_param' => 'extra_common_param_bic',//共用信息
             * 'is_success' => 'T',//表示接口调用是否成功
             * 'notify_id' => 'RqPnCoPT3K9%2Fvwbh3InTs%2FIhKi2o6Lx3ZWedp3UTX1VNE7QF6cxJRO2f7%2F0tFFfx%2FWC2',//通知校验ID
             * 'notify_time' => '2015-04-18 13:43:52',//
             * 'notify_type' => 'trade_status_sync',//返回通知类型
             * 'out_trade_no' => 'abcdfkjheflkldfnk2',//订单号
             * 'payment_type' => '1',//支付类型
             * 'seller_email' => 'mail@aihuiju.com',//卖家账号
             * 'seller_id' => '2088911258129774',//卖家id
             * 'subject' => '套餐意向金',//产品名称
             * 'total_fee' => '0.01',//总额
             * 'trade_no' => '2015041800001000400049118291',//该交易在支付宝系统中的交易流水账号
             * 'trade_status' => 'TRADE_SUCCESS',//    TRADE_FINISHED||TRADE_SUCCESS
             * 'sign' => '0c150b7bdac38e8f02a7bc06f119eff1',
             * 'sign_type' => 'MD5',
             * )
             */
            if (empty($callbackData)) { //get[]为空时
                $message = "get数据为空！";
                return false;
            }
            if ($callbackData['trade_status'] != 'TRADE_FINISHED' && $callbackData['trade_status'] != 'TRADE_SUCCESS') {
                $message = "交易状态不正确!";
                return false;
            }
            if (!isset($callbackData["notify_id"]) || empty($callbackData["notify_id"])) {
                $message = "notify_id的值为空!";
                return false;
            }
            //生成签名结果
            $isSign = $this->getSignVeryfy($callbackData, $callbackData["sign"]);
            if (!$isSign) {
                $message = "签名不正确!";
                return false;
            }
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = $this->getResponse($callbackData["notify_id"]);
            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if (!preg_match("/true$/i", $responseTxt)) {
                $message = "远程服务器ATN结果不正确！";
                return false;
            }
        } catch (Exception $e) {
            //出错时会记录下来
            $this->error_log($e->getMessage() . var_export($callbackData, true));
        }
        //回传数据
        $money = $callbackData["total_fee"];
        $orderNo = $callbackData["out_trade_no"];
        $message = "支付成功！";
        return true;
    }

    //异步处理
    public function asyncCallback($callbackData, &$money, &$message, &$orderNo)
    {
        try {
            //  return $this->callback($callbackData, $money, $message, $orderNo);
            /*
             * array (
                      'discount' => '0.00',
                      'payment_type' => '1',
                      'subject' => '套餐意向金',
                      'trade_no' => '2015041800001000400049105635',
                      'buyer_email' => '1411980609@qq.com',
                      'gmt_create' => '2015-04-18 11:32:48',
                      'notify_type' => 'trade_status_sync',
                      'quantity' => '1',
                      'out_trade_no' => 'abcdfkjheflkldfnk22224',
                      'seller_id' => '2088911258129774',
                      'notify_time' => '2015-04-18 11:32:54',
                      'body' => '套餐意向金1000测试金',
                      'trade_status' => 'TRADE_SUCCESS',
                      'is_total_fee_adjust' => 'N',
                      'total_fee' => '0.01',
                      'gmt_payment' => '2015-04-18 11:32:54',
                      'seller_email' => 'mail@aihuiju.com',
                      'price' => '0.01',
                      'buyer_id' => '2088802487896400',
                      'notify_id' => '3526fab70f58159b7427afbc504e029448',
                      'use_coupon' => 'N',
                      'sign_type' => 'MD5',
                      'sign' => 'b64bbb80e50938d2e11859c7a0fe654e',
                    )
             */
            if (empty($callbackData)) { //判断POST来的数组是否为空
                $message = "POST数据为空！";
                return false;
            }
            if ($callbackData['trade_status'] != 'TRADE_FINISHED' && $callbackData['trade_status'] != 'TRADE_SUCCESS') {
                $message = "交易状态不正确!";
                return false;
            }
            if (!isset($callbackData["notify_id"]) || empty($callbackData["notify_id"])) {
                $message = "notify_id的值为空!";
                return false;
            }
            $isSign = $this->getSignVeryfy($callbackData, $callbackData["sign"]);
            if (!$isSign) {
                $message = "签名不正确!";
                return false;
            }
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = $this->getResponse($callbackData["notify_id"], "post");
            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if (!preg_match("/true$/i", $responseTxt)) {
                $message = "远程服务器ATN结果不正确!";
                return false;
            }
        } catch (Exception $e) {
            //出错时会记录下来
            $this->error_log($e->getMessage() . var_export($callbackData, true));
        }
        //回传数据
        $money = $callbackData["total_fee"];
        $orderNo = $callbackData["out_trade_no"];
        return true;
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    protected function getSignVeryfy($para_temp, $sign)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);
        //对待签名参数数组排序
        ksort($para_filter);
        reset($para_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_filter);
        switch (strtoupper(trim($this->_config['sign_type']))) {
            case "MD5" :
                $isSgin = $this->md5Verify($prestr, $sign, $this->_config['key']);
                break;
            default :
                $isSgin = false;
        }
        return $isSgin;
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    protected function md5Verify($prestr, $sign, $key)
    {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);

        if ($mysgin == $sign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    protected function buildRequestPara($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        //对待签名参数数组排序
        ksort($para_filter);
        reset($para_filter);
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_filter);
        //签名结果与签名方式加入请求提交参数组中
        $para_filter['sign'] = $mysign;
        $para_filter['sign_type'] = strtoupper(trim($this->_config['sign_type']));
        return $para_filter;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    protected function paraFilter($para)
    {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if ($key == "sign" || $key == "sign_type" || $val == "") continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    protected function buildRequestMysign($para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        switch (strtoupper(trim($this->_config['sign_type']))) {
            case "MD5" :
                $mysign = md5($prestr . $this->_config['key']);
                break;
            default :
                $mysign = "";
        }
        return $mysign;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    protected function createLinkstring($para)
    {
        $arg = "";
        foreach ($para as $key => $val) $arg .= $key . "=" . $val . "&";
        //去掉最后一个&字符
        $arg = trim($arg, '&');
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) $arg = stripslashes($arg);
        return $arg;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * $method get|post
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    protected function getResponse($notify_id, $method = "get")
    {
        $partner = trim($this->_config['partner']);
        if ('https' == strtolower(trim($this->_config['transport'])))
            $veryfy_url = $this->https_verify_url;
        else
            $veryfy_url = $this->http_verify_url;

        $responseTxt = "";
        //用get方式
        if ($method == "get") {
            $veryfy_url .= 'partner=' . $partner . '&notify_id=' . $notify_id;;
            $responseTxt = $this->getHttpResponseGET($veryfy_url, $this->_config['cacert']);
        } elseif ($method == "post") {
            $para = http_build_query(
                array(
                    'partner' => $partner,
                    'notify_id' => $notify_id)
            );
            //用post方式
            $responseTxt = $this->getHttpResponsePOST($veryfy_url, $this->_config['cacert'], $para, $this->_config['input_charset']);
        }
        return $responseTxt;
    }

    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * @param $para 请求的数据
     * @param $input_charset 编码格式。默认值：空值
     * return 远程输出的数据
     */
    protected function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '')
    {
        if (trim($input_charset) != '') $url = $url . "_input_charset=" . $input_charset;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_POST, true); // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $para); // post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    protected function getHttpResponseGET($url, $cacert_url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }
}
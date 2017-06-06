<?php
header("Content-Type: text/html;charset=utf-8");
/**
 * 新达达开放平台发单接口DEMO
 * 详情：发单
 * 版本：1.0
 * 日期：2016-09-10
 * 说明：
 * 以下代码只是为了方便对接商户测试而提供的样例代码，对接商户可以根据自己的需求，按照技术文档编写，代码仅供参考。
 */

require_once dirname(__FILE__) . '/DadaOpenapi.php';


//*********************配置项*************************
$config = array();
$config['app_key'] = 'dadaf3f03dc32b07ed0';
$config['app_secret'] = '7e4e615af165fe63cbf40e52abbc79e8';
$config['source_id'] = '73753';
$config['url'] = 'http://newopen.qa.imdada.cn/api/order/addOrder';

$obj = new DadaOpenapi($config);

//***********************发单接口************************
//发单请求数据,只是样例数据，根据自己的需求进行更改。
$data = array(
    'shop_no'=> '11047059',
    'origin_id'=> '20160915001',
    'city_code'=> '021',
    'pay_for_supplier_fee'=> 0.0,
    'fetch_from_receiver_fee'=> 0.0,
    'deliver_fee'=> 0.0,
    'tips'=> 0,
    'info'=> '测试订单',
    'cargo_type'=> 1,
    'cargo_weight'=> 10,
    'cargo_price'=> 10,
    'cargo_num'=> 2,
    'is_prepay'=> 0,
    'expected_fetch_time'=> 1471536000,
    'expected_finish_time'=> 0,
    'invoice_title'=> '测试',
    'receiver_name'=> '测试',
    'receiver_address'=> '上海市崇明岛',
    'receiver_phone'=> '18588888888',
    'receiver_tel'=> '18599999999',
    'receiver_lat'=> 31.63,
    'receiver_lng'=> 121.41,
    'callback'=>'http://newopen.imdada.cn/inner/api/order/status/notify'
);

//请求接口
$reqStatus = $obj->makeRequest($data);
if (!$reqStatus) {
    //接口请求正常，判断接口返回的结果，自定义业务操作
    if ($obj->getCode() == 0) {
        //返回成功 ....
    }else{
        //返回失败
    }
    echo sprintf('code:%s，msg:%s', $obj->getCode(), $obj->getMsg());
}else{
    //请求异常或者失败
    echo 'except';
}




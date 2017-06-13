<?php

require_once 'data_express_open_api.php';
require_once 'data_express_open_action_base.php';
require_once 'data_express_open_action_common.php';


/**
 * 增加订单
 *
 * @param unknown $args
 * @return NULL|mixed
 * @link http://newopen.imdada.cn/#/development/file/add?_k=3c7sn1
 */
function dada_add_order($args) {

	$args_demo = array(
			//---------------必传-----------------
			'origin_id'=> '20160915001',
			'city_code'=> '021',
			'cargo_price'=> 10,
			'is_prepay'=> 0,
			'expected_fetch_time'=> 1471536000,
			'receiver_name'=> '测试',
			'receiver_address'=> '上海市崇明岛',
			'receiver_phone'=> '18588888888',
			'receiver_tel'=> '18599999999',
			'receiver_lat'=> 31.63,
			'receiver_lng'=> 121.41,
			'callback'=>'http://newopen.imdada.cn/inner/api/order/status/notify',
			//---------------非必传-----------------
			'pay_for_supplier_fee'=> 0.0,
			'fetch_from_receiver_fee'=> 0.0,
			'deliver_fee'=> 0.0,
			'tips'=> 0,
			'info'=> '测试订单',
			'cargo_type'=> 1,
			'cargo_weight'=> 10,
			'cargo_num'=> 2,
			'expected_finish_time'=> 0,
			'invoice_title'=> '测试',
	);

	return dada_get_api_result('/api/order/addOrder', $args);
}


function dada_readd_order($args) {

	/**
	 * Editplus文档参数转换
	 * 
^(\w+)\s+.+\s+([^\s]+)$
------------------------
// \2
'\1' => 'xxx',
	 * @var unknown
	 */
	$args_demo = array(
			//---------------必传-----------------
			// 门店创建后可在门店列表和单页查看
			'shop_no' => 'xxx',
			// 第三方订单ID
			'origin_id' => 'xxx',
			// 订单所在城市的code（查看各城市对应的code值）
			'city_code' => 'xxx',
			// 订单金额
			'cargo_price' => 'xxx',
			// 0:否
			'is_prepay' => 'xxx',
			// 3.建议取值为当前时间往后推10~15分钟）
			'expected_fetch_time' => 'xxx',
			// 收货人姓名
			'receiver_name' => 'xxx',
			// 收货人地址
			'receiver_address' => 'xxx',
			// 收货人地址维度（高德坐标系）
			'receiver_lat' => 'xxx',
			// 收货人地址经度（高德坐标系）
			'receiver_lng' => 'xxx',
			// 回调URL（查看回调说明）
			'callback' => 'xxx',
			//---------------非必传-----------------
			// 收货人手机号（手机号和座机号必填一项）
			'receiver_phone' => 'xxx',
			// 收货人座机号（手机号和座机号必填一项）
			'receiver_tel' => 'xxx',
			// 小费（单位：元，精确小数点后一位）
			'tips' => 'xxx',
			// 商家应付金额（单位：元）
			'pay_for_supplier_fee' => 'xxx',
			// 用户应收金额（单位：元）
			'fetch_from_receiver_fee' => 'xxx',
			// 第三方平台补贴运费金额（单位：元）
			'deliver_fee' => 'xxx',
			// 订单创建时间（时间戳,以秒计算时间，即unix-timestamp）
			'create_time' => 'xxx',
			// 订单备注
			'info' => 'xxx',
			// 13、水果
			'cargo_type' => 'xxx',
			// 订单重量（单位：Kg）
			'cargo_weight' => 'xxx',
			// 订单商品数量
			'cargo_num' => 'xxx',
			// 期望完成时间（时间戳,以秒计算时间，即unix-timestamp）
			'expected_finish_time' => 'xxx',
			// 发票抬头
			'invoice_title' => 'xxx',
			// 送货开箱码
			'deliver_locker_code' => 'xxx',
			// 取货开箱码
			'pickup_locker_code' => 'xxx',
	);

	return dada_get_api_result('/api/order/reAddOrder', $args);
}

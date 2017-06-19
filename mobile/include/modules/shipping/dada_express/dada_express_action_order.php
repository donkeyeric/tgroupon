<?php

require_once ROOT_PATH . '/include/modules/shipping/dada_express/dada_express_open_api.php';
require_once ROOT_PATH . '/include/modules/shipping/dada_express/dada_express_action_base.php';
require_once ROOT_PATH . '/include/modules/shipping/dada_express/dada_express_action_common.php';


/**
 * 新增配送单接口
 *
 * @param unknown $args
 * @return NULL|mixed
 * @link http://newopen.imdada.cn/#/development/file/add?_k=3c7sn1
 */
function dada_add_order($args) {
	return dada_get_api_result('/api/order/addOrder', $args);
}

/**
 * 重新发布订单
 * 
 * @param unknown $args
 */
function dada_readd_order($args) {
	return dada_get_api_result('/api/order/reAddOrder', $args);
}

/**
 * 查询订单运费接口
 * 
 * @param unknown $args
 * @return NULL|mixed
 */
function dada_deliver_fee($args) {
	return dada_get_api_result('/api/order/queryDeliverFee', $args);	
}

/**
 * 查询运费后发单接口
 * 
 * @param unknown $arg
 * @return NULL|mixed
 */
function dada_add_after_query($arg) {
	return dada_get_api_result('/api/order/addAfterQuery', $args);
}

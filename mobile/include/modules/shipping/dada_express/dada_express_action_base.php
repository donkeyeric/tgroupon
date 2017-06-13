<?php

/**
 * 获取达达配置
 * 
 * @param string $open_api_path
 * @return string[]|unknown[]|mixed[]
 */
function get_data_config($open_api_path = '') {
	global $_CFG;

	$config = [];
	$config['app_key'] = $_CFG['dada_app_key'];
	$config['app_secret'] = $_CFG['dada_app_secret'];
	$config['source_id'] = $_CFG['dada_source_id'];
	$config['url'] = $_CFG['dada_open_api'] . $open_api_path;
	$config['shop_no'] = $_CFG['dada_shop_no'];

	return $config;
}


/**
 * 请求解析达达接口结果
 *
 * @param string $path
 * @param array $data
 * @return NULL|mixed
 */
function dada_get_api_result($path = '', $data = []) {

	$result = null;
	$config = get_data_config($path);
	$data = array_merge($data, ['shop_no' => $config['shop_no']]);
	$obj = new DadaOpenapi($config);
	try {
		$reqStatus = $obj->makeRequest($data);
		if (!$reqStatus) {
			//接口请求正常，判断接口返回的结果，自定义业务操作
			if ($obj->getCode() == 0) {
				$result = $obj->getResult();
			} else {
				//请求异常或者失败
				$msg = sprintf('code:%s，msg:%s', $obj->getCode(), $obj->getMsg());
				api_request_exception_log($url, $msg, $retry, $type);
			}
		} else {
			//请求异常或者失败
			api_request_exception_log($url, $msg, $retry, $type);
		}


	} catch (Exception $e) {
		//请求异常或者失败
		api_request_exception_log($url, $msg, $retry, $type);

	}
	return $result;
}
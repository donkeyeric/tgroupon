<?php
/**
 * 获取达达城市列表
 *
 * @link http://newopen.imdada.cn/#/development/file/cityList
 */
function dada_get_city() {
	return dada_get_api_result('/api/cityCode/list');
}

function data_get_city_code($city_name) {
	
	$last_dada_city_code_cache_write_time = read_static_cache('dada_city_code_cache_write_time');
	$dada_city_code_cache_write_time = time();
	
	if ($dada_city_code_cache_write_time - $last_dada_city_code_cache_write_time > 60 * 60 * 24) {
		$api = dada_get_city();
		$city_serialize = [];
		foreach ($api as $value) {
			$city_serialize[] = "[{$value[cityCode]}:{$value[cityName]}]";
		}
		$city_serialize = join(',', $city_serialize);
		write_static_cache('dada_city_code_cache', $city_serialize);
		write_static_cache('dada_city_code_cache_write_time', $dada_city_code_cache_write_time);
	}
	
	$city_serialize = read_static_cache('dada_city_code_cache');
	preg_match('/\[\w+'.$city_name.'\w+\:(\w+?)\]/', $city_serialize, $cityMatch);
	if (isset($cityMatch[1])) {
		return $cityMatch[1];
	}
	return false;
}

<?php
/**
 * 获取达达城市列表
 *
 * @link http://newopen.imdada.cn/#/development/file/cityList
 */
function dada_get_city() {
	return dada_get_api_result('/api/cityCode/list');
}
<?php

/**
 * 新达达快递
 * ============================================================================
 */

if (!defined('IN_ECTOUCH'))
{
    die('Hacking attempt');
}

$shipping_lang = ROOT_PATH.'lang/' .$GLOBALS['_CFG']['lang']. '/shipping/dada_express.php';

if (file_exists($shipping_lang))
{
    global $_LANG;
    include_once($shipping_lang);
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    include_once(ROOT_PATH . 'lang/' . $GLOBALS['_CFG']['lang'] . '/admin/shipping.php');

    $i = (isset($modules)) ? count($modules) : 0;

    /* 配送方式插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    $modules[$i]['version'] = '1.0.0';

    /* 配送方式的描述 */
    $modules[$i]['desc']    = 'dada_express_desc';

    /* 配送方式是否支持货到付款 */
    $modules[$i]['cod']     = TRUE;

    /* 插件的作者 */
    $modules[$i]['author']  = 'TGROUPON TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 配送接口需要的参数 */
    $modules[$i]['configure'] = array(
                                    array('name' => 'item_fee',     'value'=>5),/* 单件商品的配送费用 */
                                    array('name' => 'base_fee',    'value'=>5), /* 1000克以内的价格   */
                                    array('name' => 'step_fee',     'value'=>2),  /* 续重每1000克增加的价格 */
									['name' => 'app_key', 'label' => 'app_key', 'value' => 'dadaf3f03dc32b07ed0'],    								
									['name' => 'app_secret','label' => 'app_secret', 'value' => '7e4e615af165fe63cbf40e52abbc79e8'],    								
									['name' => 'source_id', 'label' => 'source_id', 'value' => '73753'],    								
									['name' => 'url', 'name' => '接口地址', 'value' => 'http://newopen.qa.imdada.cn/api/order/addOrder'],    								
    );

    /* 模式编辑器 */
    $modules[$i]['print_model'] = 2;

    /* 打印单背景 */
    $modules[$i]['print_bg'] = '/images/receipt/dly_sf_express.jpg';

   /* 打印快递单标签位置信息 */
    $modules[$i]['config_lable'] = 't_shop_name,' . $_LANG['lable_box']['shop_name'] . ',150,29,112,137,b_shop_name||,||t_shop_address,' . $_LANG['lable_box']['shop_address'] . ',268,55,105,168,b_shop_address||,||t_shop_tel,' . $_LANG['lable_box']['shop_tel'] . ',55,25,177,224,b_shop_tel||,||t_customer_name,' . $_LANG['lable_box']['customer_name'] . ',78,23,299,265,b_customer_name||,||t_customer_address,' . $_LANG['lable_box']['customer_address'] . ',271,94,104,293,b_customer_address||,||';

    return;
}

/**
 * 顺丰速运费用计算方式: 起点到终点 * 重量(kg)
 * ====================================================================================
 * -浙江，上海，江苏地区为15元/公斤，续重(2元/公斤)
 * -续重每500克或其零数 (具体请上顺丰速运网站查询:http://www.sf-express.com/sfwebapp/price.jsp 客服电话 4008111111)
 *
 * -------------------------------------------------------------------------------------
 */

class dada_express
{
    /*------------------------------------------------------ */
    //-- PUBLIC ATTRIBUTEs
    /*------------------------------------------------------ */

    /**
     * 配置信息参数
     */
    var $configure;

    /*------------------------------------------------------ */
    //-- PUBLIC METHODs
    /*------------------------------------------------------ */

    /**
     * 构造函数
     *
     * @param: $configure[array]    配送方式的参数的数组
     *
     * @return null
     */
    function __construct($cfg=array())
    {
        foreach ($cfg AS $key=>$val)
        {
            $this->configure[$val['name']] = $val['value'];
        }

    }

    public function initApi() {
    	$config = array();
    	$config['app_key'] = 'dadaf3f03dc32b07ed0';
    	$config['app_secret'] = '7e4e615af165fe63cbf40e52abbc79e8';
    	$config['source_id'] = '73753';
    	$config['url'] = 'http://newopen.qa.imdada.cn/api/order/addOrder';
    	
    	return new DadaOpenapi($config);    	
    }
    
    /**
     * 计算订单的配送费用的函数
     *
     * @param   float   $goods_weight   商品重量
     * @param   float   $goods_amount   商品金额
     * @param   float   $goods_number   商品数量
     * @return  decimal
     */
    function calculate($goods_weight, $goods_amount, $goods_number)
    {
        if ($this->configure['free_money'] > 0 && $goods_amount >= $this->configure['free_money'])
        {
            return 0;
        }
        else
        {
            $fee = isset($this->configure['base_fee']) ? $this->configure['base_fee'] : 0;
            $this->configure['fee_compute_mode'] = !empty($this->configure['fee_compute_mode']) ? $this->configure['fee_compute_mode'] : 'by_weight';
            
            if ($this->configure['fee_compute_mode'] == 'by_number')
            {
                $fee = $goods_number * $this->configure['item_fee'];
            }
            else
            {
                if ($goods_weight > 1)
                {
                    $fee += (ceil(($goods_weight - 1))) * $this->configure['step_fee'];
                }
            }

            return $fee;
        }
    }

    /**
     * 查询快递状态
     *
     * @access  public
     * @return  string  查询窗口的链接地址
     */
    function query($invoice_sn)
    {
        $form_str = '<a href="http://www.sf-express.com/tabid/68/Default.aspx" target="_blank">' .$invoice_sn. '</a>';
        return $form_str;
    }
    
    /**
     * 返回快递100查询链接 by wang 
     * URL：https://code.google.com/p/kuaidi-api/wiki/Open_API_Chaxun_URL
     */
    function kuaidi100($invoice_sn){
        $url = 'http://m.kuaidi100.com/query?type=shunfeng&id=1&postid=' .$invoice_sn. '&temp='.time();
        return $url;
    }
}


/**
 * 新达达开放平台接口调用工具类
* 详情：签名，接口调用
* 版本：1.0
* 日期：2016-09-10
* 说明：
* 以下代码只是为了方便对接商户测试而提供的样例代码，对接商户可以根据自己的需求，按照技术文档编写，代码仅供参考。
*/

class DadaOpenapi{

	/**
	 * 达达开发者app_key
	 */
	private $app_key;
	/**
	 * 达达开发者app_secret
	 */
	private $app_secret;

	/**
	 * api url地址
	 */
	private $url;

	/**
	 * api版本
	 */
	private $v = "1.0";

	/**
	 * 数据格式
	 */
	private $format = "json";

	/**
	 * 商户ID
	 */
	private $source_id;

	/**
	 * http request timeout;
	 */
	private $httpTimeout = 5;

	/**
	 * 请求响应返回的数据状态
	 */
	private $status;

	/**
	 * 请求响应返回的code
	 */
	private $code;

	/**
	 * 请求响应返回的信息
	 */
	private $msg;

	/**
	 * 请求响应返回的结果
	 */
	private $result;

	/**
	 * 判断求是否异常
	 */
	private $isExcepet = false;

	/**
	 * 异常信息
	 */
	private $excepetMsg;

	/**
	 * 构造函数
	 * param array $config = array();
	 */
	public function __construct($config){
		isset($config['app_key']) ? $this->app_key = $config['app_key'] : trigger_error('app_key不能为空', E_USER_ERROR);
		isset($config['app_secret']) ? $this->app_secret = $config['app_secret'] : trigger_error('app_secret不能为空', E_USER_ERROR);
		isset($config['url']) ? $this->url = $config['url'] : trigger_error('url不能为空', E_USER_ERROR);
		isset($config['source_id']) ? $this->source_id = $config['source_id'] : trigger_error('source_id不能为空', E_USER_ERROR);
		isset($config['v']) && $this->v = $config['v'];
		isset($config['format']) && $this->format = $config['format'];
		isset($config['timeout']) && $this->httpTimeout = intval($config['timeout']);
	}

	/**
	 * 请求调用api
	 * data:业务数据
	 * @return bool
	 */
	public function makeRequest($data){
		$reqParams = $this->bulidRequestParams(json_encode($data));
		$resp = $this->getHttpRequestWithPost($this->url, json_encode($reqParams));
		$this->parseResponseData($resp);
		return $this->isExcepet;
	}

	/**
	 * 构造请求数据
	 * data:业务参数，json字符串
	 */
	public function bulidRequestParams($body){
		$requestParams = array();
		$requestParams['app_key'] = $this->app_key;
		$requestParams['body'] = $body;
		$requestParams['format'] = $this->format;
		$requestParams['v'] = $this->v;
		$requestParams['source_id'] = $this->source_id;
		$requestParams['timestamp'] = time();
		$requestParams['signature'] = $this->_sign($requestParams);
		return $requestParams;
	}

	/**
	 * 签名生成signature
	 */
	public function _sign($data){

		//1.升序排序
		ksort($data);

		//2.字符串拼接
		$args = "";
		foreach ($data as $key => $value) {
			$args.=$key.$value;
		}
		$args = $this->app_secret.$args.$this->app_secret;

		//3.MD5签名,转为大写
		$sign = strtoupper(md5($args));

		return $sign;
	}


	/**
	 * 发送请求,POST
	 * @param $url 指定URL完整路径地址
	 * @param $data 请求的数据
	 */
	public function getHttpRequestWithPost($url, $data){
		// json
		$headers = array(
				'Content-Type: application/json',
		);
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_TIMEOUT, 3);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$resp = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果在执行curl的过程中出现异常，可以打开此开关查看异常内容。
		$info = curl_getinfo($curl);
		curl_close($curl);
		if (isset($info['http_code']) && $info['http_code'] == 200) {
			return $resp;
		}
		return '';
	}

	/**
	 * 解析响应数据
	 * @param $arr返回的数据
	 * 响应数据格式：{"status":"success","result":{},"code":0,"msg":"成功"}
	 */
	public function parseResponseData($arr){
		if (empty($arr)) {
			$this->isExcepet = true;
			$this->excepetMsg = "接口请求失败";
		}else{
			$data = json_decode($arr, true);
			$this->status = $data['status'];
			$this->result = $data['result'];
			$this->code = $data['code'];
			$this->msg = $data['msg'];
		}
		return true;
	}

	/**
	 * 获取返回code
	 */
	public function getCode(){
		return $this->code;
	}

	/**
	 * 获取返回status
	 */
	public function getStatus(){
		return $this->status;
	}

	/**
	 * 获取返回msg
	 */
	public function getMsg(){
		return $this->msg;
	}

	/**
	 * 获取返回result
	 */
	public function getResult(){
		return $this->result;
	}

}

function get_data_config() {
	$config = array();
	$config['app_key'] = 'dadaf3f03dc32b07ed0';
	$config['app_secret'] = '7e4e615af165fe63cbf40e52abbc79e8';
	$config['source_id'] = '73753';
	$config['url'] = 'http://newopen.qa.imdada.cn/api/order/addOrder';
	return $config;
}

function dada_add_order() {
	
}


?>
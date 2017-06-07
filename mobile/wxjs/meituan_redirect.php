<?php
define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/include/init.php');

//美团
$meituan = 'meituan';

//
$ele = 'ele';
$r = isset($_GET['r']) ? $_GET['r'] : false;
$r = in_array($r, ['meituan', 'ele']) : $r : 'meituan';

if ($r == '') {
	
}
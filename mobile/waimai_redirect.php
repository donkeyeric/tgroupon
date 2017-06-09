<?php
define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/include/init.php');

$r = isset($_GET['r']) ? $_GET['r'] : false;

if ($r == 'meituan') {
	
	$location = $GLOBALS['_CFG']['meituan_redirect'];
	
} elseif ($r = 'ele') {
	
	$location = $GLOBALS['_CFG']['ele_redirect'];
	
} else {
	
	$location = $GLOBALS['_CFG']['default_redirect'];
	
}

header('location:' . $location);

<?php
ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.'../lib');
include_once('adodb5/adodb.inc.php');
include_once('Smarty/Smarty.class.php');
include_once('owasp-php-filters/sanitize.inc.php');
include_once('Framework.php');
include_once('misc/Debugger.php');
include_once('misc/Validator.php');

include_once('../applications/initialize.php');
include_once('../applications/config.php');

//Development Patterns: Zend, Shozu

//DB to Model Mapping by: ADODB
//Views by: Smarty
//Input Sanitization by: OWASP/PHP
//Email by: Zend/Mail

//Debugger::out($_REQUEST);
//Debugger::out(get_declared_classes());

$Framework = new Framework();
try { 
	$Framework->dispatchRequest($_REQUEST);
} catch(FrameworkException $e){
	echo $e->getMessage();
}
exit;
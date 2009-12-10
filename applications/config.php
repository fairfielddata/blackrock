<?php
//Framework
$CONFIGS['DEFAULT_APPLICATION'] = 'admin';
$CONFIGS['DEFAULT_CONTROLLER']  = 'admin';
$CONFIGS['DEFAULT_ACTION']      = 'index';

//Model
$CONFIGS['DB_DRIVER'] = 'mysql';
$CONFIGS['DB_HOST']   = 'host';
$CONFIGS['DB_NAME']   = 'database';
$CONFIGS['DB_USER']   = 'username';
$CONFIGS['DB_PASS']   = 'password';
$CONFIGS['DB_DSN']    = $CONFIGS['DB_DRIVER']."://".$CONFIGS['DB_USER'].":".$CONFIGS['DB_PASS']."@".$CONFIGS['DB_HOST']."/".$CONFIGS['DB_NAME'];

//View
$CONFIGS['SMARTY_ENABLE_CACHING'] = false;

//Controller
//Data


//Application Configs

//Site
$CONFIGS['SITE_NAME']   = 'Black Rock Site';
$CONFIGS['SITE_PREFIX'] = 'brs_';
//Email
$CONFIGS['ADMIN_EMAIL'] = '\"Site Admin\" <admin@site.com>';
$CONFIGS['SMTP_SERVER'] = 'smtp.server.com';
$CONFIGS['SMTP_CONFIG'] = array('auth'=>'login', 'username'=>'username', 'password'=>'password', 'port' => '26');
?>
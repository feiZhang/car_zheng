<?php
if (! defined ( 'THINK_PATH' ))
	exit ();

$theProjectConfig 	= require (DXINFO_PATH."/config.inc.php");
$theAppConfig 		= array(
		//基本配置区
		'DELETE_TAGS' 			=> array("delete_status"=>1),		
        'DEFAULT_MODULE'   => 'Customer',
		//个性配置区
		'USER_AUTH_KEY'			=> "id",		//用户登录认证字符
		'DEFAULT_THEME'			=> '',
		'TOKEN_ON'		    	=> false,
		
		//登录相关参数，，PublicAction使用
		//'TEST_USERNAME'		=> "admin",		//如果设置此字段，则登录不进行密码和验证码确认。发布版本，注释掉。
		'USER_NICK_NAME'		=> "true_name",		//用户昵称字段名		作为登录提醒显示的字段信息
		'LOGIN_MD5'			=> true,		//是否md5加密密码

		'FULLTEXT_SEARCH'		=> true,
    'DATAOPE_LIST_HEIGHT'=>80,
);


$theProjectDatabase = require ("./database.inc.php");

$theAppDatabase = require ("database.php");

if(file_exists("./debug.inc.php"))
	$theDebugConfig = require ("./debug.inc.php");
else
	$theDebugConfig = array();

$endConfig = array_merge ( $theProjectConfig, $theProjectDatabase, $theAppDatabase, $theAppConfig, $theDebugConfig );
//var_dump($endConfig)
return $endConfig;

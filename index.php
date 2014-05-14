<?php
session_name('car_sale');
//定义ThinkPHP路径
// error_reporting (E_ALL);
define('THINK_PATH', './TP312/');
//定义应用程序名称和路径
define('APP_NAME','aika');
define('APP_PATH','./sales_system/');
define('APP_DEBUG',true);

//安全配置
define('BUILD_DIR_SECURE',true);
define('DIR_SECURE_FILENAME', 'index.html');
define('DIR_SECURE_CONTENT', 'deney Access!');

define("DXINFO_PATH","/Users/pengL/job/car/DxInfo");

define('RUNTIME_PATH', '/tmp/'.APP_NAME."/");
//加载框架入口函数
require(THINK_PATH."ThinkPHP.php");


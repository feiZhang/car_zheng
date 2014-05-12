<?php
//定义ThinkPHP路径
// error_reporting (E_ALL);
define('THINK_PATH', './TP312/');
//定义应用程序名称和路径
define('APP_NAME','销售系统');
define('APP_PATH','./sales_system/');
define('APP_DEBUG',false);

//安全配置
define('BUILD_DIR_SECURE',true);
define('DIR_SECURE_FILENAME', 'index.html');
define('DIR_SECURE_CONTENT', 'deney Access!');

define("DXINFO_PATH","/home/liangpeng/car/DxInfo");
//加载框架入口函数
require(THINK_PATH."ThinkPHP.php");
?>

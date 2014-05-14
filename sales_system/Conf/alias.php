<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();
// 系统别名定义文件

//遍历本地文件，将class加入到别名列表中，以能够自动加载
$dx_alias_class     = array();
$handle = opendir(DXINFO_PATH);
if($handle) {
    while(false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $filename = $DXINFO_PATH . "/"  . $file;
            if(is_file($filename) && substr($file,-10)==".class.php") {
                $dx_alias_class[substr($file,0,-10)]  = $filename;
            }
        }
    }
    closedir($handle);
}

/*
 * TP生成runtime.php缓存文件后，将不在引用此文件，而是将这些配置项的最终值写到缓存文件中，所以需要将alias内容，要写入缓存中
 * */
return $dx_alias_class;
//var_export($dx_alias_class);

//return array ( 'DataOpeAction' => '/home/liangpeng/car/DxInfo/DataOpeAction.class.php', 'DxBasicAction' => '/home/liangpeng/car/DxInfo/DxBasicAction.class.php', 'DxCantonAction' => '/home/liangpeng/car/DxInfo/DxCantonAction.class.php', 'DxCantonModel' => '/home/liangpeng/car/DxInfo/DxCantonModel.class.php', 'DxDataSync' => '/home/liangpeng/car/DxInfo/DxDataSync.class.php', 'DxExtAccountModel' => '/home/liangpeng/car/DxInfo/DxExtAccountModel.class.php', 'DxExtCommonAction' => '/home/liangpeng/car/DxInfo/DxExtCommonAction.class.php', 'DxExtCommonModel' => '/home/liangpeng/car/DxInfo/DxExtCommonModel.class.php', 'DxExtDataChangeLogModel' => '/home/liangpeng/car/DxInfo/DxExtDataChangeLogModel.class.php', 'DxExtMenuModel' => '/home/liangpeng/car/DxInfo/DxExtMenuModel.class.php', 'DxExtOperationLogModel' => '/home/liangpeng/car/DxInfo/DxExtOperationLogModel.class.php', 'DxExtPublicAction' => '/home/liangpeng/car/DxInfo/DxExtPublicAction.class.php', 'DxExtRoleModel' => '/home/liangpeng/car/DxInfo/DxExtRoleModel.class.php', 'DxFunction' => '/home/liangpeng/car/DxInfo/DxFunction.class.php', 'DxParseTemplateBehavior' => '/home/liangpeng/car/DxInfo/DxParseTemplateBehavior.class.php', 'DxWidget' => '/home/liangpeng/car/DxInfo/DxWidget.class.php', 'httpsqs' => '/home/liangpeng/car/DxInfo/httpsqs.class.php', 'Image' => '/home/liangpeng/car/DxInfo/Image.class.php', 'ImageResize' => '/home/liangpeng/car/DxInfo/ImageResize.class.php', 'String' => '/home/liangpeng/car/DxInfo/String.class.php', 'UploadHandler' => '/home/liangpeng/car/DxInfo/UploadHandler.class.php' );

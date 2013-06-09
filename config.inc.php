<?php
//DXIPCC 核心配置文件
if (!defined('THINK_PATH')) exit();
return array(
	//设置公共模板路径,属于TP的配置内容,一般情况下，不需要覆盖修改的内容。
	'TMPL_ACTION_ERROR'		=> 'DxPublic:success',
	'TMPL_ACTION_SUCCESS'	=> 'DxPublic:success',
	'TOKEN_ON'				=> true, //关闭表单令牌
	'DEFAULT_MODULE'		=> 'Home',
	'APP_AUTOLOAD_PATH'		=> '@.DxInfo,@.Project',
	"LOAD_EXT_FILE"			=> "BasicFunction,ProjectFunction",
	'SESSION_AUTO_START'	=> true,
	'URL_ROUTER_ON' 		=> true,
	'URL_ROUTE_RULES'		=> array(			//正则模式下，不能使用 /xx/xx/xx/xx传递GET参数，会强制引入分组概念，导致action对应不上
			"/(\w+)\/edit\/(\d+)/"=>":1/add?id=:2",
			"/(\w+)\/delete\/(\d+)/"=>":1/delete?id=:2",
	),
	'DEFAULT_THEME'			=> '',
	'URL_MODEL'             => 2,
		
	//自己的数据节点，同步数据要从此节点获取数据
	'APP_DEBUG'				=> false,	// 是否开启调试模式
	'LOG_RECORD'			=> false,
	
	//我的桌面默认宽度和高度,宽度和高度要不带单位，用于页面css显示 如 style="width:300px;height:206px"
	'MY_DESKTOP'			=> array('width'=>'300','height'=>'206'),
	'DATAOPE_LIST_HEIGHT'	=> 20,
	'UPLOAD_FILETYPE'		=> ".gif、.jpeg、.jpg、.png、.pdf、.doc、.docx、.xls、.ppt、.txt、.mp4、.mov",
		
	'DP_POWER_FIELDS'	=> array(
			array('field_name'=>'create_userid','auto_type'=>1,'type'=>0,'session_field'=>"_id"),
			array('field_name'=>'create_username','auto_type'=>1,'type'=>0,'session_field'=>'_truename'),
			array('field_name'=>'create_dept_fdn','auto_type'=>1,'type'=>1,'session_field'=>'_cantonfdn'),
			array('field_name'=>'create_public','auto_type'=>0,'type'=>2,'session_field'=>''),
	),
	'DP_NOT_CHECK_ACTION'	=> array("Public"=>1,"DataSync"=>1),	//不进行数据权限控制的Action
	//不进行数据权限控制的Model	1.减少程序执行量，所以错误日志不需要
	'DP_NOT_CHECK_MODEL'	=> array("OperationLog"=>1),
	'DELETE_TAGS'			=> array("delete_status"=>"1"),
	'NOSHOW_OPERATION_LOG_ACTIONS'	=> array("get_datalist","ajaxPaging","SigmaData"),	//不写日志的Action列表
	
	'NOT_AUTH_ACTION'		=> array("Public"=>1,"Cron"=>1),		//无需权限认证的Action
	'REQUIST_AUTH_ACTION'	=> array(),					//必须权限认证的Action
	'LOGIN_USER_NICK_NAME'	=> "name",		//用户昵称字段名
	'LOGIN_MD5'				=> false,		//是否md5加密密码
	'TEST_USERNAME'			=> false,
	'USER_AUTH_KEY'         => 'login_user_id',
		
	"UPLOAD_BASE_PATH"		=> dirname(__FILE__)."/Uploads",		//实际存放上传文件的路径.
	"UPLOAD_BASE_URL"		=> __ROOT__."/Uploads",
	"TEMP_FILE_PATH"		=> RUNTIME_PATH."TMP_IMG",	//临时上传文件存放地址，上传的文件不一定都会提交，所以暂存到临时文件，后续可以清空临时文件目录
);
?>
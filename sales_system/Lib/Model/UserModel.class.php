<?php
class UserModel extends DxExtCommonModel{
	protected $listFields = array (
		"id"	=> array (
			'name' => 'id','title' => '操作','pk' => true,'hide' => 6,
			'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
									var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
									v	+= ' <a href=\"javascript:dataOpeDelete(' + value + ');\">删除</a>';
									return v;
								}","width"=>"120",
		),
		"login_name"	=> array (
			'name' => 'login_name','type'	=> "string",'title' => '登录名','frozen' => true,'readOnly' => 2,
		),
		array (
			'name' => 'job_type','title' => '工种','frozen' => true,
		),
		array (
				'name' => 'base_salary','title' => '基本工资','frozen' => true,'type'=>'int','hide'=>7,
		),
		array (
				'name' => 'real_name','title' => '真实姓名','frozen' => true,
		),
		array (
				'name' => 'nick_name','title' => '昵称','frozen' => true,
		),
		array (
				'name' => 'rose_type','title' => '用户角色','readOnly' => 0,'type'=>'enum',
		),
		array (
				'name' => 'status','title' => '账户状态','hide'=>6,
		),
		array (
				'name' => 'last_login_time','title' => '上次登陆时间','hide'=>6,
		),
		array (
				'name' => 'login_count','title' => '登陆次数','hide'=>6,
		),
		array (
				'name' => 'last_login_ip','title' => '上次登陆IP','hide'=>6,
		),
		array (
				'name' => 'create_time','title' => '创建时间','hide'=>6,
		),
		array (
				'name' => 'update_time','title' => '更新时间','hide'=>6,
		),
	);
	protected $modelInfo = array (
		'title' => '员工','toString'=>array("%s %s 昵称:%s 基本工资:%s元",array('real_name','job_type','nick_name','base_salary')),
		'dictTable' => 'real_name',
	);
	protected $_validate = array (
		array (
				0 => 'real_name',
				1 => 'require',
				2 => '真实姓名不能为空!',
		),
		array (
				0 => 'login_name',
				1 => 'require',
				2 => '用户名不能为空!',
		),
		array (
				0 => 'login_passwd',
				1 => 'require',
				2 => '密码不能为空!',
		),
	);
}

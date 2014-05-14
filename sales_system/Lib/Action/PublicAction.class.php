<?php
/**
 * 系统公共方法，包括：登录、注销。
 * 登录提交方法有2中，一种ajax，一种直接form提交
 *
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login_username` varchar(32) DEFAULT NULL COMMENT '用户名',
  `login_pwd` varchar(100) DEFAULT NULL COMMENT '密码',
  `name` varchar(31) DEFAULT NULL COMMENT '姓名',
  `tel` varchar(31) DEFAULT NULL COMMENT '电话',
  `address` varchar(255) DEFAULT NULL COMMENT '住址或联系地址',
  `role_id` int(11) unsigned DEFAULT NULL COMMENT '角色id',
  `canton_id` int(11) unsigned DEFAULT NULL COMMENT '所在区域',
  `canton_fdn` varchar(100) DEFAULT NULL COMMENT '区域串',
  `status` tinyint(1) DEFAULT NULL COMMENT '1正常-1已删除',
  `user_type` enum('sys','org') DEFAULT NULL COMMENT 'sys系统管理员org表示机构账户',
  `last_login_time` timestamp NULL DEFAULT NULL,
  `login_count` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` varchar(24) DEFAULT NULL,
  `create_userid` int(11) unsigned DEFAULT NULL COMMENT '创建人ID',
  `create_dept_fdn` varchar(255) DEFAULT NULL COMMENT '创建部门的fdn',
  `create_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `canton_fdn` (`canton_fdn`)
) ENGINE=MyISAM COMMENT='登录账户表';
 * **/
class PublicAction extends DxExtPublicAction {
	protected function setSession($user){
		session(C('USER_AUTH_KEY'), $user['id']);
		session('login_name', $user['login_username']);
		session('true_name', $user['true_name']);
		session('role_id', $user['role_id']);
		session('canton_id', $user['canton_id']);
		session('canton_fdn', $user['canton_fdn']);
		session('user_type', $user['user_type']);
		if($user['user_type']=="admin") session('DP_ADMIN', true);

		//数据权限功能。
        foreach(C('DP_PWOER_FIELDS') as $dp_fields){
			if(array_key_exists("session_field",$dp_fields)) $field_name 	= $dp_fields["session_field"];
            else $field_name         = $dp_fields["name"];
            if($dp_fields["isWhere"] && array_key_exists($field_name,$user)){
                session($field_name,$user[$field_name]);
            }
        }

		$data['id']	    			= $user['id'];
		$data['last_login_time']	= DxFunction::getMySqlNow();
		$data['login_count']	    = array('exp','login_count+1');
		$data['last_login_ip']	    = $_SERVER["REMOTE_ADDR"];
		if(!empty($user["save_account"])){
			//保存自动登录验证码
			$data['save_account']		= $user["save_account"];
		}
		$user   = D('Account');
		$user->save($data);
	}
}
?>

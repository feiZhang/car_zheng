<?php
class CantonAction extends SimpleAction{
	
	/**
	 * 通过ajax查询区域信息 
	 */
	public function getCantonInfoById() {
		$model = M ( "Canton" );
		$id = $_REQUEST ["id"];
		$vo = $model->getById ( $id );
		if (! empty ( $vo )) {
			$vo ['hasFlag'] = 1;
		} else {
			$vo ['hasFlag'] = 0;
		}
		echo json_encode ( $vo );
	}
	
	public function _before_add() {
		$this->assign ( "parent_id", $_REQUEST ['parent_id'] );
	}
	
	/**
	 * 处理默认字段
	 * @param int $id 新插库数据记录的ID
	 */
	public function afterinsert_dealing($id) {
		$parentId = getValueById ( "Canton", $id, "name,parent_id" );
		$list = getValueById ( "Canton", $parentId ['parent_id'], "fdn,text_name" );
		$M = D ( 'Canton' );
		$data['fdn'] = $list['fdn'] . "$id.";
		$data['text_name'] = $list['text_name']."|".$parentId['name'];
		$M->where ( array ("id" => array ("eq", $id ) ) )->save ( $data );
	}
	
	public function tree() {
		$id = $_REQUEST ['id'];
		$m = D ( 'Canton' );
		$ret = array ();
		if (empty($id)) {
			$id = $this->getCantonid ();
			$cond = array ("id" => $id );
			$list = $m->where ( $cond )->field ( "id,name,parent_id" )->find ();
			$ret ["data"] = $list ['name'];
			$ret ['attr'] = array ('id' => $list ['id'], 'rel' => 'root' );
			$ret ["state"] = "open";
			$ret ["children"] = $this->handlesub ( $this->getsubcanton ( $id ) );
		} else {
			$ret = $this->handlesub ( $this->getsubcanton ( $id ) );
		}
		echo json_encode ( $ret );
	}
	
	function handlesub($data) {
		if($data!==false){
			$ret = array ();
			foreach ( $data as $v ) {
				//如果当前区域已么有下级，则直接展开，不用closed了
				$has_chiledren=$this->getsubcanton($v['id']);
				if($has_chiledren){
					$state='closed';
				}
				else{
					$state='opened';
				}
				$a = array ("state" => $state, "attr" => array ("id" => $v ['id'] ), 'data' => $v ['name'] );
				$ret [] = $a;
			}
			return $ret;
		}
		return false;
		
		
	}
	
	function getsubcanton($pid) {
		$m = D ( 'Canton' );
		$cond = array ("parent_id" => $pid );
		$ret = $m->where ( $cond )->order ( 'ordernum asc' )->field ( 'id,name,parent_id' )->select ();
		if($ret){
			return $ret;
		}
		else{
			return false;
		}
		
	}
	
	public function adds() {
		$SysCanton = D ( 'Canton' );
		$parentid = $_POST ['parent_id'];
		$name = trim ( $_POST ['name'] );
		$where ['parent_id'] = $parentid;
		$list = $SysCanton->where ( $where )->select ();
		
		if ($list) {
			$count = count ( $list );
		} else {
			$count = 0;
		}
		$diflist = $SysCanton->where (array('name'=>$name,'parent_id'=>$parentid))->find ();
		$rs = array ('result' => 0, 'id' => 0 );
		if ($diflist) {
			//$this->error("输入区域名称重复");
			$rs ['result'] = 3;
		} else {
			$parent = $SysCanton->where ( "id='$parentid'" )->find ();
			if ($parent) {
				$layer = $parent ['layer'] + 1;
			} else {
				$layer = 1;
			}
			$data ['name'] = $name;
			$data ['ordernum'] = $count + 1;
			$data ['parent_id'] = $parentid;
			$data ['layer'] = $layer;
			$data ['accountID'] = $this->getUid ();
			$data ['is_del'] = '1';
			$data ['date'] = date ( "Y-m-d H:i:s" );
			$id = $SysCanton->data ( $data )->add ();
			if ($id !== false) {
				$rs ['result'] = 1;
				$rs ['id'] = $id;
			} else {
				//$this->error(L('_INSERT_FAIL_'));
				$rs ['result'] = 0;
			}
		}
		echo json_encode ( $rs );
	}
	/**
	 * ajax删除一个区域节点，有下级节点不允许删除返回2，有老人或业务数据不允许删除返回3，删除成功返回1，删除失败返回0
	 * @see SimpleAction::delete()
	 */
	public function delete() {
		$flag=0;
		$DictCanton = D ( 'Canton' );
		$id = $_GET ['id'];
		//先看是否有重要业务数据，有不允许删除
		$where ['parent_id'] = array ('eq', $id );
		//有下级区域时把下级区域全部删除
		$info = $DictCanton->where ("id='$id'" )->find ();
		$fdn = $info['fdn'];
		$is_del=$info['is_del'];
		if($is_del){
			$data['fdn'] = array("like",$fdn."%");
			$rs = $DictCanton->where($data)->delete();
			if($rs)
			{
				$flag = 1;
			}
		}
		else{
			$flag=4;
		}
		
		echo $flag;
	}
	
	public function move() {
		$id = $_POST ['id'];
		$parentid = $_POST ['new_parent_id']; //新的父ID
		$old_parentid = $_POST ['old_parent_id']; //原父ID
		$move = intval ( $_POST ['move'] ); //移动距离  move的值越小说明他将来显示的优先级就越高
		$rs = 0;
		if (! empty ( $id ) && ! empty ( $parentid )) {
			//当新的ID和原父ID相等时，说明是同级移动
			if ($parentid == $old_parentid) {
				$data1 ['parent_id'] = array ('eq', $parentid );
				$DictCanton = D ( 'Canton' );
				$list = $DictCanton->where ( $data1 )->field ( 'id, ordernum' )->order ( 'ordernum ASC' )->select ();
				$rs = $this->set_update_sql ( $id, $move, $list );
				foreach ( $rs as $key => $val ) {
					$DictCanton->execute ( $val );
				}
				$rs = 1;
			} else {
				//处理不同级的移动
			}
		}
		echo $rs;
	}
	
	/*
     * 设置update语句
     * @param    string    $id    移动的ID
     * @param    int       $move  移动的位置
     * @param    array     $array 包含统计节点的数组
     * return array
     */
	
	public function set_update_sql($id, $move, $array) {
		$n = count ( $array );
		foreach ( $array as $key => $val ) {
			if ($id == $val ['id']) {
				unset ( $array [$key] );
			}
		}
		$rs = array ();
		if ($move == $n) {
			$i = 0;
			foreach ( $array as $key => $val ) {
				$rs [] = "UPDATE __TABLE__ SET ordernum='$i' WHERE id='" . $val ['id'] . "'";
				$i ++;
			}
		} else {
			$i = 0;
			foreach ( $array as $key => $val ) {
				if ($i == $move) {
					$i ++;
				}
				$rs [] = "UPDATE __TABLE__ SET ordernum='$i' WHERE id='" . $val ['id'] . "'";
				$i ++;
			}
		}
		$rs [] = "UPDATE __TABLE__ SET ordernum='$move' WHERE id='$id'";
		return $rs;
	}
	
	/**
	 * 选择所属区域弹出的页面，把该区域下的地区展示成树形结构。有一定权限，只能选择当前登录人员所在或其下区域
	 *
	 * @param	(类型)	 (参数名)	(描述)
	 */
	function selectCanton() {
		$DictCanton = D ( 'Canton' );
		$login_canton = $this->getCantonid ();
		
		$rs = $DictCanton->where ( "id='$login_canton'" )->find ();
		if ($rs) {
			$root_name = $rs ['name'];
			$this->assign ( 'root_name', $root_name );
			$this->assign ( 'currentCanton', $login_canton );
		}
		
		/* 当前地区的子节点==== */
		$currentCanton = $rs ['id'];
		$subcanton = $DictCanton->where ( "parent_id='$login_canton'" )->select ();
		if ($subcanton) {
			$retstr = '<ul>';
			foreach ( $subcanton as $key => $val ) {
				$subid = $val ['id'];
				$subname = $val ['name'];
				$subid_a = $subid . '_a';
				/* 判断一下该区域是否有下级区域==== */
				$count = $DictCanton->where ( "parent_id='$subid'" )->count ();
				if ($count > 0) {
					$retstr .= "<li id=\"$subid\"><span>$subname</span>\r\t";
					$retstr .= "<ul class=\"ajax\">\r\t";
					$retstr .= "<li id='$subid_a'>{url:/Canton/ajaxFindChildren/tree_id/$subid}</li>\r\t";
					$retstr .= "</ul></li>\r\t";
				} else {
					$retstr .= "<li id=\"$subid\"><span class='text'>$subname</span></li>\r\t";
				}
			}
			$retstr .= '</ul>';
			$this->assign ( 'subcanton', $retstr );
		}
		$this->display ();
	}
	
	/**
	 * 动态加载树形结构图
	 *
	 * @param	(类型)	 (参数名)	(描述)
	 */
	function ajaxFindChildren() {
		$returnStr = '';
		$treeId = $_REQUEST ['tree_id'];
		if ($treeId) {
			$DictCanton = D ( 'Canton' );
			$rs = $DictCanton->where ( "parent_id='$treeId'" )->select ();
			if ($rs) {
				foreach ( $rs as $key => $val ) {
					$trid = $val ['id'];
					$trname = $val ['name'];
					/* 判断一下该区域是否有下级区域==== */
					$count = $DictCanton->where ( "parent_id='$trid'" )->count ();
					if ($count > 0) {
						$returnStr .= "<li id='$trid'><span >$trname</span>\r\t";
						$returnStr .= "<ul class='ajax'><li id='$trid'>{url:/Canton/ajaxFindChildren/tree_id/$trid}</li></ul></li>";
					} else {
						$returnStr .= "<li id='$trid'><span class='text'>$trname</span></li>\r\t";
					}
				}
			}
		}
		echo ($returnStr);
	}

	public function update() {
		$model = D ( "Canton" );
		$model->id = $_REQUEST ['id'];
		$model->name = $_REQUEST ['name'];
		$list = $model->save ();
		if ($list !== false) {
		//调用自定义函数对处理过的结果进行二次处理
			if (method_exists ( $this, 'afterinsert_dealing' )) {
				$this->afterinsert_dealing ( $_REQUEST['id'] );
			}
			$this->ajaxReturn ( '', '修改成功!', 1 );
		} else {
			//失败提示
			$this->error ( '', '修改失败!', 0 );
		}
	}
	
    /*
     * 获得地区的汉字
     */
    function get_chinese_canton()
    {
    	set_time_limit(0);
    	$Canton = M("Canton");
    	$list  = $Canton->select();//Canto表里面的数据不算太多，所以不用采用分段处理。
    	foreach($list as $key=>$val)
    	{
    		$id = $val['id'];
    		$fdn = $val['fdn'];
    		$fdn_arr = explode('.', $fdn);
    		$data = array();
    		$data['text_name']="";
    		if(!empty($fdn_arr[1]))
    		{
    			$arr = $this->get_canton($fdn_arr[1]);
    			$data['text_name'] = $arr['name'];
    			if($fdn_arr[2])
    			{
    				$arr = $this->get_canton($fdn_arr[2]);
    				$data['text_name'] .="|".$arr['name'];
    				if($fdn_arr[3])
    				{
    					$arr = $this->get_canton($fdn_arr[3]);
    					$data['text_name'] .="|".$arr['name'];
    				}
    			}
    		}
    		if($data['text_name'])
    		{
    			
    			$Canton->where("id='$id'")->save($data);
    		}
    		echo $id;echo "<br />";
    	}
    	
    }
    function get_canton($id)
    {
    	$arr = D('Canton')->where("id='$id'")->field("name")->find();
    	return $arr;
    }
    
    
    public function getSelectSelectSelect(){
    	$m = D('Canton');
    	$list = $m->field("id,name title,parent_id,fdn val")->select();
    	if($list) $data	= array($list);
    	else $data = array();
    	echo json_encode($list);
    	
    }
}
?>
<?php
class MenuModel extends DxExtCommonModel {
    public function getMyMenuList(){
    	return $this->where(array("type"=>array('in',"menu"),'id'=>array('in',D("Role")->getMeunID())))->order("order_no asc")->select();
    }
    public function getAllAction(){
    	return $this->order("order_no")->select();
    }
    public function getMyAction(){
    	$data	= $this->where(array('id'=>array('in',D("Role")->getMeunID())))->order("order_no asc")->select();
    	return $data;
    }
    public function getMyQuickMenu(){
    	$data	= $this->where(array("type"=>"quick_menu",'id'=>array('in',D("Role")->getMeunID())))->order("order_no asc")->select();
    	//die($this->getLastSQL());
    	return $data;
    }
    public function getRoleDeskTop(){
    	$data	= $this->where(array("is_desktop"=>"1",'id'=>array('in',D("Role")->getMeunID())))->order("order_no asc")->select();
    	return $data;
    }
}
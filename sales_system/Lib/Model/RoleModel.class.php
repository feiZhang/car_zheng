<?php
class RoleModel extends DxExtCommonModel {
    public function getMeunID(){
    	$role_id			= session("role_id");
    	if(intval($role_id)<1) return "0";
    	$data	= $this->where(array("id"=>$role_id))->field("menu_ids")->find();
    	return empty($data["menu_ids"])?"0":$data["menu_ids"];
    }
}

?>

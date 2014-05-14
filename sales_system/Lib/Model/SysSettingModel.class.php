<?php
class SysSettingModel extends DxExtCommonModel {
	protected $listFields = array (
		array("name"=>"id","pk"=>true,'title'=>'操作',"hide"=>6,
			'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
								var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
								return v;
							}",
		),
		array (
			'name'=>'title','title'=>'主题','frozen'=>true,'readOnly'=>true,
		),
		array (
			'name' => 'val','title' => '内容','width'=>"3000",
		),
		array (
			'name' => 'memo','title' => '备注',"width"=>"3000",
		),
	);
	
	protected $modelInfo=array(
		"title"=>'系统设置','readOnly'=>true,
		//'searchHTML'=>"类型:<input name='type' value='Sex' type='radio' />性别<input name='type' value='SubsidyRank' type='radio' />补贴标准 <input type='button' class='d-button d-state-highlight' value='查询' id='item_query_items' /> <input type='button' class='d-button d-state-highlight' value='全部数据' id='item_query_all' />",
	);
	
}
?>

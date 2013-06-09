<?php
class SysDicModel extends DxExtCommonModel{
	protected $listFields = array (
		array("name"=>"id","pk"=>true,'title'=>'操作',"hide"=>6,
					'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
										var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
										v	+= ' <a href=\"javascript:dataOpeDelete(' + value + ');\">删除</a>';
										return v;
									}",
			),
		array (
			'name' 		=> 'type','title'=>'类型','frozen' 	=> true,
			'type'		=> 'enum','readOnly'=>true,
			'valChange'	=> array('disagree_reason'=>"补贴拒绝原因",'SubsidyRank'=>'补贴类型'),
		),
		array (
			'name' 		=> 'content','title' 	=> '内容'
		),
		array (
			'name' 		=> 'memo','title' 	=> '备注',"width"=>"300",
		),
	);
	
    protected $modelInfo=array(
    	"title"=>'数据字典','readOnly'=>false,
    	//'searchHTML'=>"类型:<input name='type' value='Sex' type='radio' />性别<input name='type' value='SubsidyRank' type='radio' />补贴标准 <input type='button' class='d-button d-state-highlight' value='查询' id='item_query_items' /> <input type='button' class='d-button d-state-highlight' value='全部数据' id='item_query_all' />",
    );
}
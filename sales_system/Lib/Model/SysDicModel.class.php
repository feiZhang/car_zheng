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
		"type"    => array ('title'=>'类型','frozen'=>true,'type'=>'enum','readOnly'=>true,'valChange'=>array('month_zhichu'=>"杂支"),),
		"content" => array ('title' 	=> '内容'),
		"memo"    => array ('title' 	=> '备注',"width"=>"300",),
	);
	
    protected $modelInfo=array(
    	"title"=>'数据字典','readOnly'=>false,"dictTable"=>"type,id,content",
    	//'searchHTML'=>"类型:<input name='type' value='Sex' type='radio' />性别<input name='type' value='SubsidyRank' type='radio' />补贴标准 <input type='button' class='d-button d-state-highlight' value='查询' id='item_query_items' /> <input type='button' class='d-button d-state-highlight' value='全部数据' id='item_query_all' />",
    );
}

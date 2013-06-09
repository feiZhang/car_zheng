<?php
class CommodityModel extends DxExtCommonModel{
	protected $listFields = array (
		array (
				'name' => 'id',
				'title' => '标识',
				'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
									var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
									v	+= ' <a href=\"javascript:dataOpeDelete(' + value + ');\">删除</a>';
									return v;
}
",
				'pk' => true,
				'hide' => 6,		//self::HIDE_FIELD_ADD + self::HIDE_FIELD_LIST
		),
		array (
				'name' 		=> 'commodity_sort_id',
				'title' 	=> '类别',
				'frozen' 	=> true,
				'valChange'	=> array("model"=>"CommoditySort"),
				'type'		=> "enum",
		),
		array (
				'name' 		=> 'title',
				'title' 	=> '商品名称',
				'frozen' 	=> true,
				'width'		=> 180,
				'search' 	=> array(),
		),
		array (
				'name' 		=> 'price',
				'title' 	=> '价格',
				'frozen' 	=> true,
				"type"		=> 'float',
		),
		
		array (
				'name' => 'commodity_type',
				'title' => '商品属性',
				'type'	=> "set",
		),
		array (
				'name' 		=> 'brokerage_user',
				'title' 	=> '提成人员',
				'type'		=> 'set',
				'valChange'	=> array("model"=>"User"),
				'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){ 
											var valChangeDatas=eval(value);
											var v	= '';
											$(valChangeDatas).each(function(i){
												v	+= ' '+this[1];
											});
											return v;
										}",
		),
		array (
				'name' => 'brokerage_type',
				'title' => '提成类型',
				'type'	=> 'enum',
		),
		array (
				'name' => 'brokerage_basic_value',
				'title' => '提成基础值',
		), 
		array (
				'name' => 'create_time','title' => '创建时间','hide'=>2,
		),
		array (
				'name' => 'update_time','title' => '更新时间','hide'=>2,
		),
		array (
				'name' => 'status','title' => '状态','hide'=>2,
		), 
	);
	protected $modelInfo = array (
		'title' => '商品','otherManageAction'=>"",
		'dictTable' => 'title',
	);
}

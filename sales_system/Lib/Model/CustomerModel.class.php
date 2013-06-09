<?php
class CustomerModel extends DxExtCommonModel{
	protected $listFields = array (
		array (
				'name' => 'id','title' => '操作','pk' => true,'hide' => 6,
				'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
									var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
									v	+= ' <a href=\"javascript:dataOpeDelete(' + value + ');\">删除</a>';
									return v;
								}","width"=>"120",
		),
		array (
				'name' => 'real_name',
				'title' => '真实姓名',
				'frozen' => true,
				'hide' => 7,
		),
		array (
				'name' => 'card_no',
				'title' => '会员卡号',
				'frozen' => true,
				'readOnly'=>true,
		),
		array (
				'name' => 'car_no',
				'title' => '车牌号',
		),
		
		array (
				'name' => 'remaining_money',
				'title' => '卡余额',
				'readOnly'=>true,
		),
		array (
				'name' => 'tel',
				'title' => '手机号',
		),
		array (
				'name' => 'descript',
				'title' => '备注',
		),
		array (
				'name' => 'create_time','title' => '创建时间','hide'=>6,
		),
		array (
				'name' => 'update_time','title' => '更新时间','hide'=>6,
		),
		array (
				'name' => 'status','title' => '状态','type'=>'enum',
		), 
	);
	protected $modelInfo = array (
		'title' => '客户','rowAction' => '充值',"readOnly"=>true,
		'toString'=>array("%s %s 卡号:%s 车牌:%s",array('real_name','tel','car_no','card_no')),
		'dictTable' => 'real_name',
	);
}

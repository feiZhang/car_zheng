<?php
class CustomerSavedCommodityModel extends DxExtCommonModel{
	protected $listFields = array (
			array (
					'name' => 'id','title' => '操作','pk' => true,'hide'=>6,
					'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
									var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
									v	+= ' <a href=\"javascript:dataOpeDelete(' + value + ');\">删除</a>';
									return v;
								}","width"=>"120",
			),
			array (
					'name' 		=> 'customer_id',"type"=>"select",'title' 	=> '客户',
					'valChange'	=> array("model"=>"Customer"),
			),
			array (
					'name' => 'commodity_title',"hide"=>7,
					'title' => '商品名称',"formFiled"=>"commodity_id",
			),
			array (
					'name' => 'commodity_id',
					'title' => '商品名称','type'=>'select',
					'valChange'	=> array("model"=>"Commodity"),
			),
			array (
					'name' => 'descript',
					'title' => '描述',
			),
			array (
					'name' => 'status','title' => '状态','type'=>'enum',
					'valChange'	=> array("1"=>'寄存','0'=>"已取走",'-1'=>'忽略'),
			),
			array (
					'name' => 'create_time','title' => '创建时间','hide'=>6
			),
			array (
					'name' => 'update_time','title' => '更新时间','hide'=>6
			),
	);
	protected $modelInfo = array (
			'title' => '商品寄存',"readOnly"=>false,
			'toString'=>array("%s %s 卡号:%s 车牌:%s",array('real_name','tel','car_no','card_no')),
	);
}
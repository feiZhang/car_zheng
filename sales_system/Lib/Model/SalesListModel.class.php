<?php
class SalesListModel extends DxExtCommonModel{
	protected $listFields = array (
		array (
				'name' => 'title','title' => '商品名称','frozen' => true,
		),
		array (
				'name' => 'brokerage_user_name','title' => '提成人','frozen' => true,
		),
		array (
				'name' => 'customer_name','title' => '客户车牌号','frozen' => true,
		),
		array (
				'name' => 'id','title' => '操作','frozen'=>true,'pk' => true,'hide'=>7,
									'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
											var v	= '<a href=\"javascript:editPriceSaleInfo(' + value + ',' + record.sales_price + ');\">再优惠</a>';
											if(record.status=='待取消'){
												v	+= ' <a href=\"javascript:cancelSaleInfo(' + value + ');\">撤销销售</a>';
											}else{
												v	+= ' <a href=\"javascript:redayCancelSaleInfo(' + value + ');\">标记撤销销售</a>';
											}
											if(record.status=='赊账'){
												v	+= ' <a href=\"javascript:paySaleInfo(' + value + ');\">还账</a>';
											}
											return v;
										}",
				"width"=>150,
		),
		array (
				'name' => 'sales_price','title' => '销售量',
		),
// 		array (
// 				'name' => 'finish_price','title' => '实收价格',
// 		),
		array (
				'name' => 'commodity_type',
				'title' => '商品类型',
		),
		array (
				'name' => 'brokerage_value',
				'title' => '提成(元)',
		),
		array (
				'name' => 'brokerage_type',
				'title' => '提成类型',
		),
		array (
				'name' => 'brokerage_basic_value',
				'title' => '提成基础值',
		),
		array (
				'name' => 'create_time','title' => '创建时间','width'=>120,
		),
		array (
				'name' => 'update_time','title' => '更新时间','width'=>120,
		),
		array (
				'name' => 'status','title' => '状态',
		),
	);
	protected $modelInfo = array (
		'title' => '销售记录','readOnly'=>true,'order'=>'id desc',
	);
}

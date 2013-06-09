<?php
class UserBuyCommodityModel extends DxExtCommonModel{
	protected $listFields = array (
		"id"	=> array (
			'title' => '标识','pk' => true,'hide' => 7,
		),
		"create_time"	=> array (
			'title' => '消费时间','frozen' => true,'width'=>120,
		),
		"commodity_id"	=> array (
			'title' => '消费商品','frozen' => true,'readOnly'=>true,"valChange"=>array("model"=>"Commodity")
		),
		"commodity_type"	=> array (
			'title' => '消费类型',
		),
		"finish_price"		=> array (
			'title' => '消费金额',
		),
		"descript"	=> array (
			'title' => '描述',"width"=>2000,
		),
	);
	protected $modelInfo = array (
		'title' => '客户消费记录',"readOnly"=>true,
		'searchHTML'=>'消费时间:<input onfocus="WdatePicker()" type="text" size="3" id="egt_create_time" name="create_time" class="dataOpeSearch input-small search-query span2" value=""/>
							  -<input onfocus="WdatePicker()" type="text" size="3" class="dataOpeSearch input-small search-query span2" id="elt_create_time" name="create_time" value=""/>
							<input onclick="javascript:dataOpeSearch(true);" type="button" class="btn" value="查询" id="item_query_items" />
	    					<input onclick="javascript:dataOpeSearch(false);" type="button" class="btn" value="全部数据" id="item_query_all" />
					');
}
<?php
class CustomerModel extends DxExtCommonModel{
  protected $viewTableName  = "(SELECT
    c.*,k.count_times,k.remaining_times,m.title 
    FROM customer c LEFT JOIN times_card k ON k.customer_id=c.id
    LEFT JOIN commodity m ON m.id=k.commodity_id
    ) cust";
	protected $listFields = array (
		"id"  => array (
				'title' => '业务操作','pk' => true,'hide' => 6,'grouped'=>true,
				'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
									var v	= ' <a href=\"javascript:saleCommodity(\'' + record['car_no'] + '\');\">消费</a>';
									v	+= ' <a href=\"javascript:cardTopUp(\'' + record['car_no'] + '\');\">充值</a>';
									v	+= ' <a href=\"__ROOT__/UserBuyCommodity/index/ignoreInitSearch/1?customer_name=' + encodeURIComponent(record['real_name']) + '\" target=\"showCustomerSaleList\">历史消费</a>';
									return v;
								}","width"=>"120",
		),
		"car_no"      => array ('title' => '车牌号','grouped'=>true,),
        'real_name'   => array ('title' => '真实姓名','hide' => 7,),
        'card_no'     => array ('title' => '会员卡号','readOnly'=>2,'grouped'=>true,),
		'remaining_money'   => array ('title' => '卡余额','readOnly'=>3,'width'=>80,'hide'=>2,'grouped'=>true,),
		'tel'         => array ('title' => '手机号','grouped'=>true,),
		'status'      => array ('title' => '状态','type'=>'enum','hide'=>7),
		'descript'    => array ('title' => '备注',),
        'delete_status'   => array('title' => '数据操作','hide'=>30,
                'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
									var v	= '<a href=\"javascript:dataOpeEdit(' + record['id'] + ');\">修改</a>';
									v	+= ' <a href=\"javascript:dataOpeDelete(' + record['id'] + ');\">删除</a>';
									return v;
								}",),
    'title'           => array("title"=>"计次卡项目","hide"=>6),
    'remaining_times' => array("title"=>"计次卡剩余次数","hide"=>6),
		'create_time' => array ('title' => '创建时间','hide'=>6,'width'=>120,),
		'update_time' => array ('title' => '更新时间','hide'=>6,'width'=>120,),
	);
	protected $modelInfo = array (
		'title' => '客户','addTitle'=>'售新卡','rowAction' => '充值',"readOnly"=>false,
		'toString'=>array("%s %s 卡号:%s 车牌:%s",array('real_name','tel','card_no','car_no')),
		'dictTable' => 'real_name',
    'otherManageAction' => '<input onclick="javascript:saleCommodity(\'\');" type="button" class="btn pull-right" value="现金消费" id="noCardSaleCommodity" />',
    'searchHTML'=>'
    车牌号:<input type="text" size="3" id="car_no" name="tel" class="dataOpeSearch input-small search-query span2 likeLeft likeRight" value=""/>
    手机号:<input type="text" size="3" id="tel" name="tel" class="dataOpeSearch input-small search-query span2 likeLeft likeRight" value=""/>
							        <input onclick="javascript:dataOpeSearch(true);" type="button" class="btn" value="查询" id="item_query_items" />
	    					      <input onclick="javascript:dataOpeSearch(false);" type="button" class="btn" value="全部数据" id="item_query_all" />
                      ',
	);
  //去掉客户名称，使用车牌号作为客户名称
  protected function _before_insert(&$data, $options) {
    if(array_key_exists("car_no",$data)) $data["real_name"]  = $data["car_no"];
    parent::_before_insert($data, $options);
    return true;
  }
  protected function _before_update(&$data, $options) {
    if(array_key_exists("car_no",$data)) $data["real_name"]  = $data["car_no"];
    parent::_before_update($data, $options);
    return true;
  }
}

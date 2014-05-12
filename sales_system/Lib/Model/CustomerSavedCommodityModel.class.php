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
          'valChange'	=> array("model"=>"Customer"),'hide'=>1,'textTo'=>'customer_name',
			),
			array (
					'name' => 'customer_name',"hide"=>6,'title' => '客户',
			),
			array (
					'name' => 'commodity_title',"hide"=>6,'title' => '商品名称'
			),
			'commodity_id'  => array (
					'title' => '商品名称','type'=>'enum','valChange'	=> array("sql"=>"select id,title name from commodity where commodity_type LIKE '%可寄存%'"),'textTo'=>'commodity_title','hide'=>1,
			),
			'descript'      => array ('title' => '描述',),
			'status'        => array ('title' => '状态','type'=>'enum','valChange'	=> array("1"=>'寄存','0'=>"已取走",'-1'=>'忽略'),),
			'create_time'   => array ('title' => '创建时间','hide'=>6,'width'=>120),
			'update_time'   => array ('title' => '更新时间','hide'=>6,'width'=>120,),
	);
	protected $modelInfo = array (
    'title' => '商品寄存',"readOnly"=>false,
    'toString'=>array("%s %s 卡号:%s 车牌:%s",array('real_name','tel','car_no','card_no')),
    'searchHTML'=>'
    车牌号:<input type="text" size="3" id="customer_name" name="customer_name" class="dataOpeSearch input-small search-query span2 likeLeft likeRight" value=""/>
    商品名称:<input type="text" size="3" id="commodity_title" name="commodity_title" class="dataOpeSearch input-small search-query span2 likeLeft likeRight" value=""/>
    描述:<input type="text" size="3" id="descript" name="descript" class="dataOpeSearch input-small search-query span2 likeLeft likeRight" value=""/>
							        <input onclick="javascript:dataOpeSearch(true);" type="button" class="btn" value="查询" id="item_query_items" />
	    					      <input onclick="javascript:dataOpeSearch(false);" type="button" class="btn" value="全部数据" id="item_query_all" />
                      ',
	);
}

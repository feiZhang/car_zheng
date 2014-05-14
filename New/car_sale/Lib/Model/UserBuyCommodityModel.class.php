<?php
class UserBuyCommodityModel extends DxExtCommonModel{
  protected $viewTableName  = "(SELECT
   `u`.`real_name` AS `brokerage_user_name`,
   `c`.`title` AS `commodity_title`,
   `cc`.`real_name` AS `customer_name`,
   `uc`.`id` AS `id`,
   `uc`.`commodity_id` AS `commodity_id`,
   `uc`.`customer_id` AS `customer_id`,
   `uc`.`commodity_type` AS `commodity_type`,
   `uc`.`status` AS `status`,
   `uc`.`sales_user_id` AS `sales_user_id`,
   `uc`.`sales_price` AS `sales_price`,
   `uc`.`finish_price` AS `finish_price`,
   `uc`.`remaining_money` AS `remaining_money`,
   `uc`.`brokerage_type` AS `brokerage_type`,
   `uc`.`brokerage_value` AS `brokerage_value`,
   `uc`.`brokerage_basic_value` AS `brokerage_basic_value`,
   `uc`.`descript` AS `descript`,
   `uc`.`create_time` AS `create_time`,
   `uc`.`update_time` AS `update_time`,
   `uc`.`create_user_id` AS `create_user_id`,
   uc.delete_status
   FROM (((`user_buy_commodity` `uc` 
    left join `user` `u` on((`u`.`id` = `uc`.`sales_user_id`))) 
    left join `commodity` `c` on((`c`.`id` = `uc`.`commodity_id`))) 
    left join `customer` `cc` on((`cc`.`id` = `uc`.`customer_id`))) ) SaleList";

    protected $listFields = array (
        'commodity_title' => array ('title' => '商品名称','frozen' => true,),
        'customer_name'   => array ('title' => '客户','frozen' => true,),
        'customer_id'     => array ('title' => '客户ID','hide' => 7,),
        'id'        => array ('title' => '操作','frozen'=>true,'pk' => true,'hide'=>0,
                                    'renderer'  => "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
                                            //var v = '<a href=\"javascript:editPriceSaleInfo(' + value + ',' + record.sales_price + ');\">再优惠</a>';
                                            var v   = '';
                      v += ' <a href=\"javascript:dataOpeDelete(' + value + ',\'取消销售会将本次消费额返还到客户账户，您确认要取消销售?<br \>注意：同一次销售的其他商品，本次操作将不处理！\');\">取消销售</a>';

                                            if(record.status=='待取消'){
                                                v   += ' <a href=\"javascript:cancelSaleInfo(' + value + ');\">撤销销售</a>';
                                            }
                                            if(record.status=='赊账'){
                                                v   += ' <a href=\"javascript:paySaleInfo(' + value + ');\">还账</a>';
                                            }
                                            return v;
                                        }",
                "width"=>150,
        ),
        'commodity_type'        => array ('title' => '商品类型',),
        'sales_price'           => array ('title' => '销售量',),
        //'finish_price'          => array ('title' => '余额',),
        'remaining_money'       => array ('title' => '余额',),
        'brokerage_user_name'   => array ('title' => '提成人',),
        'brokerage_value'       => array ('title' => '提成(元)',),
        'brokerage_type'        => array ('title' => '提成类型'),
        'brokerage_basic_value' => array ('title' => '提成基础值',),
        'create_time'           => array ('title' => '创建时间','type'=>'date','width'=>120,),
        'update_time'           => array ('title' => '更新时间','type'=>'date','width'=>120,),
        "status"                => array ('title' => '状态',),
        "descript"              => array ('title' => '描述',"width"=>2000,),
    );
    protected $modelInfo = array (
        'title' => '销售记录','readOnly'=>true,'order'=>'id desc',
        'searchHTML'=>'
            从<input onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\'})" type="text" class="dataOpeSearch z-input Wdate" name="egt_create_time" value="{$Think.request.egt_create_time}"/>
            到<input onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\'})" type="text" class="dataOpeSearch z-input Wdate" name="elt_create_time" value="{$Think.request.elt_create_time}"/>
            <input onclick="javascript:getStatForUBC(\'dataListSearch\')" type="button" class="btn" value="查询" id="item_query_items" />
            <script>
            function getStatForUBC(formId){
                dataOpeSearch(formId);
                $.get("__URL__/getStatForUBC",$("#" + formId).serialize(),function(data){
                    $("#grid-help-info").html(data);
                });
            }
                $.get("__URL__/getStatForUBC",function(data){
                    $("#grid-help-info").html(data);
                });
            </script>
        ',
        'helpInfo'=>'',
    );
  protected function _before_delete($options){
    if(empty($options["where"])){
      return false;
    }
    $d  = $this->where($options["where"])->select();
    foreach($d as $row){
      //返还消费到客户账号。
      switch($row["commodity_type"]){
        case "按价销售":
          $sql  = sprintf("UPDATE customer SET remaining_money=remaining_money+%d WHERE id=%d",$row["sales_price"],$row["customer_id"]);
          break;
        case "按次销售":
          $sql  = sprintf("UPDATE times_card SET remaining_times=remaining_times+%d WHERE customer_id=%d AND commodity_id=%d",$row["sales_price"],$row["customer_id"],$row["commodity_id"]);
          break;
        case "购买计次卡":
          $sql  = sprintf("UPDATE times_card SET remaining_times=remaining_times-%d WHERE customer_id=%d AND commodity_id=%d",$row["sales_price"],$row["customer_id"],$row["commodity_id"]);
          break;
        case "用户充值":
        case "充值赠送":
          $sql  = sprintf("UPDATE customer SET remaining_money=remaining_money-%d WHERE id=%d",$row["sales_price"],$row["customer_id"]);
          break;
      }
      $this->query($sql);
    }
  }
}

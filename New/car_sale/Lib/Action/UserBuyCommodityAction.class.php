<?php
class UserBuyCommodityAction extends DataOpeAction {
    function __construct() {
        parent::__construct();
        if(empty($_REQUEST["egt_create_time"]) && empty($_REQUEST["elt_create_time"])){
            $_REQUEST["egt_create_time"] = date("Y-m-d",time());
        }
    }

    function getStatForUBC(){
        $where = "1";
        $startTime = $_REQUEST["egt_create_time"];
        $endTime = $_REQUEST["elt_create_time"];
        if(!empty($startTime)){
            $startDate = date("Ymd",strtotime($startTime));
            $where = "date_format(create_time,'%Y%m%d')>=".$startDate;
        }
        if(!empty($endTime)){
            $endDate = date("Ymd",strtotime($endTime));
            if($where==="1")
                $where = "date_format(create_time,'%Y%m%d')<=".$endDate;
            else
                $where .= " AND date_format(create_time,'%Y%m%d')<=".$endDate;
        }
        $sql = "SELECT commodity_id,commodity_type,sum(sales_price) jiner FROM user_buy_commodity 
            WHERE $where GROUP BY commodity_id,commodity_type";
        $m = M();
        $statInfo = $m->query($sql);
        if(!$statInfo) echo "无数据";

        $commodity = D("Commodity")->getCacheDictTableData();

        $rv = "";
        foreach($statInfo as $sale){
            if($sale["commodity_type"]=="用户充值" || $sale["commodity_type"]=="充值赠送"){
                $rv .= sprintf("<span>%s:%s</span> ",$sale["commodity_type"],$sale["jiner"]);
            }else{
                $rv .= sprintf("<span>%s:%s</span> ",$commodity[$sale["commodity_id"]],$sale["jiner"]);
            }
        }
        echo $rv;
    }

}

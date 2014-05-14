<?php
class CommodityAction extends DataOpeAction {
    //将请求销售的商品进行处理，记录
    public function sale_selectitems(){
        $message    = "";
        //按价格消费的商品
//      print_r($_REQUEST);die();
        if(isset($_REQUEST["selectItem"]) && is_array($_REQUEST["selectItem"])){
            foreach($_REQUEST["selectItem"] as $itemId){
                $m      = D("UserBuyCommodity");
                $data   = array("commodity_id"=>$itemId,"customer_id"=>intval($_REQUEST["customer_id"]));
                if(C('TOKEN_ON')) $data[C('TOKEN_NAME')]    = $_REQUEST[C('TOKEN_NAME')];
                //有次数，则按照次数，每次数，则按照价格
                $data["commodity_type"] = '按价销售';
                $data["descript"]       = $_REQUEST["descirpt"];
                $data["finish_price"]   = $data["sales_price"]  = floatval($_REQUEST["money".$itemId]);
                switch($_REQUEST["sale_type"]){
                    case "oncredit":
                        $data["status"]         = "赊账";
                        break;
                    default:
                        $data["status"]         = "正常";
                        break;
                }
                
                //提成人员
                $data["sales_user_id"]          = intval($_REQUEST["sale_user".$itemId]);
                $data["brokerage_type"]         = $_REQUEST["brokerage_type".$itemId];
                $data["brokerage_basic_value"]  = floatval($_REQUEST["brokerage_basic_value".$itemId]);
                $data["brokerage_value"]        = $this->computeBrokerageValue($data["brokerage_type"],$data["brokerage_basic_value"],$data["finish_price"]);
                $totle_brokerage                += $data["brokerage_value"];


                $mCustomer  = D("customer");
                $theCustomer    = $mCustomer->find($_REQUEST["customer_id"]);
                $data['remaining_money']        = $theCustomer["remaining_money"]-$data["finish_price"];

                if($m->create($data)){
                    $m->add();
                
                    if(intval($_REQUEST["customer_id"])>0){
                        //减少会员卡内数值
                        $card   = D("Customer");
                        $card->save(array("id"=>intval($_REQUEST["customer_id"]),
                            "remaining_money"=>array("exp","remaining_money-".$data["finish_price"])));
                    }
                
                    $message    .= sprintf("%s%.2f元;提成%.2f元;<br \>",$_REQUEST["commodity_title".$itemId],$data["finish_price"],$data["brokerage_value"]);
                }else{
                    $message    .= $m->getError()."<br \>";
                }
            }
        }
        //消费的计次卡信息
        if(isset($_REQUEST["xiaoFeiTimes"])){
            //print_r($_REQUEST["xiaoFeiTimes"]);
            $card   = D("TimesCard");
            foreach($_REQUEST["xiaoFeiTimes"] as $key=>$val){
                if(intval($val)>0){
                    //记录消费信息
                    $m      = D("UserBuyCommodity");
                    $data   = array("commodity_id"=>$key,"customer_id"=>intval($_REQUEST["customer_id"]));
                    if(C('TOKEN_ON')) $data[C('TOKEN_NAME')]    = $_REQUEST[C('TOKEN_NAME')];       //表单令牌
                    $data["commodity_type"] = '按次销售';
                    $data["finish_price"]   = $data["sales_price"]  = $val; //消费次数
                    if($m->create($data)){              
                        $m->add();
                    
                        //计次卡减去对应次数
                        $card->where(array("customer_id"=>intval($_REQUEST["customer_id"]),"commodity_id"=>$key))->save(array("remaining_times"=>array("exp","remaining_times-".$val)));
                        $message    .= sprintf("%s计次消费%d次;<br \>",$_REQUEST["xiaoFeiItems"][$key],$val);
                    }else{
                        $message    .= $m->getError()."<br \>";
                    }
                }
            }
        }
        printf("销售成功!<br \>%s",$message);
    }
    //计算提成
    public function computeBrokerageValue($brokerage_type,$brokerage_base_value,$price){
        switch($brokerage_type){
            case "比例提成":    //比例提成
                return $brokerage_base_value*$price;
                break;
            case "固定提成":    //固定提成
                return $brokerage_base_value;
                break;
            default:
                return 0;
                break;
        }
    }
    //获取可销售的商品列表
    // FIND_IN_SET 在 TP2.1时代，可以使用  $where["FIND_IN_SET('按次销售',commodity_type)"] = array("gt","0");  格式生成SQL，
    // 但是在3.0时代，因为安全性检查，无法这样用了。 where的key不能包含 ' 和 中文
    // 目前直接组合成Where字符串条件，不再使用TP进行组合。
    public function sale_itemlist(){
        $where  = "status in ('正常','保留项目')";
        if(isset($_REQUEST["type"]) && $_REQUEST["type"]=="topup"){
            $where  .= " AND FIND_IN_SET('按次销售',commodity_type)";
        }
        $itemList   = $this->model->where($where)->select();
        $this->assign("itemList",$itemList);
        $this->display();
    }
}

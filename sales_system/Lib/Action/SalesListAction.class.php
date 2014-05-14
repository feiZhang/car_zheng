<?php
class SalesListAction extends DataOpeAction {
	public function cancel_sale(){
		if(isset($_REQUEST["sale"]) && intval($_REQUEST["sale"])>0){
			$sale_id	= intval($_REQUEST["sale"]);
			$saleInfo	= $this->model->find($sale_id);
			//更新数据为取消
			if($this->model->where(array("id"=>$sale_id))->save(array("stauts"=>"取消"))){
				$msg	= "";
				//更新用户的卡余额
				switch($saleInfo["commodity_type"]){
					case '按价销售':
						if(intval($saleInfo["customer_id"])>0){
							//退款到用户账号
							$customer	= D("Customer");
							$customer->where(array("id"=>intval($saleInfo["customer_id"])))->save(array("remaining_money"=>array("exp","remaining_money+".$saleInfo["finish_price"])));
							$msg		= "退款给用户购买".$saleInfo["title"]."的".$saleInfo["finish_price"]."元!";
						}
						break;
					case '按次销售':
						if(intval($saleInfo["customer_id"])>0){
							//退款到用户计次卡
							$tc	= D("TimesCard");
							$tc->where(array("id"=>intval($saleInfo["customer_id"])))->save(array("remaining_times"=>array("exp","remaining_times+".$saleInfo["finish_price"])));
							$msg	= "退还用户".$saleInfo["title"].$saleInfo["finish_price"]."次!";
						}
						break;
					case '购买计次卡':
						if(intval($saleInfo["customer_id"])>0){
							//取消购买计次卡
							$tc	= D("TimesCard");
							$tc->where(array("id"=>intval($saleInfo["customer_id"])))->save(array("remaining_times"=>array("exp","remaining_times-".$saleInfo["finish_price"])));
							$msg	= "取消用户购买".$saleInfo["title"].$saleInfo["finish_price"]."次!";
						}
						break;
					case "用户充值":
						if(intval($saleInfo["customer_id"])>0){
							//取消充值信息
							$customer	= D("Customer");
							$customer->where(array("id"=>intval($saleInfo["customer_id"])))->save(array("remaining_money"=>array("exp","remaining_money-".$saleInfo["finish_price"])));
							$msg	= "取消用户充值".$saleInfo["finish_price"]."元!";
						}
						break;
				}
				$this->ajaxReturn(0,"商品撤销成功!".$msg,1);
			}else{
				$this->ajaxReturn(0,"无效的销售信息!",0);
			}
		}else{
			$this->ajaxReturn(0,"无效的销售信息操作!",0);
		}
	}
	//标记要取消销售信息
	public function ready_cancel_sale(){
		if(isset($_REQUEST["sale"]) && intval($_REQUEST["sale"])>0){
			$m	= D("UserBuyCommodity");
			if($m->where(array("id"=>intval($_REQUEST["sale"])))->save(array("status"=>"待取消")))
				$this->ajaxReturn(0,"标记撤销操作成功!",1);
			else $this->ajaxReturn(0,"标记撤销操作失败!",0);
		}else $this->ajaxReturn(0,"无效的销售信息操作!",0);
	}
}
<?php
class CardAction extends DataOpeAction {
	//开新卡，卡售卖
	public function sale_card(){
		$this->assign("card_no",$_REQUEST["card_no"]);
		$this->display();
	}
	public function save_salecard(){
		$_REQUEST["passwd"]	= $_POST["passwd"]	= md5(trim($_REQUEST["passwd"]));
		$m			= D("Customer");
		$findCard	= $m->where(array("card_no"=>$_REQUEST["card_no"]))->find();
		if($findCard){
			$this->ajaxReturn(0,"卡号已存在!",0);
			return;
		}
		if($m->create() && $m->add()){
			$this->ajaxReturn($_REQUEST["card_no"],"售卡成功!",1);
		}else{
			$this->ajaxReturn(0,"创建用户不成功!!",1);
		}
	}
	
	//保存充值信息
	public function save_topup(){
		$topupItems	= "";
		if(isset($_REQUEST["selectItem"]) && is_array($_REQUEST["selectItem"])){
			foreach($_REQUEST["selectItem"] as $itemId){
				$topupTimes			= intval($_REQUEST["times".$itemId]);
				$UserBuyCommodity	= D("UserBuyCommodity");
				$data	= array("customer_id"=>intval($_REQUEST["customer_id"]),"commodity_id"=>$itemId,'commodity_type'=>'购买计次卡',
									"sales_price"=>$topupTimes,"finish_price"=>$topupTimes);
				if(C('TOKEN_ON')) $data[C('TOKEN_NAME')]	= $_REQUEST[C('TOKEN_NAME')];		//表单令牌
				$data["descript"]		= $_REQUEST["descirpt"];
				if($UserBuyCommodity->create($data)){
					if($UserBuyCommodity->add()){
						$m		= D("TimesCard");
						//已有此商品的计次卡，则追加次数，没有则新增计次卡信息
						$data		= array();
						$data["count_times"]		= array("exp","count_times+".$topupTimes);
						$data["remaining_times"]	= array("exp","remaining_times+".$topupTimes);
						if(!$m->where(array("customer_id"=>intval($_REQUEST["customer_id"]),"commodity_id"=>$itemId))->save($data)){
							$m->add(array("customer_id"=>intval($_REQUEST["customer_id"]),"commodity_id"=>$itemId,
									"count_times"=>$topupTimes,"remaining_times"=>$topupTimes));
						}
						
						$topupItems	.= $_REQUEST["commodity_title".$itemId].$topupTimes."次<br \>";
					}
				}
			}
		}
		if(isset($_REQUEST["topupMoney"])){
			$topupMoney	= floatval($_REQUEST["topupMoney"]);
			$mCustomer	= D("customer");
			$mTopup		= D("UserBuyCommodity");
			$theCustomer	= $mCustomer->find($_REQUEST["customer_id"]);
			//记录每次充值的信息
			$mTopup->add(array("customer_id"=>intval($_REQUEST["customer_id"]),'sales_price'=>$topupMoney,'finish_price'=>$topupMoney+$theCustomer["remaining_money"],'commodity_type'=>'用户充值','descript'=>$_REQUEST["descript"]."[上次结余:".$theCustomer["remaining_money"].";充值后结余:".($topupMoney+$theCustomer["remaining_money"])."]"));
			//更新总计
			$mCustomer->save(array("id"=>intval($_REQUEST["customer_id"]),
					"remaining_money"=>array("exp","remaining_money+".$topupMoney),
					"total_money"=>array("exp","total_money+".$topupMoney),
					));
		}
		if(isset($_REQUEST["giftMoney"])){
			$giftMoney	= floatval($_REQUEST["giftMoney"]);
			$mCustomer	= D("customer");
			$mTopup		= D("UserBuyCommodity");
			$theCustomer	= $mCustomer->find($_REQUEST["customer_id"]);
			//记录每次充值的信息
			$mTopup->add(array("customer_id"=>intval($_REQUEST["customer_id"]),'sales_price'=>$giftMoney,'finish_price'=>$giftMoney+$theCustomer["remaining_money"],'commodity_type'=>'充值赠送','descript'=>$_REQUEST["descript"]."[上次结余:".$theCustomer["remaining_money"].";充值后结余:".($topupMoney+$theCustomer["remaining_money"])."]"));
			//更新总计
			$mCustomer->save(array("id"=>intval($_REQUEST["customer_id"]),
					"remaining_money"=>array("exp","remaining_money+".$giftMoney),
					"total_money"=>array("exp","total_money+".$giftMoney),
			));
		}
		
		printf("充值成功,%.2f元.赠送%.2f元<br \>%s",$topupMoney,$giftMoney,$topupItems);
	}
	
	//用卡消费商品、给卡充值等，都需要先获取到卡数据
	public function card_select(){
		if(isset($_REQUEST["card_no"])){
			//先查询输入的数据，对应几个客户，然后列出客户，供前台选择一个使用的客户帐号
			$customer	= D("FulltextSearch");
			$custInfo	= $customer->where(array("content"=>array("like","%".$_REQUEST["card_no"]."%"),"object"=>"Customer"))->select();
			if(sizeof($custInfo)>0){
				$this->assign("customerList",$custInfo);
			}
		}else{
			$this->assign("cardInfo","无卡消费!");
		}
		$this->display();
	}
	//获得card的具体信息	
	public function card_info(){
		if(isset($_REQUEST["i"])){
			$customer	= D("Customer");
			$custInfo	= $customer->find(intval($_REQUEST["i"]));
			if($custInfo["status"]=="禁用"){
				$this->ajaxReturn("此卡已被禁用,要使用此卡,请<a href='javascript:\$.dialog.get(\"select_card_info\").close();dataOpeEdit(".intval($_REQUEST["i"]).",\"Customer\")'>修改</a>此卡状态为正常!","",false);
			}else if($custInfo && ($custInfo["passwd"]==md5($_REQUEST["pass"]) || empty($custInfo["passwd"]))){
				$this->assign("customer",$custInfo);
				//计次卡信息
				$timesCardList	= $customer->query("SELECT tc.commodity_id,c.title,tc.count_times,tc.remaining_times FROM times_card tc LEFT JOIN commodity c ON c.id=tc.commodity_id WHERE tc.customer_id=".intval($custInfo["id"]));
				$this->assign("timesCardList",$timesCardList);
				//寄存物品信息
				$csc	= D("CustomerSavedCommodity");
				$customerSavedCommodity	= $csc->where(array("customer_id"=>$_REQUEST["i"],"status"=>array("egt",0)))->order("commodity_id ASC")->select();
				$this->assign("customerSavedCommodity",$customerSavedCommodity);
// 				print_r($customerSavedCommodity);
				
				$tHtml	= $this->fetch();
// 				die($tHtml);
				$this->ajaxReturn($tHtml,"",true);
			}else{
				//dump($customer->getLastSQL());dump($custInfo);dump($_REQUEST);
				$this->ajaxReturn("卡密码不正确!,请重新操作或用现金消费!","",false);
			}
		}else{
			$this->ajaxReturn("未输入卡信息!","",false);
		}
	}
}
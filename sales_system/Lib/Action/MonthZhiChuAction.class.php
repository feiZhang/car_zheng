<?php
class MonthZhiChuAction extends DataOpeAction {
    function index() {
      if(isset($_REQUEST["month"])) $month = $_REQUEST["month"]."-1";
      else $month = date("Y-m-1");
      $dic  = D("SysDic");
      $dicType  = $dic->getCacheDictTableData();
      $this->assign("monthType",$dicType["month_zhichu"]);
      $this->assign("monthZhiChu",D("MonthZhiChu")->where(array("month"=>$month))->getField("type_id,money"));
      $this->display();
    }
}

?>

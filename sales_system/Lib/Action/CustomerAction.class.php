<?php
class CustomerAction extends DataOpeAction {
	public function add(){
		if(empty($_REQUEST["id"])){
			$this->error("请勿非法操作!");
		}
		parent::add();
	}
}
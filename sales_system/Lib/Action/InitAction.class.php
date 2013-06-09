<?php
class InitAction extends DxExtCommonAction{
//class HomeAction extends Action{
	public function index(){
		$m	= D("User");
		$m->fulltext_init();
		$m	= D("Customer");
		$m->fulltext_init();
	}
}
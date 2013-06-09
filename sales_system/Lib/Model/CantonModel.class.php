<?php
class CantonModel extends DxExtCommonModel{
	protected $listFields = array (
	);
	
    protected $modelInfo=array(
    	"title"=>'行政区域','readOnly'=>true,
    	"dictTable"=>array("fdn","text_name"),
    );
}
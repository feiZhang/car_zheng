<?php
class CommoditySortModel extends DxExtCommonModel{
	protected $listFields = array (
		array (
				'name' => 'id',
				'title' => '标识',
				'pk' => true,
				'hide' => true,
		),
	);
	protected $modelInfo = array (
		'title' => '商品类别','dictTable' => 'title',
	);
}
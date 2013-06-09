<?php
class JobTypeModel extends DxExtCommonModel{
	protected $listFields = array (
		array (
				'name' => 'id',
				'title' => '标识',
				'pk' => true,
				'hide' => true,
		),
	);
	protected $modelInfo = array (
		'title' => '工种','dictTable' => 'name',
	);
}
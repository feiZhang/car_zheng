<?php
class AccountModel extends DxExtCommonModel {
	protected $listFields = array (
			array("name"=>"id","pk"=>true,'title'=>'操作',"hide"=>6,
					'renderer'	=> "var valChange=function valChangeCCCC(value ,record,columnObj,grid,colNo,rowNo){
										var v	= '<a href=\"javascript:dataOpeEdit(' + value + ');\">修改</a>';
										v	+= ' <a href=\"javascript:dataOpeDelete(' + value + ');\">删除</a>';
										return v;
									}","width"=>"120",
					//v	+= ' <a href=\"javascript:findThePass(' + value + ');\">重置密码</a>';
			),
			array (
					'name' => 'login_username','title'=>'登录名',"width"=>"130",'frozen'=> true
			),
			array (
					'name'=> 'name','title'=>'真实姓名','frozen'=> true,"width"=>"80"
			),
			array (
					'name'=> 'canton_fdn','title'=>'所属单位',"valChange"=>array("model"=>"Canton"),"width"=>"160",
					'editor'=>'<div id="selectCanton"></div><input type="hidden" id="canton_fdn" name="canton_fdn" value="" /><input type="hidden" id="canton_id" name="canton_id" value="" />
					    		<script type="text/javascript">
					    		(function($){
						    		$.get(APP_URL + "/Canton/getSelectSelectSelect",function(data){
						    			$.selectselectselect(data,"selectCanton",0,3520,function(t){
					    					$("#canton_fdn").val($(t).val());
											$("#canton_id").val($(t).find("option:selected").attr("key"));
										});
									},"json");
					    		})(jQuery);
					    		</script>
							',
			),
			array (
					'name'=> 'tel','title'=>'电话',
			),
			array (
					'name'=>'address','title'=>'地址',"width"=>"400",
			),
			array (
					'name'=> 'create_time','title'=> '创建时间',"width"=>"120","hide"=>6
			),
			array (
					'name'=> 'update_time','title'=>'更新时间',"width"=>"120","hide"=>6
			),
	);
	
	protected $modelInfo=array(
			"title"=>'系统账号','readOnly'=>false,
			'searchHTML'=>"
    		登录名:<input id='login_username' size='10' class='dataOpeSearch' value='' />
    		真实姓名:<input id='name' size='10' class='dataOpeSearch' value='' />
    		<input onclick='javascript:dataOpeSearch(true);' type='button' class='d-button d-state-highlight' value='查询' id='item_query_items' />
    		<input onclick='javascript:dataOpeSearch(false);' type='button' class='d-button d-state-highlight' value='全部数据' id='item_query_all' />",
	);
}
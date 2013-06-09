/* 
 * 多级联动，数据选择组件。
 * 比如：区域选取
 */
(function($){
    /**
     * 使用方式 
     $(function(){
        $.selectselectselect(list, 'cantonDis', '1673', '3520',function(t){alert(t);});
     }); 
     */
    /**
     * data {[parent_id:1,id:1,title:"郑州",val:""],[parent_id:1,id:1,title:"郑州",val:""]} json格式的数据信息..
     * containerDomId 		显示被加到目标dom元素的id
     * defaultKey 			默认选择的数据 可以是数组
     * rootKey 				树的根ID
     * selectEven			选择数据后的回调函数
     * completeEven			select构建完成后的回调函数
     */
    $.selectselectselect = function (data,containerDomId,defaultKey,rootKey,selectEven,completeEven) {
        if(typeof this !== 'object'){
        	//强制进行new操作
            return new $.selectselectselect(data,containerDomId,defaultKey,rootKey,selectEven,completeEven);
        }

        var containerDom 	= $("#" + containerDomId);
        var _this 			= this;
        var tree 			= new Array();
        //将data数据重组为多个数组数据
        _this.initData = function(data,rootKey) {
            var cantonLength = data.length;
            var rootData	 = new Array();
            for (i=0;i<cantonLength; i++) {
            	if (rootKey!=undefined && data[i].id==rootKey) rootData.push(data[i]);
                if (undefined == tree[data[i].parent_id]) {
                    tree[data[i].parent_id] = new Array();
                }
                tree[data[i].parent_id].push(data[i]);
            }
            
            $("select[type='canton']", containerDom).live('change', function(){
                $(this).parent("div").nextAll("div.cantonDiv").remove();
                _this.select($(this).find("option:selected").attr("key"));
                if(selectEven!=0 && selectEven!=undefined){
                	//先选“xx街道办”，在选同一select的“请选择”，则获取的值为空，而实际已经选择了“金水区”，则应将“金水区的select框传递出去”
                	if($(this).val()=="" && $(this).parent("div").prev("div").length>0) valSelect = $(this).parent("div").prev("div").children("select");
                	else valSelect	= this;
                	selectEven(valSelect);
                }
            });
            
            if(rootKey!=undefined){
            	_this.createSelect(rootData);
            	_this.setDefaultSelect(rootKey);
            }
        }
        //选取某个数据后，触发，生成下级选取列表
        _this.select = function(key) {
            var data = tree[key];
            _this.createSelect(data);
        }
        //根据数组生成select下拉选项
        _this.createSelect	= function(data){
            if (undefined != data) {
                var dataLength = data.length;
                var strHtml = "<div class=\"cantonDiv\" style=\"display:inline\"><select name='aCanton' type='canton'>";
                strHtml += "<option value=\"\">请选择</option>";
                for(i=0;i<dataLength; i++) {
                    if (undefined != data[i]) {
                    	if (undefined != data[i].val) {
                    		strHtml += "<option key=\"" + data[i].id + "\" value=\""+ data[i].val +"\">" + data[i].title + "</option>";
                    	}else{
                    		strHtml += "<option key=\"" + data[i].id + "\" value=\""+ data[i].id +"\">" + data[i].title + "</option>";
                    	}
                    }
                }
                strHtml += "</select></div>";
                $(strHtml).appendTo(containerDom);
            }
        }
        //设置下拉框的默认值
        _this.setDefaultSelect = function(defaultKey) {
            if ("" != defaultKey) {
                if (defaultKey.constructor == Array) {
                    $.each(defaultKey, function(i, item){
                        $("#"+containerDomId+" select[type='canton']").each(function(j){
                            var selectItem = $(this).find("option[key='"+item+"']");
                            if (null != selectItem.get(0)) {
                                selectItem.attr("selected", true);
                                $(this).change();
                            }
                        });                                                
                    });
                } else {
                    if(defaultKey!=0 && $("select[type='canton']").length>0){
                        $("#"+containerDomId+" select[type='canton']").each(function(i){
                            $(this).find("option[key='"+defaultKey+"']").attr("selected", true);
                            $(this).change();
                        });
                    }   
                }
            }        
        }
        
        _this.initData(data,rootKey);
        //对第一个下拉列表选择默认数据。
        if(defaultKey!=undefined) _this.setDefaultSelect(defaultKey);
        if(completeEven!=undefined){
        	completeEven();
        }
        return _this;
    }
})(jQuery);
function showDialog(strTitle, strContent) {
	$.dialog({
		title : strTitle,
		content : strContent,
		esc : true,
		time : 3000,
		lock : true
	});
}

function cantonIdToName(id) {
	return cantonDataNoTree[id].title;
}

/**
 * 异步操作提示提示函数 
 * options = {
 * url:"",//string 
 * beforemsg:"",//string
 * ingmsg:"",//string 
  * data:"",//object 参数
 * afterfunction:"",//function name }
 */
function showDealDialog() {
	var args = arguments;
	var default_options = {
			url:"",//string 
			beforemsg:'',//string
			ingmsg:'正在处理，请稍后!<img src="' + PUBLIC_URL + '/public/loading.gif"/>',//string 
			data:"",//object 参数
			afterfunction:""
	};
	if (typeof args[0] == 'object') {
		var msg_options = $.extend(default_options, args[0]);
	} else {
		var msg_options = $.extend(default_options, {
			url : args[0],
			beforemsg : args[1],
			ingmsg:(typeof args[3] =="string" && arg[3]!="")?args[3]:default_options.ingmsg,
			data:(typeof args[4] == 'object')?args[4]:default_options.data
		});
	}
	var dialog_options = {
	        id:"showDataOpeItem",
	        title:"提醒",
	        lock:true,
	        content:msg_options.beforemsg,
	        ok:function(){
	            var theThis   	= this;
	            theThis.content(msg_options.ingmsg);
	            theThis.button();
	            $.post(msg_options.url,msg_options.data,function(data){
	            	if(typeof afterfunction=="function"){
	            		msg_options.afterfunction(data);
	            	}else{
	            		if(typeof default_options.aftermsg == "string"&& default_options.aftermsg!=""){
	            			theThis.content(default_options.aftermsg);
	            		}else{
	            			theThis.content(data.info);
	            		}
	            		theThis.time(3000);
	                    theThis.button({
	                       id: 'ok',
	                       disabled: true
	                    },{
	                       id:'cancel',
	                       value:'关闭'
	                    });
                         if(Sigma.GridCache["theDataOpeGrid"]){
                        	Sigma.GridCache["theDataOpeGrid"].reload();
                         }
	            	}
	            },"json");
	            return false;
	        },
	        okValue:"确定",
	        cancel:function(){},
	        cancelValue:"取消"
	    }
	$.dialog(dialog_options);
}
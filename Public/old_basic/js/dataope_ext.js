/**
 * DataOpe的扩展js操作，比如：删除、修改、状态改变等等。
 * */
function dataOpeAdd(model){
    var	dataUrl		= URL_URL;
    if(model!=undefined){
    	dataUrl	= APP_URL + "/" + model;
    }
    $.dialog({
        id:"addObject",
        title:"新增",
        content:'正在加载页面!<img src="' + PUBLIC_URL + '/public/loading.gif" />',
        esc:true,
        lock:true,
        ok:function(){
            if($("#itemAddForm").length<1) return true;
            if(!$('#itemAddForm').validationEngine('validate')){
                return false;
            }
            
            var theThis		= this;
            $.ajax({
                type : "POST",
                url : dataUrl + "/save",
                data : $("#itemAddForm").serialize(),
                success : function(msg) {
                    if (msg["status"] == 0) {
                        showDialog("提示",msg["info"]);
                    } else {
                        theThis.content(msg['info']);
                        theThis.time(2000);
                        theThis.button({
                            id: 'ok',
                            disabled: true
                        },{
                            id:'cancel',
                            value:'关闭'
                        });
                        Sigma.GridCache["theDataOpeGrid"].reload();
                    }
                },
                dataType : "json"
            });
            return false;
        },
        okValue:"确定",
        cancelValue:"取消",
        cancel:function(){},
        initialize:function(){
            var theThis   	= this;
            $.get(dataUrl + "/add",function(html){
                theThis.content(html);
                $(theThis.dom.main).contents().find(":input:visible:eq(0)").focus();
            });
        }
    });
}

function dataOpeEdit(id,model){
    var	dataUrl		= URL_URL;
    if(model!=undefined){
    	dataUrl	= APP_URL + "/" + model;
    }
    $.dialog({
        id:"editObject",
        title:"修改",
        content:'正在加载页面!<img src="' + PUBLIC_URL + '/public/loading.gif" />',
        esc:true,
        lock:true,
        ok:function(){
            if($("#itemAddForm").length<1) return true;
            if(!$('#itemAddForm').validationEngine('validate')){
                return false;
            }
            var theThis		= this;
            $.ajax({
                type : "POST",
                url : dataUrl + "/save",
                data : $("#itemAddForm").serialize(),
                success : function(msg) {
                    if (msg["status"] == 0) {
                        showDialog("提示",msg["info"]);
                    } else {
                        if(window.Sigma && Sigma.GridCache["theDataOpeGrid"]!=undefined) Sigma.GridCache["theDataOpeGrid"].reload();
                        theThis.content(msg['info']).time(2000).button({
                            id: 'ok',
                            disabled: true
                        },{
                            id:'cancel',
                            value:'关闭'
                        });
                    }
                },
                dataType : "json"
            });
            return false;
        },
        okValue:"保存",
        cancelValue:"取消",
        cancel:function(){},
        initialize:function(){
            var theThis   	= this;
            $.get(dataUrl + "/edit/" + id,function(html){
                theThis.content(html);
            });
        }
    });
}
function dataOpeDelete(id,model){
    var	dataUrl		= URL_URL;
    if(model!=undefined){
    	dataUrl	= APP_URL + "/" + model;
    }
    $.dialog({
        id:"deleteDataOpeItem",
        title:"提醒",
        lock:true,
        content:"确定要删除此数据?",
        ok:function(){
            _this	= this;
            $.get(dataUrl+"/delete/"+id,function(data){
                if(data.status){
                    Sigma.GridCache["theDataOpeGrid"].reload();
                }
                _this.time(2000).title("提示").content(data.info).button({
                    id: 'ok',
                    disabled: true
                },{
                    id:'cancel',
                    value:'关闭'
                });
            },"json");
            return false;
        },
        okValue:"确定",
        cancel:function(){},
        cancelValue:"取消"
    });
}

var	gridBaseUrl	= "";
function dataOpeSearch(noAllData){
    var para	= new Object();
    var grid	= Sigma.GridCache["theDataOpeGrid"];
    if(gridBaseUrl=="") gridBaseUrl	= grid.loadURL;
    var loadUrl	= gridBaseUrl;
    if(noAllData){
        $("input.dataOpeSearch").each(function(){
            if($(this).val()=="") return;
            if($(this).attr("type")=="radio"){
                if($(this).attr("checked")=="checked"){
                    para[$(this).attr("id")]	= $(this).val();
                }
            }else{
                var tPara	= $(this).val();
                if($(this).hasClass("likeLeft")) tPara	= "%" + tPara;
                if($(this).hasClass("likeRight")) tPara	= tPara + "%";
                para[$(this).attr("id")]	= tPara;
            //if(console) console.log(tPara);
            }
        });
        para = jQuery.param(para);
        //if(console) console.log(para);
        if(loadUrl.indexOf('?')>-1){
            loadUrl	+= "&"+para;
        }else{
            loadUrl	+= "?"+para;
        }
    }
    grid.loadURL		= loadUrl;
    grid.cleanParameters();
    grid.setPageInfo({pageNum:1});
    grid.reload();
}
function dataOpeExport(noAllData){
    var para	= new Object();
    para["export"]='xls';
    var grid	= Sigma.GridCache["theDataOpeGrid"];
    if(gridBaseUrl=="") gridBaseUrl	= grid.loadURL;
    var loadUrl	= gridBaseUrl;
    if(noAllData){
        $("input.dataOpeSearch").each(function(){
            if($(this).val()=="") return;
            if($(this).attr("type")=="radio"){
                if($(this).attr("checked")=="checked"){
                    para[$(this).attr("id")]	= $(this).val();
                }
            }else{
                var tPara	= $(this).val();
                if($(this).hasClass("likeLeft")) tPara	= "%" + tPara;
                if($(this).hasClass("likeRight")) tPara	= tPara + "%";
                para[$(this).attr("id")]	= tPara;
            //if(console) console.log(tPara);
            }
        });
    }
    para = jQuery.param(para);
    //if(console) console.log(para);
    if(loadUrl.indexOf('?')>-1){
        loadUrl	+= "&"+para;
    }else{
        loadUrl	+= "?"+para;
    }
    var ifa=$("<iframe src='about:blank'></iframe>").hide().appendTo(document.body).attr('src', loadUrl);
    setTimeout(function(){
        ifa.remove();
    }, 180000);
}
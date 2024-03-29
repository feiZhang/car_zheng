function getLodop(oOBJECT,oEMBED){
/**************************
  本函数根据浏览器类型决定采用哪个对象作为控件实例：
  IE系列、IE内核系列的浏览器采用oOBJECT，
  其它浏览器(Firefox系列、Chrome系列、Opera系列、Safari系列等)采用oEMBED,
  对于64位浏览器指向64位的安装程序install_lodop64.exe。
**************************/
    
        var strHtmInstall="<font color='#FF00FF'>打印控件未安装!点击这里<a href='"+ DX_PUBLIC + "/public/Lodop6.010/install_lodop32.exe'>执行安装</a>,安装后请刷新页面或重新进入。</font>";
        var strHtmUpdate="<font color='#FF00FF'>打印控件需要升级!点击这里<a href='"+ DX_PUBLIC + "/public/Lodop6.010/install_lodop32.exe'>执行升级</a>,升级后请重新进入。</font>";
        var strHtm64_Install="<font color='#FF00FF'>打印控件未安装!点击这里<a href='"+ DX_PUBLIC + "/public/Lodop6.010/install_lodop64.exe'>执行安装</a>,安装后请刷新页面或重新进入。</font>";
        var strHtm64_Update="<font color='#FF00FF'>打印控件需要升级!点击这里<a href='"+ DX_PUBLIC + "/public/Lodop6.010/install_lodop64.exe'>执行升级</a>,升级后请重新进入。</font>";
        var strHtmFireFox="<br><br><font color='#FF00FF'>注意：<br>1：如曾安装过Lodop旧版附件npActiveXPLugin,请在【工具】->【附加组件】->【扩展】中先卸它。</font>";
        var LODOP=oEMBED;
        var msg='';
    try{    
         var isIE    =  (navigator.userAgent.indexOf('MSIE')>=0) || (navigator.userAgent.indexOf('Trident')>=0);
         var is64IE  = isIE && (navigator.userAgent.indexOf('x64')>=0);
         if (isIE) LODOP=oOBJECT;
         if ((LODOP==null)||(typeof(LODOP.VERSION)=="undefined")) {
             if (is64IE) {msg = strHtm64_Install;} else      
             if (isIE)   {msg = strHtmInstall;   } else 
                             {msg = strHtmInstall;};
             if (navigator.userAgent.indexOf('Firefox')>=0)
                    {msg += strHtmFireFox;};
         } else 
         if (LODOP.VERSION<"6.0.1.0") {
            if (is64IE){msg = strHtm64_Update;} else
            if (isIE)  {msg = strHtmUpdate; } else
                       {msg = strHtmUpdate; };
         }
         //=====如下空白位置适合调用统一功能:=====         
        if (msg != "") {
            $.dialog({
                title : '提示',
                content : msg,
                esc : true,
                lock : true
            });
        }
        //showDialog('提示',msg);
        if (msg == "") {
            LODOP.SET_LICENSES("郑州大象通信信息技术有限公司", "864567677838688778794958093190",
                "", "");
            return LODOP;
        }else return false;

         //=======================================
    }catch(err){
        if (is64IE) 
        msg = "Error:"+strHtm64_Install;else
        msg = "Error:"+strHtmInstall;
        $.dialog({title:'提示',content:msg,esc:true,lock:true});
         return LODOP; 
    }
}


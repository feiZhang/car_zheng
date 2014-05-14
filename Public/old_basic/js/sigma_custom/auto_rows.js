/**@description
 *Sigmagrid 显示记录的条数随grid的大小变化
 *@inherit Sigma
 *@namespace Sigma
 *@author: zhangyud
 **/
var AutoRows={
    "_autoRowhdr":false,
    /**激活自动调整grid显示记录条数功能.*/
    autoRows:function(){
        //防止重复初始化.
        if(this._autoRowhdr){
            return;
        }
        var grid=this;
        //需运行调整标志
        var run_auto=false;
        //任务结束标志
        var fin_auto=false;
        //定时标记
        var timeoutid=false;
        var lpagesize=-1;
        var lpagenum=-1;
        //设置高度调整方法
        this._autoRowhdr=function(force){
            //防止进入递归调用
            {
                //another thread finish this task
                if(fin_auto){
                    return;
                }
                fin_auto=true;
            }
            //页码转换
            if(!force){
                var pinfo=grid.getPageInfo();
                if(pinfo.pageSize == lpagesize){
                    return;
                }
            }
            //topContainer元素的的高度
            grid.id+"_bodyDiv";
            //当前内容所在容器的高度
            var max=Sigma.U.getHeight(Sigma.$(grid.id+"_bodyDiv"), true);
            //当前内容
            var inner=Sigma.U.firstChildElement(Sigma.$(grid.id+"_bodyDiv"));
            //当前内容显示的高度
            var content_height=Sigma.U.getHeight(inner);
            //计算当前grid显示的行数
            var content_rows=grid.getRows().length;
            var new_rows=content_rows;
            //计算每行数据的高度,默认使用20
            var each_row_height=20;
            if(content_rows>0){
                each_row_height=Sigma.U.getHeight(grid.getRows()[0])
            }
            //计算空隙
            var gap=each_row_height*2;
            //计算新的行数.有此情况下不重新计算高度.空隙太大或太小时才计算.
            if(content_height<max-gap){
                new_rows=(max-gap)/each_row_height;
            }else if(content_height > max){
                new_rows=(max-gap)/each_row_height;
            }else{
                return;
            }
            
            new_rows=Math.floor(new_rows);
            //设置出错时的行数.
            if(new_rows<=0){
                new_rows=5;
            }
            //设置每页行数
            grid.setPageInfo({
                "pageSize":new_rows
            });
            lpagesize=new_rows;
            //新页码计算.
            {
                var oinfo=grid.getPageInfo();
                var np=Math.ceil(oinfo.startRowNum/oinfo.pageSize);
                if(np==0){
                    np=1;
                }
                grid.gotoPage(np);
            }
            try{
                grid.bodyDiv.focus();
            }catch(e){}
        };
        //此方法主要功能是当同时发生多个事件,需要调整行数时,只执行一次就可以完成调整动做.
        var task=function(force){
            //防止进入递归调用
            do{
                if(!run_auto){
                    run_auto=true;
                    fin_auto=false;
                    //设置是否强制执行调整
                    var func=function(){
                        var force=(force==false)?force:true;
                        grid._autoRowhdr(force);
                    };
                    timeoutid=setTimeout(func, 500);
                }else{
                    if(fin_auto){
                        fin_auto=false;
                        run_auto=false;
                    }else{
                        if(timeoutid){
                            clearTimeout(timeoutid)
                            timeoutid=false;
                            run_auto=false;
                            continue;
                        }
                    }
                }
                break;
            }while(true);
        };
        //窗口大小改变时调整grid
        Sigma.U.addEvent(window, 'resize', task);
        //sigma grid oncomplete have some thing wrong
        //grid 载入完成时调整grid
        var _hdr=grid.onComplete;
        grid.onComplete=function(){
            try{
                _hdr();
            }catch(e){
                
            }
            task(false);
        };
        
        //设置清除操作
        var _dst_hdr=grid.beforeDestroy;
        grid.beforeDestroy=function(){
            try{
                _dst_hdr();
            }catch(e){
                
            }
            delete _dst_hdr;
            {
                delete _hdr;
                Sigma.U.removeEvent(window, 'resize', task);
                delete this._autoRowhdr;
            }
        };
    }
}

Sigma.$extend(Sigma.GridDefault, AutoRows);
    delete AutoRows;

Sigma.$extend( Sigma.Grid, {
    'autoRows':function(grid, container, exclude){
        grid=Sigma.$grid(grid);
        return function(){
            grid.autoRows(container, exclude);
        };
    }
});
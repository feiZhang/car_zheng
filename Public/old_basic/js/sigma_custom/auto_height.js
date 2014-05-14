/**@description
 *Sigmagrid 高度自调整扩展
 *@inherit Sigma
 *@namespace Sigma
 *@author: zhangyud
 **/
var AutoHeight={
    /**@description {Config} enableAutoHeight 
     *{Boolean} enable auto height, default false */
    'enableAutoHeight':false,
    
    /**@description {Config} topContainer 
     *{String or null} 顶级容器id或name值, grid高度以此元素为基准.*/
    'topContainer': null,
    
    /**@description {Config} exclude 
     *{Array or null} 需要排除的元素, 顶级容器高度-exclude中指定的元素的高度=grid的高度. Array中指定是是元素的ID或Name值,例['id', 'name1']*/
    'exclude':null,
    
    /**@description {Config} _autoHeighthdr 
     *{function or Boolean} 私有,禁此外部使用. private handler, must not be used, contain resize handler */
    '_autoHeighthdr':false,
    
    /**激活自动调整高度功能.
     *@param topContainer {String} 顶级容器id或name值, grid高度以此元素为基准
     *@param exclude {Array or null} 需要排除的元素. Array中指定是是元素的ID或Name值*/
    autoHeight:function(topContainer, exclude_p){
        var container=null;
        var exclude=null;
        var enable=this.enableAutoHeight;
        if(this.topContainer){
            container=this.topContainer;
        }
        if(topContainer){
            container=topContainer;
            enable=true;
        }
        if(this.exclude){
            exclude=this.exclude;
        }
        if(exclude_p){
            exclude=exclude_p;
        }
        //enable
        if(!container || !enable){
            return;
        }
        //防止重复初始化.
        if(this._autoHeighthdr){
            return;
        }
        var grid=this;
        //需运行调整标志
        var run_auto=false;
        //任务结束标志
        var fin_auto=false;
        //定时标记
        var timeoutid=false;
        //设置高度调整方法
        this._autoHeighthdr=function(){
            //防止进入递归调用
            {
                //another thread finish this task
                if(fin_auto){
                    return;
                }
                fin_auto=true;
            }
            //topContainer元素的的高度
            var max=Sigma.U.getHeight(Sigma.$(container), true);
            //计算exclude 中指定元素的高度
            var exclude_height=0;
            Sigma.$each(exclude, function(idn){
                var h=0;
                h=Sigma.U.getHeight(Sigma.$(idn));
                exclude_height+=h;
            });
            //调整grid高度
            var dim=grid.getDimension();
            dim.height=max-exclude_height;
            if(dim.height>0){
                grid.setDimension(dim.width, dim.height);
            }
        };
        //此方法主要功能是当同时发生多个事件,需要调整行数时,只执行一次就可以完成调整动做.
        var task=function(){
            //防止进入递归调用
            do{
                if(!run_auto){
                    run_auto=true;
                    fin_auto=false;
                    //设置是否强制执行调整
                    var func=function(){
                        grid._autoHeighthdr();
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
//        grid.bindEvent(grid, 'complete', this._autoHeighthdr);
        //grid 载入完成时调整grid
        var _hdr=grid.onComplete;
        grid.onComplete=function(){
            try{
                _hdr();
            }catch(e){
                
            }
            task();
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
                delete this._autoHeighthdr;
            }
        };
    }
}

Sigma.$extend(Sigma.GridDefault, AutoHeight);
delete AutoHeight;

Sigma.$extend( Sigma.Grid, {
    'autoHeight':function(grid, container, exclude){
        grid=Sigma.$grid(grid);
        return function(){
            grid.autoHeight(container, exclude);
        };
    }
});
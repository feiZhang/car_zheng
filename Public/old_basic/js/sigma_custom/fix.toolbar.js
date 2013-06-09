/* 
 * 修正sigmagrid只有一条数据时显示"NO_DATA"提示.
 */

Sigma.GridDefault.refreshToolBar = function(pageInfo,doCount){
        pageInfo && ( this.setPageInfo(pageInfo) );
        if (this.over_initToolbar){
                this.navigator.refreshState(pageInfo,doCount);
                this.navigator.refreshNavBar();
                var pageInput= this.navigator.pageInput;
                if (this.pageStateBar){
                        pageInfo=this.getPageInfo();
                        //this.pageStateBar.innerHTML="";
                        Sigma.U.removeNode(this.pageStateBar.firstChild);
                        //this line to fix prompt when only one record;
                        //修正sigmagrid只有一条数据时显示"NO_DATA"提示.
                        if (pageInfo.endRowNum-pageInfo.startRowNum<0) {
                                this.pageStateBar.innerHTML= '<div>'+this.getMsg('NO_DATA')+'</div>';
                        }else{
                                this.pageStateBar.innerHTML= '<div>'+Sigma.$msg( this.getMsg( pageInput?'PAGE_STATE':'PAGE_STATE_FULL') ,
                                        pageInfo.startRowNum,pageInfo.endRowNum,pageInfo.totalPageNum,pageInfo.totalRowNum , pageInfo.pageNum )+'</div>';
                        }
                }
        }

}
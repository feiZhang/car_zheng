/** 
 * 此文件用于定义Sigmagrid导出插件
 */

(function($){
    //export function
    //source:https://gist.github.com/1031969
    //work in firefox.
    var tableToExcel = (function() {
        var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        }
        , format = function(s, c) {
            return s.replace("{table}",c.table).replace("{worksheet}", c.worksheet);
        };
        return function(table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {
                worksheet: name || 'Worksheet', 
                table: table.innerHTML
            }
            window.location.href = uri + base64(format(template, ctx))
        }
    })();
    
    var ieTableToExcel = function(xt){
        var xlsWin = null;
        var width = 6;
        var height = 4;
        var openPara = "left=" + (window.screen.width / 2 - width / 2)
            + ",top=" + (window.screen.height / 2 - height / 2)
            + ",scrollbars=no,width=" + width + ",height=" + height;
	xlsWin = window.open("", "_blank", openPara);
        xlsWin.document.write("<table>"+xt.html()+"</table>");
        xlsWin.document.close();
        xlsWin.document.execCommand('Saveas', true, "data.xls");
        xlsWin.close();
    };

    function clone_table(grid, exclude){
        var id="cln"+(new Date().getTime());
        var xt=$("<table><thead></thead><tbody></tbody></table>").attr('id', id).hide().appendTo(document.body);
        var rows=1;
        var head=xt.find('thead');
        $(grid.gridDiv).find(".gt-head-table").each(function(idx,e){
            var _this=$(e);
            if(_this.parent().hasClass('gt-head-wrap')){
                rows=_this.find("tr").not(".gt-hd-hidden").clone().appendTo(head).length;
            }else if(_this.parent().hasClass('gt-freeze-div')){
                _this.find("tr").each(function(i,ei){
                    var x=$(ei).find("td").clone().attr("rowspan", rows).each(function(){
                        $(this).prependTo(head.find("tr").eq(i));
                    });
                });
            }
        });
        var body=xt.find('tbody')
        $(grid.gridDiv).find(".gt-table").each(function(idx,e){
            var _this=$(e);
            if(_this.parent().hasClass("gt-body-div")){
                _this.find("tr").clone().appendTo(body);
            }else if(_this.parent().hasClass('gt-freeze-div')){
                _this.find("tr").each(function(i,ei){
                    $(ei).find("td").clone().prependTo(body.find("tr").eq(i));
                });
            }
        });
        {
            $.each(exclude, function(idx, e){
                xt.find('[class$="'+e+'"]').remove();
            });
        }
        //remove link keep content
        xt.find("a").contents().unwrap();
        xt.find("div").contents().unwrap();
        xt.find("span").contents().unwrap();
        xt.find("*").removeAttr('class').removeAttr('__gt_ds_index__');
        xt.find("tbody").find("*").removeAttr("id").removeAttr("style");
        xt.find("thead").find("*").removeAttr("resizable").removeAttr("columnid");
        return xt;
    }
    function clone_remove(xt){
        xt.remove();
    }
    {
        Sigma.Msg.Grid.cn.TOOL_DX_EXPORT=Sigma.Msg.Grid.cn.TOOL_XLS;
    }
Sigma.ToolFactroy.register(
    //plugin identify
    'dx_export',
    {
        'cls':'gt-tool-xls',
        'toolTip':'export into Excel',
        "action": function(event, grid){
            //save page info
            var npinfo=grid.getPageInfo();
            var pinfo=$.extend({}, npinfo);
            //show all data
            npinfo.pageNum=1;
            npinfo.pageSize=npinfo.totalRowNum;
            grid.setPageInfo(npinfo);
            grid.refreshGrid();
            var exclude=[];
            $.each(grid.columns, function(idx,e){
                if(e.skip_export){
                    exclude.push(e.id);
                }
            });
            //copy table content
            var xt=clone_table(grid, exclude);
            //export table
            if($.browser.mozilla){
                tableToExcel(xt.attr('id'));
            }else{
                ieTableToExcel(xt);
            }
            //remove clone table
            clone_remove(xt);
            
            //restore pageinfo
            grid.setPageInfo(pinfo);
            grid.refreshGrid();
        }
    }
);
})(jQuery);
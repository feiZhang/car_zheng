/* 此补丁用于解决 WdatePicker 选择选择完日期后不会触发change事件的问题.
 * 
 */


$(function(){
    _WdatePicker=WdatePicker;
    WdatePicker=function(opt){
        _WdatePicker($.extend({'onpicked':function(){
                var _this=$(this);
                _this.trigger("change").trigger('blur');
        }}, opt));
    };
});
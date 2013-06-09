/**
 * jQuery validate 添加rangein, function验证方法. 扩展基础验证
 */

(function($) {
	window.rangein = function(field, rules, i, options) {
		var range = rules[i + 2];
		if ($.inArray(field.val(), range.split('|'))) {
			return "数据不合法!";
		}
	};
    
	if($.validationEngineLanguage == undefined || $.validationEngineLanguage.allRules == undefined )
		alert("Please include other-validations.js AFTER the translation file");
	else {
		//后台数据唯一性验证
        $.validationEngineLanguage.allRules["checkFieldByUnique"] = {
                "ajaxmethod": "POST",
                "url": (URL_URL+"/checkFieldByUnique"),
                "alertText": "此数据不可用!已存在!",
                "alertTextOk": "此数据有效!",
                "alertTextLoad": "正在验证数据!"
        };
        //后台函数验证
        $.validationEngineLanguage.allRules["checkFieldByFunction"] = {
                "ajaxmethod": "POST",
                "url": (URL_URL+"/checkFieldByFunction"),
                "alertText": "此数据不可用!",
                "alertTextLoad": "正在验证数据!"
        };
	}
})(jQuery);
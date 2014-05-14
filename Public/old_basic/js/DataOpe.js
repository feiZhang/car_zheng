/**
 * 封装sigma grid，根据ThinkPHP的后台Model定义，自动生成前台界面
 * **/
(function($){
	$.dxGrid	= function(){
		var _this				= this;
		var orginUrl			= "";

		/*
		 * 生成Grid展示字段列表，包含字段类型。
		 * 1.设置默认数据
		 * 2.填充字段列表
		 */
		_this.mygrid			= null;
		_this.grid_id			= "theDataOpeGrid";
		_this.parentGridDiv	= "dataListCon";	//grid容器的父容器，因为刷新grid时，要摧毁grid容器，所以需要在父容器中重新创建容器
		_this.dsOption		= {
								fields:[],
								data : [],
								uniqueField : 'id',
								recordType : 'object'
								};
		_this.colsOption		= [];
		_this.gridOption		= {
								id 		: _this.grid_id,
							    width	: "100%",
							    height	: "320",
							    minHeight:'150',
							    loadURL	:	"",
							    container : 'dataList',
							    replaceContainer 	: false, 
							    toolbarContent 		: 'nav | goto | pagesize | print | state',
							    pageSize 			: 20,
							    loadURL				: "",
								remotePaging 		: true,
							  	pageSizeList 		: [100,1000,10000],
							  	showIndexColumn 	: true,
							  	autoLoad 			: true,
							  	selectRowByCheck	: true,
							  	showGridMenu 		: false,//sigmaGrid2添加主菜单
							  	allowCustomSkin		: false,//主菜单添加换肤功能
								allowFreeze			: true,//主菜单添加lock某些列功能
								allowHide			: true,//主菜单添加hide某些列功能
								allowGroup			: true, //主菜单添加group某些列功能
							    onMouseOver : function(value,  record,  cell,  row,  colNo, rowNo,  columnObj,  grid){
							        if (columnObj && columnObj.toolTip) {
							            grid.showCellToolTip(cell,columnObj.toolTipWidth);
							        }else{
							            grid.hideCellToolTip();
							        }
							    },
							    onMouseOut : function(value,  record,  cell,  row,  colNo, rowNo,  columnObj,  grid){
							        grid.hideCellToolTip();
							    }
							};
			//init
			_this.init	= function(gridDiv,loadUrl,gridFields,datasetFields,parentGridDiv){
			_this.gridOption.columns		= _this.colsOption;
			_this.gridOption.dataset		= _this.dsOption;

			_this.setGridContainer(gridDiv,parentGridDiv);
			_this.setGridFields(gridFields);
			_this.setDataSetFields(datasetFields);
			_this.setData(loadUrl);
		}
		_this.setGridContainer	= function(gridDiv,parentGridDiv){
			_this.gridOption.container	= gridDiv;
			_this.parentGridDiv			= parentGridDiv;
		};
		_this.setDataSetFields	= function(dataFields){
			_this.gridOption.dataset.fields	= _this.gridOption.dataset.fields.concat(dataFields);
		};
		_this.setGridFields		= function(gridFields){
			_.each(gridFields,function(o){
					if(o.renderer != undefined){
						eval(o.renderer);
						o.renderer	= valChange;
					}
					if(o.width > 1000){
						o.toolTip	= true;
						o.width		= 300;
						o.toolTipWidth	= 300;
					}
				});
			_this.gridOption.columns	= _this.gridOption.columns.concat(gridFields);
		};

		_this.setData			= function(data){
			if(typeof data == 'object')
				_this.dsOption.data		= data;
			else if(typeof data == 'string'){
				_this.gridOption.loadURL	= data;
                _this.orginUrl				= data;
            }
		};
		_this.showGrid			= function(excludeHeight){
			var gridOption	= _this.gridOption;
			
            var max	= $(window).height();
            //计算exclude 中指定元素的高度
            Sigma.$each(excludeHeight, function(idn){
                max		= max - Sigma.U.getHeight(Sigma.$(idn));
            });
            if($.browser.msie && ($.browser.version=="6.0")){
            	max		= max - 20;
            }
            gridOption.height	= max-5;
            var pageSize	= (gridOption.height-50)/23;
            if(pageSize>gridOption.pageSize) gridOption.pageSize = parseInt(pageSize);
            gridOption.pageSizeList.unshift(gridOption.pageSize);
            
			_this.mygrid	= new Sigma.Grid( gridOption );
			_this.mygrid.render();
		};
		//查询数据
        _this.query				= function(para){
            var loadUrl	= _this.orginUrl;
            if(loadUrl.indexOf('?')>-1){
                loadUrl	+= "&"+para;
            }else{
                loadUrl	+= "?"+para;
            }
            _this.gridOption.loadURL		= loadUrl;
            _this.reload();
        };
        _this.reload	= function(){
        	_this.mygrid.reload();
        }
        //grid全刷新，，在列信息改变的情况下，刷新。
		_this.refreshGrid			= function(){
//	    	colsOption.push({id: 'id' , header: "ID" , width :100});
	    	_this.mygrid.destroy();
	    	$("#"+_this.parentGridDiv).append("<div id=\"" + _this.gridOption.container + "\"></div>");
	    	_this.mygrid	= new Sigma.Grid( _this.gridOption );
	    	_this.mygrid.render("dataList");
		};
	}
})(jQuery);
/*
 * 生成Grid展示字段处理规则
 * 功能:字典表数据转换、数据列默认冻结、Html数据格式转换、默认的数据操作（修改、删除、改变状态）
 * 参数:删除数据的URL、修改数据的URL、状态转换的URL
 * 备注:
 * 		1.改变状态有 下拉、Radio列表两种格式
 */
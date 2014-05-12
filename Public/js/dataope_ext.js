//消费
function saleCommodity(card_no){
  $.dialog({
    id:"select_card_info",
    title:"消费",
    content:"等待查询卡信息!<img src='" + DX_PUBLIC + "/public/loading.gif' />",
    lock:true,
    ok:function(){
      submitSaleInfo();
      return false;
    },
    okValue:"确定销售",
    cancelValue:"取消",
    cancel:function(){},
    initialize:function(){
      var _this  = this;
      var url    = APP_ROOT + "/Card/card_select/type/sale/card_no/" + card_no;
      $.get(url,function(html){
        _this.content(html);
        _this.position();
      });
    }
  });
}

function submitSaleInfo(){
  //输入密码错误后，则无订单信息。
  //判断是否有次数消费。
  var haveSalInfo	= false;
  var html		    = "";
  $("#coustomerHasTimesCard .saleTimesCard").each(function(i){
    if($(this).val()!=""){
      haveSalInfo	= true;
      html	+= $(this).attr("itemTitle") + "计次消费" + $(this).val() + "次<br />";
    }
  });
  //console.log(html);console.log($("#coustomerHasTimesCard .saleTimesCard"));
  if($("#commodityList input[type='checkbox']:checked").length>0 || haveSalInfo){
    //无卡的，可以赊账，有卡的则不再赊账，直接记录到卡余额中。
    $.dialog({
      id:"saleAffirmInfo",title:"销售确认",content:'正在处理中!<img src="' + DX_PUBLIC+ '/public/loading.gif" />',esc:true,
      lock:true,
      ok:function(){
        saveSaleInfo("");
        this.position();
      },
      okValue:"确定销售",
      cancelValue:"取消",
      cancel:function(){},
      initialize:function(){
        var _this   = this;
        $("#commodityList input[type='checkbox']:checked").each(function(i){
          var tid = $(this).val();
          html   += $("#commodityList input[name='commodity_title"+tid+"']").val() + $("#commodityList input[name='money"+tid+"']").val() + "元<br />";
        });
        if($("#saleCommodityForm input[name='customer_id']").length==0){
          //_this.button({value: '赊账',callback: function () { saveSaleInfo("/sale_type/oncredit");}});
        }
        _this.content(html);
      }
    });
  }else{
    $.dialog({
      id:"showMessage",title:"提示",content:"没有选择要销售的商品!",esc:true,time:3000
    });
  }
}

function saveSaleInfo(saleType){
  $.dialog({
    id:"saleItemsResult",title:"销售结果",content:'正在处理中!<img src="' + DX_PUBLIC + '/public/loading.gif" />',esc:true
  });
  $.ajax({
    type: "POST",
    url: APP_ROOT + "/Commodity/sale_selectitems" + saleType,
    data: $("#saleCommodityForm").serialize(),
    success: function(msg){
            if(Sigma.GridCache["theDataOpeGrid"]){
                Sigma.GridCache["theDataOpeGrid"].reload();
            }   


      $.dialog.get('saleItemsResult').content(msg);
      $.dialog.get('saleItemsResult').time(3000);
      $.dialog.get('select_card_info').close();
    }
  });
}

//充值
function cardTopUp(cardNo){
  if(cardNo==0){
    $.dialog({title:"提醒",content:"充值必须有卡号",time:3000});
    return;
  }

  $.dialog({
    id:"select_card_info",
    title:"卡充值",
    lock:true,
    content:"等待查询卡信息!<img src='" + DX_PUBLIC + "/public/loading.gif' />",
    ok:function(){
      $.dialog({
        id:"topUpResult",title:"充值结果",content:"正在处理中!<img src='" + DX_PUBLIC + "/public/loading.gif' />"
      });

      $.ajax({
        type: "POST",
        url: APP_ROOT + "/Card/save_topup",
        data: $("#saleCommodityForm").serialize(),
        success: function(msg){
            if(Sigma.GridCache["theDataOpeGrid"]){
                Sigma.GridCache["theDataOpeGrid"].reload();
            }   

          $.dialog.get('topUpResult').content(msg);
          $.dialog.get('topUpResult').time(3000);
        }
      });
    },
    okValue:"确定充值",
    cancelValue:"取消",
    cancel:function(){},
    initialize:function(){
      var _this   = this;
      $.get(APP_ROOT + "/Card/card_select/type/topup/card_no/" + cardNo,function(html){
        _this.content(html);
      });
    }
  });
}




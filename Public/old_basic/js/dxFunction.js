function showDialog(strTitle,strContent){
	$.dialog({title:strTitle,content:strContent,esc:true,time:3000,lock:true});
}


function resetPasswd(id){
	$.dialog({
        id: 'Prompt',
        fixed: true,
        lock: true,
        content: [
            '<div style="margin-bottom:5px;font-size:12px">请输入新密码:</div>',
            '<div>',
            '<input type="password" class="d-input-text" value="" style="width:18em;padding:6px 4px" />',
            '</div>'
            ].join(''),
        initialize: function () {
            input = this.dom.content.find('.d-input-text')[0];
            input.select();
            input.focus();
        },
        ok: function () {
        	$.get(URL_URL + "/resetPassword?i="+id+"&p="+input.value);
            this.content("密码修改成功!");
            this.time(2000);
        },
        okValue:"确定",
        cancel: function () {},
        cancelValue:"取消"
    });
}
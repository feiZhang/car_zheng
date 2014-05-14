<?php

/**
 * 用户管理， 目前只有一个密码修改功能
 *
 * @author Think
 */
class AccountAction extends DataOpeAction {

    function editpass() {
        if ($_REQUEST["subEditPass"] == "修改") {
            $m = D("Account");
            $oldP = $m->field("pwd")->find($this->getUid());
            if (authcode($oldP["pwd"], "DECODE") == trim($_REQUEST["oldpass"])) {
                $r = $m->where(array("id" => $this->getUid()))->save(array("pwd" => authcode(trim($_REQUEST['newPass']), 'ENCODE')));
                if (FALSE !== $r) {
                    $this->assign("message", "密码修改成功!");
                } else {
                    $this->assign("message", "密码修改失败!");
                }
            }else
                $this->assign("message", "旧密码不正确!");
        }
        $this->display();
    }

}
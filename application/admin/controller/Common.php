<?php
namespace app\admin\controller;
use think\Controller;

class Common extends Controller
{
    #登录
    public function login()
    {
        $request=request();
        if($request->isPost()){
            $verify=input('post.verify');
            $uname=$request->post('uname');
            $pass='7059777b64ff9af2627a329289e129b1';
            $r=model('common.Admin')->login($uname,$pass);
            if($r->status==1){
                session('AdminInfo',$r->data);
                $this->_getActions($r->data['id']);
               // $return_url=session('login_return_url');
               // if(empty($return_url)){
                   
              //  }
			   $return_url=url('index/index');
               $r->data=$return_url;
				
            }
            return json($r);
        }
        return view();
    }
    #退出登录
    public function logout()
    {
        session(null);
        $this->redirect("login");
    }
    #获取权限
    private function _getActions($uid)
    {
        $codes=model('common.Actions')->listByAdminId($uid);
        session('AdminActions',$codes);
    }
}
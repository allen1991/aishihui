<?php
namespace Home\Controller;

use Think\Controller;
use Home\Pojo;
//引入用户对象
//require("./../Pojo/User.php");

class IndexController extends Controller
{
    public function indexAction()
    {   
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    	

        $user = new \Home\Pojo\User();
        
        echo $user->test();
        
        //$this->show("hello world");
        //C方法是获取配置的方法
        $modal = C('APP_AUTOLOAD_LAYER');

        echo $modal;
    	//$this->show("hi" . $user::test());
    	
    }
    
    public function sayAction(){
        //$this->fetch("login");//获取模板地址
        
    	//$this->show("say hi". CORE_PATH, 'utf-8', 'text/xml');
        //$this->theme("login")->display("login");
        $this->assign("name","this is login module");
        $array["name"] = "allen";
        $array["email"] = "389836147@qq.com";
        $array["phone"] = 15118067318;
        $array["THINK_PATH"] = THINK_PATH;
        
        $array["script"] = "http://www.ihuyi.com/js/jquery-1.9.1.min.js";
        $upload = new \Think\Upload();
        $array["maxSize"] = $upload->maxSize;
        

        $this->assign($array);
        $this->display("User:user");
        
    }

    //查询数据库用户表1
    public function queryUserAction($id=1001){
        //$this->show($id);
        $User = new \Home\Model\UserModel();
        
        // $this->show($User->select());
        $this->ajaxReturn($User->select());
    }

    //测试参数传递
    public function testParamsAction($name=1,$id=-1){
        $array["name"] = $name;
        $array["id"] = $id;
        $array["url"] = U('User/add'); //U = url
        $array["I"] = I("id"); //I = input;
        $this->ajaxReturn($array);
    }

    //查询数据库用户表2 
    public function queryUserAllAction(){
        $User = D("User");
        
        $dbKeys = $User->queryUser();
        for($i = 0 ; $i<count($dbKeys);$i++){
        //    echo $dbKeys[$i];
        }
        $this->ajaxReturn($dbKeys);
        //$this->ajaxReturn($User->find());
        //$this->ajaxReturn($User->queryUser());
        //$this->ajaxReturn($User->getDbFields());
    }
    //上传图片
    public function uploadimgAction(){

        $upload = new \Think\Upload();
        //设置图片文件最大size
        $upload->maxSize = 3292200; 
        //设置文件允许上传的类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

        $upload->savePath = './Uploads/';
        
        echo $upload->maxSize;
    }

}
?>


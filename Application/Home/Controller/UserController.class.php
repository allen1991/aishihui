<?php 
namespace Home\Controller;
use Think\Controller;
/*
 设置了最后接口action后缀
*/
class UserController extends Controller{

	//判断是否存在
	public function isExistAction(){
		echo "no-exist";
		//echo REQUEST_METHOD;
		//echo CONTROLLER_NAME;
		$db = array(
		    'db_type'  => 'mysql',
		    'db_user'  => 'root',
		    'db_pwd'   => '1234',
		    'db_host'  => 'localhost',
		    'db_port'  => '3306',
		    'db_name'  => 'thinkphp'
		);
		//$data['status']  = 1;
		$this->ajaxReturn($db);
	}

	//管理员登录
	public function loginAction($phone=1000,$pwd='pwd'){
		/*
		if($phone==1000||$pwd=='pwd'){
			$array["code"] = 0;
	        $array["res"] = "账号密码错误";
	        $this->ajaxReturn($array);
		}
		*/
		
		$User = D("User");
		$user= $User->queryUser($phone);
		
        //对象属性与数据库字段映射
        $user = $User->parseFieldsMap($user);
        $this->ajaxReturn($user["0"]);
		
	}
	//更新用户状态
	public function updateUserStateAction($operateid=0,$userid=0,$state=-1){
		/*
		if($operateid==0||$userid==0||$state==-1){
			$array["code"] = 0;
	        $array["res"] = "参数错误";
	        $this->ajaxReturn($array);
		}
		*/
		$UserDao = D("User");
		
		//获取用户状态与权限
		$user = $UserDao->getUserStateAndLimit($operateid);
		$user = $UserDao->parseFieldsMap($user);

		$user = $user["0"];

		

		$this->ajaxReturn($user);


	}


}


?>

<?php 
namespace Home\Controller;
use Think\Controller;
/*
 设置了最后接口action后缀
*/
class UserController extends Controller{

	//判断是否存在
	public function isExistTestAction($userid=0){
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
	
	//判断用户是否存在
	public function isExistAction($userphone=0){
		if($userphone<10){
			$map["code"] = 0;
	        $map["res"] = "参数错误";
	        echo json_encode($map);
	        return;
		}
		//先判断用户状态
        $UserInfo = D("UserInfo")->getUserInfoByPhone($userphone);
        if($UserInfo == null ||count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户不存在";
            echo json_encode($map);
            return;
        }else{
        	$map["code"] = 1;
            $map["res"] = "该用户已经存在";
            echo json_encode($map);
            return;
        }
	}

	//管理员登录
	public function loginAction($userphone=1000,$userpwd=''){
		
		if($userphone==1000||$userphone==''){
			$array["code"] = 0;
	        $array["res"] = "账号密码错误";
	        $this->ajaxReturn($array);
		}
		
		$User = D("User");
		$user= $User->queryUser($userphone);
		
        // //对象属性与数据库字段映射
        // $user = $User->parseFieldsMap($user);
        
        $map["code"] = 1;
        $map["data"] = $user["0"];
        echo json_encode($map);
        // $this->ajaxReturn($map);
		
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
	
	//获取用户信息
	public function getUserInfoAction($userid=0){
		header('Content-Type:text/json; charset=utf-8');
		if($userid==0){
        	$map["code"] = 0;
	        $map["res"] = "参数不正确";
	        echo json_encode($map);
	        return;
        }
		//先判断用户是否有权限上传文章
        $UserInfo = D("UserInfo")->getBasicUserInfo($userid);
        if($UserInfo == null ||count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "未查找到该用户信息";
            echo json_encode($map);
            return;
        }else{
            $UserInfo = $UserInfo[0];
        }
        $map["code"] = 1;
        $map["data"] = $UserInfo;
        echo json_encode($map);
        return;
	}
	
	//上传修改用户信息
	public function updateUserInfoAction($userid=0,$username="",$useridnumber="",$userphone="",$img1="",$img2="",
			$img3="",$img4="",$img5="",$infoopen="",$usernickname=""){
		header('Content-Type:text/json; charset=utf-8');
		
        if($userid==0){
        	$map["code"] = 0;
	        $map["res"] = "参数不正确";
	        echo json_encode($map);
	        return;
        }
        //查询userid信息
        //do-query-userinfo-by-userid
        
        $data["user_Id"] = $userid;
        $data["user_Name"] = $username;
        $data["user_Nick"] = $usernickname;
        $data["user_Phone"] = $userphone;
        $data["user_id_code"] = $useridnumber;
        $data["user_open_img1"] = $img1;
        $data["user_open_img2"] = $img2;
        $data["user_open_img3"] = $img3;
        $data["user_open_img4"] = $img4;
        $data["user_open_img5"] = $img5;
        $UserModel = D("UserInfo");
        $result = $UserModel->updateUserInfo($data);
        if($result!=1){
        	$map["code"] = 0;
	        $map["data"] = "更新用户信息失败";
	        echo json_encode($map);
	        return;
        }else{
        	$map["code"] = 1;
	        $map["data"] = "更新用户信息成功";
	        echo json_encode($map);
	        return;
        }
	}
	
	


}


?>

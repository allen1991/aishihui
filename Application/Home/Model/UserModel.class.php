<?php 
namespace Home\Model;
use Think\Model;

/*
	用户数据库操作表
*/

class UserModel extends Model{
	protected $autoCheckFields = false;
	//数据库表前缀
	protected $tablePrefix = "j_";
	//数据库表名
	protected $tableName = "user_info";
	protected $trueTableName = 'j_user_info'; 
	protected $_map = array(
        'userid' =>'user_id', 
        'userphone'  =>'user_phone', 
        'userstate' =>'user_state',
    );

    
	//数据库表字段(这里写了字段之后就可以不依赖字段缓存，可以减少io加载开销)
	//protected $fields = array("id");
	//查询数据库
	public function queryUser($phone,$pwd){
		
		$map["user_phone"] = $phone;
		
		return $this->field("user_id,user_phone,user_state")->where($map)->select();
	}
	
	//获取用户状态与权限，通过用户id
	public function getUserStateAndLimit($userid){
		$map["j_user_id"] = $userid;
		return $this->field("user_phone,user_state,user_id")->where($map)->select();
	}
	

}


?>
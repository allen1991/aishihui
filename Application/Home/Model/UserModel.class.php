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
        'userid' =>'j_user_id', 
        'userphone'  =>'j_user_phone', 
        'userstate' =>'j_user_state',
    );
	//数据库表字段(这里写了字段之后就可以不依赖字段缓存，可以减少io加载开销)
	//protected $fields = array("id");
	//查询数据库
	public function queryUser($phone,$pwd){
		
		$map["j_user_phone"] = $phone;
		
		return $this->where($map)->select();
		
	}

	//获取用户状态与权限，通过用户id
	public function getUserStateAndLimit($userid){
		$map["j_user_id"] = $userid;
		return $this->field("j_user_phone,j_user_state,j_user_id")->where($map)->select();
	}

}


?>
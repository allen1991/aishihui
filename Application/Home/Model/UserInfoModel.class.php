<?php 
namespace Home\Model;
use Think\Model;
/**
*	用户基本信息模型
*/
class UserInfoModel extends Model{

    //系统支持数据的批量验证功能，只需要在模型类里面设置patchValidate属性为true（ 默认为false );
    protected $patchValidate = true;
    
	protected $autoCheckFields = false;
	//真正的用户信息表
	protected $trueTableName = 'j_user_info'; 
	
	protected $_map = array(
        'userid' =>'user_id', 
        'username' => 'user_name',
        'userphone'  =>'user_phone', 
        'userstate' =>'user_state',
        'usernick' => 'user_nick',
        'userlastlogintime' => 'last_login_time',
        'userimg' => 'user_img',
        'userqq' => 'user_qq',
        'userwx' => 'user_wx',
        'usernick' => 'user_nick',
        'userrealname' => 'user_realname',
        'useropenimg1' => 'user_open_img1',
        'useropenimg2' => 'user_open_img2',
        'useropenimg3' => 'user_open_img3',
        'useropenimg4' => 'user_open_img4',
        'useropenimg5' => 'user_open_img5',
        
    );

	//获取用户基础信息
    public function getBasicUserInfo($userid=0){
    	if($userid==0){
    		
    		return null;
    	}
        
    	$map["user_id"] = $userid;
    	//查找第一个
    	return $this->where($map)->limit(1)->select();
        
    }

    //通过用户手机查找用户信息
    public function getUserInfoByPhone($phone=0){
        if($phone==0){
            return null;
        }
        $map["user_phone"] = $phone;
        return $this->where($map)->limit(1)-select();
    }
    
    //更新用户信息
    public function updateUserInfo($data){
        if(!$data){
            return 0;
        }
        
        $condition["user_Id"] = $data["user_Id"];
        
        return $this->where($condition)->save($data);
    }

}


?>
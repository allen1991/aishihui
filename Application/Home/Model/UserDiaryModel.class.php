<?php 
namespace Home\Model;
use Think\Model;

/*
*用户日记模型
*/
class UserDiaryModel extends Model{
	//真正用户日记数据库表
	protected $trueTableName = 'j_userdiary_info';
	//系统支持数据的批量验证功能，只需要在模型类里面设置patchValidate属性为true（ 默认为false );（用在表单create上）
	protected $patchValidate = true;
	//验证规则
	protected $_validate = array( 
		array('user_Id',"number",'数据类型不对',self::MUST_VALIDATE)
	);

	//映射
	protected $_map = array(
        'userid' =>'user_id',
        'diaryid' => 'diary_id', 
        'diarypictures' =>"diary_pictures",
        'diaryvideo' =>"diary_video",
        'diarystate' => "diary_state",
        'diaryscan' => 'diary_scan',//日记浏览量
        'diarylike' => 'diary_like',//日记点赞
        'diarydislike' => 'diary_dislike',//日记踩
        'diarycomments' => 'diary_comments',//日记评论
        'diarycanbecomment' => 'diary_canbe_comment',//日志是否可以被评论
    );


    //发表日记
	public function publicDiary($data){
		//$this->create($data);//表单提交的时候，直接create进入
		return $this->data($data)->add();

	}

	//获取个人日记记录
	public function getUserDiaryRecord($data,$page=1,$pagesize=15){
		$pageStr =$page .',' . $pagesize ;
		return $this->page($pageStr)->where($data)->order("diary_id desc")->select();
	}

	//根据类型获取日记
	public function getDiaryRecordByType($data,$page=1,$pagesize=15){
		$pageStr =  $page .',' . $pagesize ;
		return $this->page($pageStr)->where($data)->order("diary_id desc")->select();
	}

	//个人删除日志
	public function deleteDiaryRecord($userid=0,$diaryid=0){
		if($userid==0||$diaryid==0){
			return 0;
		}
		$data["user_id"] = $userid;
		$data["diary_id"] = $diaryid;
		
		return $this->where($data)->delete();
	}

	//系统管理员禁止日记
	public function updateDiaryState($userid=0,$diaryid=0,$diarystate=0){
		if($userid==0||$diaryid==0){
			return 0;
		}
		$condition["user_id"] = $userid;
		$condition["diary_id"] = $diaryid;
		
		$data["diary_state"] = $diarystate;
		
		return $this->where($condition)->save($data);
	}


}



?>
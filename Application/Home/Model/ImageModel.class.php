<?php
namespace Home\Model;
use Think\Model;

class ImageModel extends Model{

	protected $trueTableName = 'j_image_info';
	
	protected $map = array(
				'userid' => "user_id",
			 	"imageid" => 'image_id',
			 	"imageurl" => "image_url",
			 	'imagetype' => 'image_type',
			);
	

	//获取我的图片列表
	public function getMyImageList($userid=0,$page=1,$pagesize=10){
		if($userid==0){
			return null;
		}
		$map["user_id"] = $userid;
		$pageStr =  $page .',' . $pagesize ;
		return $this->page($pageStr)->where($map)->order("image_id desc")->select();
	}

	//删除我的图片
	public function deleteMyImageList($userid=0,$imageid=0){
		if($userid==0||$imageid==0){
			return null;
		}

		$map["user_id"] = $userid;
		$map["image_id"] = $imageid;
		
		return $this->where($map)->delete();
	}

	//通过userid和imageid获取照片信息
	public function getMyPhotoInfoById($userid=0,$imageid=0){
		if($userid==0||$imageid==0){
			return null;
		}
		
		$map["user_id"] = $userid;
		$map["image_id"] = $imageid;
		return $this->where($map)->select();
	}

	//新增图片
	public function addPhoto($data){
		$this->create($data);
		return $this->data($data)->add();
	}


}

?>
<?php 
namespace Home\Model;
use Think\Model;

/*
*第三方图片上传model
*/
class ImageCenterModel extends Model{
	//真正的表名
	//protected $trueTableName = 'j_imagecenter_info'; 
	protected $trueTableName = 'j_imagecenter_info';
	//系统支持数据的批量验证功能，只需要在模型类里面设置patchValidate属性为true（ 默认为false );
	protected $patchValidate = true;
	//验证规则
	protected $_validate = array( 
		array('image_Id',"number",'数据类型不对',self::MUST_VALIDATE)
	);
	
	protected $_map = array(
        'imageid' =>'image_id', 
        'imageurl' =>"image_url",
        'imagestate' => 'image_state',
        'imageappkey' => 'image_appkey',
        'imagelocation' => "image_location",
        "imagetype" => "image_type"    
    );
	
	//上传图片
	public function uploadImage($data){
		$this->create($data);//表单提交的时候，直接create进入
		return $this->data($data)->add();
	}
	
}


?>
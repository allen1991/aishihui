<?php
namespace Home\Controller;
use Think\Controller;

/**
*图片管理控制器
*/
class ImageController extends Controller{
	public function indexAction(){
		header('Content-Type:text/json; charset=utf-8');
		$this->ajaxReturn("抱歉,该接口正在努力修复中");
		return;
	}	
    
	//查找我的照片
	public function getMyPhotosAction($userid=0,$page=1,$pagesize=10){
		header('Content-Type:text/json; charset=utf-8');
        
        if($userid<1){
            $map["code"] = 0;
            $map["res"] = "userid参数错误";
            echo json_encode($map);
            return;
        }
        //先判断用户是否有权限上传文章
        $UserInfo = D("UserInfo")->getBasicUserInfo($userid);
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户无法查找图片信息";
            echo json_encode($map);
            return;
        }else{
            $UserInfo = $UserInfo[0];
        }
        if($UserInfo["userstate"]==0){
            $map["code"] = 0;
            $map["res"] = "该用户已经被禁止";
            echo json_encode($map);
            return;
        }
        
        $Images = D("Image")->getMyImageList($userid,$page,$pagesize);
        $map["code"] = 1;	
        $map["data"] = $Images;
        echo json_encode($map);
        return;
	}

    //删除照片
    public function deletePhotoAction($userid=0,$imageid=0){
        header('Content-Type:text/json; charset=utf-8');
        if($userid<1){
            $map["code"] = 0;
            $map["res"] = "userid参数错误";
            echo json_encode($map);
            return;
        }
        if($imageid<1){
            $map["code"] = 0;
            $map["res"] = "imageid参数错误";
            echo json_encode($map);
            return;
        }
        $model = D("Image");
        $model->execute('begin');  

        //获取我的照片信息
        $ImageInfo = $model->getMyPhotoInfoById($userid,$imageid);
        if($ImageInfo == null ||count($ImageInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "查找照片信息失败";
            echo json_encode($map);
            return;
        }else{
            $ImageInfo = $ImageInfo[0];
        }

        //删除我的照片
        $result = $model->deleteMyImageList($userid,$imageid);
        $model->execute('commit'); 
        if($result<1){
            $map["code"] = 0;
            $map["res"] = "删除照片失败";
            echo json_encode($map);
            return;
        }
        if($result>1){
            $model->execute('rollback');  
            $map["code"] = 0;
            $map["res"] = "重复照片删除失败";
            echo json_encode($map);
            return;
        }
        
        if($ImageInfo["image_type"]==10){
            //图片路径
            $ImagePath = "./../Uploads/" . $ImageInfo["image_location"];
            if(!unlink($ImagePath)){
                $map["code"] = 1;
                $map["res"] = "删除失败";
                $model->execute('rollback'); 
                echo json_encode($map);
                return;
            }
        }
        

        $map["code"] = 1;
        $map["res"] = "删除成功";
        echo json_encode($map);
        return;
    }
    //上传base64的照片
    public function uploadBase64ImgAction($base64="",$path = '/Images/'){
        header('Content-Type:text/json; charset=utf-8');
        
        if($base64==""){
            $map["code"] = 0;
            $map["res"] = "参数错误";
            echo json_encode($map);
            return;
        }

        //正则匹配
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/',  $base64,$result)){
            
            $type = $result[2];
            $new_file = $path.time().".jpg";
            
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
                $map["code"] = 1;
                $map["data"] = $new_file;
                echo json_encode($map);
                return;
            }else{
                $map["code"] = 0;
                $map["res"] = "上传失败";
                echo json_encode($map);
                return false;
            }
        }else{
            $map["code"] = 0;
            $map["res"] = "上传失败";
            echo json_encode($map);
            return false;
        }
    }
}

?>
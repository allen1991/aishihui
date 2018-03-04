<?php
namespace Home\Controller;
use Think\Controller;

/**
*第三方图片上传
*/
class ImageCenterController extends Controller{

	public function indexAction(){
        header('Content-Type:text/json; charset=utf-8');
		echo "此接口还未实现";
		return;
	}
	//上传base64文章
	public function uploadbase64imgAction($base64="",$appkey="",$customerpath="",$childpath="",$sourcetype=""){//base64，appkey,自定义路径,自定义子路径,资源来源类型
		header('Content-Type:text/json; charset=utf-8');
		$size = file_get_contents($base64);
        
		if($size>5*1024*1024){
			$map["code"] = 0;
			$map["res"] = "图片大小最多不超过5M";
			echo json_encode($map);
			return;
		}
        if(!$appkey||$appkey==""){
            $map["code"] = 0;
            $map["res"] = "appkey校验未通过";
            echo json_encode($map);
            return;
        }
        
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/',  $base64,$result)){
            $customerpath = $customerpath ? $customerpath : $appkey;//自定义路径没传则直接为appkey
            $server_root = $_SERVER['DOCUMENT_ROOT'];
            $path = '/IMAGESCENTERFILE/'.$appkey."/".$customerpath."/"; //系统文件夹根目录下
            
            if($childpath!=""){//子路径
                $path = $path . $childpath . "/";
            }
            //资源路径
            $sourceurl = ($server_root.$path);
            //创建目录
			$this->createDir($sourceurl);
            
            $type = $result[2];
            $time = substr(strval(microtime(true)*10000) ,0,13);

            $new_file = $path.$time."."."{$type}";
            
            
            if (file_put_contents(($server_root.$new_file), base64_decode(str_replace($result[1], '', $base64)))){
                //生成图片id
				$imageId  = $time;
                //存储到数据库中
                $data["image_Id"] = $imageId;
                $data["image_Url"] = $new_file;
                $data["image_Appkey"] = $appkey;
                $data["image_State"] = 1;
                $data["image_Location"] = "";
                
                $ImageCenter = D("ImageCenter");
                //上传图片到数据库,如果上传失败，则直接删除掉图片，并且返回相对的信息
                $count = $ImageCenter->uploadImage($data);
                if($count!=1){
                	$map["code"] = 0;
					$map["res"] = "上传失败，插入记录失败";
                    
					if(file_exists($new_file)){
						$res = unlink($new_file);
						if($res){
							$return = array('success'=>1,'errors'=>'删除图片或文件成功');
						}else{
							$return = array('success'=>0,'errors'=>'操作失误导致图片或文件无法删除');
						}
						$map["return"] = $return;
					}else{
						$return = array('success'=>404,'errors'=>'无法找到文件或者已经删除');
						$map["return"] = $return;
					}
					echo json_encode($map);
					return;
                }
                
                $map["code"] = 1;
                $ret["data"] = $new_file;
                $ret["imageId"] = $imageId;
                $map["data"] = $ret;
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
            $map["res"] = "上传失败,解析错误";
            echo json_encode($map);
            return false;
        }

	}
	//生成目录
	private function createDir($aimUrl) {
        $aimUrl = str_replace('', '/', $aimUrl);
        $aimDir = '';

        $arr = explode('/', $aimUrl);

        $result = true;
        
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                $result = mkdir($aimDir,0777);
            }
        }
        return $result;
    }
}

?>
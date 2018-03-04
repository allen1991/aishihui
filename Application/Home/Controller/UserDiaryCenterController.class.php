<?php
namespace Home\Controller;
use Think\Controller;

/*
*用户日记controller
*/
class UserDiaryCenterController extends Controller{

	public function indexAction(){
		header('Content-Type:text/json; charset=utf-8');
		echo "该接口为实现";
		return;
	}

	//发布日记
	public function publicDiaryAction($userid="",$diaryNote=""){//用户id,日记留言+方法内的获取日记图片
		header('Content-Type:text/json; charset=utf-8');
		
		$data = json_decode(file_get_contents("php://input"),true);//获取日记图片
		
		$list = $data["diaryPictures"];
		$count = count($list);

		//做数据处理
		for($i=0;$i<$count;$i++){  
            echo "<h1>第".($i+1)."个人的名字是{$list[$i]}</h1>";  
        } 
        //日记类型,如果count>0，说明是图片日记
        $type = 0;
        if($count>0){
        	$type = 1;
        }

        //获取用户日记model
        $UserDiaryModel = D("UserDiary");
        //上传文章
        $result = $UserDiaryModel->publicDiary($data);


		return;

	}

	//获取个人日记记录
	public function getUserDiaryRecordAction($userid="",$page=1,$type=0){//用户id,获取条数,类型(存日志，图片，图片)
		header('Content-Type:text/json; charset=utf-8');
		if($userid==""){
			$map["code"] = 0;
			$map["res"] = "用户参数错误";
			echo json_encode($map);
			return;
		}

		//首先判断用户状态

		$data["user_Id"] = $userid;
        $data["type"] = $type;
        $data["page"] = $page;
        $data["pagesize"] = 15;//默认查询15条一次

        //获取用户日记model
        $UserDiaryModel = D("UserDiary");
        //查询用户日记
        $result = $UserDiaryModel->getUserDiaryRecord($data,$page);

        $map["code"] = 1;
        $map["data"] = $result;
        echo json_encode($map);
        return;

	}

	//获取热门日记
	public function getDiaryAction($appkey="",$page=1,$type=0){
		header('Content-Type:text/json; charset=utf-8');
		if($appkey==""){
			$map["code"] = 0;
            $map["res"] = "缺少参数";
            echo json_encode($map);
            return;
		}
		//先验证appkey是否有效


		$data["type"] = $type;
        $data["page"] = $page;

        //获取用户日记model
        $UserDiaryModel = D("UserDiary");
        //根据类型获取日记
        $result = $UserDiaryModel->getDiaryRecordByType($data,$page);
        $map["code"] = 1;
        $map["data"] = $result;
        echo json_encode($map);
        return;

	}

	//修改文章状态
	public function updateDiaryStateAction($appkey='',$userid=0,$diaryid=0,$diarystate=0){
		header('Content-Type:text/json; charset=utf-8');
		if($appkey==""||$userid==0||$diaryid==0){
			$map["code"] = 0;
            $map["res"] = "缺少参数";
            echo json_encode($map);
            return;
		}

		//先验证appkey是否有效
		//再验证userid是否有效

		//获取用户日记model
        $UserDiaryModel = D("UserDiary");
		$result = $UserDiaryModel->updateDiaryState($userid,$diaryid,$diarystate);
		if($result==1){
			$map["code"] = 1;
            $map["res"] = "修改状态成功";
            echo json_encode($map);
            return;
		}else{
			$map["code"] = 0;
            $map["res"] = "修改状态失败";
            echo json_encode($map);
            return;
		}

	}
	//删除日记
	public function deleteDiaryRecordAction($appkey='',$userid=0,$diaryid=0){
		header('Content-Type:text/json; charset=utf-8');
		if($appkey==""||$userid==0||$diaryid==0){
			$map["code"] = 0;
            $map["res"] = "缺少参数";
            echo json_encode($map);
            return;
		}
		
		//先验证appkey是否有效
		//再验证userid是否有效

		//获取用户日记model
        $UserDiaryModel = D("UserDiary");
		$result = $UserDiaryModel->deleteDiaryRecord($userid,$diaryid);
		if($result==1){
			$map["code"] = 1;
            $map["res"] = "删除成功";
            echo json_encode($map);
            return;
		}else{
			$map["code"] = 0;
            $map["res"] = "删除失败";
            echo json_encode($map);
            return;
		}		

	}

	




}










?>
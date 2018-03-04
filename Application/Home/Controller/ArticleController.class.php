<?php
namespace Home\Controller;
use Think\Controller;

class ArticleController extends Controller{

	public function indexAction()
    {   
        echo "index";
    }

	//查询文章列表
	public function querylistAction(){
		$article = new \Home\Pojo\Article();
		//echo $article->articleId;

		//echo "the inteface is ok";
		$arti = json_encode($article);
		//$arti = array(
		//    'db_type'  => $article->articleId   
		//);
		echo($arti);
		$this->ajaxReturn($arti);
	}

	//提交文章
	public function submitArticleAction($operateid=0,$articletitle="",$articlegroupid=0,$articlevicetitle="",$articleabstract="",$articlekey="",$articledesc="",
		
		$articleimg1="",$articleimg2="",$articleimg3="",$articlevideo="",$articlecontent="",$articlepublicchannel=0,$articlelink=""){
		header('Content-Type:text/json; charset=utf-8');
        if($operateid<1){
            $map["code"] = 0;
            $map["res"] = "operateid参数错误";
            echo json_encode($map);
            return;
        }
        //先判断用户是否有权限上传文章
        $UserInfo = D("UserInfo")->getBasicUserInfo($operateid);
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户不能上传文章";
            echo json_encode($map);
            return;
        }else{
            $UserInfo = $UserInfo[0];
        }
        
        // 判断用户是否已经通过实名制
        // if($UserInfo["userrealname"]==0){
        //     $map["code"] = 0;
        //     $map["res"] = "未实名制的用户不能够发表文章，请先实名制认证后再发表";
        //     echo json_encode($map);
        //     return;
        // }
        
        //state==0为用户状态禁止中
        if($UserInfo["userstate"]==0){
            $map["code"] = 0;
            $map["res"] = "该用户已经被禁止发表文章";
            echo json_encode($map);
            return;
        }


        //生成文章id
		$articleId  = $time = substr(strval(microtime(true)*10000) ,0,13);

		
        // $this->assign("nickname",$UserInfo["usernick"]);
        // $this->assign("createtime",date("Y-m-d H:i:s",time()));
        // // $this->assign("userimg",$UserInfo["userimg"]);
        // $this->assign("userimg","http://puui.qpic.cn/tv/0/15788736_285160/0");
        // $this->assign("userwx",$UserInfo["userwx"]);
        // $this->assign("userqq",$UserInfo["userqq"]);

       
        //设置文章填充内容
        $this->assign("content",$articlecontent);
        $this->assign("keywords",$articlekey);
        $this->assign("description",$articledesc);
        $this->assign("title",$articletitle);
        $this->assign("artilceid",$articleId);
        $this->assign("authorid",$UserInfo["userid"]);
        

        //填充之后获取整个文档内容
		//$txt =  $this->fetch('article:m/detail');
        $txt =  $this->fetch('article:m/detail3');
        
		$aimDir = 'articles/m/' . ($operateid);
        
		//创建目录
		$this->createDir($aimDir);
		
        $filePath = $aimDir . "/" .  $articleId .".html";

		//函数把一个字符串写入文件中。
		file_put_contents($filePath,$txt);
		//将文章信息存储到数据库中
        $model = D("Article");
        
        $data['article_Id'] = $articleId;//生成文章id
        $data['article_Title'] = $articletitle;
        $data['article_Author_id'] = $operateid;//生成文章作者id
        $data['article_Vice_Title'] = $articlevicetitle;//文章副标题
        $data['artilce_Group_Id'] = $articlegroupid;//文章分组id
        $data['article_Abstract'] = $articleabstract;//文章摘要
        $data['article_Keywords'] = $articlekey;//seokey
        $data['article_Descriptions'] = $articledesc;//seo描述
        $data['article_List_Img1'] = $articleimg1;//文章图片
        $data['article_List_Img2'] = $articleimg2;//文章图片
        $data['article_List_Img3'] = $articleimg3;//文章图片
        $data['article_Short_Video_Url'] = $articlevideo;//文章视频
        $data['article_Public_Channel'] = $articlepublicchannel;//文章发布渠道
        $data['artitle_Related_url'] = $articlelink;//文章原文链接
        
        try{
            $model->execute('begin');  
            $model->addArticle($data);
            $model->execute('commit');  
        }catch(Exception $e){
            $model->execute('rollback');  
            //删除文件
            unlink($filePath);
            $map["code"] = 2;
            $map["res"] = "存储文章信息错误，发表失败";
            echo json_encode($map);
            return;
        }


        $map["code"] = 1;
        $map["res"] = "发表成功";
        $map["data"] = $articleId;
        $map["path"]  = $filePath;
        echo json_encode($map);
        return;
		
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
    //单独上传图片链接
    public function uploadImgAction($operateid=0){
    	header('Content-Type:text/json; charset=utf-8');
    	if($operateid<1){
    		$map["code"] = 0;
            $map["res"] = "用户信息错误";
            
            echo json_encode($map);
    		return;
    	}
        //先判断用户是否有权限上传文章
        $UserInfo = D("UserInfo")->getBasicUserInfo($operateid);
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户不能上传图片";
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

    	//生成相应的路径
        $this->createDir('./../Uploads/' . $operateid . "/");
        
    	$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','pdf',"mp4");// 设置附件上传类型
        // $upload->rootPath  =     '/Uploads/' . $operateid ; // 设置附件上传根目录(相对磁盘目录)
         // $upload->rootPath  =     './Uploads/' . $operateid ; // 设置附件上传根目录(当前目录)
        // $upload->rootPath  =     '/phpstudy/WWW/Uploads/'  ; // 设置附件上传根目录(相对磁盘目录)
        $upload->rootPath  =     './../Uploads/'  ; // 设置附件上传根目录(相对磁盘目录)
        $upload->savePath  =     "/" . $operateid . "/"; // 设置附件上传（子）目录
        $upload->autoSub = false;//防止照片路径生成有日期
        // $upload->saveName = "自定义名字";//文件名
        
        
        // 上传文件
        $info  =  $upload->upload();
        // echo json_encode($info);
        if(!$info) {// 上传错误提示错误信息
            //dump($upload);
            //$this->error($upload->getError());

           	$map["code"] = 0;
            $map["res"] = "上传图片出错";
            
            echo json_encode($map);
            return;
        }else{// 上传成功
            foreach($info as $file){
                $bigimg = $file['savepath'].$file['savename'];
                
            }
        }
        
        //新增图片model
        $ImageModel = D("Image");
        
        try{
            $imageid= substr(strval(microtime(true)*10000) ,0,13);
            $ImageModel->execute('begin');  
            $data["user_id"] = $operateid;
            $data["image_location"] = $bigimg;
            $data["image_id"] = $imageid;
            $data["image_type"] = 10;
            //新增图片
            $result = $ImageModel->addPhoto($data);
            
            if($result!=1){
                $ImageModel->execute('rollback'); 
                $map["code"] = 0;
                $map["res"] = "上传图片失败";
                
                echo json_encode($map);
                unlink($upload->rootPath . $bigimg);
                return;
            }
            $ImageModel->execute('commit');      
        }catch(Exception $e){
            $ImageModel->execute('rollback');  
            $map["code"] = 0;
            $map["res"] = "上传图片失败";
            unlink($upload->rootPath . $bigimg);
            echo json_encode($map);
            return;
        }

    	// 取得成功上传的文件信息
        $info = $upload->upload();
        // 保存当前数据对象
        $data['goods_img'] = $bigimg;

        $map["code"] = 1;
        $map["res"] = "上传图片成功";
        $map["data"] = $bigimg;
        echo json_encode($map);
        return;
    	// $this->ajaxReturn($data);
    }


    //新增文章测试
    public function testAddArticleAction(){
    	header('Content-Type:text/json; charset=utf-8');
    	//生成文章id
		$articleId  = $time = substr(strval(microtime(true)*10000) ,0,13);
		$model = D("Article");

		//$model->addArticle($articleId);
        
		$articleId  = $time = substr(strval(microtime(true)*10000) ,0,13);
        
		$data['article_Id'] = $articleId;//生成文章id
        
		$data['article_Title'] = '中国永远是中国';
        
		$data['article_Author_id'] = $articleId;//生成文章作者id
        
		$model->data($data)->add();
        
		$this->ajaxReturn(json_encode($data));
		// dump($data);
        
    }
    //获取我的文章列表
    public function getMyArticlelistAction($userid=0,$page=1,$minid=0,$maxid=0){
        header('Content-Type:text/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        $Article = D("Article");
        $data =  $Article->getArticleByUserId($userid,$page);
        
        $map["code"] = 1;
        $map["data"] = $data;
        echo json_encode($map);
        return;
    }
    
    


    //测试查询文章
    public function testQueryArticleAction(){
        header('Content-Type:text/json; charset=utf-8');
    	$Article = D("Article");
    	
    	$data =  $Article->queryArticleList();
        


        foreach ($data as $dd){ 
            // dump($dd);
        }
    	// $jsondata = json_encode($data);

        // $obj = "{code : 0,data:". $jsondata .  "}";
        $map["code"] = 0;

        $map["data"] = $data;
    	
        echo json_encode($map);
    	// $this->ajaxReturn(json_encode($map));
    }
    //测试curl
    public function testCurlAction(){
        
        $User = A('Article','Event'); 
        $User->logout();
        
        $url = "http://localhost/article/Home/article/getArticleInfo";
        $post_data = array("username" => "bob","key" => "12345");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        $result = curl_exec($ch);
        echo $result;
        if(curl_errno($ch)){
            print_r(curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result,TRUE);
    }


    //通过article查询文章信息
    public function getArticleInfoAction($articleid=0){
        header('Content-Type:text/json; charset=utf-8');
        if($articleid==null||$articleid<10){
            $map["code"] = 0;
            $map["res"] = "articleid参数错误";
            echo json_encode($map);
            return;
        }
        $Article = D("Article")->queryArticleList($articleid);

        if($Article == null ||count($Article)!=1){
            $map["code"] = 0;
            $map["res"] = "未查找到该文章信息";
            echo json_encode($map);
            return;
        }else{

            $Article = $Article[0];
        }
        $map["code"] = 1;
        $map["data"] = $Article;
        echo json_encode($map);
    }
    //获取系统文章分类
    public function getSysArticleTypesAction($userid=0){
        header('Content-Type:text/json; charset=utf-8');
        if($userid<1){
            $map["code"] = 0;
            $map["res"] = "参数错误";
            echo json_encode($map);
            return;
        }
        $articletypes = D("ArticleType")->getArticleTypes();
        $map["code"] = 1;
        $map["data"] = $articletypes;
        echo json_encode($map);
        return;
        
    }
    //获取我的文章分组方法一
    public function getGroupAction($userid=0){
        $this->getMyArticlelistAction($userid);
    }
	//获取我的文章分组方法二
    public function getMyArticleGroupAction($userid=0){
         header('Content-Type:text/json; charset=utf-8');
        if($userid<1){
            $map["code"] = 0;
            $map["res"] = "userid参数错误";
            echo json_encode($map);
            return;
        }
        //现获取用户信息
        $UserInfo = D("UserInfo")->getBasicUserInfo($userid);
        
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户无法获取分组信息";
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
        
        //获取文章分组
        $ArticleGroup = D("ArticleGroup")->queryArticleGroup($userid);
        
        $map["code"] = 1;
        //$map["user"] = $UserInfo;
        $map["data"] = $ArticleGroup;
        echo json_encode($map);
        return;
        
    }
    //通过文章id获取文章信息
    public function getarticlebyidAction($userid=0,$articleid = 0){
        header('Content-Type:text/json; charset=utf-8');
        if($userid<1||$articleid<1){
            $map["code"] = 0;
            $map["res"] = "参数错误";
            echo json_encode($map);
            return;
        }
        //现获取用户信息
        $UserInfo = D("UserInfo")->getBasicUserInfo($userid);
        
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "改用户尚未注册";
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
        
        $result = "";
        //获取文章信息
        $model = D("Article");
        try{
            $model->execute('begin');  
            $result =  $model->getArticleInfoById($articleid);
            $model->execute('commit');  
        }catch(Exception $e){
            $model->execute('rollback');  
            $map["code"] = 2;
            $map["res"] = "获取文章信息失败";
            echo json_encode($map);
            return;
        }

        $map["code"] = 1;
        $map["res"] = "获取文章信息成功";
        $map["data"] = $result;
        echo json_encode($map);
        return;

    }
    //新增文章分组
    public function addArticleGroupAction($userid=0,$groupname = ""){
        header('Content-Type:text/json; charset=utf-8');
        if($userid==0||$groupname==""){
            $map["code"] = 0;
            $map["res"] = "参数错误";
            echo json_encode($map);
            return;
        }
        //现获取用户信息
        $UserInfo = D("UserInfo")->getBasicUserInfo($userid);
        
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户无法新增分组";
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

        //生成分组id
        $groupid  = $time = substr(strval(microtime(true)*10000) ,0,13);
        $data["user_id"] = $userid;
        $data["group_name"] = $groupname;
        $data["group_id"] = $groupid;

        //新增文章分组
        $model = D("ArticleGroup");
        try{
            $model->execute('begin');  
            $model->addArticleGroup($data);
            $model->execute('commit');  
        }catch(Exception $e){
            $model->execute('rollback');  
            $map["code"] = 2;
            $map["res"] = "新增文章分组失败";
            echo json_encode($map);
            return;
        }

        $map["code"] = 1;
        $map["res"] = "新增文章分组成功";
        //$map["data"] = $data;
        echo json_encode($map);
        return;

    }
    
    //删除文章分组
    public function deleteArticleGroupAction($userid=0,$groupid = 0){
        header('Content-Type:text/json; charset=utf-8');
        if($userid==0||$groupid<1){
            $map["code"] = 0;
            $map["res"] = "参数错误";
            echo json_encode($map);
            return;
        }
        //现获取用户信息
        $UserInfo = D("UserInfo")->getBasicUserInfo($userid);
        
        if(count($UserInfo)!=1){
            $map["code"] = 0;
            $map["res"] = "该用户无法删除分组";
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
        $data["user_id"] = $userid;
        $data["group_id"] = $groupid;

        //删除文章分组
        $model = D("ArticleGroup");
        $result= 0;
        try{
            $model->execute('begin');  
            $result= $model->deleteArticleGroup($groupid);
            $model->execute('commit');  
        }catch(Exception $e){
            $model->execute('rollback');  
            $map["code"] = 2;
            $map["res"] = "删除文章分组失败";
            echo json_encode($map);
            return;
        }
        if($result<1){
            $map["code"] = 2;
            $map["res"] = "删除文章分组失败";
            echo json_encode($map);
            return;
        }
        $map["code"] = 1;
        $map["res"] = "删除文章分组成功";
        
        echo json_encode($map);
        return;
    }

}


?>
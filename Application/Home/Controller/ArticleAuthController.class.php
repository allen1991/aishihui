<?php
namespace Home\Controller;
use Think\Controller;
/**
*文章授权中心
*/
class ArticleAuthController extends Controller{

	public  static  $appkeys = array("fs1223434"=>true);

	public function indexAction()
    {   
        header('Content-Type:text/json; charset=utf-8');
        echo "article auth center,please contact the manager via qq:389836169";
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

	//授权第三方获取文章信息
    public function getArticlesAction($appkey="",$page=1,$minid=0,$maxid=0,$timestamp=0,$group="",$terminal="web"){
        header('Content-Type:text/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        //先检查是否appkey是符合要求的
        if($this::$appkeys[$appkey]){

        }else{
            $map["code"] = 0;
            $map["res"] = "appkey校验失败";
            echo json_encode($map);
            return;
        }

        if(!is_numeric($page)||!is_numeric($maxid)||!is_numeric($minid)){
            $map["code"] = 0;
            $map["res"] = "page,maxid,minid只能是数字";
            echo json_encode($map);
            return;
        }
        if($maxid<$minid){
            $map["code"] = 0;
            $map["res"] = "maxid不能小于minid";
            echo json_encode($map);
            return;
        }

        $ArticleAuth = D("ArticleAuth");

        if($group!=""){
            $param["artilce_group_name"] = $group;
        }

        /*
        if($maxid!=0){
            $param["artilce_id"] = array('gt',$maxid);
        }
        if($minid!=0){
            $param["artilce_id"] = array('lt',$minid);
        }
        */
        //如果是最大id值和最小id值都不为0，
        if($maxid!=0&&$minid!=0){
            $min["article_id"] = array('lt',$minid);
            $max["article_id"] = array('gt',$maxid);

            $param["_complex"] =  array($max,$min,'_logic' => 'or');
            $data = $ArticleAuth->getLatestArticle($param);
            $map["code"] = 1;
            $map["data"] = $data;
            echo json_encode($map);
            return;
        }

        //如果最小值与最大值都为0的情况下，则直接获取文章
        if($minid==0&&$maxid==0){
            //$param["article_id"] =  array('gt',$maxid);
            $data = $ArticleAuth->getLatestArticle(null,$page);
            $map["code"] = 1;
            $map["data"] = $data;
            echo json_encode($map);
            return;
        }


        if($maxid!=0||$minid!=0){

            $min["article_id"] = array('lt',$minid);
            $max["article_id"] = array('gt',$maxid);

            $param["_complex"] =  array($max,$min,'_logic' => 'or');
            $data = $ArticleAuth->getLatestArticle($param);
            $map["code"] = 1;
            $map["data"] = $data;
            echo json_encode($map);
            return;
        }
         
        $map["code"] = 1;
        $map["data"] = $data;
        echo json_encode($map);
        return;
    }

    //通过分类获取文章
    public  function getArticleBygroupAction($appkey=0,$group="",$articleid=0,$page=1,$pagesize=10){

        if($this::$appkeys[$appkey]){
            
        }else{
            $map["code"] = 0;
            $map["res"] = "appkey校验失败";
            echo json_encode($map);
            return;
        }

        if($group==""){
            $map["code"] = 0;
            $map["res"] = "group参数不符合要求";
            echo json_encode($map);
            return;
        }

        if(!is_numeric($page)||!is_numeric($pagesize)||is_numeric($articleid)){
            $map["code"] = 0;
            $map["res"] = "page,pagesize,articleid只能是数字";
            echo json_encode($map);
            return;
        }
        if($pagesize>20){
            $map["code"] = 0;
            $map["res"] = "pagesize最多不能超过20";
            echo json_encode($map);
            return;
        }
        $ArticleAuth = D("ArticleAuth");
        //通过分组来获取
        $data = $ArticleAuth->getArticleByGroup($articleid,$page,$pagesize);
        $map["code"] = 1;
        $map["data"] = $data;
        echo json_encode($map);
        return;

    }   

    

    /**
    *同步文章数据
    *从数据中心同步过来
    **/
    public function synarticleDataAction(){
        header('Content-Type:text/json; charset=utf-8');
        //$data = $this::_request_post("http://localhost/article/Home/articleAuth/getArticles?appkey=fs1223434&minid=1&maxid=100");
        
        $data = $this::synArticleDataFromDataCenter("http://localhost/article/Home/articleAuth/getArticles?appkey=fs1223434&minid=1&maxid=100");
         
        try{
            //$map["code"] = $data->code;
            $data = $data->data;
            //$map["data"] = $data;
        }catch(Exception $e){
            
            $res["code"] = 0;
            $res["res"] = "获取文章参考数据失败.";
            echo json_encode($res);
            return;
        }
        $success = 0;
        $fail = 0;
        $ArticleAuth = D("ArticleAuth");
        foreach ($data as $key=>$value) {
            //echo $value->goodsid;
            $articleid = $value->articleid;
            $articlelike = $value->articlelike;
            $articledislike = $value->articledislike;
            $articlescan = $value->articlescan;
            
            //更新文章统计信息
            if($ArticleAuth->updateArticleAnalysis($articleid,$articlelike,$articledislike,$articlescan)){
            //if($ArticleAuth->test($articleid,$articledislike)){
                $success++;
            }else{
                $fail++;
            }
        }
        
        $map["code"] = 1;
        $map["res"] = $success."更新成功;" . $fail ."更新失败";
        echo json_encode($map);
        return;

    }
    private function synArticleDataFromDataCenter($url){
        $data = $this::_request_post($url);

        return $data;
    }
    //post方法封装
    private function _request_post($url="http://www.aishihuixiaogou.com/ManagerCenter/goods/querylist?operatorid=1001&goodsstock=10000"){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息不作为数据流输出(不输出头部信息)
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置时间
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        
        curl_setopt($curl, CURLINFO_HEADER_OUT,1);//启用时追踪句柄的请求字符串。
        
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36");
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);

        $header[0] =  "Content-Type:application/x-www-form-urlencoded; charset=UTF-8";

        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);//在头部带信息

        //$info  = curl_getinfo( $curl );//获取头部信息
        //echo json_encode($info);

        //设置post数据
        /*$post_data = array(
            "appkey" => "fs1223434",
            "appsecret" => "123456"
        );*/
        
        $post_data = array(
            "operatorid" => "1001",
            "goodsstock" => "10000"
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        //执行命令
        $data = curl_exec($curl);
        $curl_errno = curl_errno($curl);  
        $curl_error = curl_error($curl);

        //echo $curl_errno."<br>";
        //echo $curl_error."<br>";
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //print_r($data);
        //echo $data;
        return json_decode($data);

    }
    //get方法封装
    private function _request_get($url="http://www.aishihuixiaogou.com/ManagerCenter/goods/querylist?operatorid=1001&goodsstock=10000"){
        header('Content-Type:text/json; charset=utf-8');
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //print_r($data);
        return json_decode($data);
    }

    
}

?>
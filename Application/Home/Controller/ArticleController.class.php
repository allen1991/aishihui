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
}


?>
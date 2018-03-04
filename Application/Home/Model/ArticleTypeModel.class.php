<?php
namespace Home\Model;
use Think\Model;

/*
*文章系统分类数据库操作
*/
class ArticleTypeModel extends Model{
	
	protected $autoCheckFields = false;
	protected $trueTableName = 'j_article_type_info';

	protected $_map = array(
        'typeid' =>'article_type_id', 
        'typename'  =>'article_type_name', 
    );


	//获取文章分类
	public function getArticleTypes(){
		return $this->field("article_type_id,article_type_name")->select();
	}
	

}

?>
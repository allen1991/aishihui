<?php   
namespace Home\Pojo;

class Article {
	
	public $articleId=0;      //文章id
	public $artilceTitle = ""; //文章标题
	public $articleAuthorId = 0; //文章作者id
	public $articleAuthorName = ""; //文章作者姓名
	public $articleKeywords = "";//文章搜索keywords
	public $articleDescription = "";//文章搜索描述
	public $articleListImg1 = ""; //文章显示图片1（在文章列表里面显示）
	public $articleListImg2 = "";
	public $articleListImg3 = "";
	public $articleContentUrl = "";//文章内容存放地址
	public $articleContent="";//文章内容
	
}


?>
<?php 
namespace Home\Model;
use Think\Model;
//文章分组model
class ArticleGroupModel extends Model{
	protected $autoCheckFields = false;
	//数据库表前缀
	protected $tablePrefix = "j_";
	//数据库表名
	protected $tableName = "article_group";
	protected $trueTableName = 'j_article_group'; 

	protected $_map = array(
        'userid' =>'user_id', 
        'groupid'  =>'group_id', 
        'groupname' =>'group_name',
    );
	//数据库表字段(这里写了字段之后就可以不依赖字段缓存，可以减少io加载开销)
	//protected $fields = array("id");
	//查询数据库
	public function queryArticleGroup($userid){
		
		$map["user_id"] = $userid;
		
		return $this->where($map)->select();
	}
	
	//新增文章分组
	public function addArticleGroup($data){
		$this->create($data);
		return $this->data($data)->add();
	}
	//删除文章分组
    public function deleteArticleGroup($groupid=0){
        
        if($groupid>0){
            $map["group_id"] = $groupid;
            return $this->where($map)->delete();
        }
        
        return -1;
    }
}

?>
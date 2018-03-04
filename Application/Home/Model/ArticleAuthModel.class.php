<?php 
namespace Home\Model;
use Think\Model;

/*
    文章授权中心
*/
class ArticleAuthModel extends Model{
	
	protected $trueTableName = 'j_article_info'; 
	//系统支持数据的批量验证功能，只需要在模型类里面设置patchValidate属性为true（ 默认为false );
	protected $patchValidate = true;
	//验证规则
	protected $_validate = array( 
		array('article_Id',"number",'数据类型不对',self::MUST_VALIDATE)
	);
	
	protected $_map = array(
        'articleid' =>'article_id', 
        'articletitle'  =>'article_title', 
        'articleauthorid' =>'article_author_id', 
        'articleauthorname' => 'article_author_name',
        'articlekeywords'=>'article_keywords',
        'articledesc' => 'article_descriptions',
        'articleimg1' => 'article_list_img1',
        'articleimg2' => 'article_list_img2',
        'articleimg3' => 'article_list_img3',
        'articlecontenturl' => 'article_content_url',
        'articlecontent' => 'article_content',
        'articlequerykey1' => 'article_query_key1',
        'articlequerykey2' => 'article_query_key2',
        'articlequerykey3' => 'article_query_key3',
        'articlevicetitle' => 'article_vice_title',
        'articlescan' => 'article_scan',
        'articlelike' => 'article_like',
        'articledislike' => 'article_dislike',
        'articlecomments' => 'articel_comments',
        'articlejs' => 'article_auto_load_js',
        'articlehtml' => 'article_auto_load_html_module',
        'articlestate' => 'article_state',
        'articlecollect' => 'article_collect',
        'articleadveimg' => 'article_manager_adver_img',
        'articleadvejumpurl' => 'article_manager_adver_jump_url',
        'articletaobao' => 'article_related_taobao',
        'authorsharetimes' => 'article_share_times',
        'authorshareinfo' => 'article_share_author_info',
        'shortvideourl' => 'article_short_video_url',
        'authorinfoopen' => 'article_author_info_open',
        'authorinfoisopen' => 'article_is_open',
        'articlescanmarks' => 'article_scan_marks',
        'articleclass' => 'article_class',
        'articleclassname' => 'article_class_name',
        'articleseries' => 'article_individual_series',
        'articleseriesname' => 'article_individual_series_name',
        'forbiddenreason' => 'article_forbidden_reason',
        'isorigin' => 'article_is_original',
        'sourcefrom' => 'article_source_from',
        'articlestars' => 'article_stars',
        'articleshare' => 'article_share',
        'articleiscountry' => 'article_is_country',
        'articleprovince' => 'article_province',
        'articlecity' => 'article_city',
        'articlecounty' => 'article_county',
        'articlerecommendnotes' => 'article_recommend_notes',
        'articlerecommendtitle' => 'article_recommend_title',
        'artilcerelatedlink' => 'artitle_related_url',
        'articlecanbecomment' => 'article_canbe_comment',
        'articleauditid' => ' article_auditor_id',
        'articlegroupid' => 'artilce_group_id',
        'articlegroupname' => 'artilce_group_name',
        'articleabstract' => 'article_abstract',
        'articlepublicchannel' => 'article_public_channel',
        'artilcetype1' => 'articletype1',
        'artilcetype2' => 'articletype2',
        'artilcetype3' => 'articletype3'
    );
    

    //通过用户id查询查询文章列表
    public function getArticleByUserId($userid=0,$page=1,$pagesize=10){
        
        if($userid == 1000){
            $pageStr = $page .',' . $pagesize ;
            return $this->page($pageStr)->select();
        }
        
        if($userid){
            $pageStr = $page .',' . $pagesize ;
            $map["article_author_id"] = $userid;
            return $this->page($pageStr)->where($map)->select();
        }
        //return $this->select();
        
        return $this->select();
    }
    
    //按时间戳顺序查询最新文章
    public function getLatestArticle($map,$page=1,$pagesize=10){
        
        $pageStr = $page .',' . $pagesize ;
        return $this->page($pageStr)->where($map)->order("article_id desc")->select();
    }
    //通过分组获取文章
    public function getArticleByGroup($article=0,$page=1,$pagesize=10){
        
        if($article!=0){
            $map["article_id"] = $article;
        }
        $pageStr = $page .',' . $pagesize ;
        return $this->page($pageStr)->where($map)->order("article_id desc")->select();
    }

    //更新文章分析统计数据
    public function updateArticleAnalysis($articleid=0,$articlelike=0,$articledislike=0,$articlescan=0){
        /*
        $data["article_scan"] = $articlescan;
        $data["article_like"] = $articlelike;
        $data["article_dislike"] = $articledislike;
        */

        $data["article_dislike"] = array('exp','article_dislike+' .$articledislike);
        $data["article_like"] = array('exp','article_like+' .$articlelike);
        $data["article_scan"] = array('exp','article_scan+' .$articlescan);
        $data["article_id"] = $articleid;

        $condition["article_id"] = $articleid;

        $res =  $this->field("article_dislike,article_like,article_scan")->where($condition)->save($data);

        return $res;
    }

    //更新文章分析统计数据
    public function test($articleid=0,$articledislike=0){

        
        $data["article_dislike"] = array('exp','article_dislike+' .$articledislike);

        //$data["article_dislike"] = $articledislike;
        $data["article_id"] = $articleid;

        $condition["article_id"] = $articleid;

        return $this->field("article_dislike")->where($condition)->save($data);
    }

}



?>
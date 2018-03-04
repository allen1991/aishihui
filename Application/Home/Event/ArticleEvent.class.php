<?php
namespace Home\Event;

/**
*文章event
*/
class ArticleEvent {
    public function login(){
        echo 'login article event';
    }
    public function logout(){
        echo 'logout article event';
    }
}

?>
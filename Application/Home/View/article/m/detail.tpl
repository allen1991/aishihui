<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{$title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=8">
        <meta http-equiv="Cache-control" content="max-age=3600">
        <meta name="format-detection" content="telephone=no, email=no">
        <meta name="keywords" content="{$keywords}" />
        <meta name="description" content="{$description}" />

        <meta name=viewport content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
        
        <link rel="stylesheet" type="text/css" href="./../css/common.css">
        <link rel="stylesheet" type="text/css" href="./../css/adetail.css">
        <script type="text/javascript">
            document.documentElement.style.fontSize = document.documentElement.clientWidth/16 + "px";
        </script>
        <script type="text/javascript" src="./../js/m/jquery.js"></script>
        <script type="text/javascript" src="./../js/m/detail.js"></script>
    </head>
    <body>
        
        <div class="box-wrap">
            <div class="header">
                <div class="title">
                    <span>{$title}</span>
                </div>
                <div class="title-desc">
                    <span id="nickname"></span>
                    <span id="publictime"></span>
                    <span class="activered">{$platform?$platform:"综合平台"}</span>
                </div>
                <div class="title-label">
                    <span class="focus">
                        关注
                    </span>
                    <span class="author-img">
                        <img  src="http://puui.qpic.cn/tv/0/15788736_285160/0" width="100%" height="100%" id="authorimg">
                        <div class="focus-author activered">点击查看--></div>
                    </span>
                    
                </div>
            </div>
            <div class="content-area">
                {$content}
            </div>
            <div class="article-claim">
                该文章为原著者观点，如果该文章有违反行为，请及时反馈到官方处理。谢谢。
            </div>
            <div class="article-data">
                <span><a>501</a>阅读</span>
                <span><a>154</a>赞</span>
                <span><a>5</a>踩</span>
                <span>我要评论</span>
                <span class="activered">我要举报</span>
            </div>

            <div class="author-area">
                <div class="author-claim activered"> 
                    <span>作者空间</span>
                    <span>(该作者已XX网认证，XX网建议与作者私下交谈请不要时刻保持警惕，注意自己人生财产安全)</span>
                </div>
                <div class="author-img-wrap">
                    <ul>
                        <li>
                            <span>
                                <img src="./../images/jackma2.jpg" width="100%" height="100%">
                            </span>
                        </li>
                        <li>
                            <span>
                                <img src="./../images/jackma2.jpg" width="100%" height="100%">
                            </span>
                        </li>
                        <li>
                            <span>
                                <img src="./../images/jackma2.jpg" width="100%" height="100%">
                            </span>
                        </li>
                        <li>
                            <span>
                                <img src="./../images/jackma2.jpg" width="100%" height="100%">
                            </span>
                        </li>
                        <li>
                            <span>
                                <img src="./../images/jackma2.jpg" width="100%" height="100%">
                            </span>
                        </li>
                        
                    </ul>
                </div>
                <div class="author-open-info">
                    <div>作者<span class="activered padding02" id="nickname2">{$nickname}</span>已公开个人信息。（xx网提醒，读者谨慎添加已公开个人信息账号，注意保障个人人身财产安全。）</div>
                    <span>一骑红尘妃子笑</span>
                    <span>微信:</span><span id="userwx"></span>
                    <span>qq:</span><span id="userqq"></span>
                </div>  
                <div class="animate-area">
                    动画广告区域
                </div>
            </div>

        </div>
        <!-- 文章信息和用户信息隐藏 -->
        <div style="display:none">
            <input id="authorid" value="{$authorid}">
            <input id="articleid" value="{$articleid}">
        </div>
    </body>
</html>


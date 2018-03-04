<?php
return array(
	//'配置项'=>'配置值'
	'ACTION_SUFFIX' => "Action",//设置该模块下请求方法的后缀
	/*数据库配置*/
	
    'DB_TYPE'                => 'mysql', // 数据库类型
    // 'DB_HOST'                => '123.207.39.250', // 服务器地址
    'DB_HOST'                => 'localhost', // 服务器地址
    'DB_NAME'                => 'jp', // 数据库名
    'DB_USER'                => 'root', // 用户名
    'DB_PWD'                 => 'root', // 本地密码
    //'DB_PWD'                 => 'szLH199154', // 密码 root
    'DB_PORT'                => '3306', // 端口
    //'DB_PREFIX'              => 'j_', // 数据库表前缀
    //'DB_DSN'                 =>'mysql://root:199154@172.22.250.4:3306/juzhi#utf-8'
    
    
    /*
    //数据库配置1
    'DB_CONFIG1' =>array(
        'DB_TYPE' => 'mysql',
        'DB_USER' => 'root',
        'DB_PWD'  =>'199154',
        'DB_HOST' => '172.22.250.4',
        'DB_PORT' => '3306',
        'DB_NAME' => 'juzhi',
        'db_charset' => 'utf8',
    ),
     
    //数据库配置2
    'DB_CONFIG2' => 'mysql://root:root@localhost:3306/juzhi#utf8',
    
    'ERROR_PAGE'       => 'http://localhost/' ,
    'URL_404_REDIRECT'       => 'http://localhost/' 
      
    */
     "READ_DATA_MAP" => true, //自动开启
);
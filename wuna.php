<?php 
header('Content-type: application/json');  

//_POST只能接收Content-Type: application/x-www-form-urlencoded提交的数据
//接收金融产品名称
$title = @$_POST['title'];//名称
$successrate = @$_POST['successrate'];//成功率
$limit = @$_POST['limit'];//额度
$desc = @$_POST['desc'];//描述
$intro = @$_POST['intro'];//介绍
$href = @$_POST['href'];//点击跳转链接
$action = @$_POST['action'];//触发的动作,查询，禁止，开启
$id = @$_POST['id'];//产品id
$img = @$_POST['img'];//图片
$backend = @$_POST['backend'];//前段还是管理端
$page = @$_POST['page'];//页数

//查询
if($action == "query"){
	$con=mysql_connect("localhost","root","szLH199154") or die("Unable to connect to the MySQL!");
	mysql_select_db("wuna", $con);
	if($backend){
		//执行语句
		$result=mysql_query("select * from wuna_products limit ".(($page-1)*5) .",5" );
	}else{
		//执行语句
		$result=mysql_query("select * from wuna_products where state = 1 limit ".(($page-1)*5) .",5");
	}
	
	
	$arr = [];
	while($row = mysql_fetch_array($result)){

	  //echo $row['productid'] . " " . $row['productname'] . " ".$row['productlimit'];
	  array_push($arr,$row);
	  

	}
	$map["code"] = 1;
	$map["data"] = $arr;
	echo json_encode($map);
	mysql_close($con);
	return;
}


if($action=="insert"){
	if($title==""){
		$map["code"] = 0;
		$map["res"] = "产品名称参数错误";
		echo json_encode($map);
		return ;
	}
	if($successrate==""){
		$map["code"] = 0;
		$map["res"] = "成功率参数错误";
		echo json_encode($map);
		return ;
	}
	
	if($limit==""){
		$map["code"] = 0;
		$map["res"] = "额度参数错误";
		echo json_encode($map);
		return ;
	}
	$imgurl = "";
	if($img==""){
		$map["code"] = 0;
		$map["res"] = "图片参数错误";
		echo json_encode($map);
		return ;
	}else{
		
		//正则匹配
		if(preg_match("/^(data:\s*image\/(\w+);base64,)/", $img,$result)){
			$path="./Uploads/";
			$type = $result[2];
			$time = time();
			$new_file = $path.$time.".jpg";
			$b64 =  base64_decode(str_replace($result[1], '',$img));
			
			if(file_put_contents($new_file,$b64)){
				
				$imgurl = "/Uploads/".$time.".jpg";
			}else{
				$map["code"] = 0;
				$map["res"] = "图片上传失败";
				echo json_encode($map);
				return;
			}
		}else{
			$map["code"] = 0;
			$map["res"] = "图片base64的解析正则错误";
			echo json_encode($map);
			return; 
		}
		

	}

	if($desc==""){
		$map["code"] = 0;
		$map["res"] = "描述参数错误";
		echo json_encode($map);
		return ;
	}
	if($intro==""){
		$map["code"] = 0;
		$map["res"] = "介绍参数错误";
		echo json_encode($map);
		return ;
	}
	if($href==""){
		$map["code"] = 0;
		$map["res"] = "跳转路径参数错误";
		echo json_encode($map);
		return ;
	}
	$con=mysql_connect("localhost","root","szLH199154") or die("Unable to connect to the MySQL!");
	mysql_select_db("wuna", $con);

	$productid = time();
	//执行语句
	$sql = " insert into wuna_products (productid,productname,productlimit,sucrate,productdesc,intro,producturl,img,state) values (".$productid .",'" . $title."',".$limit.",". $successrate.",'". $desc ."','".$intro."','".$href."','".$imgurl."',1)" ;
	
	$result=mysql_query($sql);
	
	if($result!=1){
		$map["code"] = 0;
		$map["res"] = "新增失败";
		echo json_encode($map);
		mysql_close($con);
		return ;
	}else{
		$map["code"] = 1;
		$map["res"] = "新增成功";
		
echo json_encode($map);
		
mysql_close($con);
		return ;
	}

}

if($action=="on"){
	if($id==""){
		$map["code"] = 0;
		$map["res"] = "id参数错误";
		echo json_encode($map);
		return ;
	}
	$con=mysql_connect("localhost","root","szLH199154") or die("Unable to connect to the MySQL!");
	mysql_select_db("wuna", $con);
	//执行语句(数据库表产品id自增长)
	$sql = "update wuna_products set state = 1 where productid=".$id  ;
	$result=mysql_query($sql);
	
	if($result!=1){
		$map["code"] = 0;
		$map["res"] = "修改状态失败";
		echo json_encode($map);
		mysql_close($con);
		return ;
	}else{
		$map["code"] = 1;
		$map["res"] = "修改状态成功";
		echo json_encode($map);
		mysql_close($con);
		return ;
	}
	
	
}

if($action=="off"){
	if($id==""){
		$map["code"] = 0;
		$map["res"] = "id参数错误";
		echo json_encode($map);
		return ;
	}
	$con=mysql_connect("localhost","root","szLH199154") or die("Unable to connect to the MySQL!");
	mysql_select_db("wuna", $con);
	//执行语句(数据库表产品id自增长)
	$sql = "update wuna_products set state = 0 where productid=".$id  ;
	$result=mysql_query($sql);
	
	if($result!=1){
		$map["code"] = 0;
		$map["res"] = "修改状态失败";
		echo json_encode($map);
		mysql_close($con);
		return ;
	}else{
		$map["code"] = 1;
		$map["res"] = "修改状态成功";
		echo json_encode($map);
		mysql_close($con);
		return ;
	}
}

$map["code"] = 0;
$map["res"] = "action参数错误";
echo json_encode($map);
return;

?>

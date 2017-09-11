<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>user</title>
<link href="./css/wangEditor.min.css" rel="stylesheet" type="text/css">

</head>
<style type="text/css">
html,body{
    padding:0px;
    margin: 0px;
}
body{
    padding: 10px 10px 0px 10px;
}
.wrap{
    border: 1px #d8d8d8 solid;
    height: 250px;   
}
</style>
<body>
    <div id="wang" class="wrap" >
        
        <p>{$name}</p>
        <p>{$email}</p>
        <p>{$phone}</p>
        <p><literal>{$phone}</literal></p>
        <p>{$THINK_PATH}</p>
        <p>{$maxSize}</p>
        {$Think.server.script_name} 
        {$Think.const.MODULE_NAME}
        {9+1}
        <input id="name" placeholder="please fill out">
    </div>
    <script type="text/javascript" src={$script}></script>
    <script type="text/javascript">
         $("#name").val("fuck");
    </script>
</body>
</html>

/*计算统计js*/

//自定义ajax请求方法
var lhfig = {
	//自定义的ajax
	ajax: function(options,sucfn,errFn){
		var options = options || {};
		//生成xhr对象
		function createXhr(){
			if(typeof XMLHttpRequest != 'undefined'){
				var xhr = new XMLHttpRequest();
				return xhr;
			}else if(typeof ActiveXObject != 'undefined'){
				// var versions = ['MSXML2.XMLHttp.6.0','MSXML2.XMLHttp.3.0','MSXML2.XMLHttp'];
				var versions = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.5.0", "MSXML2.XMLHttp.4.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp", "Microsoft.XMLHttp"];
				for (var i = 0; i < versions.length; i ++) {
					try {
						return new ActiveXObject(version[i]);
					} catch (e) {
						//跳过
					}
				}
			} else {
				throw new Error('您的浏览器不支持XHR对象！');
			}
		}
		var xhr = new createXhr();
		xhr.open(options.type ||'get', options.url, options.async==false?false:true); //设置了异步
		xhr.setRequestHeader("Content-Type", options["contentType"] || "application/x-www-form-urlencoded");

		xhr.send(options.data);
		if (xhr.status == 200&&xhr.readyState == 4) { //如果返回成功了
			var data = xhr.responseText;
			if(options.dataType=="json"||options.dataType=="JSON"){
				data = eval(data);
			}else if (dataType == "xml" || dataType == "XML") {
                //接收xml文档    
               data =  xhr.responseXML;
            }

			if(Object.prototype.toString.call(sucfn)=="function"){
				sucfn(data);
			}
		} else {
			if(Object.prototype.toString.call(errFn)=="function"){
				errFn(xhr.statusText);
			}
			
		}

	}
}


(function(_dataAnalytic){
	var _dataAnalytic = _dataAnalytic || {};
	var analytics = false;//是否统计分析标志
	var analyticsUrl = "http://www.cnblogs.com/hubgit/p/6178311.html?";//统计接口,后面需接?符号
	var params = {};
	//获取主域
	var host = window.location.host;
	var searchParams = GetRequest();
	
	// //通过正则获取search参数
	// function getQueryString(name) {  
 //        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
 //        var r = window.location.search.substr(1).match(reg);  
 //        if (r != null) return unescape(r[2]);  
 //        return null;  
 //    }  
    //通过split获取search参数
    function GetRequest() {   
	   	var url = location.search; //获取url中"?"符后的字串
	   	var theRequest = new Object();   
	   	if (url.indexOf("?") != -1) {   
	      	var str = url.substr(1);   
	      	strs = str.split("&");   
	      	for(var i = 0; i < strs.length; i ++) {   
	         	theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);   
	      	}   
	   	}   
	   	return theRequest;   
	}

	//在获取当前时间戳(毫秒级别)
	var timestamp = Date.parse(new Date());
	var _dataAnalyticTime = 0; 
	if(localStorage){
		_dataAnalyticTime = localStorage.getItem("_dataAnalyticTime"+host);

		//如果10分钟之内重新点击进入，统计将不做计算
		if(timestamp-_dataAnalyticTime<10*60*1000){
			return false;
		}else{
			localStorage.removeItem("_dataAnalyticTime"+host);
		}
	}

	//当时间和分析正确情况下把可以分析统计标志修改为true
	analytics = true;

	//Document对象数据
    if(document) {
        params.domain = document.domain || ''; 
        params.url = document.URL || ''; 
        params.title = document.title || ''; 
        params.referrer = document.referrer || ''; 
    }   
    //Window对象数据
    if(window && window.screen) {
        params.sh = window.screen.height || 0;
        params.sw = window.screen.width || 0;
        params.cd = window.screen.colorDepth || 0;
    }   
    //navigator对象数据
    if(navigator) {
        params.lang = navigator.language || ''; 
    }   

    //将_dataAnalytic属性赋值给param
    if(Object.prototype.toString.call(_dataAnalytic)=="[object Object]"){
    	for(var i in _dataAnalytic){
    		params[i] = _dataAnalytic[i];
    	}
    }
    //留下当前页面进入的时间戳
    params.timestamp = timestamp;
	//拼接参数串
    var args = ''; 
    for(var i in params) {
        if(args != '') {
            args += '&';
        }   
        args += i + '=' + encodeURIComponent(params[i]);
    }   

    //只有支持localStorage,并且在页面停留1秒钟之后,才能进入统计表
    if(localStorage){
    	var timoeout = setTimeout(function(){
    		//只有页面分析统计标志允许情况下才能发送统计标志
    		if(analytics){
    			var img = new Image(1, 1); 
				img.src = analyticsUrl + args;
				localStorage.setItem("_dataAnalyticTime"+host,timestamp);
    		}
    		
    	},10000);
    	//页面离开的方法
    	window.onbeforeunload = function(){
    		if(timeout){
    			analytics = false;
    			window.clearTimeout(timeout);
    		}
		}
    }else{
    	console.log("无法统计该页面");
    }
	
	
})(_dataAnalytic);


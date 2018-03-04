

(function  (factory,global) {
	if(typeof factory == "function"){
		factory();
		//amd模式
		if(typeof define == "function" && define.amd){
			define([],factory);
			return;
		}
		// cmd模式
		if(typeof exports === 'object'){
			module.exports = factory;
			return;
		}
		window.factory = factory;
	}else{
		console.error("factory不是方法");
	}
})(function(){
	var locationurl = window.location.href;
	var getuserinfo = "http://www.hao1170.com/article/Home/user/getUserInfo";
	var getarticleinfo = "http://www.hao1170.com/article/Home/article/getArticleInfo";
	if(locationurl.indexOf("localhost")>-1){
		getuserinfo = "/article/Home/user/getUserInfo";
		getarticleinfo = "/article/Home/article/getArticleInfo";
	}
	//定义请求方法
	var request = (function(){
		
		return {
			GETUSERINFO : getuserinfo,
			GETARTICLEINFO : getarticleinfo,
			//普通的ajax请求
			regularRequest(options,sucfn,errFn){
				if(typeof options.data == "object" ){
					//options.data.operatorid = this.operatorid;
				}
				$.ajax({
					url : options.url,
					type : "post",
					timeout :options.time || 12000,//设置超时时间为12s
			      	data : options.data,  
			        dataType: "json",
			      	success : function(res){
			      		sucfn(res);
			      	},
			      	complete : function(info){
			      		console.log("info:",info);
			      	},
			      	error : function(err){
			      		errFn(err);
			      	}
				});
			},
			
			//application/json请求
			applicationRequest(options,sucfn,errFn){
				if(typeof options.data == "object" ){
					//options.data.operatorid = this.operatorid;
				}
				$.ajax({
					url : options.url,
					type : "post",
					timeout :options.time || 12000,//设置超时时间为12s
		        	data : JSON.stringify(options.data),  
		        	contentType : options.contentType || 'application/json;charset=utf-8',
		            dataType: "json", 
		        	success : function(res){
		        		sucfn(res);
		        	},
		        	complete : function(info){
			      		console.log("info:",info);
			      	},
		        	error : function(err){
		        		errFn(err);
		        	}
				});
			},
		}
	}());
	
	//请求信息
	var InfoModule =  (function(request){
		return {
			//获取用户信息
			getUserInfo : function(userid){
				var option = {}
				option.url = request.GETUSERINFO;
				option.data = {
					userid : userid
				}
				request.regularRequest(option,function(res){
					console.log("res",res);
					var data = res.data;
					if(res.code==1){
						$("#nickname").text(data.usernick);
						$("#userqq").text(data.userqq);
						$("#userwx").text(data.userwx);
						$("#nickname2").text(data.usernick);
						$("#authorimg").attr("src",data.userimg||"http://puui.qpic.cn/tv/0/15788736_285160/0");
						
					}
				},function(err){
					console.log("err",err);
				});
			},
			//获取文章信息
			getArticleInfo : function(articleid){
				var option = {}
				option.url = request.GETARTICLEINFO;
				option.data = {
					articleid : articleid
				}
				request.regularRequest(option,function(res){
					console.log("res",res);
					var data = res.data;
					if(res.code==1){
						
					}
				},function(err){
					console.log("err",err);
				});
			}
		}

	}(request));		
	InfoModule.getUserInfo(1506684423);//获取作者信息
	InfoModule.getArticleInfo(1506750143870);//获取文章信息

},window||null);
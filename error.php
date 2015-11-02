<?php
	include_once("func.php");
	function errorPage($title,$content){
		loadHeader($title);
		echo '
		<h1 style="text-align:center;">'.$title.'</h1>
		';
		echo '
		<p>'.$content.'</p>
		<a onclick="window.history.go(-1)">返回上一页</a>
		';
		loadFooter();
	}
	if(isset($_GET['errorcode'])){
		$result=$_GET['errorcode'];
		if($result=='1')errorPage("发帖错误！","数据库处理请求时出错，帖子未能发表。。。");
	}
?>
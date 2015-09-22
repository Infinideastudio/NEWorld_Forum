<?php
$con=0;
function loadHeader($title="NEWorld Forum"){
	echo '<!DOCTYPE html>
	<html lang="cn">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv=”content-type” content=”text/html;charset=UTF-8″ />
		<meta name="author" content="Null, qiaozhanrong" />
		<meta name="keywords" content="NEWorld, Forum" />
		<meta name="description" content="NEWorld Forum" />
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<title>'.$title.'</title>
	</head>
	<body>
	<div id="header">
		<div style="margin:0 20%">
			<h1 class="nmp" style="color:#ffffff;float:left;">NEWorld Forum</h1>
			&nbsp;Alpha 0.3.1
			<div id="navi">
				<div class="item' . ($_SERVER['REQUEST_URI']=="/index.php"?"_selected":"") . '" onclick="window.open(\'index.php\',\'_self\')">论坛首页</div>
				<div class="item' . ($_SERVER['REQUEST_URI']=="/login.php"?"_selected":"") . '" onclick="window.open(\'login.php\',\'_self\')">登录</div>
				<div class="item" onclick="window.open(\'http://neblog.newinfinideas.com/admin/register.php\',\'_self\')">注册</div>
				<div class="item" onclick="window.open(\'http://www.newinfinideas.com\',\'_self\')">工作室官网</div>
				<div class="item" onclick="window.open(\'http://neblog.newinfinideas.com\',\'_self\')">BLOG</div>
			</div>
		</div>
	</div>
	<div id="main">';
}
function loadFooter(){
	echo '</div>
	<div id="footer">
	 CopyLEFT &copy; Infinideas 新创无际
	</div>
	<div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script></div>
	</body>
	</html>';
}
function ConnectDb(){
	Global $con;
 $con = mysql_connect("10.4.26.93","umwL1o3zEYZqG","pmbY5i0FKqqEX");
	mysql_select_db("d4e0d8a0deecf44fbb10e95159892f968",$con);
}
function DisconnectDb(){
	Global $con;
	mysql_close($con);
}
function filter($str, $canUseSometime){
	$ret=$str;
	if($canUseSometime){
		$usehtmltag=false;
		if(substr($ret,0,12)=="USE HTML TAG"){
			$ret=substr($ret,12);
			$usehtmltag=true;
		}
		else{
			$ret=htmlspecialchars($ret);
		}
		if(substr($ret,0,11)=="USE ALL TAG"){
			$ret=substr($ret,11);
		}
		else{
			if($usehtmltag){
				$ret=str_replace("script","",$ret);
				$ret=str_replace("iframe","",$ret);
				$ret=str_replace("style","",$ret);
				$ret=str_replace("<!--","",$ret);
			}
		}
	}
	else{
		$ret=htmlspecialchars($ret);
	}
	return $ret;
}

//如果登录了，返回用户名，如果没有登录，返回""
function getUsername(){
	if(!isset($_COOKIE["islogin"])||$_COOKIE["islogin"]==0){
		return "";
	}else{
		return $_COOKIE["username"];
	}
}

?>
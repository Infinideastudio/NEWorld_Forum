<?php
$con=0;
function loadHeader($title="The Forum of NEWorld"){
	echo '
	<!DOCTYPE html>
	<html lang="cn">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Null,qiaozhanrong" />
		<meta name="keywords" content="NEWorld,Forum" />
		<meta name="description" content="The Forum of NEWorld" />
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>'.$title.'</title>
	</head>
	<body>
	';
}
function loadFooter(){
	echo '
	<div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script></div>
	</body>
	</html>
	';
}
function ConnectDb(){
	Global $con;
	$con = mysql_connect("10.4.26.93","umwL1o3zEYZqG","pmbY5i0FKqqEX");
	mysql_select_db("d4e0d8a0deecf44fbb10e95159892f968", $con);

}
function DisconnectDb(){
	Global $con;
	mysql_close($con);
}
function filter($str, $canUseSometime){
	$ret=str_replace($ret, "<", "[");
	$ret=htmlspecialchars($str);
	$ret=str_replace($ret, "<", "[");
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
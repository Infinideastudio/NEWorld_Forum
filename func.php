﻿<?php
$con=0;

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
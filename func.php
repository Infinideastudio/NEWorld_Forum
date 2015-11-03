<?php
$con=0;
$mysqlHost=getenv("MOPAAS_MYSQL29074_HOST");
$mysqlUsername=getenv("MOPAAS_MYSQL29074_USERNAME");
$mysqlPassword=getenv("MOPAAS_MYSQL29074_PASSWORD");
$mysqlDBName=getenv("MOPAAS_MYSQL29074_NAME");

$verifyHost="http://infusers.sturgeon.mopaas.com/system/verify.php";
$registerHost="http://infusers.sturgeon.mopaas.com/register.php";

session_start();
if(!isset($_SESSION["key"])) $_SESSION["key"]=time()%1024+rand()*10;

function loadHeader($title="NEWorld Forum"){
	global $registerHost;
	echo '<!DOCTYPE html>
	<html lang="cn">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv=”content-type” content=”text/html;charset=UTF-8″ />
		<meta name="author" content="Null, qiaozhanrong" />
		<meta name="keywords" content="NEWorld, Forum" />
		<meta name="description" content="NEWorld Forum" />
		<link rel="stylesheet" type="text/css" href="'.(isset($_COOKIE["flat"])&&$_COOKIE["flat"]=="1"?"flat":"styles").'.css" />
   <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">
   <script src="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js"></script>
   <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<script type="text/javascript" src="func.js"></script>
		<link rel="shortcut icon" type="image/ico" href="/favicon.ico"/>
		<title>'.$title.'</title>
	</head>
	<body>
<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title">NEWorldForum</span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
      <!-- Navigation. We hide it in small screens. -->
      <nav class="mdl-navigation mdl-layout--large-screen-only">
<a class="mdl-navigation__link" onclick="window.open(\'index.php\',\'_self\')">首页</a>
				<a class="mdl-navigation__link" onclick="window.open(\'login.php\',\'_self\')">登录</a>
				<a class="mdl-navigation__link" onclick="window.open(\''.$registerHost.'\',\'_self\')">注册</a>
				<a class="mdl-navigation__link" onclick="window.open(\'http://www.newinfinideas.com\',\'_self\')">工作室官网</a>
				<a class="mdl-navigation__link" onclick="window.open(\'http://neblog.newinfinideas.com\',\'_self\')">BLOG</a>
      </nav>
    </div>
  </header>
  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">Title</span>
    <nav class="mdl-navigation">
  <a class="mdl-navigation__link" onclick="window.open(\'index.php\',\'_self\')">首页</a>
				<a class="mdl-navigation__link" onclick="window.open(\'login.php\',\'_self\')">登录</a>
				<a class="mdl-navigation__link" onclick="window.open(\''.$registerHost.'\',\'_self\')">注册</a>
				<a class="mdl-navigation__link" onclick="window.open(\'http://www.newinfinideas.com\',\'_self\')">工作室官网</a>
				<a class="mdl-navigation__link" onclick="window.open(\'http://neblog.newinfinideas.com\',\'_self\')">BLOG</a>
    </nav>
  </div>
  <main class="mdl-layout__content">
    <div class="page-content"><!-- Your content goes here -->';
}
function loadFooter(){
	echo '
	<div id="footer">
		新创无际 Infinideas &copy; 2015
	</div></div>
  </main>
	<div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script></div>
	</body>
	</html>';
}

function loaduserinfo(){
	global $registerHost;
	echo '<div class="box">';
	$un=getUsername();
	if($un==""){
		echo '<input type="button" value="登录" onclick="window.location=\'login.php\';" class="btn" />';
		echo ' | <input type="button" value="注册" onclick="window.location=\''.$registerHost.'\';" class="btn" />';
	}
	else{
		echo "$un";
		echo ' | <input type="button" value="退出" onclick="window.location=\'logout.php\';" class="btn" />
		<br />
		<a href="usercenter.php">个人中心[测试版]</a>
		';
	}
	echo '
		<br />
		<a href="flatswitch.php" style="font-weight:bold;">简约版/普通版切换</a>
	</div>';
}
function encrypt($data, $key) { 
	$prep_code = serialize($data); 
	$block = mcrypt_get_block_size('des', 'ecb'); 
	if (($pad = $block - (strlen($prep_code) % $block)) < $block) { 
		$prep_code .= str_repeat(chr($pad), $pad); 
	}
	$encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB); 
	return base64_encode($encrypt); 
} 

function decrypt($str, $key) { 
	$str = base64_decode($str); 
	$str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB); 
	$block = mcrypt_get_block_size('des', 'ecb'); 
	$pad = ord($str[($len = strlen($str)) - 1]); 
	if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) { 
		$str = substr($str, 0, strlen($str) - $pad); 
	} 
	return @unserialize($str); 
} 
function filter($str){
	$ret=$str;
	$ret=htmlspecialchars($ret);
	$ret=str_replace("\\","\\\\",$ret);
	$ret=str_replace("'","\\'",$ret);
	return $ret;
}

function getUsername(){
	if(!isset($_COOKIE["islogin"])||$_COOKIE["islogin"]==0){
		return "";
	}else{
		return filter(decrypt($_COOKIE['token'],$_SESSION["key"]));
	}
}

function findroot($pid){
	$currow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = " . $pid));
	$ppid=$currow['parent'];
	if($ppid==1)return $pid;
	else return findroot($ppid);
}

function GBsubstr($string,$start,$length){
	if(strlen($string)>$length){
		$str=null;
		$len=$start+$length;
		for($i=$start;$i<$len;$i++){
			if(ord(substr($string,$i,1))>127){
				$str.=substr($string,$i,3);
				$i+=2;
			}
			else{
				$str.=substr($string,$i,1);
			}
		}
		return $str;
	}
	else return $string;
}

function ConnectDb(){
	global $con,$mysqlHost,$mysqlDBName;
	global $mysqlUsername,$mysqlPassword;
	$con=mysql_connect($mysqlHost,$mysqlUsername,$mysqlPassword);
	mysql_select_db($mysqlDBName,$con);
}
function DisconnectDb(){
	global $con;
	mysql_close($con);
}

?>

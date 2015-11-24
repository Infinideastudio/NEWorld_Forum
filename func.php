<?php
$con=0;
$mysqlHost=getenv("MOPAAS_MYSQL29074_HOST");
$mysqlUsername=getenv("MOPAAS_MYSQL29074_USERNAME");
$mysqlPassword=getenv("MOPAAS_MYSQL29074_PASSWORD");
$mysqlDBName=getenv("MOPAAS_MYSQL29074_NAME");

$verifyHost="http://infusers.sturgeon.mopaas.com/system/verify.php";
$registerHost="http://infusers.sturgeon.mopaas.com/register.php";
$adminHost="http://infusers.sturgeon.mopaas.com/system/isadmin.php";
$userinfoHost="http://infusers.sturgeon.mopaas.com/publicinfo.php";

session_start();
if(!isset($_SESSION["key"])) $_SESSION["key"]=time()%1024+rand()*10;

function loadHeader($title="NEWorld Forum"){
	global $registerHost;
	echo '<!DOCTYPE html>
	<html lang="cn">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="author" content="Null, qiaozhanrong" />
		<meta name="keywords" content="NEWorld, Forum" />
		<meta name="description" content="NEWorld Forum" />
		<meta content="width=device-width,initial-scale=1.0,user-scalable=yes" id="viewport" name="viewport">
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/'.getCSSName().'" />
		<script type="text/javascript" src="func.js"></script>
		<link rel="shortcut icon" type="image/ico" href="/favicon.ico"/>
		<title>'.$title.'</title>
	</head>
	<body>
	<div id="header">
		<div id="header_container">
			<h1>NEWorld Forum</h1>
			<div id="navi">
				<div class="item' . ($_SERVER['REQUEST_URI']=="/index.php" || $_SERVER['REQUEST_URI']=="/"?"_selected":"") . '" onclick="window.open(\'index.php\',\'_self\')">首页</div>
				<div class="item' . ($_SERVER['REQUEST_URI']=="/login.php"?"_selected":"") . '" onclick="window.open(\'login.php\',\'_self\')">登录</div>
				<div class="item" onclick="window.open(\''.$registerHost.'\',\'_self\')">注册</div>
				<div class="item" onclick="window.open(\'http://www.newinfinideas.com\',\'_self\')">新创无际</div>
				<div class="item" onclick="window.open(\'http://neblog.newinfinideas.com\',\'_self\')">BLOG</div>
			</div>
		</div>
	</div>
	<div id="main">';
}
function loadFooter(){
	echo '</div>
	<div id="footer">
		<div style="margin:0 12%;float:left;">
			<a href="http://www.newinfinideas.com/">新创无际网站</a> | 
			<a href="http://infusers.sturgeon.mopaas.com/">新创无际用户管理系统</a> | 
			<a href="http://neworld.newinfinideas.com/">NEWorld网站</a> | 
			<a href="http://neworldgame.sinaapp.com/">NEWorld BLOG</a> | 
			<a href="http://tieba.baidu.com/p/2822071396/">NEWorld贴吧直播贴</a>
		</div>
		<div style="margin:0 12%;float:right;">
			<a href="http://neforum.sturgeon.mopaas.com/">抢鲜版论坛地址</a> | 
			<script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script>
		</div>
		<div style="margin:0 12%;clear:both;">
			新创无际 Infinideas &copy; 2015
		</div>
	</div>
	</body>
	</html>';
}

function loaduserinfo(){
	global $registerHost,$userinfoHost;
	echo '<div class="box" id="userinfo">';
	$un=getUsername();
	if($un==""){
		echo '<p><input type="button" value="登录" onclick="window.location=\'login.php\';" class="btn" />';
		echo ' <input type="button" value="注册" onclick="window.location=\''.$registerHost.'\';" class="btn" /></p>';
	}
	else{
		echo "<p><a href={$userinfoHost}?username={$un}>{$un}</a>";
		echo ' <input type="button" value="退出" onclick="window.location=\'logout.php\';" class="btn" /></p>
		<p><a href="usercenter.php">个人中心</a></p>
		';
	}
	echo '
		<p><a href="flatswitch.php">页面样式：'.getStyleName().'</a></p>
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

function isalpha($chr){return $chr>="A" && $chr<="Z" || $chr>="a" && $chr<="z";}
function isname($chr){return isalpha($chr) || $chr=="_" || $chr=="-";}

function nfilter($str){
	return intval($str);
}
function filter($str){
	$ret=$str;
	$ret=htmlspecialchars($ret);
	$ret=addslashes($ret);
	return $ret;
}
function filter2($str){
	$ret=$str;
	//Normalize
	$ret=str_replace("</p><p>","<br>",$ret);
	$ret=str_replace("<p>","",$ret);$ret=str_replace("</p>","",$ret);
	$ret=str_replace("</div><div>","<br>",$ret);
	$ret=str_replace("<div>","",$ret);$ret=str_replace("</div>","",$ret);
	$p=0;$q=strpos($ret,"<");
	while($q!==false){
		$closepos=strpos($ret,">",$q+1);
		if($closepos===false)break;
		$i=$q;
		while($i<$closepos){$i++;if($ret[$i]!=" ")break;}
		$tagbegin=$i;
		while($i<$closepos){$i++;if($ret[$i]==" ")break;}
		$tagend=$i;
		$tag=strtolower(substr($ret,$tagbegin,$tagend-$tagbegin));
		if(tag_allowed($tag)){
			//Escape previous
			$origin=substr($ret,$p,$q-$p);
			$originlen=strlen($origin);
			$replaced=htmlspecialchars($origin);
			$replacedlen=strlen($replaced);
			$ret=substr($ret,0,$p).$replaced.substr($ret,$q);
			//Offset
			$closepos+=$replacedlen-$originlen;
			$tagend+=$replacedlen-$originlen;
			//Filter property
			$p=$tagend;$q=$closepos;
			$origin=substr($ret,$p,$q-$p);
			$originlen=strlen($origin);
			$properties=explode(" ",$origin);
			$ppcount=count($properties);
			$replaced="";
			for($i=0;$i<$ppcount;$i++){
				$pr=$properties[$i];
				$plen=strlen($pr);
				//Get property name
				for($j=0;$j<$plen;$j++)if(!isname($pr[$j]))break;
				$property=substr($pr,0,$j);
				//Check property name
				if(!property_allowed($tag,$property))continue;
				//Get property value
				for(;$j<$plen;$j++)if($pr[$j]=="\""||$pr[$j]=="'")break;
				$j++;$valuebegin=$j;
				for(;$j<$plen;$j++)if($pr[$j]=="\""||$pr[$j]=="'")break;
				$value=substr($pr,$valuebegin,$j-$valuebegin);
				//Check property value
				if(!value_allowed($tag,$property,$value))continue;
				//Append string
				$replaced.=" ".$pr;
			}
			$replaced.=" /";
			$replacedlen=strlen($replaced);
			$ret=substr($ret,0,$p).$replaced.substr($ret,$q);
			$closepos+=$replacedlen-$originlen;
			//Find next
			$p=$closepos+1;
			$q=strpos($ret,"<",$p);
		}
		else $q=strpos($ret,"<",$q+1);
	}
	$ret=substr($ret,0,$p).htmlspecialchars(substr($ret,$p,strlen($ret)-$p));
	$ret=str_ireplace("&amp;","&",$ret);
	return addslashes($ret);
}
function indexpage_filter($str){
	$ret=$str;
	if(strlen($ret)>1024)$ret=GBsubstr($ret,0,1024)." ...";
	//忽略过多换行
	$len=strlen($ret);
	$brs=0;$lpos=$pos=-1;
	while($pos!==false && $brs<=5){
		$lpos=$pos;
		$pos=strpos($ret,"<br",$pos+1);
		if($pos!==false)$brs++;
	}
	if($brs>5)$ret=substr($ret,0,$lpos)."<br />...";
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
function getStyleName(){
	$style="0";
	if(isset($_COOKIE["style"]))$style=$_COOKIE["style"];
	if($style=="0")return "普通版";
	if($style=="1")return "简约版";
}
function getCSSName(){
	$style="0";
	if(isset($_COOKIE["style"]))$style=$_COOKIE["style"];
	if($style=="0")return "normal.css";
	if($style=="1")return "flat.css";
}

function Post($url, $post) {
	if (is_array($post)) {
		ksort($post);
		$content = http_build_query($post);
		$content_length = strlen($content);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' =>
				"Content-type: application/x-www-form-urlencoded\r\n" .
				"Content-length: $content_length\r\n",
				'content' => $content
			)
		);
		return file_get_contents($url,false,stream_context_create($options));
	}
}

function delete_auth($pid){
	global $adminHost;
	$un=getUsername();
	$row=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=".$pid));
	if($row['username']==$un)return true;
	while($row['parent']!=1){
		$pid=$row['parent'];
		$row=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=".$pid));
		if($row['username']==$un)return true;
	}
	$data=array('username'=>$un);
	$response=Post($adminHost,$data);
	if($response=="1")return true;
	return false;
}

function tag_allowed($tag){
	if($tag=="img"||$tag=="br")return true;
	return false;
}

function property_allowed($tag,$p){
	if($tag=="img"){
		if($p=="src" || $p=="alt")return true;
		return false;
	}
	return false;
}

function value_allowed($tag,$p,$v){
	if($tag=="img" && $p=="src"){
		$suffix=substr($v,strlen($v)-4,4);
		if($suffix!=".jpg"&&$suffix!=".png"&&$suffix!=".gif")return false;
	}
	return true;
}

?>
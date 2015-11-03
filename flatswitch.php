<?php
if(isset($_COOKIE["style"])){
	if($_COOKIE["style"]=="1"){
		setcookie("style","0",time()+2592000);
	}
	else{
		setcookie("style","1",time()+2592000);
	}
}
else{
	setcookie("style","1",time()+2592000);
}
if (isset($_SERVER['HTTP_REFERER'])) {
	echo '<meta http-equiv="Refresh" content="0; url='. $_SERVER['HTTP_REFERER'] . '">';
}else{
	echo '<meta http-equiv="Refresh" content="0; url=index.php">';
}
?>
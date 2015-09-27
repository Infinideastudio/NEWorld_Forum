<?php
if(isset($_COOKIE["flat"]))
{
	if($_COOKIE["flat"]=="1"){
		$_COOKIE["flat"]="0";
	}
	else{
		$_COOKIE["flat"]="1";
	}
}
else{
	setcookie("flat","1");
}
if (isset($_SERVER['HTTP_REFERER'])) {
	echo '<meta http-equiv="Refresh" content="0; url='. $_SERVER['HTTP_REFERER'] . '">';
}else{
	echo '<meta http-equiv="Refresh" content="0; url=index.php">';
}
?>
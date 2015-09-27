<?php
if(isset($_COOKIE["flat"]))
{
	if($_COOKIE["flat"]=="1"){
		setcookie("flat","0");
	}
	else{
		setcookie("flat","1");
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
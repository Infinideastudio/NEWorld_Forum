<?php
setcookie("islogin",0);
setcookie("token");
if (isset($_SERVER['HTTP_REFERER'])) {
	echo '<meta http-equiv="Refresh" content="0; url='. $_SERVER['HTTP_REFERER'] . '">';
}else{
	echo '<meta http-equiv="Refresh" content="0; url=index.php">';
}
?>
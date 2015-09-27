<?php
if(isset($_COOKIE["flat"]))
{
	if($_COOKIE["flat"]==1)$_COOKIE["flat"]=0;
	else $_COOKIE["flat"]=1;
}
else{
	setcookie("flat",1);
}
?>
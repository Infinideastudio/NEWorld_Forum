function showreplybox(pid){
	var box=document.getElementById("replybox_"+pid);
	box.innerHTML="<form action='post.php' method='post'> \
	<input type='hidden' name='type' value='2' readonly='true'> \
	<input type='hidden' name='pid' value='"+pid+"' readonly='true'> \
	<textarea class='txtbox' name='content' placeholder='快速回复' required='true' style='width:95%;height:100px;resize:none;margin:10px;'></textarea> \
	<input type='submit' value='发表' class='btn' style='color:#ffffff;background-color:#0099ff;' /> \
	 | \
	<input type='button' value='进入楼层' class='btn' onclick=\" window.open('posts.php?p="+pid+"','_self') \" /> \
	</form>";
	if(box.style.display=="none")box.style.display="block";
	else box.style.display="none";
}
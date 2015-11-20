var handle;
function showreplybox(pid){
	var box=document.getElementById("replybox_"+pid);
	box.innerHTML="<form action='post.php' method='post'> \
	<input type='hidden' name='type' value='2' readonly='true'> \
	<input type='hidden' name='pid' value='"+pid+"' readonly='true'> \
	<textarea class='txtbox' name='content' id='content_"+pid+"' placeholder='快速回复' required='true' style='width:95%;resize:none;margin:10px;'></textarea> \
	<input type='submit' value='发表' class='btn' style='color:#ffffff;background-color:#0099ff;' />&nbsp;&nbsp; \
	<input type='button' value='进入楼层' class='btn' onclick=\" window.open('posts.php?p="+pid+"','_self') \" /> \
	</form>";
	var content=document.getElementById("content_"+pid);
	clearInterval(handle);
	if(box.style.display=="none"){
		box.style.display="block";
		content.style.height="1px";
		handle=setInterval(function(){
			var height=parseInt(content.style.height);
			if(height>=100){clearInterval(handle);return;}
			var theight=100.0-(100-height)*0.8;
			if(theight>100)theight=100;
			content.style.height=String(theight)+"px";
		},17);
	}
	else{
		content.style.height="96px";
		handle=setInterval(function(){
			var height=parseInt(content.style.height);
			if(height<=1){clearInterval(handle);box.style.display="none";return;}
			var theight=100.0-(100-height)/0.8;
			if(theight<1)theight=1;
			content.style.height=String(theight)+"px";
		},17);
	}
}

function SubmitPost(){
	document.getElementById("content").value=document.getElementById("editor").innerHTML;
	document.getElementById("postreply").submit();
}
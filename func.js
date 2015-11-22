var handle;
var noticeboard_items=new Array();
var noticeboard_itemcount=0;
var noticeboard_curitem=0;

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
/*
function noticeboard_additem(s){
	noticeboard_items[noticeboard_itemcount]=s;
	noticeboard_itemcount++;
}

function noticeboard_scroll(){
	var nextitem;
	if(noticeboard_curitem==noticeboard_items.length-1)nextitem=0;
	else nextitem=noticeboard_curitem+1;
	var upper=document.getElementById("noticeboard_upper");
	var lower=document.getElementById("noticeboard_lower");
	lower.innerHTML=noticeboard_items[nextitem];
	upper.style.transition="top 0.3s";
	lower.style.transition="top 0.3s";
	upper.style.top="-32px";
	lower.style.top="0px";
	setTimeout("upper.style.transition='0s';\
				lower.style.transition='0s';\
				upper.style.top='0px';upper.innerHTML=noticeboard_items[nextitem];\
				lower.style.top='32px';lower.innerHTML='';",500);
	noticeboard_curitem=nextitem;
	setTimeout("noticeboard_scroll()",5000);
}
*/
<?php
	include_once("func.php");
	ConnectDb();
	
	function delete_replies($ppid){
		$results=mysql_query("SELECT * FROM Posts WHERE parent=".$ppid);
		$count=0;
		while($result = mysql_fetch_array($results)){
			$pid=$result['PID'];
			mysql_query("DELETE FROM Posts WHERE PID='" . $pid . "'");
			$count++;
			$count+=delete_replies($pid);
		}
		return $count;
	}
	
	function add_reply_to($ppid,$curtime){
		mysql_query("UPDATE Posts SET replycount=replycount+1,lastreplytime='".$curtime."' WHERE PID=".$ppid);
		$result=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=".$ppid));
		if($result['parent']!=0)add_reply_to($result['parent'],$curtime);
	}
	
	function delete_reply_from($ppid,$count){
		mysql_query("UPDATE Posts SET replycount=replycount-".$count." WHERE PID=".$ppid);
		$result=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=".$ppid));
		if($result['parent']!=0)delete_reply_from($result['parent'],$count);
	}
	
	switch ($_POST['type']) {
		case '0': //新建主题
			if(getUsername()==""){
				echo '<div style="text-align:center">';
				echo '<p>请先注册后再发帖</p>';
				echo '<a href="index.php">返回主页</a> | <a href="login.php">登录</a>';
				echo '</div>';
				exit();
			}
			if ($_POST['title']!="" && $_POST['content']!=""){
				$_POST['title']=filter($_POST['title']);
				$_POST['content']=filter($_POST['content']);
				mysql_query("UPDATE Posts SET replycount=replycount+1 WHERE PID=1"); //主贴只负责记录独立帖子数量，独立帖子数加一
				mysql_query("INSERT INTO Posts (username, title, content, parent)
							VALUES ('" . getUsername() . "', '" . $_POST['title'] . "', '" . $_POST['content'] . "', 0) ");
				mysql_query("UPDATE Posts SET lastedittime=createtime,lastreplytime=createtime WHERE PID = LAST_INSERT_ID()");
			}
			break;
			
		case '1': //删除帖子
			$pid=filter($_POST['pid']);
			$row=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=".$pid));
			if ($row['username']==getUsername()) {
				mysql_query("DELETE FROM Posts WHERE PID = '" . $pid . "'");
				if($row['parent']==0){
					mysql_query("UPDATE Posts SET replycount=replycount-1 WHERE PID=1"); //主贴只负责记录独立帖子数量，独立帖子数减一
					delete_replies($pid);
				}
				else{
					delete_reply_from($row['parent'],delete_replies($pid)+1); //给本帖所有的祖父节点（不包括main帖）回复数全部减去此次被删除的帖子总数
				}
			}
			break;
			
		case '2': //添加回帖
			if(getUsername()==""){
				echo '<div style="text-align:center">';
				echo '<p>请先注册后再发帖</p>';
				echo '<a href="index.php">返回主页</a> | <a href="login.php">登录</a>';
				echo '</div>';
				exit();
			}
			$pid=filter($_POST['pid']);
			$_POST['content']=filter($_POST['content']);
			if ($_POST['content']!=""){
				mysql_query("UPDATE Posts SET maxfloor=maxfloor+1 WHERE PID=" . $pid); //parent楼层数加一
				$parentrow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=" . $pid));
				mysql_query("INSERT INTO Posts (username, title, content, parent, floor)
							VALUES ('" . getUsername() . "', '', '" . $_POST['content'] . "', '" . $pid . "', " . $parentrow['maxfloor'] . ") ");
				mysql_query("UPDATE Posts SET lastedittime=createtime,lastreplytime=createtime WHERE PID = LAST_INSERT_ID()");
				$currow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = LAST_INSERT_ID()"));
				add_reply_to($pid,$currow['createtime']); //给本帖所有的祖父节点（不包括main帖）回复数全部加一，并更新最后回复时间
			}
			break;
	}
	if (isset($_SERVER['HTTP_REFERER'])) {
		echo '<meta http-equiv="Refresh" content="0; url='. $_SERVER['HTTP_REFERER'] . '">';
	}else{
		echo '<meta http-equiv="Refresh" content="0; url=index.php">';
	}
	DisconnectDb();
?>
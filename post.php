<?php
	include_once("func.php");
	ConnectDb();
	
	function delete_replies($ppid){
		$results = mysql_query("SELECT * FROM Posts WHERE parent='" . $ppid . "'");
		$count=0;
		while($result = mysql_fetch_array($results)){
			$pid=$result['PID'];
			mysql_query("DELETE FROM Posts WHERE PID='" . $pid . "'");
			delete_replies($pid);
		}
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
				$_POST['title']=filter($_POST['title'], true);
				$_POST['content']=filter($_POST['content'], true);
				mysql_query("UPDATE Posts SET replycount=replycount+1 WHERE PID = 0");
				mysql_query("INSERT INTO Posts (username, title, content, parent)
							VALUES ('" . getUsername() . "', '" . $_POST['title'] . "', '" . $_POST['content'] . "', 0) ");
				mysql_query("UPDATE Posts SET lastedittime=createtime, lastreplytime=createtime WHERE PID = LAST_INSERT_ID()");
			}
			break;
   
		case '1': //删除帖子
			if ($_POST['username']==getUsername()) {
				mysql_query("UPDATE Posts SET replycount=replycount-1 WHERE PID = " . $_POST['parent']);
				mysql_query("DELETE FROM Posts WHERE PID = '" . $_POST['pid'] . "'");
				delete_replies($_POST['pid']);
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
			if ($_POST['content']!=""){
				$_POST['content']=filter($_POST['content'], true);
				mysql_query("UPDATE Posts SET replycount=replycount+1, maxfloor=maxfloor+1 WHERE PID = " . $_POST['pid']);
				$parentrow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = " . $_POST['pid']));
				mysql_query("INSERT INTO Posts (username, title, content, parent, floor)
							VALUES ('" . getUsername() . "', '', '" . $_POST['content'] . "', '" . $_POST['pid'] . "', " . $parentrow['maxfloor'] . ") ");
				mysql_query("UPDATE Posts SET lastedittime=createtime WHERE PID = LAST_INSERT_ID()");
				$currow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = LAST_INSERT_ID()"));
				mysql_query("UPDATE Posts SET lastreplytime='" . $currow['createtime'] . "' WHERE PID = " . findroot($_POST['pid']));
			}
			break;
	}
	if (isset($_SERVER['HTTP_REFERER'])) {
		//header("Location:" . $_SERVER['HTTP_REFERER']);
		echo '<meta http-equiv="Refresh" content="0; url='. $_SERVER['HTTP_REFERER'] . '">';
	}else{
		//header("Location:index.php");
		echo '<meta http-equiv="Refresh" content="0; url=index.php">';
	}
	DisconnectDb();
?>
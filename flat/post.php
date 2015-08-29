<?php
	include_once("func.php");
	ConnectDb();
	switch ($_POST['type']) {

		case '0': //新建主题
			if(getUsername()==""){
				echo '<div style="text-align:center">';
				echo '<p>请先注册后再发帖</p>';
				echo '<a href="index.php">返回主页</a> | <a href="login.php">登录</a>';
				echo '</div>';
				exit();
			}

			if (isset($_POST['title'])&&$_POST['title']!="" && $_POST['content']!=""){
				$_POST['title']=filter($_POST['title'], true);
				$_POST['content']=filter($_POST['content'], true);
				mysql_query("INSERT INTO Posts (username, title, content, children) 
							VALUES ('" . getUsername() . "', '" . $_POST['title'] . "', '" . $_POST['content'] . "', '')");
				$pids=mysql_fetch_array(mysql_query("select last_insert_id()"));
				$pid=$pids[0];
				mysql_query("UPDATE Posts SET children=concat(children,',{$pid}') WHERE PID = 1");
				mysql_query("UPDATE Posts SET replycount=replycount+1 WHERE PID = 1");
			}
			break;

		case '1': //删除帖子
			if (isset($_POST['pwd'])&&md5($_POST['pwd'])=="d95fe34d61c24917b41cf22d4073e60e") {
				mysql_query("UPDATE Posts SET children=replace(children,',{$_POST['pid']},',',') WHERE PID = {$_POST['pidparent']}");
				mysql_query("DELETE FROM Posts WHERE PID='" . $_POST['pid'] . "'");
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
			if (isset($_POST['pid'])) {
				$_POST['content']=filter($_POST['content'], true);
				mysql_query("INSERT INTO Posts (username, title, content, children) 
							VALUES ('" . getUsername() . "', '', '" . $_POST['content'] . "', '')");
				$pids=mysql_fetch_array(mysql_query("select last_insert_id()"));
				$pid=$pids[0];
				mysql_query("UPDATE Posts SET children=concat(children,',{$pid}') WHERE PID = {$_POST['pid']}");
				mysql_query("UPDATE Posts SET replycount=replycount+1 WHERE PID = {$_POST['pid']}");
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
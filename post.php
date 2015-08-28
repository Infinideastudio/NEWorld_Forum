<?php
	include_once("func.php");
	ConnectDb();
	switch ($_POST['type']) {

		case '0': //新建主题
			$usehtmltag=false;
			if(getUsername()==""){
				echo '<p>请先注册后再发表主题帖</p>';
				echo '<a href="index.php">返回主页</a> | <a href="login.php">登录</a>';
				exit();
			}
			if (isset($_POST['title'])&&$_POST['title']!="" && $_POST['content']!=""){
				$_POST['title']=filter($_POST['title'], true);
				$_POST['content']=filter($_POST['content'], true);
				mysql_query("INSERT INTO Posts (username, title, content, children) VALUES ('" . getUsername() . "', '" . $_POST['title'] . "', '" . $_POST['content'] . "', '')");
				$pida=mysql_fetch_array(mysql_query("select last_insert_id()"));
				$pid=$pida[0];
				mysql_query("UPDATE Posts SET children=concat(children,',{$pid}') WHERE PID = 1");
				mysql_query("UPDATE Posts SET replycount=replycount+1 WHERE PID = 1");
			}
			break;

		case '1': //删除帖子
			if (isset($_POST['pwd'])&&$_POST['pwd']=="fixedpwd") {
				mysql_query("DELETE FROM Posts WHERE PID='" . $_POST['pid'] . "'");
			}

			break;
		
		case '2': //添加回帖
			if (isset($_POST['pid'])) {
				$_POST['content']=filter($_POST['content'], true);
				mysql_query("INSERT INTO Posts (username, title, content, children) 
							VALUES ('" . getUsername() . "', '" . $_POST['title'] . "', '" . $_POST['content'] . "', '')");
				$pida=mysql_fetch_array(mysql_query("select last_insert_id()"));
				$pid=$pida[0];
				mysql_query("UPDATE Posts SET children=concat(children,',{$pid}') WHERE PID = {$_POST['pid']}");
				mysql_query("UPDATE Posts SET replycount=replycount+1 WHERE PID = {$_POST['pid']}");
			}

			break;
	}
	if (isset($_SERVER['HTTP_REFERER'])) {
		header("Location:" . $_SERVER['HTTP_REFERER']);
	}else{
		header("Location:index.php");
	}
	DisconnectDb();
?>
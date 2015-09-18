<?php include_once("func.php"); loadHeader(); ?>
	<div id="header">
		<h1 style="color:#ffffff;">The Forum of NEWorld</h1>
		<h3>——by Null and qiaozhanrong</h3>
	</div>
	<div id="main">
		<div style="margin:0 5%">
			<p>版本: 0.2.8</p>
			<p>更新内容：加入了首页的登录注册按钮，修复了一些BUG，修改了一些细节，添加了易于操作的回复楼中楼的功能（按回复上的PID xxx即可回复）<p>
			<a href="flat/index.php">简约版 </a>
			<?php
			$un=getUsername();
			if($un==""){
				echo '<a href="login.php">登录</a> | ';
				echo '<a href="http://neblog.newinfinideas.com/admin/register.php">注册</a>';
			}else{
				echo "<p>欢迎用户$un</p>";
				echo '<a href="logout.php">退出</a>';
			}
			?>
		</div>
		<div class="box">

			<?php
			ConnectDb();
			if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'Posts'"))!=1){
				$sql = "CREATE TABLE Posts(
				PID int AUTO_INCREMENT,
				PRIMARY KEY(PID),
				username TINYTEXT,
				title TINYTEXT,
				content TEXT,
				createtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				lastedittime TIMESTAMP,
				parent int,
				replycount int default 0
				)";
				mysql_query($sql);
				mysql_query("CREATE CLUSTERED INDEX parent_cindex ON Posts(parent) WITH ALLOW_DUP_ROW");
				mysql_query("INSERT INTO Posts (username, title, content, parent) VALUES ('system', 'main', '', -1) ");
				echo "POSTS INIT FINSHED!!!";
			}
   
			$results = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE parent = 0"));
			foreach (array_reverse($results) as $result) {
				if($result!=""){
					echo "<div class='topic'>$result  . {$row["username"]} <a href='posts.php?p={$row['PID']}' > {$row['title']} </a> <br />
					<span style='padding: 0 2em;'> </span> {$row['content']} <br /> 回复数： {$row['replycount']}  | 发布时间： {$row['createtime']}</div>";
				}
			}

			DisconnectDb();
			?>

		</div>

		<div class="box">
			<form action="post.php" method="post">
				<input type="hidden" name="type" value="0" readonly="true">
				<p><input type="text" name="title" id="title" placeholder="标题" autocomplete="off" style="width:99%;"></p>
				<textarea name="content" id="content" placeholder="内容" required="true" class="txtbox"></textarea>
				<p><input type="submit" value="发布" class="btn" /></p>
			</form>
		</div>
	</div>

<?php loadFooter(); ?>
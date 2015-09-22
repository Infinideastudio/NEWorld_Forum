<?php include_once("func.php");loadHeader(); ?>
<div style="margin:0 5%">
	<a href="flat/index.php">切换到简约版</a>
	<?php
		$un=getUsername();
		if($un!=""){
			echo " | $un";
			echo ' | <input type="button" value="退出" onclick="window.location=\'logout.php\';" class="btn" />';
		}
	?>
</div>
<div class="box">
	
	<?php
		ConnectDb();
		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'Posts'"))!=1){
			$sqlquery = "CREATE TABLE Posts(
			PID INT AUTO_INCREMENT,
			PRIMARY KEY(PID),
			username TINYTEXT,
			title TINYTEXT,
			content TEXT,
			createtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			lastedittime TIMESTAMP,
			lastreplytime TIMESTAMP,
			parent INT,
			replycount INT DEFAULT 0,
			floor INT,
			maxfloor INT DEFAULT 0
			)";
			mysql_query($sqlquery);
			mysql_query("CREATE CLUSTERED INDEX parent_cindex ON Posts(parent) WITH ALLOW_DUP_ROW");
			mysql_query("INSERT INTO Posts (username, title, content, parent) VALUES ('system', 'main', '', -1) ");
			echo "Table 'Posts' initialized.";
		}
		
		$results = mysql_query("SELECT * FROM Posts WHERE parent = 0 ORDER BY lastreplytime DESC");
		while($result = mysql_fetch_array($results)){
			echo "<div class='topic'>{$result['PID']}. {$result["username"]} <a href='posts.php?p={$result['PID']}' > {$result['title']} </a> <br />
			<span style='padding: 0 2em;'> </span> {$result['content']} <br /> 回复数： {$result['replycount']}  | 发布时间： {$result['createtime']}</div>";
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
<?php loadFooter(); ?>
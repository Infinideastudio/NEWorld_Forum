﻿<!DOCTYPE html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Null,qiaozhanrong" />
	<meta name="keywords" content="NEWorld,Forum" />
	<meta name="description" content="The Forum of NEWorld" />
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>The Forum of NEWorld</title>
</head>
<body>
	<div id="header">
		<h1 style="color:#ffffff;">The Forum of NEWorld</h1>
		<h3>——by Null and qiaozhanrong</h3>
	</div>
	<div id="main">
	 <div id="mainleft" align="left">
			<p>版本: 0.2.5</p>
			<p>更新内容：加入了css样式，更改布局，支持楼中楼回复<p>
			<a href="index_sample.php">点此进入简约版</a>
			
			<div class="box">

				<?php
				include_once("func.php");

				ConnectDb();
				if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'Posts'"))!=1){
					$sql = "CREATE TABLE Posts(
					PID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(PID),
					username TINYTEXT,
					title TINYTEXT,
					content TEXT,
					createtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					lastedittime TIMESTAMP,
					children TEXT default '',
					replycount int default 0
					)";
					mysql_query($sql,$con);
					mysql_query("INSERT INTO Posts (username, title, content, children) VALUES ('system', 'main', '', '') ");
					echo "INIT FINSHED!!!";
				}

				$result = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = 1"));

				$postids = explode(",", $result['children']);
					
				foreach ($postids as $postid) {
					if($postid!=""){
						$row = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = $postid"));
						echo "<div class='topic'><a href='posts.php?p={$row['PID']}' >{$row['title']}</a> --by {$row["username"]}<br />
						{$row['content']}<br /> 回复数： {$row['replycount']}  | 发布时间： {$row['createtime']} | PID：$postid</div>";
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
		
		<div id="mainright">
		 <div>
			 <h3>我在论坛</h3>
				<p>[这里什么也没有2333]</p>
			</div>
			<div>
			 <h3>论坛信息</h3>
				<p>[这里什么也没有2333]</p>
			</div>
			<div>
			 <h3>传送门</h3>
				<p>
				 <a href="http://www.neworldsite.gq/">NEWorld官网主站</a>
				 <a href="http://blog.neworldsite.gq/">NEWorld Blog</a>
					<a href="http://tieba.baidu.com/f?kw=minecraft&ie=utf-8">Minecraft百度贴吧</a>
					<a href="http://www.mcbbs.net/forum.php">Minecraft中文论坛</a>
				</p>
			</div>
		</div>
		
	</div>
	</div><div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script></div>
 <div id="footer">
		<p style="color:#ffffff">CopyLEFT &copy; Infinideas 2015</h3>
	</div>
</body>
</html>
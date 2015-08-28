<!DOCTYPE html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Null,qiaozhanrong" />
	<meta name="keywords" content="NEWorld,Forum" />
	<meta name="description" content="The Forum of NEWorld" />
	<title>The forum of NEWorld</title>
	<style>
	body{padding:3% 25%;font-family: 'Microsoft YaHei';}
	.main{margin:0 auto; padding:20px;}
	p{margin:10px 5px;}
	.topic:nth-child(even),.topic:hover{
	background-color:#FAFAFA;
	}
	a{
	color:#0099ff;
	text-decoration:none;
	}
	a:hover{color:#bbb;}
	</style>
</head>
<body>
	<div class="main">
		<div style="text-align:center">
			<h1>The Forum of NEWorld</h1>
			<p><a href="index.php" style="color:#233333;">简约版</a></p>
			<p>版本: 0.2.5<p>
		</div>
		<span style="margin: 20px"></span>
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
					echo "<div class='topic'><p>$postid  . {$row["username"]} <a href='posts.php?p={$row['PID']}' > {$row['title']} </a> </p><p>
					<span style='padding: 0 2em;'> </span> {$row['content']} </p><p>回复数： {$row['replycount']}  | 发布时间： {$row['createtime']}</p></div>";
				}
			}

			DisconnectDb();
			?>

			<form action="post.php" method="post" style="text-align:center;">
				<input type="hidden" name="type" value="0" readonly="true">
				<p><input type="text" name="title" id="title" placeholder="标题" autocomplete="off" style="width:100%;margin:20px 0;">
				<textarea name="content" id="content" placeholder="内容" required="true" style="width:100%;height:280px;"></textarea></p>
				<p><input type="submit" value="发布" class="btn" style="width:200px" /></p>
			</form>
		</div>
<div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script></div>



</body>
</html>
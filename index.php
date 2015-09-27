<?php
include_once("func.php");loadHeader();
function getfontcolor($replycount){
	/*
	if($replycount<10)return "#000000";
	if($replycount<30)return "#646464";
	if($replycount<100)return "#0099ff";
	if($replycount<300)return "#dddd00";
	if($replycount<1000)return "#dd0000";
	return "#ff0000";
	*/
	return "#000000";
}
?>
<div id="main_left">
	<div class="box">
		<p class="nmp">NEWorld Forum - NEWorld玩家们讨论与交流的自由空间！</p>
		<hr />
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
				echo "<div class='topic clearfix'>";
				echo "<p class='nmp' style='font-size:18px;'><a href='posts.php?p={$result['PID']}' > {$result['title']}</a></p>";
				echo "<p class='nmp' style='font-size:14px;'>{$result['content']}</p>";
				echo "<span style='font-size:12px;float:right;'>作者：{$result["username"]} | ";
				echo "<span style='color:" . getfontcolor($result['replycount']) . ";'>回复数：{$result['replycount']}</span>";
				echo " | 发布时间：{$result['createtime']}</span>";
				echo "</div>";
			}
			DisconnectDb();
		?>
	</div>
	<div class="box" style="margin-top:10px;">
		<form action="post.php" method="post">
			<input type="hidden" name="type" value="0" readonly="true">
			<p><input type="text" name="title" id="title" placeholder="标题" autocomplete="off" style="width:99%;height:18px;" class="txtbox"></p>
			<textarea name="content" id="content" placeholder="内容" required="true" style="width:99%;height:280px;" class="txtbox"></textarea>
			<p><input type="submit" value="发布" class="btn" /></p>
		</form>
	</div>
</div>
<div id="main_right">
	<a href="flatswitch.php">切换到简约版</a>
	<br />
	<div class="box">
		<?php
			$un=getUsername();
			if($un==""){
				echo '<input type="button" value="登录" onclick="window.location=\'login.php\';" class="btn" />';
				echo ' | <input type="button" value="注册" onclick="window.location=\'http://neblog.newinfinideas.com/admin/register.php\';" class="btn" />';
			}
			else{
				echo "$un";
				echo ' | <input type="button" value="退出" onclick="window.location=\'logout.php\';" class="btn" />';
			}
		?>
	</div>
	<p class="nmp">最新帖子：</p>
	<div class="box">
		<?php
		ConnectDb();
		$results = mysql_query("SELECT * FROM Posts WHERE parent=0 ORDER BY createtime DESC LIMIT 0,5");
		while($result = mysql_fetch_array($results)){
			echo "<div class='topic'>{$result["username"]}：<br /><a href='posts.php?p={$result['PID']}'>{$result['title']}</a><br />
			回复数：{$result['replycount']}<br />发布时间：{$result['createtime']}</div>";
		}
		DisconnectDb();
		?>
	</div>
	<div class="box" style="margin-top:10px;">
		<p class="nmp" style="font-size:10px;">
			透露一个小彩蛋：其实发帖的框可以通过拖动右下角的小东西来改变高度。。。（简约版里还能改变宽度2333）
		</p>
	</div>
</div>
<?php loadFooter(); ?>
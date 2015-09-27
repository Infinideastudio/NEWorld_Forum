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
		<p class="nmp" style="float:left;margin-bottom:5px;">NEWorld Forum - NEWorld玩家们讨论与交流的自由空间！</p>
		<?php
			ConnectDb();
			$mainrow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=1"));
			echo "<p class='nmp' style='font-size:12px;float:right;'>这里已有{$mainrow['replycount']}个主题帖！</p>";
			echo "<hr style='clear:both;'/>";
			$pageposts=50;
			$page=1;
			$maxpage=ceil($mainrow['replycount']/$pageposts);
			if(isset($_GET['pn']))$page=$_GET['pn'];
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
			$results = mysql_query("SELECT * FROM Posts WHERE parent = 0 ORDER BY lastreplytime DESC LIMIT ".(($page-1)*$pageposts).",".($pageposts));
			while($result = mysql_fetch_array($results)){
				$content=$result['content'];
				if(strlen($content)>1024)$content=GBsubstr($content,0,1024)." ...";
				echo "<div class='topic clearfix'>";
				echo "<p class='nmp' style='font-size:18px;'><a href='posts.php?p={$result['PID']}'>{$result['title']}</a></p>";
				echo "<p class='nmp' style='font-size:14px;'>{$content}</p>";
				echo "<span style='font-size:12px;float:right;'>作者：{$result["username"]} | ";
				echo "<span style='color:" . getfontcolor($result['replycount']) . ";'>回复数：{$result['replycount']}</span>";
				echo "<br />最后回复：{$result['lastreplytime']} | 发布时间：{$result['createtime']}</span>";
				echo "</div>";
			}
			DisconnectDb();
			//坑爹的翻页系统
			if($page>1)echo "<input type='button' value='上一页' class='btn' onclick=\" window.open('index.php?pn=".($page-1)."','_self') \" />";
			if($page>1)echo " | ";
			$f=false;
			if($page-5>1)echo "<a href='index.php?pn=1'>[1]</a><span style='margin:0 0.2em;'></span>";
			if($page-5>2)echo "...<span style='margin:0 0.2em;'></span>";
			for($i=$page-5;$i<=$page+5;$i++){
				if($i>=1 && $i<=$maxpage){
					if($f)echo "<span style='margin:0 0.2em;'></span>";$f=true;
					if($i==$page)echo "[".$i."]";
					else echo "<a href='index.php?pn=".$i."'>[".$i."]</a>";
				}
			}
			if($page+5<$maxpage-1)echo "<span style='margin:0 0.2em;'></span>...";
			if($page+5<$maxpage)echo "<span style='margin:0 0.2em;'></span><a href='index.php?pn=".$maxpage."'>[".$maxpage."]</a>";
			if($page<$maxpage)echo " | ";
			if($page<$maxpage)echo "<input type='button' value='下一页' class='btn' onclick=\" window.open('index.php?pn=".($page+1)."','_self') \" />";
			//翻页系统到此结束
		?>
	</div>
	<div class="box" style="margin-top:10px;">
		<p class="nmp">发表新帖</p>
		<hr />
		<form action="post.php" method="post">
			<input type="hidden" name="type" value="0" readonly="true">
			<p><input type="text" name="title" id="title" placeholder="标题" autocomplete="off" style="width:99%;height:18px;" class="txtbox"></p>
			<textarea name="content" id="content" placeholder="内容" required="true" style="width:99%;height:280px;" class="txtbox"></textarea>
			<p><input type="submit" value="发布" class="btn" /></p>
		</form>
	</div>
</div>
<div id="main_right">
	<?php loaduserinfo() ?>
	<p class="nmp">最新帖子：</p>
	<div class="box">
		<?php
		ConnectDb();
		$results = mysql_query("SELECT * FROM Posts WHERE parent=0 ORDER BY createtime DESC LIMIT 0,5");
		while($result = mysql_fetch_array($results)){
			echo "<div class='topic'>{$result["username"]}：<br /><a href='posts.php?p={$result['PID']}'>{$result['title']}</a><br />
			<span style='font-size:12px;'>回复数：{$result['replycount']}<br />发布时间：{$result['createtime']}</span></div>";
		}
		DisconnectDb();
		?>
	</div>
	<div class="box" style="margin-top:10px;">
		<p class="nmp" style="font-size:12px;">
			透露一个小彩蛋：其实发帖的框可以通过拖动右下角的小东西来改变高度。。。
		</p>
	</div>
</div>
<?php loadFooter(); ?>
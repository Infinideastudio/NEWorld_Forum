<?php
	include_once("func.php");loadHeader();
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
		echo "Initialized table 'Posts'.";
	}
	if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'broadcast'"))!=1){
		mysql_query("CREATE TABLE broadcast(
					number INT AUTO_INCREMENT, PRIMARY KEY(number),
					content TEXT)");
		echo "Initialized table 'broadcast'.";
	}
	DisconnectDb();

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
<style type="text/css">
	.topic img{max-width:45%;height:150px;}
	@media all and (max-width:740px){
		.topic img{max-width:90%;height:100px;}
	}
	@media all and (max-width:600px){
		.topic img{height:80px;}
	}
</style>
<div id="main_right">
	<?php
	loaduserinfo();
	function latest_topics(){
		echo '<div id="latest_topics">';
		ConnectDb();
		$results = mysql_query("SELECT * FROM Posts WHERE parent=1 ORDER BY createtime DESC LIMIT 0,5");
		if(mysql_num_rows($results)){
			echo '<p class="nmp">最新帖子：</p>
					<div class="box" style="padding:0px;">';
			while($result = mysql_fetch_array($results)){
				echo "<div class='topic'>{$result["username"]}：<br /><a href='posts.php?p={$result['PID']}'>{$result['title']}</a><br />
				<span style='font-size:12px;'>回复数：{$result['replycount']}<br />发布时间：{$result['createtime']}</span></div>";
			}
			echo '</div>';
		}
		DisconnectDb();
		echo '</div>';
	}
	latest_topics();
	?>
</div>
<div id="main_left">
	<div class="box">
		<?php
			ConnectDb();
			$mainrow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=1"));
			echo '<p class="nmp" style="float:left;margin-bottom:5px;">NEWorld Forum - NEWorld玩家们讨论与交流的自由空间！</p>';
			echo "<p class='nmp' style='font-size:12px;float:right;'>这里已有{$mainrow['replycount']}个主题帖！</p>";
			echo "<hr style='clear:both;'/>";
			//公告栏
			echo '<div class="txtbox">
					<marquee direction="up" style="height:100px;" id=m scrollamount="1" scrolldelay="20">';
			$result=mysql_query("SELECT * FROM broadcast");
			while($row=mysql_fetch_array($result)){
				echo '<p style="margin:10px 10%;">'.$row['content'].'</p>';
			}
			echo '</marquee></div>';
			/*
			echo '<div class="txtbox" style="height:32px;">
			<p id="noticeboard_upper" style="position:relative;"></p>
			<p id="noticeboard_lower" style="position:relative;"></p>
			</div><script>';
			$result=mysql_query("SELECT * FROM broadcast");
			while($row=mysql_fetch_array($result)){
				echo 'noticeboard_additem("'.$row['content'].'");';
			}
			echo 'noticeboard_scroll();</script>';
			*/
			//帖子列表
			echo '<div style="margin:0px -10px;">';
			$pageposts=50;
			$page=1;
			$maxpage=ceil($mainrow['replycount']/$pageposts);
			if(isset($_GET['pn']))$page=nfilter($_GET['pn'],true);
			$results = mysql_query("SELECT * FROM Posts WHERE parent=1 ORDER BY lastreplytime DESC LIMIT ".(($page-1)*$pageposts).",".($pageposts));
			while($result = mysql_fetch_array($results)){
				$content=indexpage_filter($result['content']);
				echo "<div class='topic clearfix'>";
				echo "<p class='nmp' style='font-size:18px;'><a href='posts.php?p={$result['PID']}'>{$result['title']}</a></p>";
				echo "<p class='nmp' style='font-size:14px;'>{$content}</p>";
				echo "<span style='font-size:12px;float:right;'>作者：<a href={$userinfoHost}?username={$result['username']}>{$result['username']}</a> | ";
				echo "<span style='color:" . getfontcolor($result['replycount']) . ";'>回复数：{$result['replycount']}</span>";
				echo "<br />最后回复：{$result['lastreplytime']} | 发布时间：{$result['createtime']}</span>";
				echo "</div>";
			}
			echo '</div>';
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
		?>
	</div>
	<div class="box" style="margin-top:10px;">
		<p class="nmp">发表新帖</p>
		<hr />
		<form id="postreply" action="post.php" method="post">
			<input type="hidden" name="type" value="0" readonly="true" />
			<p><input type="text" name="title" id="title" placeholder="标题" autocomplete="off" style="width:99%;height:18px;" class="txtbox" /></p>
			<input type="hidden" name="content" id="content" value="" />
			<div id="editor" contenteditable="true" class="txtbox"></div>
		</form>
		<p><button onclick="SubmitPost();" class="btn" style='color:#ffffff;background-color:#0099ff;'>发布</button></p>
	</div>
</div>
<?php loadFooter(); ?>

<?php
include_once("func.php");loadHeader();
?>
<div id="main_left">
	<div class="box">
		<?php
			ConnectDb();
			echo '<p class="nmp" style="margin-bottom:5px;">我的帖子 - '.getUsername().' [本页仅显示30条记录]</p>';
			echo "<hr />";
			$results = mysql_query("SELECT * FROM Posts WHERE username='".getUsername()."' and parent=0 ORDER BY createtime DESC LIMIT 0,30");
			while($result = mysql_fetch_array($results)){
				$content=$result['content'];
				if(strlen($content)>1024)$content=GBsubstr($content,0,1024)." ...";
				echo "<div class='topic clearfix'>";
				echo "<p class='nmp' style='font-size:18px;'><a href='posts.php?p={$result['PID']}'>{$result['title']}</a></p>";
				echo "<p class='nmp' style='font-size:14px;'>{$content}</p>";
				echo "<span style='font-size:12px;float:right;'>回复数：{$result['replycount']} | 最后回复：{$result['lastreplytime']} | 发布时间：{$result['createtime']}</span>";
				echo "</div>";
			}
			DisconnectDb();
		?>
	</div>
</div>
<div id="main_right">
	<?php loaduserinfo() ?>
	<p class="nmp">最新回复：</p>
	<div class="box">
		[coming soon...]
	</div>
</div>
<?php loadFooter(); ?>
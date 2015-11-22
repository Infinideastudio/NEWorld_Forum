<?php
include_once("func.php");
ConnectDb();
$pid=nfilter($_GET['p']);
$result=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID = '" . $pid . "'"));
$un=$result['username'];
$ppid=$result['parent'];
$title=$result['title'];
$rootrow=$result;
$isroot=true;
if($ppid!=1){
	$isroot=false;
	$rootrow=mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID=".findroot($pid)));
	$title="回复：" . $rootrow['title'];
}
loadHeader($title . " - NEWorld Forum");
?>
<div id="main_left">
	<div class="box clearfix">
		<?php
		echo '<form action="post.php" method="post">';
		echo '<input type="hidden" name="type" value="1" readonly="true">';
		echo '<input type="hidden" name="pid" value="' . $pid . '" readonly="true">';
		echo "<div style='float:left;'><h2>" . $title . "</h2></div>";
		if(!$isroot){
			echo "<div style='float:right;'>";
			echo "<input type='button' value='返回上一级' class='btn' onclick=\" window.open('posts.php?p=".$ppid."','_self') \" />";
			echo " | ";
			echo "<input type='button' value='返回所在帖子' class='btn' onclick=\" window.open('posts.php?p=".$rootrow['PID']."','_self') \" />";
			echo "</div>";
		}
		echo "<hr style='clear:both;'/>";
		echo '<p>' . $result['content'] . '</p>';
		echo "<p class='nmp' style='font-size:12px;float:right;'>";
		echo "作者：{$result['username']} | 回复数： {$result['replycount']} | 最后回复：{$result['lastreplytime']} | 发布时间： {$result['createtime']}</p>";
		$candelete=false;
		if(delete_auth($pid)){
			echo '<p><input type="submit" value="删除" class="btn" /></p>';
			$candelete=true;
		}
		echo '</form>';
		
		function show_replies($ppid,$deep,$candelete){
			$results = mysql_query("SELECT * FROM Posts WHERE parent='" . $ppid . "' ORDER BY floor ASC");
			$count=0;
			while($result = mysql_fetch_array($results)){
				$content=$result['content'];
				$pid=$result['PID'];
				$un=$result['username'];
				echo '<div class="topic clearfix">';
				echo "<p class='nmp'>[".($deep==0?"":$deep."层堆叠,")."{$result['floor']}楼] {$result['username']}:<br />{$result['content']}</p>";
				echo "<p class='nmp' style='font-size:12px;float:right;'>";
				echo "回复数： {$result['replycount']} | 最后回复：{$result['lastreplytime']} | 发布时间： {$result['createtime']}</p>";
				echo '<input type="button" value="回复" class="btn" onclick="showreplybox(' . $pid . ')" />';
				if(!$candelete && delete_auth($pid))$candelete=true;
				if($candelete){
					echo '<form action="post.php" method="post" style="display:inline;">
					<input type="hidden" name="type" value="1" readonly="true">
					<input type="hidden" name="pid" value="' . $pid . '" readonly="true">
					<input type="submit" value="删除" class="btn" />
					</form>';
				}
				echo '<div id="replybox_'.$pid.'" style="display:none;padding:10px;"></div>';
				echo '</div>';
				if($result['replycount']){
					echo '<div class="box reply" style="z-index:'.$deep.';">';
					show_replies($pid,$deep+1,$candelete);
					echo '</div>';
				}
			}
			
		}
		?>
	</div>
	<?php
	if($result['replycount']){
		echo '<div class="box" style="padding:0px;margin-top:10px;">';
		show_replies($pid,0,$candelete);
		echo '</div>';
	}
	DisconnectDb();
	?>
	
	<div class="box" style="margin-top:10px;">
		<p class="nmp">发表回复</p>
		<hr />
		<form id="postreply" action="post.php" method="post">
			<input type="hidden" name="type" value="2" readonly="true" />
			<input type="hidden" name="pid" value="<?php echo $_GET['p']; ?>" readonly="true" />
			<input type="hidden" name="content" id="content" value="" />
			<div id="editor" contenteditable="true" class="txtbox"></div>
		</form>
		<p><button onclick="SubmitPost();" class="btn" style='color:#ffffff;background-color:#0099ff;'>回复</button></p>
	</div>
	
</div>

<div id="main_right">
	<?php
	loaduserinfo();
	ConnectDb();
	$results = mysql_query("SELECT * FROM Posts WHERE parent=".$pid." ORDER BY createtime DESC LIMIT 0,5");
	if(mysql_num_rows($results)){
		echo '<p class="nmp">本帖的最新回复：</p>
				<div class="box" style="padding:0px;">';
		while($result = mysql_fetch_array($results)){
			echo "<div class='topic'>{$result["username"]}：<br /><a href='posts.php?p={$result['PID']}'>{$result['content']}</a><br />
			<span style='font-size:12px;'>回复数：{$result['replycount']}<br />发布时间：{$result['createtime']}</span></div>";
		}
		echo '</div>';
	}
	DisconnectDb();
	?>
</div>
<?php loadFooter(); ?>
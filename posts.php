<?php include_once("func.php"); loadHeader(); ?>
 <a class="nmp" href="javascript:history.go(-1)">返回上一页</a>
	<?php
	ConnectDb();
	$result = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID='" . $_GET['p'] . "'"));
 $pid=$result['PID'];
	$un=$result['username'];
	$ppid=$result['parent'];
	echo '<form action="post.php" method="post">';
	echo '<input type="hidden" name="type" value="1" readonly="true">';
	echo '<input type="hidden" name="pid" value="' . $pid . '" readonly="true">';
	echo '<input type="hidden" name="username" value="' . $un . '" readonly="true">';
	echo '<input type="hidden" name="parent" value="' . $ppid . '" readonly="true">';
	echo "<h1>" . $result['title'] . "</h1>";
	echo '<p>' . $result['content'] . '</p>';
	if($un==getUsername()){
		echo '<input type="submit" value="删除" class="btn" />';
	}
	echo '</form>';
 
	function show_replies($ppid,$deep,$firstlevel=false){
		$results = mysql_query("SELECT * FROM Posts WHERE parent='" . $ppid . "' ORDER BY floor ASC");
		$count=0;
		while($result = mysql_fetch_array($results)){
			$content=$result['content'];
			$pid=$result['PID'];
			$un=$result['username'];
			echo '<form action="post.php" method="post">';
			echo '<input type="hidden" name="type" value="1" readonly="true">';
			echo '<input type="hidden" name="pid" value="' . $pid . '" readonly="true">';
			echo '<input type="hidden" name="username" value="' . $un . '" readonly="true">';
			echo '<input type="hidden" name="parent" value="' . $ppid . '" readonly="true">';
			echo "<p>" . str_repeat("<span style='margin:0 2em;display:inline-block;'>",$deep);
			echo "$un: $content</p>";
			echo "<p>" . str_repeat("<span style='margin:0 2em;display:inline-block;'>",$deep);
			echo '<input type="button" value="回复" onclick="window.location=\'posts.php?p=' . $pid . '\';" class="btn" />';
			if($un==getUsername()){
			 echo '<input type="submit" value="删除" class="btn" />';
			}
			echo '</p></form>';
			show_replies($pid,$deep+1);
		}
	}
	show_replies($_GET['p'],0,true);

	DisconnectDb();
	?>
	
	<form action="post.php" method="post">
		<input type="hidden" name="type" value="2" readonly="true">
		<input type="hidden" name="pid" value="<?php echo $_GET['p']; ?>" readonly="true">
		<textarea name="content" id="content" placeholder="内容" required="true" style="resize: none; width:500px; height: 300px;"></textarea>
		<p><button type="submit">回复</button></p>
	</form>
<?php loadFooter(); ?>
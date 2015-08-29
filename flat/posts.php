<?php include_once("func.php"); loadHeader(); ?>
	<a href="index.php">返回首页</a>
	<?php
	ConnectDb();

	$result = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID='" . $_GET['p'] . "'"));

	echo "<h1>" . $result['title'] . "</h1>";
	echo "<p>" . $result['content'] . "</p>";

	function show_replies($pid,$deep,$firstlevel=false){
		$result = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID='" . $pid . "'"));
		if($result['children']==""||$result['children']==",") return;
		$replys=explode(",",$result['children']);
		$count=0;
	 	foreach ($replys as $reply){
	 		$count=$count+1;
	 	    if($count!=1){
	 	    	$contenta = mysql_fetch_array(mysql_query("SELECT * FROM Posts WHERE PID='" . $reply . "'"));
				$content=$contenta['content'];
				$pid=$contenta['PID'];
				$un=$contenta['username'];
	        	if($firstlevel) echo "<p>$count(PID $pid;Username $un) ： $content</p>"; 
	        	else echo "<p>" . str_repeat("<span style='margin:0 2em;display:inline-block;'>",$deep) . "楼中楼(PID $pid;Username $un)：$content</p>"; 
	        	show_replies($reply,$deep+1);
	  	    }
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
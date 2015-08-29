<!DOCTYPE html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<title>Admin Tools</title>
</head>
<body>
	<form action="post.php" method="post">
		<input type="hidden" name="type" value="1" readonly="true">
		<p><input type="text" name="pidparent" id="pidparent" placeholder="PID - Parent" autocomplete="off" style="width:50px;"></p>
		<p><input type="text" name="pid" id="pid" placeholder="PID" autocomplete="off" style="width:50px;"></p>
		<p><input type="password" name="pwd" id="pwd" placeholder="删除密码" style="width:100px;"></p>
		<p><input type="submit" value="删除" class="btn" /></p>
	</form>
</body>
</html>
<!DOCTYPE html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<?php
	if(isset($_POST['username'])){
		function Post($url, $post) {
		    if (is_array($post)) {
		        ksort($post);
		        $content = http_build_query($post);
		        $content_length = strlen($content);
		        $options = array(
		            'http' => array(
		                'method' => 'POST',
		                'header' =>
		                "Content-type: application/x-www-form-urlencoded\r\n" .
		                "Content-length: $content_length\r\n",
		                'content' => $content
		            )
		        );
		        return substr(file_get_contents($url, false, stream_context_create($options)),0,1);
		    }
		}
		$data = array
		    (
		    'username' => $_POST['username'],
		    'password' => $_POST['pwd']
		);
		 
		$response = Post('http://blog.neworldsite.gq/admin/islogin.php', $data);
		setcookie('islogin',(int)$response);
		setcookie('username',$_POST['username']);
		if((int)$response==1) echo "登录成功！"; else{
			echo "登录失败！用户名或密码错误！";
			sleep(5);
		}
	}
	?>
	<form action="login.php" method="post">
		<p><input type="text" name="username" id="username" placeholder="用户名" style="width:100px;"></p>
		<p><input type="password" name="pwd" id="pwd" placeholder="密码" style="width:100px;"></p>
		<p><button type="submit">Login</button></p>
	</form>
<div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1255967045&web_id=1255967045" language="JavaScript"></script></div>

</body>
</html>
<?php
	include_once("func.php"); 
	loadHeader();
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
		if((int)$response==1){
			echo "登录成功！一秒后将自动跳转！";
			echo '<meta http-equiv="Refresh" content="1; url=index.php">';
		}else{
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
	<a href="http://blog.neworldsite.gq/admin/register.php">没有账号？免费注册</a>
<?php loadFooter(); ?>
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
		        return file_get_contents($url, false, stream_context_create($options));
		    }
		}
		$data = array
		    (
		    'username' => $_POST['username'],
		    'password' => $_POST['pwd']
		);
		 
		$response = Post($verifyHost, $data);
		setcookie('islogin',$response=="1"?"0":"1",time()+2592000);
		setcookie('token',encrypt($response, $_SESSION["key"]),time()+2592000);
		if($response!="1"){
			echo "登录成功！一秒后将自动跳转！";
			echo '<meta http-equiv="Refresh" content="1; url=index.php">';
		}else{
			echo "登录失败！用户名或密码错误！";
			sleep(5);
		}
	}
	?>
	<div style="text-align:center;">
		<div class="box" style="width:50%;height:50%;margin:12% auto;padding:6% 3%;">
			<form action="login.php" method="post">
				<p><input type="text" name="username" id="username" placeholder="用户名" class="txtbox" style="width:180px;"></p>
				<p><input type="password" name="pwd" id="pwd" placeholder="密码" class="txtbox" style="width:180px;"></p>
				<p><button type="submit" class="btn">登录</button></p>
			</form>
			<a href="<?php echo $registerHost; ?>">没有账号？免费注册</a>
		</div>
	</div>
<?php loadFooter(); ?>
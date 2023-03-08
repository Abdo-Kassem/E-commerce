<?php
	session_start();
	if(isset($_SESSION['user'])){
		header('location:index.php');
		exit();
	}
	$title='login'; //title of page
	//include database connection file
	include_once 'admin\php\config.php';
	//include function file
	include_once 'admin\include\function.php';
	include_once 'navbar.php';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$userName=$_POST['username'];
		$password=$_POST['password'];
		$stmt=$connect->prepare(
			"select user_name,user_id from users where user_name='$userName' and user_pass=$password"
		);
		$stmt->execute();
		$info=$stmt->fetch();
		$count=$stmt->rowcount();
		if($count==1){
			$_SESSION['user']=$userName;
			$_SESSION['user_id']=$info['user_id'];
			header('location:index.php');
			exit();
		}
	}
?>
<h1 class="header">Login</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form">
	<input type="text" name="username" placeholder="username" autocomplete="off" class="input" required='required'>
	<input type="password" name="password" placeholder="password" autocomplete="new-password" class="input" required='required'>
	<input type="submit" value="login" class="submit">
</form>
<?php
	//start session
	session_start();
	if(isset($_SESSION['username'])){
		header('location:dashboard.php');
	}
	$title='login'; //title of page
	$navbar=false;  //tell us add navbar file or not
	//include database connection file
	include_once 'config.php';
	//include function file
	include_once '..\include\function.php';
	//include header file
	include_once '..\include\template\header.php';
	include_navbar(); //function from function file to include navbar
	//check if page requested by request method
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$username = $_POST['username'];
		//encrept password of user
		$password = $_POST['password'];
		$stmt=$connect->prepare('select user_id,user_name,user_pass from Users where user_name=?
		and user_pass=? and user_is_admin=1');
		$stmt->execute(array($username,$password));
		$data=$stmt->fetch();
		print_r($data);
		$count=$stmt->rowCount();
		if($count>0){
			$_SESSION['username']=$username;   //create session of user if correct
			$_SESSION['user_id']=$data['user_id'];
			header('location:dashboard.php');
			exit();   //terminate script
		}
		
	}
?>
	<h1 class="header">Login</h1>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form">
		<input type="text" name="username" placeholder="username" autocomplete="off" class="input">
		<input type="password" name="password" placeholder="password" autocomplete="new-password" class="input">
		<input type="submit" value="login" class="submit">
	</form>
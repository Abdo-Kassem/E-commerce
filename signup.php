<?php
	session_start();
	if(isset($_SESSION['user'])){
		header('location:index.php');
	}
	$title='signup'; //title of page
	//include database connection file
	include_once 'admin\php\config.php';
	//include function file
	include_once 'admin\include\function.php';
	include 'navbar.php';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$date=new dateTime();
		$userName=$_POST['username'];
		$password=$_POST['password'];
		$email=$_POST['email'];
		$fullName=$_POST['full_name'];
		$errorMessage=array();
		$stmt=$connect->prepare(
			"select user_name,user_email from users where user_name=? or user_email=?"
		);
		$stmt->execute(array($userName,$email));
		$res=$stmt->fetch();
		if($res['user_name']==$userName){
			$errorMessage[]='userName already exist';
		}
		if($res['user_email']==$email){
			$errorMessage[]='email already exist';
		}
		if(count($errorMessage)==0){
			$stmt=$connect->prepare(
			"insert into users(user_name,user_pass,user_email,user_full_name,
			 user_is_approval,user_is_admin,user_date) values(?,?,?,?,?,?,?)"
			);
			$stmt->execute(array(
				$userName,$password,$email,$fullName,0,0,$date->format('Y-m-d H:i:s')
			));
			$count=$stmt->rowcount();
			if($count==1){
				$_SESSIOn['user']=$userName;
				header('location:login.php');
				exit();
			}
		}
	}
?>
<h1 class="header">signUp</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form">
	<input minlength='3' maxlength='20' type="text" name="username" placeholder="UserName" autocomplete="off" class="input" required='required'>
	<input type="password" name="password" placeholder="Password" autocomplete="new-password" class="input" required='required'>
	<input type="email" name="email" placeholder="Email"  class="input" required='required'>
	<input type="text" name="full_name" placeholder="Full Name"  class="input" required='required'>
	<input type="submit" value="signUp" class="submit">
</form>
<?php
	if(isset($errorMessage))
		foreach($errorMessage as $error)
			echo "<div class='message'>$error</div>";
?>
<?php
	$dsn='mysql:host=127.0.0.1;dbname=shoping';
	$user_name='root';
	$password='';
	try{
		$connect=new PDO($dsn,$user_name,$password);
	}catch(PDOException $e){
		echo 'failed to connection'.'<br>'.$e->getMessage();
	}
?>
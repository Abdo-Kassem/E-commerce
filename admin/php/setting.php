<?php
	ob_start();
	session_start();
	if(isset($_SESSION['username'])&&isset($_SESSION['user_id'])){
		$navbar=true;
		$title='comment';
		//include databas connection file
		include_once 'config.php';
		//include function file
		include_once '..\include\function.php';
		//include header file
		include_once '..\include\template\header.php';
		//include navebar function
		include_navbar();
?>
		<h1 class='header'>Setting</h1>
		<div class='setting-manager'>
			<a href="member.php?action=manage" class="add-new-member">
				 Manage Member
			</a>
			<a class='add-new-member' href='categories.php?action=manage'>
				Manage Category
			</a>
			<a href="item.php?action=manage" class="add-new-member">
				Manage Item
			</a>
			<a href="comment.php?action=manage" class="add-new-member">
				Manage Comment
			</a>
		</div>
<?php
		
	}else{
		header('location:index.php');
	}
	ob_end_flush();
?>
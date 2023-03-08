<?php
	include_once '..\include\template\header.php';
?>
<div class="navbar"> 
	<div class='container'>
		<ul>
			<div class="left">
				<li>
					<a href="dashboard.php">Home</a>
				</li>
			</div>
			<div class="right">
				<li>
					<a href="categories.php">Categories</a>
				</li>
				<li>
					<a href="item.php">Items</a>
				</li>
				<li>
					<a href="member.php?">Member</a>
				</li>
				<li>
					<a href="comment.php?">Comment</a>
				</li>
				<li class="li_drop_down">
					<a class="active" href="#"><?php echo $_SESSION['username'];?></a>
					<div class="drop_down">
						<ul>
							<li><a href="member.php?action=edit&userId=<?php echo $_SESSION['user_id'];?>">Edit My Profile</a></li>
							<li><a href="setting.php">Setting</a></li>
							<li><a href="..\..\index.php">Shoping</a></li>
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</div>
				</li>
			</div>
			<div class="clear"></div> <!--clear float effect and replace to overflow hidden-->
		</ul>
		<div class="clear"></div> <!--clear float effect and replace to overflow hidden-->
	</div>
</div>
<?php
	include '..\include\template\fotter.php'
?>
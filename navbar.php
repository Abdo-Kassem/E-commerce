<?php
	include_once 'header.php';
?>
<div class="top-navbar">
	<div class='container'>
		<?php
			if(!isset($_SESSION['user'])){
		?>
		<div class='login-signup'>
			<a href='login.php' class='login'>login</a>
			<a href='signup.php' class='signup'>signUp</a>
		</div>
		<?php
			}else{
				echo "<div class='image-user'>";
					echo "<a href='profile.php'>";
						$stmt=$connect->prepare(
							"select user_image from users where user_id=?"
						);
						$stmt->execute(array($_SESSION['user_id']));
						$user_image=$stmt->fetch();
						echo "<img class='circle-image' src='images\profile\\".$user_image['user_image']."'>";
						echo "<span href='profile.php' >{$_SESSION['user']}</span>";
					echo "</a>";
				echo "</div>";
				echo '<div class="login-signup">';
					if($title!='profile')
						echo '<a class="profile" href="profile.php">profile</a> ';
					echo '<a class="logout" href="logout.php">logout</a>';
					if($title=='profile')
						echo '<a class="add-ads-bottom" href="add_item.php">Add Ads</a>';
				echo "</div>";
				echo "<div class='clear'></div>";
			}
		?>
	</div>
</div>
<div class="navbar"> 
	<div class='container'>
		<ul>
			<div class="left">
				<li>
					<a href="index.php">Home</a>
				</li>
			</div>
			<div class="right">
			<?php
				$cats=get_cats();
				foreach($cats as $cat){
					echo '<li><a href="category.php?catId='.$cat['cat_id'].'&catName='.str_replace(' ','-',$cat['cat_name']).'">'.$cat['cat_name'].'</li></a>';
				}
			?>
			</div>
			<div class="clear"></div> <!--clear float effect and replace to overflow hidden-->
		</ul>
		<div class="clear"></div> <!--clear float effect and replace to overflow hidden-->
	</div>
</div>
<?php
	include 'admin\include\template\fotter.php'
?>
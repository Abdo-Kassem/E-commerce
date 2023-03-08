<?php
	session_start(); //start session must be set to tell that you belong this page to session
	if(isset($_SESSION['user'])){
		$title='profile'; //title of page
		//include database connection file
		include_once 'admin\php\config.php'; //contain database connection
		//include function file
		include_once 'admin\include\function.php';//include function file
		include_once 'navbar.php'; //incude navbar
		?>
		<!-- start user data section-->
		<div class="container">
			<div class="user-data">
				<?php
					$stmt=$connect->prepare(
						'select * from users where user_name=?'
					);
					$stmt->execute(array($_SESSION['user']));
					$data=$stmt->fetch();
				?>
				<h1>Member Data</h1>
				<ul class="data">
					<li>UserName : <?php echo $data['user_name']; ?></li>
					<li>FullName : <?php echo $data['user_full_name']; ?></li>
					<li>Email : <?php echo $data['user_email']; ?></li>
					<li>Added Date : <?php echo $data['user_date']; ?></li>
					<li>Favorite : <?php ?></li>
				</ul>
			</div>
			<!-- end user comment section-->
			<!-- start user ads section-->
			<div class="user-ads">
				<h1>Ads</h1>
				<?php
					$stmt=$connect->prepare(
						'select * from items where user_id=?'
					);
					$stmt->execute(array($data['user_id']));
					$items=$stmt->fetchAll();
					if($stmt->rowCount()>0){
				?>
				<div class='item-manage'>
				<?php
					foreach($items as $item){
						echo"<div class='item'>";
							echo "<img src='image\image.jpg'>";
							echo"<div class='caption'>";
								echo'<h3>'.$item['item_name'].'</h3>';
								echo'<p class="description">'.$item['item_description'].'</p>';
								echo'<p class="date">'.$item['item_add_date'].'</p>';
								echo'<span>'.$item['item_price'].'</span>';
							echo"</div>";
						echo"</div>";
					}
					echo "<div class='clear'></div>";
				?>
				</div>
				<?php
				}else{
					echo'<a class="add-new-member" href="add_item.php">
						add new ads</a>';
				} 
				?>
			</div>
			<!-- end user ads section-->
			<!-- start user comment section-->
			<div class="user-comments">
				<?php
					$stmt=$connect->prepare(
						'select com_value,com_date,item_name from comments 
						 inner join items on comments.item_id=items.item_id and comments.user_id=?'
					);
					$stmt->execute(array($data['user_id']));
					$coms=$stmt->fetchAll();
					if($stmt->rowCount()>0){
						echo '<h1>Comments</h1>';
						echo"<div class='comments'>";
							foreach($coms as $com){
								echo"<div class='comment'>";
									echo "<p class='comment_value'>{$com['com_value']}</p>";
									echo"<div class='caption'>";
										echo'<span>Item Name : '.$com['item_name'].'</span>';
										echo'<span class="date">Added Date : '.$com['com_date'].'</span>';
									echo"</div>";
								echo"</div>";
							}
					}
					?>
				</div>
			</div>
			<!-- end user comment section-->
		</div>
	<?php
	}else{
		header('location:index.php');
	}
	?>
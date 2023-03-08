<?php
	session_start();
	if(isset($_SESSION['username']) && isset($_SESSION['user_id'])){
		$title='dashboard';  //title of page
		$navbar=true;  //tell us add navbar file or not
		//include databas connection file
		include_once 'config.php';
		//include function file
		include_once '..\include\function.php';
		//include header file
		include_once '..\include\template\header.php';
		//include navebar function
		include_navbar();
	?>
		<h1 class="header">Dashboard</h1>
		<div class="container">
			<div class="total-member dashboard-common">
				<h3>Total member</h3>
				<span><a href="member.php"><?php echo count_item('user_id','Users');?></a></span>
			</div>
			<div class="bending-member dashboard-common">
				<h3>pending member</h3>
				<span><a href="member.php?action=manage&show=approve"><?php echo count_item('user_id','Users',true);?></a></span>
			</div>
			<div class="total-item dashboard-common">
				<h3>Total item</h3>
				<span>
					<a href="item.php">
					<?php
						$stmt=$connect->prepare(
							"select count(item_id) from items"
						);
						$stmt->execute();
						echo $stmt->fetchColumn();
					?>
					</a>
				</span>
			</div>
			<div class="total-comment dashboard-common">
				<h3>Total comment</h3>
				<span><a href="comment.php">
					<?php
						echo count_item('com_id','comments');
					?>
				</a></span>
			</div>
			<div class="clear"></div>
			<div class='latest'>
				<?php $number=4?>
				<div class="latest-member latest-common">
					<h4>
						<i class="fa fa-users"></i>
						Latest <span><?php echo $number;?></span> Registered user
					</h4>
					<span class="line"></span>
					<div class="data">
					<?php
						$data=get_latest('*','Users','user_id',$number);
						echo '<ul>';
						foreach($data as $row){
							echo '<li>'."<a href='member.php?action=edit&userId=".$row['user_id']."'".'>'.$row['user_name'].'</a></li>';
						}
						echo '</ul>';
					?>
					</div>
				</div>
				<div class="latest-item latest-common">
					<h4>
						<i class="fa fa-users"></i>
						Latest Item <?php echo $number;?>
					</h4>
					<span class="line"></span>
					<div class="data">
						
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	<?php	
	}else{
		header('location:index.php');
		exit();
	}
?>
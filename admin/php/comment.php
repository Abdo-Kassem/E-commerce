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
		$action = (isset($_GET['action']) && $_GET['action']!='')?$_GET['action']:'manage';
		
		//start manage page
		if($action=='manage'){
			$stmt=$connect->prepare(
					"select com_id,com_value,com_date,item_name,user_name
					 from comments inner join items on
					 comments.item_id=items.item_id inner join users on
					users.user_id=comments.user_id"
				);
				$stmt->execute();
				$comments=$stmt->fetchAll();
				
				?>
				<div class="container">
					<h1 class="header">Manage Comments</h1>
					<table class="table">
						<tr>
							<th>Comment</th>
							<th>Date</th>
							<th >Item</th>
							<th >Owner</th>
							<th class='control-item'>Controle</th>
						</tr>
				<?php
						foreach($comments as $comment){
								echo "
									<tr>
										<td>{$comment['com_value']}</td>
										<td>{$comment['com_date']}</td>
										<td>{$comment['item_name']}</td>
										<td>{$comment['user_name']}</td>
										<td class='approved-links'>
											<a href=comment.php?action=delete&comId={$comment['com_id']} class='delete-link delete-full-width'>delete</a>
								        </td>
									</tr>
								";
						}
			?>
			
					</table>
				</div>
	<?php 
		}
		else if($action=='delete'){
			if($_SERVER['REQUEST_METHOD']=='GET'){
				if(check_id('comId')=='n'){
					$comId=intval($_GET['comId']);
					$stmt=$connect->prepare(
						"delete from comments where com_id=$comId"
					);
					$stmt->execute();
					if($stmt->rowCount()>0){
						echo '<div class="message">one comment deleted</div>';
						check_http_referer(2);
					}else{
						echo '<div class="message">no comment deleted</div>';
						check_http_referer(2);
					}
				}
				else if(check_id('catId')=='s'){
					echo "<div class='message'>comId must be number</div>";
					check_http_referer(2);
				}
				else{
					echo "<div class='message'>comId must be set</div>";
					check_http_referer(2);
				}
			}else{
				check_http_referer(0);
			}
		}
		//end delete page
		else{
			echo "<div class='message'>page not found</div>";
			header("refresh:2;url=dashboard.php");
		}
	}else{
		header('location:index.php');
	}
	ob_end_flush();
?>
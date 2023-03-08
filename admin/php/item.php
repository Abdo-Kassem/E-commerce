<?php
	ob_start();
	session_start();
	if(isset($_SESSION['username'])&&isset($_SESSION['user_id'])){
		$navbar=true;
		$title='item';
		//include databas connection file
		include_once 'config.php';
		//include function file
		include_once '..\include\function.php';
		//include header file
		include_once '..\include\template\header.php';
		//include navebar function
		include_navbar();
		if(isset($_GET['action']) && !empty($_GET['action'])){
			$action=$_GET['action'];
		}else{
			$action='manage';
		}
		switch($action){
			//start manage page
			case 'manage':
				$stmt=$connect->prepare(
					"select item_approve,item_name,item_id,item_description,item_status,cat_name,
					user_name,item_price from items inner join categories on
					items.cat_id=categories.cat_id inner join users on
					users.user_id=items.user_id"
				);
				$stmt->execute();
				$items=$stmt->fetchAll();
				
				?>
				<div class="container">
					<h1 class="header">Manage Items</h1>
					<table class="table">
						<tr>
							<th class='user-id'>ItemId</th>
							<th>Name</th>
							<th>Description</th>
							<th>status</th>
							<th class='user_id'>Price</th>
							<th >Category</th>
							<th >Owner</th>
							<th class='control-item'>Controle</th>
						</tr>
				<?php
						foreach($items as $item){
								echo "
									<tr>
										<td>{$item['item_id']}</td>
										<td>{$item['item_name']}</td>
										<td><p class='description'>{$item['item_description']}</p></td>
										<td>{$item['item_status']}</td>
										<td>{$item['item_price']}</td>
										<td>{$item['cat_name']}</td>
										<td>{$item['user_name']}</td>
										<td class='approved-links'>
											<a href=item.php?action=edit&itemId={$item['item_id']} class='edit-link'>Edit</a>
											<a href=item.php?action=delete&itemId={$item['item_id']} class='delete-link'>delete</a>
									";//end echo
											if($item['item_approve']==0){
												echo "<a href=item.php?action=approve&itemId={$item['item_id']} class='approve edit-link'>Approve</a>";
											}
								echo    "</td>
									</tr>";//end echo
						}
			?>
			
					</table>
					<a href="item.php?action=add" class="add-new-member">
						<i class="fa fa-plus plus-icon"></i>
						Add New Item
					</a>
				</div>
			<?php
			break;
			//end manage page
			//start edit page
			case 'edit':
				if(check_id('itemId')=='n'){
					$item_id=intval($_GET['itemId']);
					$data=query('item_name,item_description,item_price,item_country_made,
					item_status,cat_id','items',"item_id=$item_id");?>
				
					<h1 class="header">Edit Item</h1>
					<form class="form" method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=update&itemId='.$item_id; ?> >
						<input class="input" type="text" name="name" placeholder="name of item" required="required" value="<?php echo $data[0]['item_name'];?>">
						<input class="input" type="text" name="description" placeholder="description" required="required" value="<?php echo $data[0]['item_description'];?>">
						<input class="input" type="text" name="price" placeholder="price" required="required" value="<?php echo $data[0]['item_price'];?>">	
						<input class="input" type="text" name="country_made" placeholder="country made" value="<?php echo $data[0]['item_country_made'];?>"> 
						<select class='input' name="status">
							<option value='new' <?php if($data[0]['item_status']=='new') echo 'selected';?>>new</option>
							<option value='like new' <?php if($data[0]['item_status']=='like new') echo 'selected';?>>like new</option>
							<option value='worked' <?php if($data[0]['item_status']=='worked') echo 'selected';?>>worked</option>
							<option value='old' <?php if($data[0]['item_status']=='old') echo 'selected';?>>old</option>
						</select>	
						<select class='input' name="category">
							<?php
								$stmt=$connect->prepare('select cat_name,cat_id from categories');
								$stmt->execute();
								$cats=$stmt->fetchAll();
								foreach($cats as $cat){
									echo '<option value="'.$cat['cat_id'].'"'.($data[0]['cat_id']==$cat['cat_id']?'selected':'').'>'.$cat['cat_name'].'</option>';
								}
							?>
						</select>
						<input class="submit" type="submit" value="Save" >
					</form>
			<?php	
					$stmt=$connect->prepare(
						"select com_id,com_value,com_date,item_name,user_name
						 from comments inner join items on
						 comments.item_id=items.item_id and item_name=? inner join users on
						 users.user_id=comments.user_id "
					);
					$stmt->execute(array($data[0]['item_name']));
					$comments=$stmt->fetchAll();
					
					?>
					<?php
					if($stmt->rowCount()>0){?>
						<div class="container">
						<h1 class="header">Comments</h1>
						<table class="table">
							<tr>
								<th>Comment</th>
								<th>Date</th>
								<th >Owner</th>
								<th class='control-item'>Controle</th>
							</tr>
							<?php
								foreach($comments as $comment){
									if($comment['item_name']==$data[0]['item_name']){
										echo "
											<tr>
												<td>{$comment['com_value']}</td>
												<td>{$comment['com_date']}</td>
												<td>{$comment['user_name']}</td>
												<td class='approved-links'>
													<a href=comment.php?action=delete&comId={$comment['com_id']} class='delete-link delete-full-width'>delete</a>
												</td>
											</tr>
										";
									}
								}
							?>
					
							</table>
						</div>
					<?php
					}
				}
				else if(check_id()=='s'){
					echo "<div class='message'>user_id must be number</div>";
					check_http_referer(2);
				}

				else{
					echo "<div class='message'>user_id must be set</div>";
					check_http_referer(2);
				}
			break;
			//end edit page
			//start update page
			case 'update':
				if(check_id('itemId')=='n'){
					$itemId=intval($_GET['itemId']);
					$name=$_POST['name'];
					$description=$_POST['description'];
					$price=$_POST['price'];
					$country_made=$_POST['country_made'];
					$status=$_POST['status'];
					$category=$_POST['category'];
					$stmt=$connect->prepare(
						"update items set item_name=?,item_description=?,
						 item_price=?,item_country_made=?,item_status=?,
						 cat_id=? where item_id=$itemId"
					);
					$stmt->execute(array($name,$description,$price,$country_made,
					$status,$category));
					if($stmt->rowCount()>0){
						echo '<div class="message">one item updated</div>';
						header('refresh:2;url=item.php');
					}else{
						echo '<div class="message">no item updated</div>';
						check_http_referer(2);
					}
				}
				else if(check_id('catId')=='s'){
					echo "<div class='message'>itemId must be number</div>";
					check_http_referer(2);
				}
				else{
					echo "<div class='message'>itemId must be set</div>";
					check_http_referer(2);
				}
			break;
			//end update page
			//start add page
			case 'add':?>
				<h1 class="header">Add Item</h1>
				<form class="form" method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=insert'; ?> >
					<input class="input" type="text" name="name" placeholder="name of item" required="required">
					<input class="input" type="text" name="description" placeholder="description" required="required">
					<input class="input" type="text" name="price" placeholder="price" required="required">	
					<input class="input" type="text" name="country_made" placeholder="country made">
					<input class="input" type="file" name="image" placeholder="image">
					<select class='input' name="status">
						<option value='new' selected>new</option>
						<option value='like new'>like new</option>
						<option value='woeked'>worked</option>
						<option value='old'>old</option>
					</select>	
					<select class='input' name="category">
						<?php
							$stmt=$connect->prepare('select cat_name,cat_id from categories');
							$stmt->execute();
							$cats=$stmt->fetchAll();
							foreach($cats as $cat){
								echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].'</option>';
							}
						?>
					</select>
					<select class='input' name="user">
						<?php
							$stmt=$connect->prepare('select user_name,user_id from users');
							$stmt->execute();
							$users=$stmt->fetchAll();
							foreach($users as $user){
								echo '<option value="'.$user['user_id'].'">'.$user['user_name'].'</option>';
							}
						?>
					</select>
					<input class="submit" type="submit" value="Add Item" >
				</form>
			<?php
			break;
			//end add page
			//start insert page
			case 'insert':
				if($_SERVER['REQUEST_METHOD']=="POST"){
					$name=$_POST['name'];
					$description=$_POST['description'];
					$price=$_POST['price'];
					$status=$_POST['status'];
					$country_made=$_POST['country_made'];
					$cat_id=$_POST['category'];
					$user_id=$_POST['user'];
					$stmt=$connect->prepare(
						"insert into items(item_name,item_description,
						item_price,item_status,item_country_made,
						item_add_date,item_approve,cat_id,user_id)values(?,?,?,?,?,?,0,?,?)"
					);
					$stmt->execute(
						array($name,$description,$price,$status,
						$country_made,time(),$cat_id,$user_id)
					);
					if($stmt->rowCount()>0){
						echo '<div class="message">one item added</div>';
						header('refresh:2;url=item.php');
					}else{
						echo '<div class="message">no item added</div>';
						check_http_referer(2);
					}
				}else{
					check_http_referer(0);
				}
			break;
			//end insert page
			//start delete page
			case 'delete':
				if(check_id('itemId')=='n'){
					$item_id=intval($_GET['itemId']);
					if(check_item('item_id','items',"item_id={$item_id}")){
						$stmt=$connect->prepare(
							'delete from items where item_id=?'
						);
						$stmt->execute(array($item_id));
						echo "<div class='message'>{$stmt->rowCount()} member deleted</div>";
						check_http_referer(1);
					}else{
						echo "<div class='message'>member is not found</div>";
						check_http_referer(1);
					}
				}else if(check_id()=='s'){
					echo "<div class='message'>id must be number value </div>";
					check_http_referer(1);
				}
				else{
					echo "<div class='message'>user_id must be set</div>";
						check_http_referer(1);
				}
			break;
			//end delete page
			//start approve page
			case 'approve':
				
			break;
			//end approve page
			//start not found page
			default: check_http_referer(0);
		}
		ob_end_flush();
	}
?>
<?php
	session_start();
	if(isset($_SESSION['user'])){
		$title='new item'; //title of page
		//include database connection file
		include_once 'admin\php\config.php'; //contain database connection
		//include function file
		include_once 'admin\include\function.php';//include function file
		include_once 'navbar.php'; //incude navbar
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$name=$_POST['name'];
			$description=$_POST['description'];
			$price=$_POST['price'];
			$country_made=$_POST['country_made'];
			$status=$_POST['status'];
			$cat=$_POST['category'];
			$stmt=$connect->prepare(
				"select item_name from items where item_name=? and
				 user_id=?"
			);
			$stmt->execute(array($name,$_SESSION['user_id']));
			if($stmt->rowCount()==0){
				$date=new dateTime();
				$stmt=$connect->prepare(
					"insert into items(item_name,item_description,
					 item_price,item_country_made,item_status,cat_id,
					 user_id,item_add_date) values(?,?,?,?,?,?,?,?)"
				);
				$stmt->execute(array(
					$name,$description,$price,$country_made,$status,
					$cat,$_SESSION['user_id'],$date->format('Y-m-d H:i:s')
				));
				echo "<div class='message'>one item added</div>";
				header('refresh:2;url=profile.php');
				exit();
			}else{
				$message='can\'t add tow item the same';
			}
		}
	}else{
		header('location:index.php');
	}
?>
<div class='form'>
	<h1 class="header">Add Ads</h1>
	<form method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=insert'; ?> >
		<input class="input" type="text" name="name" placeholder="name of item" required="required">
		<input class="input" type="text" name="description" placeholder="description" required="required">
		<input class="input" type="text" name="price" placeholder="price" required="required">	
		<input class="input" type="text" name="country_made" placeholder="country made" required='required'>
		<input class="input" type="file" name="image">
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
		<input class="submit" type="submit" value="Add Item" >
	</form>
</div>
<?php
	if(isset($message)){
		echo "<div class='message'>$message</div>";
	}
?>
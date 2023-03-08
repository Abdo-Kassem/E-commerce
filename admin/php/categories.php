<?php
	ob_start();
	session_start();
	if(isset($_SESSION['username'])&&isset($_SESSION['user_id'])){
		$navbar=true;
		$title='categories';
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
			$order=array('ASC','DESC');
			$sort='DESC';
			$ordered_by='cat_ordering';
			if(isset($_GET['order'])&&in_array($_GET['order'],$order)){
				$sort=$_GET['order'];
			}
			if(isset($_GET['ordered_by'])&&$_GET['ordered_by']!=''){
				$ordered_by=$_GET['ordered_by'];
			}
			$data=query('*','categories',"cat_id>0 order by $ordered_by $sort");
		?>
		<div class='container'>
			<h1 class='header '>Manage Category</h1>
			<div class="link">
				<span>order:</span>
				<a class="<?php if(!isset($_GET['order'])||$_GET['order']=='ASC') echo 'active';?>" href="categories.php?action=manage&order=ASC">ASC</a>
				<a class="<?php if($_GET['order']=='DESC') echo 'active';?>" href='categories.php?action=manage&order=DESC'>DESC</a>
			</div>
			<div class='container-data'>
			<?php
				foreach($data as $row){
					echo '<ul>';
					echo '<li class="name"><h3>Name : </h3><a href="categories.php?action=edit&catId='.$row['cat_id'].'">'
					.$row['cat_name'].'</a><a class="delete" href="categories.php?action=delete&catId='.$row['cat_id'].'">Delete</a></li>';					
					echo '<li class="vis">';
						if($row['cat_visible']==1) echo '<h3>Visible : </h3><span>Yes</span>';
						else echo '<h3>visible : </h3><span>No</span>';
					echo '</li>';
					echo '<li class="allow_com">';
						if($row['cat_allow_comment']==1) echo '<h3>Allow Comment : </h3><span>Yes</span>';
						else echo '<h3>Allow Comment : </h3><span>No</span>';
					echo '</li>';
					echo '<li class="allow_ad">';
						if($row['cat_allow_ads']==1) echo '<h3>Allow Ads : </h3><span>Yes</span>';
						else echo '<h3>Allow Ads : </h3><span>No</span>';
					echo '</li>';
					echo '<li class="description"><h3>Description : </h3><p>'.$row['cat_description'].'</p></li><hr>';
					echo '</ul>';
				}
			?>
			</div>
			<a class='add-new-member' href='categories.php?action=add'><i class="fa fa-plus plus-icon"></i>Add New Category</a>
		</div>
		<?php
		}
		//end manage page
		
		//start add page
		else if($action=='add'){?>
			<h1 class="header ">Add Category</h1>
			<form class="form" method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=insert'; ?> >
				<input class="input" type="text" name="name" placeholder="name" required="required">
				<input class="input" type="text" name="description" placeholder="description" required="required">
				<input class="input" type="text" name="ordering" placeholder="order" required='required'>
				<div class='visible'>
					<label>Visible</label>
					<div>
						<input id='vis-yes' type="radio" name='vis' value="1" checked>
						<label for='vis-yes'>Yes</label>
						<input id='vis-no' type="radio" name='vis' value="0">
						<label for='vis-no'>No</label>
					</div>
				</div>
				<div class="allow_ads">
					<label>Allow Ads</label>
					<div>
						<input id='allow-yes' type="radio" name='allow_ads' value="1" checked>
						<label for='allow-yes'>Yes</label>
						<input id='allow-no' type="radio" name='allow_ads' value="0">
						<label for='allow-no'>No</label>
					</div>
				</div>
				<div class='allow_comment'>
					<label>Allow Comment</label>
					<div>
						<input id='allow-com' type="radio" name='allow_com' value="1" checked>
						<label for='allow-com'>Yes</label>
						<input id='allow-com-no' type="radio" name='allow_com' value="0">
						<label for='allow-com-no'>No</label>
					</div>
				</div>
				<input class="submit" type="submit" value="Add Category" >
			</form>
	<?php
		}
		//end add page
		
		//start insert page
		else if($action=='insert'){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				if(!empty($_POST['name'])&&!empty($_POST['description'])){
					$name=$_POST['name'];
					$description=$_POST['description'];
					$order=$_POST['ordering'];
					$visible=$_POST['vis'];
					$allow_ads=$_POST['allow_ads'];
					$allow_com=$_POST['allow_com'];
					if(check_item('cat_name','categories',"cat_name=$name")==false){
						$stmt=$connect->prepare(
						"insert into categories(cat_name,cat_description,cat_ordering,
						cat_visible,cat_allow_comment,cat_allow_ads)
							values(?,?,?,?,?,?)"
						);
						$stmt->execute(
							array($name,$description,$order,$visible,$allow_com,
							$allow_ads)
						);
						
							echo "<div class='message'>{$stmt->rowCount()} inserted</div>";
							check_http_referer(2);
						
					}else{
						echo '<div class="message">category alerdy exist</div>';
						check_http_referer(2);
					}
				}else{
					echo '<div class="message">categories name and categories description must set</div>';
					check_http_referer(2);
				}
			}else{
				check_http_referer(0);
			}
		}
		//end insert page
		
		//start edit page
		else if($action=='edit'){
			/*catId is name that contain id value and exist in url*/
			if(check_id('catId')=='n'){
				if(check_item('cat_id','categories',"cat_id={$_GET['catId']}")==true){
					$catId=intval($_GET['catId']);
					$data=query('*','categories',"cat_id=$catId");
				?>
				<h1 class="header">Edit Category</h1>
				<form class="form" method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=Update&catId='.$catId; ?>>
					<input class="input" type="text" name="name" placeholder="name" 
					required="required" value="<?php echo $data[0]['cat_name'];?>">
					<input class="input" type="text" name="description" placeholder="description" 
					required="required" value="<?php echo $data[0]['cat_description'];?>">
					<input class="input" type="text" name="ordering" placeholder="order" 
					required='required' value="<?php echo $data[0]['cat_ordering'];?>">
					
					<div class='visible'>
						<label>Visible</label>
						<div>
							<input id='vis-yes' type="radio" name='vis' value="1" 
							<?php if($data[0]['cat_visible']==1)echo "checked";?>>
							<label for='vis-yes'>Yes</label>
							<input id='vis-no' type="radio" name='vis' value="0"
							<?php if($data[0]['cat_visible']==0)echo "checked";?>>
							<label for='vis-no'>No</label>
						</div>
					</div>
					<div class="allow_ads">
					<label>Allow Ads</label>
					<div>
						<input id='allow-yes' type="radio" name='allow_ads' value="1"
						<?php if($data[0]['cat_allow_ads']==1)echo "checked";?>>
						<label for='allow-yes'>Yes</label>
						<input id='allow-no' type="radio" name='allow_ads' value="0"
						<?php if($data[0]['cat_allow_ads']==0)echo "checked";?>>
						<label for='allow-no'>No</label>
					</div>
					</div>
					<div class='allow_comment'>
						<label>Allow Comment</label>
						<div>
							<input id='allow-com' type="radio" name='allow_com' value="1" 
							<?php if($data[0]['cat_allow_comment']==1)echo "checked";?>>
							<label for='allow-com'>Yes</label>
							<input id='allow-com-no' type="radio" name='allow_com' value="0"
							<?php if($data[0]['cat_allow_comment']==0)echo "checked";?>>
							<label for='allow-com-no'>No</label>
						</div>
					</div>
					<input class="submit" type="submit" value="Save" >
				</form>
				<?php
				}else{
					echo "<div class='message'>category not exist</div>";
					check_http_referer(2);
				}
			}
			else if(check_id('catId')=='s'){
				echo "<div class='message'>catId must be number</div>";
				check_http_referer(2);
			}
			else{
				echo "<div class='message'>catId must be set</div>";
				check_http_referer(2);
			}
		}
		//end edit page
		
		//start update page
		else if($action=='Update'){
			if(check_id('catId')=='n'){
				$catId=intval($_GET['catId']);
				$name=$_POST['name'];
				$description=$_POST['description'];
				$order=$_POST['ordering'];
				$visible=$_POST['vis'];
				$allow_ads=$_POST['allow_ads'];
				$allow_comment=$_POST['allow_com'];
				$stmt=$connect->prepare(
					"update categories set cat_name=?,cat_description=?,
					 cat_ordering=?,cat_visible=?,cat_allow_ads=?,
					 cat_allow_comment=? where cat_id=$catId"
				);
				$stmt->execute(array($name,$description,$order,$visible,
				$allow_ads,$allow_comment));
				if($stmt->rowCount()>0){
					echo '<div class="message">one category updated</div>';
					header('refresh:2;url=categories.php');
				}else{
					echo '<div class="message">no category updated</div>';
					check_http_referer(2);
				}
			}
			else if(check_id('catId')=='s'){
				echo "<div class='message'>catId must be number</div>";
				check_http_referer(2);
			}
			else{
				echo "<div class='message'>catId must be set</div>";
				check_http_referer(2);
			}
		}
		//end update page
		
		//start delete page
		else if($action=='delete'){
			if($_SERVER['REQUEST_METHOD']=='GET'){
				if(check_id('catId')=='n'){
					$catId=intval($_GET['catId']);
					$stmt=$connect->prepare(
						"delete from categories where cat_id=$catId"
					);
					$stmt->execute();
					if($stmt->rowCount()>0){
						echo '<div class="message">one categories deleted</div>';
						check_http_referer(2);
					}else{
						echo '<div class="message">no categories deleted</div>';
						check_http_referer(2);
					}
				}
				else if(check_id('catId')=='s'){
					echo "<div class='message'>catId must be number</div>";
					check_http_referer(2);
				}
				else{
					echo "<div class='message'>catId must be set</div>";
					check_http_referer(2);
				}
			}else{
				check_http_referer(0);
			}
		}
		else{
			echo "<div class='message'>page not exist</div>";
			//check_http_referer(2);
		}
		//end delete page
	}else{
		header('location:index.php');
		exit();
	}
	ob_end_flush();
?>
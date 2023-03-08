<?php
	ob_start(); //all output will store in buffer without header function and prevent url alredy send error
	session_start();
	if(isset($_SESSION['username'])&&isset($_SESSION['user_id'])){
		$title='member';  //title of page
		$navbar=true;  //tell us add navbar file or not
		//include databas connection file
		include_once 'config.php';
		//include function file
		include_once '..\include\function.php';
		//include header file
		include_once '..\include\template\header.php';
		//include navebar function
		include_navbar();
		$action=(isset($_GET['action']) && $_GET['action']!='')? $_GET['action']:'manage';
		
		//start manage page or member
		if($action=='manage'){
			$show='';
			if(isset($_GET['show']) && !empty($_GET['show'])){
				$show='and user_is_approval=0';
			}
			$data=query('*','Users',"user_is_admin=0 $show");
		?>
			<div class="container">
				<h1 class="header ">Manage Member</h1>
				<table class="table">
					<tr>
						<th>UserName</th>
						<th class='user-id'>UserId</th>
						<th>Img</th>
						<th>Email</th>
						<th>FullName</th>
						<th class='control'>Controle</th>
					</tr>
		<?php
					foreach($data as $row){
						$user_id=$row['user_id'];
						if($row['user_is_approval']==0){
							echo "
								<tr class='not-approved'>
									<td>{$row['user_name']}</td>
									<td>{$row['user_id']}</td>
									<td>
										<img class='image' src='../../images/profile/".$row['user_image']."'>
									</td>
									<td>{$row['user_email']}</td>
									<td>{$row['user_full_name']}</td>
									<td class='not-approved-links'>
										<a href=member.php?action=edit&userId={$row['user_id']} class='edit-link'>Edit</a>
										<a href=member.php?action=delete&userId=$user_id class='delete-link'>delete</a>
										<a href=member.php?action=approve&userId=$user_id class='edit-link'>Approve</a>
									</td>
								</tr>
							";
						}else{
							echo "
								<tr>
									<td>{$row['user_name']}</td>
									<td>{$row['user_id']}</td>
									<td>
										<img class='image' src='../../images/profile/".$row['user_image']."'>
									</td>
									<td>{$row['user_email']}</td>
									<td>{$row['user_full_name']}</td>
									<td class='approved-links'>
										<a href=member.php?action=edit&userId={$row['user_id']} class='edit-link'>Edit</a>
										<a href=member.php?action=delete&userId=$user_id class='delete-link'>delete</a>
									</td>
								</tr>
							";
						}
						
					}
		?>
		
				</table>
				<a href="member.php?action=add" class="add-new-member">
					<i class="fa fa-plus plus-icon"></i>
					Add New Member
				</a>
			</div>
		<?php
		}
		//end manage page member
		
		//start approve page
		else if($action=='approve'){
			//check if not come directly
			if($_SERVER['REQUEST_METHOD']=='GET'){
				if(check_id()=='n'){
					$stmt=$connect->prepare(
						'update Users set user_is_approval=? where 
						 user_id=?'
					);
					$stmt->execute(array(1,$_GET['userId']));
					echo '<div class="message">'.$stmt->rowCount().' approved</div>';
				}
				else if(check_id()=='s'){
					echo '<div class="message">user_id must be number</div>';
				}else{
					echo '<div class="message">user_id must set</div>';
				}
				check_http_referer(2);
			}
			else{
				check_http_referer(0);
			}
		}
		//end approve page
		
		//start edit page
		else if($action=='edit'){
			if(check_id()=='n'){
				$user_id=intval($_GET['userId']);
				$data=query('user_name,user_pass,user_email,user_full_name',
				'Users',"user_id=$user_id");?>
			
				<h1 class="header ">Edit Member</h1>
				<form class="form" method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=Update&userId='.$user_id; ?> enctype='multipart/form-data'>
					<input class="input" type="text" name="username" placeholder="UserName" value=<?php echo $data[0]['user_name'];?> required="required">
					<input class="input" type="password" name="password" placeholder="leave password black if not change it">
					<input class="input" type="email" name="email" placeholder="Email" value=<?php echo $data[0]['user_email'];?> required="required">
					<input class="input" type="text" name="fullname" placeholder="Full Name" value="<?php echo $data[0]['user_full_name'];?>" required="required">
					<input class="input" type="file" name="image" required="required">
					<input class="submit" type="submit" value="Save" >
				</form>
<?php		
			}
			else if(check_id()=='s'){
				echo "<div class='message'>user_id must be number</div>";
				check_http_referer(2);
			}

			else{
				echo "<div class='message'>user_id must be set</div>";
				check_http_referer(2);
			}
		}
		//end edit page
		//start update page
		else if($action=='Update'){
			//check if send by request method
			if($_SERVER['REQUEST_METHOD']=='POST'){
				if(isset($_GET['userId'])&&!empty($_GET['userId'])){
					if(check_id()=='n'){
						$userId=intval($_GET['userId']);
						echo'<h1 class="header">update</h1>';
						$username=$_POST['username'];
						$password=$_POST['password'];
						$email=$_POST['email'];
						$fullname=$_POST['fullname']; 
						// start image data
						$image_name=$_FILES['image']['name'];
						$image_tmp_name=$_FILES['image']['tmp_name'];
						$image_size=$_FILES['image']['size'];
						$image_type=$_FILES['image']['type'];
						$allow_type=array('image/jpg','image/jpeg','image/png','image/gif');
						//end image data
						$username_lenght=strlen($username);
						$message=array();
						//check if userName lenght and is string or not
						if(($username_lenght<4 && !empty($username)) || $username_lenght>20){
							$message[]='username must be between 4 and 20 ';
						}
						//check if userName is empty
						if(empty($username)){
							$message[]='UserName Must Be Set';
						}
						//check if email is empty
						if(empty($email)){
							$message[]='Email Must Be Set';
						}
						//check if fullName is empty
						if(empty($fullname)){
							$message[]='FullName Must Be Set';
						}
						if($image_size>1048576){
							$message[]='image size must be 1MB';
						}
						if(!in_array($image_type,$allow_type)){
							$mssage[]='file must be image';
						}
						else if(count($message)== 0){
							$update=false;//save make update of database or not
							//check if member user name already exist or not in database
							$stmt1=$connect->prepare(
								"select user_image,user_id,user_name from users where user_name=?"
							);
							$stmt1->execute(array($username));
							$res=$stmt1->fetch();
							if($stmt1->rowCount()==0 || $res['user_id']==$userId){
								$update=true;
							}
							$stmt2;
							if($update){
								//check if password is empty if empty not update password in database
								$image_name=rand(0,1234567886).$image_name;
								$path='../../images/profile/'.$image_name;
								$old_directoy=getcwd(); //get current work directory
								/*
									change current work directory to directory that contain 
									file that want to delete it
								*/
								chdir('../../images/profile');
								$unlink=unlink($res['user_image']); //delete file
								chdir($old_directoy);
								if($unlink){
									move_uploaded_file($image_tmp_name,$path);
								}else{
									echo "<div class='message'>erorr image update</div>";
									exit();
								}
								if(empty($password)){
									$stmt2=$connect->prepare('
										update Users set user_name=?,
										user_email=?,user_full_name=?,
										user_image=?
										where user_id=?;
									');
									$stmt2->execute(
										array($username,$email,$fullname,
										$image_name,$userId)
									);
								}else{
									$stmt2=$connect->prepare('
										update Users set user_name=?,
										user_pass=?,user_email=?,
										user_full_name=?,user_image=?
										where user_id=?;
									');
									$stmt2->execute(
										array($username,$password,$email,
										$fullname,$image_name,$userId)
									);
								}
								//check if update is sucess and database updated 
								if($stmt2->rowCount()==1){
									echo '<p class="message">member updated</p>';
									header('refresh:2;url=member.php');
									exit();
							    }else{
									echo '<p class="message">data the same </p>';
									header('refresh:2;url=member.php');
									exit();
								}
							}
							
							else{
								echo '<p class="message">userName alredy exist try again</p>';
								check_http_referer(2); //go to previuse page take second optional
							}
						}
						//check if array error messages is not empty
						if(count($message)>0){
							echo '<div class="message">';
								foreach($message as $m){
									echo "<p>$m</p>";
								}
							echo '</div>';
							check_http_referer(5);
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
					
				}//check if no userId set
				else{
					echo "<div class='message'>userId mus set</div>";
					check_http_referer(2);
				}
				
			}
			//check if user enter not from request method 
			else{
				header("location:member.php?action=manage");
				exit();
			}
		}
		//end update page
		
		//start delete page
		else if($action=='delete'){
				if(check_id()=='n'){
					$user_id=intval($_GET['userId']);
					if(check_item('user_id','Users',"user_id={$user_id}")){
						$stmt=$connect->prepare(
							"select user_image from users where user_id=?"
						);
						$stmt->execute(array($user_id));
						$res=$stmt->fetch();
						$old_directoy=getcwd(); //get current work directory
						/*
							change current work directory to directory that contain 
							file that want to delete it
						*/
						chdir('../../images/profile');
						unlink($res['user_image']); //delete file
						chdir($old_directoy);
						$stmt=$connect->prepare(
							'delete from Users where user_id=?'
						);
						$stmt->execute(array($user_id));
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
		}
		//end delete page
		
		//start add page
		else if($action=='add'){?>
			<h1 class="header ">Add Member</h1>
			<form class="form" method="post" action=<?php echo $_SERVER['PHP_SELF'].'?action=insert'; ?> enctype='multipart/form-data'>
				<input class="input" type="text" name="username" placeholder="UserName" required="required">
				<input class="input" type="password" name="password" placeholder="password" required="required">
				<input class="input" type="email" name="email" placeholder="Email" required="required">
				<input class="input" type="file" name="image" required="required">
				<input class="input" type="text" name="fullname" placeholder="Full Name" required="required">
				<input class="submit" type="submit" value="Add Member" >
			</form>
		<?php
		}
		//end add page
		
		//start insert page
		else if($action=='insert'){
			//check if send by request method
			if($_SERVER['REQUEST_METHOD']=='POST'){		
				echo'<h1 class="header">Insert</h1>';
				$username=$_POST['username'];
				$password=$_POST['password'];
				$email=$_POST['email'];
				$fullname=$_POST['fullname'];
				$username_lenght=strlen($username);
				$message=array();
				$image_name=$_FILES['image']['name'];
				$image_tmp_name=$_FILES['image']['tmp_name'];
				$image_size=$_FILES['image']['size'];
				$image_extention=$_FILES['image']['type'];
				$allow_extention=array('image/jpg','image/jpeg','image/png','image/gif');
				//check if userName lenght and is string or not
				if(($username_lenght<4 && !empty($username))|| $username_lenght>20){
					$message[]='username must be between 4 and 20';
				}
				//check if userName is empty
				if(empty($username)){
					$message[]='UserName Must Be Set';
				}
				//check if password is empty
				if(empty($password)){
					$message[]='password Must Be Set';
				}
				//check if password is least 8
				if(strlen($password)<8){
					$message[]='password Must Be at least 8 number or character';
				}
				//check if email is empty
				if(empty($email)){
				    $message[]='Email Must Be Set';
				}
				//check if fullName is empty
				if(empty($fullname)){
					$message[]='FullName Must Be Set';
				}
				if(!in_array($image_extention,$allow_extention)){
					$message[]='file uploaded must be image';
				}
				if($image_size>1048576){
					$message[]='image size must be less than or equal 1MB';
				}
				else if(count($message)==0){
					$random_value=rand(0,10000000000);
					$image_name=$random_value.$image_name;
					move_uploaded_file($image_tmp_name,'../../images/profile/'.$image_name);
					$stmt=$connect->prepare('
						insert into Users(user_name,user_pass,user_email,
						user_full_name,user_is_approval,user_image)
						values (?,?,?,?,?,?);
					');
					$stmt->execute(array($username,$password,$email,
					$fullname,0,$image_name));
					echo '<p class="message">'.($stmt->rowCount()>0 ? $stmt->rowCount().'member added':'user alredy exist <br>no member added').'</p>';
					header('refresh:3;url=member.php?action=manage');
					exit();
				}
				//check if array error messages is not empty
				if(count($message)>0){
					echo '<div class="message">';
						foreach($message as $m){
							echo "<p>$m</p>";
						}
					echo '</div>';
					check_http_referer(2);
					exit();
				}
			}
			//check if user enter not from request method 
			else {
				header("location:member.php");
				exit();
			}
		}
		//end insert page
		else{
			check_http_referer(0);
		}
		//include fotter
		include_once '..\include\template\fotter.php';
	}else{
		header('location:index.php');
	}
	ob_end_flush(); //allow of output that store in buffer to print
?>
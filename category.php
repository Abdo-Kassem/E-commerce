<?php
	session_start();
	$title='categories'; //title of page
	//include database connection file
	include_once 'admin\php\config.php';
	//include function file
	include_once 'admin\include\function.php';
	include_once 'navbar.php';
	if(isset($_GET['catId'])&&isset($_GET['catName'])&&!empty($_GET['catId'])&&!empty($_GET['catName'])){
		if(check_id('catId')=='n'){
			$catId=intval($_GET['catId']);
	?>
			<div class="container">
				<h1 class='header'><?php echo $_GET['catName'];?></h1>
				<?php
					$items=get_items($catId);
					if(count($items)>0){
						echo "<div class='item-manage'>";
						foreach($items as $item){
							echo"<div class='item'>";
								echo "<img src='images/profile/579820661image.jpg'>";
								echo"<div class='caption'>";
									echo'<h3>'.$item['item_name'].'</h3>';
									echo'<p class="description">'.$item['item_description'].'</p>';
									echo'<p class="date">'.$item['item_add_date'].'</p>';
									echo'<span>'.$item['item_price'].'</span>';
								echo"</div>";
							echo"</div>";
						}
							echo "<div class='clear'></div>";
						echo "</div>";
					}
				?>
			</div>
   <?php
		}
		else if(check_id('catId')=='s'){
			echo "<div class='message'>catId must be number</div>";
			check_http_referer(2);
		}else{
			echo "<div class='message'>catId must be set</div>";
			check_http_referer(2);
		}
	}else{
		check_http_referer(0);
	}
?>
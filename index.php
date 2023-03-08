<?php
	session_start();
	$title='home'; //title of page
	//include database connection file
	include_once 'admin\php\config.php';
	//include function file
	include_once 'admin\include\function.php';
	include_once 'navbar.php';
	$items=get_items();
?>
<div class='container'>
	<div class="user-ads">
		<h1>Ads</h1>
		<div class='item-manage'>
		<?php
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
		?>
		</div>
	</div>
</div>
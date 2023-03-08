<?php
	//start admin functions
	function print_title(){//print title of page
		global $title;
		if(isset($title)){
			echo $title;
			return;
		}
		echo 'default page';
	}
	function include_navbar(){ //include navbar
		global $navbar;
		if($navbar==true){
			include_once '..\include\template\navbar.php';
		}
	}
	function check_item($column,$table,$condition){
		global $connect;
		$stmt=$connect->prepare(
			"select $column from $table where $condition"
		);
		$stmt->execute();
		
		if($stmt->rowCount()>0)
			return true;
		return false;
	}
	function check_id($id='userId'){
		if(isset($_GET[$id])){
			if(!is_numeric($_GET[$id]))
				return 's';
			return 'n';
		}
		return 'not found';
	}
	function check_http_referer($second=3){
		if(isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER'])){
			header("refresh:$second;url={$_SERVER['HTTP_REFERER']}");
			exit();
		}
		header("refresh:$second;url=index.php");
		exit();
	}
	//return number of all member or not approved member
	function count_item($column,$table,$not_approval=false){
		global $connect;
		if($not_approval==false){
			$stmt=$connect->prepare(
				"select count($column) from $table"
			);
			$stmt->execute();
		}else{
			$stmt=$connect->prepare(
				"select count($column) from $table where user_is_approval=?"
			);
			$stmt->execute(array(0));
		}
		return $stmt->fetchColumn();
	}
	//function to select items from database
	function query($column,$table,$condition){
		global $connect;
		$stmt=$connect->prepare(
			"select $column from $table where $condition"
		);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	//function to get latest specified element in database
	function get_latest($column,$table,$ordered_by,$limit=4){
		global $connect;
		$stmt=$connect->prepare(
			"select $column from $table order by $ordered_by desc limit $limit" 
		);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	//end admin function
	
	//start front end function
	function get_cats(){
		global $connect;
		$stmt=$connect->prepare(
			"select * from categories"
		);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	function get_items($catId=null){
		global $connect;
		if($catId==null){
			$stmt=$connect->prepare(
				"select * from items order by item_add_date desc"
			);
			$stmt->execute();
			return $stmt->fetchAll();
		}
		$stmt=$connect->prepare(
			"select * from items where cat_id=$catId order by item_add_date desc"
		);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	//end front end function
?>
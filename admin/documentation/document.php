/*

 **$_GET['variable_name'] get data from url and when want to get data from 
   url use $_GET[] method
   
 **div that is inside form class name must not contain '-' for example
   class="say-hello" but is like this calss="say_hello"
   
 **if you want to check if requse come from post or get method you use this
   syntax $_SERVER['REQUEST_METHOD']=='POST' or
   $_SERVER['REQUEST_METHOD']=='GET' note must be capital
   
 **intval($specify_var) function get the integer value of specified variable
 
 **rowCount() mysql function that get number of row that effected by query
   query(update or delete or select or insert )
   
 **prepare() mysql function use to make calculation and get variable value 
   before go to database to execute
   
 **note that the last else is follow of the previuse if statement
 
 **note that function_file that include navebar file by function in it
   and depend on $navebar variable that declare in file that will add in
   it navebar
 **not that select box it that take name not option
 ** in database on update cascade mean that on update this parent item
   update relate child in other mean that this table has dependent another
   table and so can not update or delte that table
 ** in database on delete cascade mean that on delete this parent item
    delete relate child 
 **on (updat||delete) restrict mean do make delete or update of parent
   table
 **if has tow forms and we want to send to the same file to check \
   validation of them you can mark one by set name of submit form
   and check if this name isset() if true make action follow of this
   form else make action of another form
 **NOW()  function is mysql function that get date and time
 **in form exist attribute (enctype) this attribute is interested in 
   encrypt and default value is "application/x-www-form-urlencoded" and 
   if in form exist input type=file you must change this attribute by 
   "multipart/form-data" to prevent the problem upload
 **move_uploaded_file()is function that take tempraray name of file and
   destination folder and move that file to this destination for example
   (move_uploaded_file($image_tmp_name,'../../images/profile/'.$image_name);)
   move file from temprary location"set automaticy by appach" to destination 
   location where image name is the name image that exist in local host not
   temprary name 
 **$_FILES[] is global two di-array that get file data in array for example
	$_FILES['user_image'] return array that contain meta data of this file
	"name,tmp_name,size,type" where type is precedenced by type of file
	for example image/jpeg
 **when want to delete file and this file is higher hierarchy to your 
   working  directory (i.e. when trying to delete a path that starts 
   with "../") this file not delete and display permissions denied error
   to solve this thing make that
   you can use chdir() to change the working directory to the folder where
   the file you want to unlink is located.
	<?php
		$old = getcwd(); 		// Save the current directory
		chdir($path_to_file); 	//change current work directory to directory that contain file that want delte it
		unlink($filename);      //delete file
		chdir($old); 			// Restore the old working directory   
	?>
 ** to hide the scrollbar use this code
	.example::-webkit-scrollbar{
		display:none;
	}  
	->for example
		body::-webkit-scrollbar{
			display:none;
		}
 **when make check if(isset($_SERVER['REQUEST_METHOD'])) this check if 
	user come from 'post' method or 'get' method or other method but note
	that when user come from link he not consider come from request method
  **
  
*/
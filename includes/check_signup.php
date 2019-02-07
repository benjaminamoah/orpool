<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_signup.php");

if(isset($_POST['check_username'])){
	$manage_db = new manage_db();

	$user = $_POST['username'];

	$query = $manage_db->return_query("select * from $manage_db->users where username='$user'");

	if(mysql_num_rows($query)>0){
		echo "exists";
	}else if(mysql_num_rows($query) == 0 && ($user == "username" || $user == "")){
		echo "none";
	}else{
		echo "cool!";
	}

}

if(isset($_POST['check_email'])){
	$manage_db = new manage_db();

	$email = $_POST['email'];

	$query = $manage_db->return_query("select * from $manage_db->users where email='$email'");

	if(mysql_num_rows($query)>0){
		echo "exists";
	}else if(mysql_num_rows($query) == 0 && ($email == "email" || $email == "")){
		echo "none";
	}else{
		echo "cool!";
	}

}

?>
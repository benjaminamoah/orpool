<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_everyone.php");

if(isset($_POST['search'])){
	$user_id = $_POST['user_id'];
	$text = $_POST['text'];

	if(strlen($text) > 0){ //if search text given
		//displays search result; users' name that match search-text
		$manage_everyone = new manage_everyone();
		$search = $manage_everyone->displaySearch($user_id, $text);
		echo $search;
	}else{
		//displays all users
		$manage_everyone = new manage_everyone();
		$all_users = $manage_everyone->displayEveryone($user_id);
		echo $all_users;
	}
}

?>
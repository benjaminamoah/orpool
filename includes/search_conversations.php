<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_conversations.php");

if(isset($_POST['search'])){
	$user_id = $_POST['user_id'];
	$text = $_POST['text'];

	if(strlen($text) > 0){ //if search text given
		//displays search result; users' name that match search-text
		$manage_conversations = new manage_conversations();
		$search = $manage_conversations->displaySearchPublicConversations($user_id, $text);
		echo $search;
	}else{
		//displays all users
		$manage_conversations = new manage_conversations();
		$all_convs = $manage_conversations->displayPublicConversations($user_id);
		echo $all_convs;
	}
}

?>
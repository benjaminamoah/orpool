<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_everyone.php");

if(isset($_POST['show_more_users'])){
	$user_id = $_POST['user_id'];
	$num_rows = $_POST['num_rows'];
	$start_from = $_POST['start_from'];

	$manage_evr = new manage_everyone();
	$more_users = $manage_evr->displayMoreEveryone($user_id, $num_rows, $start_from);
	echo $more_users;
}

?>
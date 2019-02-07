<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_contacts.php");

//send request to be added to another user's contact list
if(isset($_POST['connect'])){
	$user_id = $_POST['user_id'];
	$connect_user_id = $_POST['connect_user_id'];

	$manage_db = new manage_db();

//add user to be connected to to the current user's contacts but set connected to "not connected"
	$query_connect_user_id = $manage_db->return_query("SELECT connect_user_id from $manage_db->contacts WHERE user_id='$user_id'");
	$result_connect_user_id01 = mysql_result($query_connect_user_id, 0, "connect_user_id");
	$result_connect_user_id02 = ",".$connect_user_id;

	$query_connected01 = $manage_db->return_query("SELECT connected from $manage_db->contacts WHERE user_id='$user_id'");
	$result_connected01 = mysql_result($query_connected01, 0, "connected");

//add current user to the user to be connected to's contacts but set connected to "not connected"
	$query_connect_user_id = $manage_db->return_query("SELECT connect_user_id from $manage_db->contacts WHERE user_id='$connect_user_id'");
	$result_connect_user_id03 = mysql_result($query_connect_user_id, 0, "connect_user_id");
	$result_connect_user_id04 = ",".$user_id;

	$query_connected02 = $manage_db->return_query("SELECT connected from $manage_db->contacts WHERE user_id='$connect_user_id'");
	$result_connected02 = mysql_result($query_connected02, 0, "connected");

//add to new requests in the user to be connect with's tuple
	$query_connect_user_id = $manage_db->return_query("SELECT new_requests from $manage_db->contacts WHERE user_id='$connect_user_id'");
	$result_connect_user_id05 = mysql_result($query_connect_user_id, 0, "new_requests");
	if(strlen($result_connect_user_id05) == 0){
		$result_connect_user_id06 = $user_id;
	}else if(strlen($result_connect_user_id05) > 0){
		$result_connect_user_id06 = ",".$user_id;
	}

	$query_first_name = $manage_db->return_query("SELECT first_name from $manage_db->users WHERE user_id='$connect_user_id'");
	$first_name = mysql_result($query_first_name, 0, "first_name");

	if($manage_db->return_query("UPDATE $manage_db->contacts SET connect_user_id=CONCAT('$result_connect_user_id01','$result_connect_user_id02'), connected=CONCAT('$result_connected01', ',n') WHERE user_id='$user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET connect_user_id=CONCAT('$result_connect_user_id03','$result_connect_user_id04'), connected=CONCAT('$result_connected02', ',n') WHERE user_id='$connect_user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET new_requests=CONCAT('$result_connect_user_id05','$result_connect_user_id06') WHERE user_id='$connect_user_id'")){
		echo "request sent to ".$first_name;
	}
}


//accepts a connection request from another user i.e. adds to contact list of both users
//user accepting request has id named "user_id"
if(isset($_POST['add'])){
	$user_id = $_POST['user_id']; //current user's id
	$connect_user_id = $_POST['connect_user_id']; //other user's id

	$manage_contacts = new manage_contacts();
	$manage_db = new manage_db();

/************************************************************************************
1. Change connected stutus for current user from "n" to "y" at index eqiv
	to other user's id location in current user's connct_user_id
2. Change connected stutus for other user from "n" to "y" at index eqiv
	to current user's id location in other user's connct_user_id
3. Remove other user's id from current user's new_requests
************************************************************************************/
	//1.
	//**take connect_user_id from current user
	$connect_user_id_all = $manage_contacts->explode_string("connect_user_id", $user_id, "LIMIT 1");
	$i = $manage_contacts->string_element_index($connect_user_id_all, $connect_user_id);

	//take connected, explode to isolated element to be changed
	$connected01 = $manage_contacts->explode_string("connected", $user_id, "LIMIT 1");

	//replace connected status of other user in current user's connected, from n to y
	$connected_recon01 = $manage_contacts->recon_string($i, "index", $connected01, "replace");

	//2.
	//**take connect_user_id from other user
	$connect_user_id_all = $manage_contacts->explode_string("connect_user_id", $connect_user_id, "LIMIT 1");
	$i = $manage_contacts->string_element_index($connect_user_id_all, $user_id);

	//take connected, explode to isolated element to be changed
	$connected02 = $manage_contacts->explode_string("connected", $connect_user_id, "LIMIT 1");

	//replace connected status of current user in other user's connected, from n to y
	$connected_recon02 = $manage_contacts->recon_string($i, "index", $connected02, "replace");

	//3.
	//**take new requests, explode to isolated element to be removed, remove this element, then reconstruct
	$new_requests = $manage_contacts->explode_string("new_requests", $user_id, "LIMIT 1");

	//remove user_id of other user, from current user's new requests
	$new_requests_recon = $manage_contacts->recon_string($connect_user_id, "value", $new_requests, "");

	//UPDATE CONTACTS TABLE
	if($manage_db->return_query("UPDATE $manage_db->contacts SET connected='$connected_recon01' WHERE user_id='$user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET connected='$connected_recon02' WHERE user_id='$connect_user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET new_requests='$new_requests_recon' WHERE user_id='$user_id'")){

	//displays updated new requests' list
	$new_requests = $manage_contacts->displayContacts($user_id, "new requests");
	echo $new_requests;

	//displays updated new contacts' list
	$contacts = $manage_contacts->displayContacts($user_id, "contacts");
	echo $contacts;

	}//end of if($manage_db->return_query("UPDA...
}


//accepts a connection request from another user i.e. adds to contact list of both users
if(isset($_POST['ignore'])){
	$user_id = $_POST['user_id']; //current user
	$connect_user_id = $_POST['connect_user_id']; //other user

	$manage_contacts = new manage_contacts();
	$manage_db = new manage_db();

	//update connected for user accepting connection
	$connect_user_id_all = $manage_contacts->explode_string("connect_user_id", $user_id, "LIMIT 1");

	$i = $manage_contacts->string_element_index($connect_user_id_all, $connect_user_id);

	//take connected, explode to isolated element to be changed, change this element, then reconstruct
	$connected01 = $manage_contacts->explode_string("connected", $user_id, "LIMIT 1");

	//reconstruct connected
	$connected_recon01 = $manage_contacts->recon_string($i, "index", $connected01, "remove");

	//**update connected for user requesting connection
	$connect_user_id_all = $manage_contacts->explode_string("connect_user_id", $connect_user_id, "LIMIT 1");

	$k = $manage_contacts->string_element_index($connect_user_id_all, $user_id);

	//take connected, explode to isolated element to be changed, change this element, then reconstruct
	$connected02 = $manage_contacts->explode_string("connected", $connect_user_id, "LIMIT 1");

	//reconstruct connected
	$connected_recon02 = $manage_contacts->recon_string($i, "index", $connected02, "remove");

	//**take connect_user_id from user being ignored/refused connection, explode to isolated element to be removed, remove this element, then reconstruct
	$connect_user_id_all = $manage_contacts->explode_string("connect_user_id", $connect_user_id, "LIMIT 1");

	$i = $manage_contacts->string_element_index($connect_user_id_all, $user_id);

	//remove user_id of user requesting connection, from connect_user_id
	$connect_user_id_recon01 = $manage_contacts->recon_string($connect_user_id_all[$i], "value", $connect_user_id_all, "");

	//**take connect_user_id from user ignoring connect request, explode to isolated element to be removed, remove this element, then reconstruct
	$connect_user_id_all = $manage_contacts->explode_string("connect_user_id", $user_id, "LIMIT 1");

	$i = $manage_contacts->string_element_index($connect_user_id_all, $connect_user_id);

	//remove user_id of user requesting connection, from connect_user_id
	$connect_user_id_recon02 = $manage_contacts->recon_string($connect_user_id_all[$i], "value", $connect_user_id_all, "");

	//**take new requests, explode to isolated element to be removed, remove this element, then reconstruct
	$new_requests = $manage_contacts->explode_string("new_requests", $user_id, "LIMIT 1");

	//remove user_id of other user, from current user's new requests
	$new_requests_recon = $manage_contacts->recon_string($connect_user_id, "value", $new_requests, "");

	//UPDATE CONTACTS TABLE
	if($manage_db->return_query("UPDATE $manage_db->contacts SET connected='$connected_recon01' WHERE user_id='$user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET connected='$connected_recon02' WHERE user_id='$connect_user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET connect_user_id='$connect_user_id_recon01' WHERE user_id='$connect_user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET connect_user_id='$connect_user_id_recon02' WHERE user_id='$user_id'") && $manage_db->return_query("UPDATE $manage_db->contacts SET new_requests='$new_requests_recon' WHERE user_id='$user_id'")){

	//displays updated new requests' list, i.e. request is ignored and user removed from new requests
	$new_requests = $manage_contacts->displayContacts($user_id, "new requests");
	echo $new_requests;

	//displays updated new requests' list, i.e. request is ignored and user removed from new requests
	$contacts = $manage_contacts->displayContacts($user_id, "contacts");
	echo $contacts;

	}//end of if($manage_db->return_query("UPDA...
}

?>
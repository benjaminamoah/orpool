<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_everyone.php");

class manage_contacts extends manage_everyone{

/**************************************
functions here
1. explode_string()
2. string_element_index()
3. recon_string()
4. displayContacts()
****************************************/

function explode_string($col, $u_id, $limit){
	$manage_db = new manage_db();
	$query = $manage_db->return_query("SELECT $col FROM $manage_db->contacts WHERE user_id='$u_id' '$limit'");
	$result = mysql_result($query, 0, $col);
	$exploded_str = explode(",",$result);
	return $exploded_str;
}

function string_element_index($exploded_str, $u_id){
	$len = count($exploded_str);
	for($k = 0; $k<$len;$k++){
		if($exploded_str[$k] == $u_id){
			$i = $k;
			$k = $len++;
		}
	}
	return $i;
}

//reconstruct exploeded string
function recon_string($x, $inx_val, $exploded_str, $rm_rp){
	if($inx_val == "index"){ //replace value at index $x
		if($rm_rp == "replace"){
			$len=count($exploded_str);
			$recon = $exploded_str[0];

			for($k = 1; $k < $len; $k++){
				if($k == $x){
					$recon = $recon.",y";
				}else{
					$recon = $recon.",".$exploded_str[$k];
				}
			}
		}else if($rm_rp == "remove"){
			$len=count($exploded_str);

			$recon = $exploded_str[0];

			for($k = 1; $k < $len; $k++){
				if($k == $x){
					//no concatinating
				}else{
					$recon = $recon.",".$exploded_str[$k];
				}
			}
		}
	}else if($inx_val == "value"){ //remove value i.e. $x from string
			$len=count($exploded_str);
			$recon = "";

			for($k = 0; $k < $len; $k++){
				if($exploded_str[$k] == $x && $k == 0){
					//add next value
					$k++;
					$recon = $recon.$exploded_str[$k];
				}else if($exploded_str[$k] == $x && $k != 0){
					//no concatinating
				}else if($exploded_str[$k] != $x && $k == 0){
					$recon = $exploded_str[$k];
				}else{
					$recon = $recon.",".$exploded_str[$k];
				}
			}
	}

	return $recon;
}

//displays all kinds of contacts i.e. new requests, contacts etc
function displayContacts($user_id , $type){
	$manage_db = new manage_db();
	$echo_scroll = "<div id='scroll_contacts'><a onClick='scroll_up(1)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(1)'><img src='images/scroll_down_comment.png' /></a></div>";
	$echo07 = "<div id='contacts_case'>";
	if($type == "new requests"){ //displays new requests
		$echo01 = "<div id='connect_requests'>";
			$query_new_requests = $manage_db->return_query("SELECT new_requests FROM $manage_db->contacts WHERE user_id='$user_id' LIMIT 1");
			$new_requests = mysql_result($query_new_requests, 0, "new_requests");
			$requests = explode(",",$new_requests);

			$requests_len = count($requests);

			if(strlen($new_requests) > 0){
				if(strlen($new_requests) == 1){
				$echo02 = '<div id="new_requests">
						1 new request
						</div>';
				}else{
				$echo02 = '<div id="new_requests">
						'.$requests_len.' new requests
						</div>';
				}

				$echo_users =  "";
				for($i=0; $i<$requests_len; $i++){
				$query_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$requests[$i]' LIMIT 1");
					while($row = mysql_fetch_array($query_users)){
						$pic = $row['profile_picture'];
						$conn_user_id = $row['user_id'];
						$echo03 = "<div id='users'>";
						if(strlen($pic) > 14){
							$echo04 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
						}else{
							$echo04 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
						}
						$echo05 = "<div id='users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."<br /><div id='connect'><a id='add_new_contact".$conn_user_id."' onClick='add_to_contacts(".$conn_user_id.")'>add</a> <a id='add_new_contact".$conn_user_id."' onClick='ignore_contact(".$conn_user_id.")'>ignore</a> </div></div></div>";
					}//end of while loop
					$echo_users = $echo_users.$echo03.$echo04.$echo05;
				}//end of for loop

			}
		$echo06 = "</div>";
	//end of echo new request list

	$echo_all = $echo01.$echo02.$echo_users.$echo06;

	return $echo_all;
	}else if($type == "contacts"){
	$echo01 = "<div id='contact_list'>";
		$query_contacts = $manage_db->return_query("SELECT connect_user_id FROM $manage_db->contacts WHERE user_id='$user_id' LIMIT 1");
		$query_connected = $manage_db->return_query("SELECT connected FROM $manage_db->contacts WHERE user_id='$user_id' LIMIT 1");
		$result_contacts = mysql_result($query_contacts, 0, "connect_user_id");
		$result_connected = mysql_result($query_connected, 0, "connected");
		$contacts = explode(",",$result_contacts);
		$connected = explode(",",$result_connected);
		$contacts_len = count($contacts);
		$connected_len = count($connected);

		$echo_users =  "";
		for($k=1; $k<$connected_len; $k++){
		if($contacts[$k] != $user_id && $connected[$k] == "y"){
			$query_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$contacts[$k]' LIMIT 1");

			while($row = mysql_fetch_array($query_users)){
					$pic = $row['profile_picture'];
					$conn_user_id = $row['user_id'];
					$echo03 = "<div id='users'>";
					if(strlen($pic) > 14){
						$echo04 =  "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
					}else{
						$echo04 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
					}
					$echo05 = "<div id='users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."<br /><div id='connect'><a><!--accept--></a></div></div></div>";
			}//end of while loop
				$echo_users = $echo_users.$echo03.$echo04.$echo05;
			}//end of if($contacts[$k] != $u...
			}//end of for loop

			$i = 0;
			for($c=0; $c<$contacts_len; $c++){
				if($connected[$c] == "y"){
					$i++;
				}
			}

			if($i == 2){
			$echo02 = '<div id="new_requests">
					1 contact
					</div>';
			}else{
			$echo02 = '<div id="new_requests">
					'.($i-1).' contacts
					</div>';
			}

	$echo06 = "</div></div>";

	$echo_all = $echo_scroll.$echo07.$echo01.$echo02.$echo_users.$echo06;

	return $echo_all;
}else{}
}//end of function displayContacts

}
?>
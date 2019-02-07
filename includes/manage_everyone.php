<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_db.php");

class manage_everyone extends manage_db{
/**************************************
functions here
1. convoDisplayUser()
2. displayEveryone()
3. displaySearch()
****************************************/

//displays main user
function convoDisplayUser($user_id){
	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	while($row = mysql_fetch_array($query_user)){
		$pic = $row['profile_picture'];
		$conn_user_id2 = "connect".$row['user_id'];
		$user = $row['user_id'];
		$echo01 = "<div id='convo_users'>";
		if(strlen($pic) > 14){
			$echo02 = "<div id='convo_user_pic'><img id='convo_profile_pic' src='images/".$pic."' /></div>";
		}else{
			$echo02 = "<div id='convo_user_pic'><img id='convo_profile_pic' src='images/default.png' /></div>";
		}
		if(strlen($row['city']) > 0 && strlen($row['country']) > 0){
			$echo03= "<div id='convo_users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."</div>";
		}else{
			$echo03= "<div id='convo_users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a></div>";
		}
		$echo04 = '<div id="start_convo_icons"><!--<div id="start_convo_private"  onClick="start_convo_private('.$user.')" title="start a private conversation"><a href="#"></a></div>-->
		<div id="start_convo_public" onClick="start_convo_public('.$user.')"  title="start a public conversation"><a href="#"></a></div></div>
		</div>';
		$echo05 = '<input type="hidden" value="'.$pic.'" id="profile_src" />';

	}

	$echo_user = $echo01.$echo02.$echo03.$echo04.$echo05;
	return $echo_user;
}

//displays another user without create conversation icons
function convoDisplayUserOther($user_id){
	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	while($row = mysql_fetch_array($query_user)){
		$pic = $row['profile_picture'];
		$conn_user_id2 = "connect".$row['user_id'];
		$user = $row['user_id'];
		$echo01 = "<div id='convo_users'>";
		if(strlen($pic) > 14){
			$echo02 = "<div id='convo_user_pic'><img id='convo_profile_pic' src='images/".$pic."' /></div>";
		}else{
			$echo02 = "<div id='convo_user_pic'><img id='convo_profile_pic' src='images/default.png' /></div>";
		}
		$echo03= "<div id='convo_users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."</div>";
		$echo04 = "</div>";
	}

	$echo_user = $echo01.$echo02.$echo03.$echo04;
	return $echo_user;
}


//displays users etc
function displayEveryone($user_id){
	$manage_db = new manage_db();
		$query_connecting = $manage_db->return_query("SELECT connect_user_id FROM $manage_db->contacts WHERE user_id='$user_id' LIMIT 1");
		$connect_user_id = mysql_result($query_connecting, 0, "connect_user_id");
		$connections = explode(",",$connect_user_id);
		$len = count($connections);
		$echo_users = "";
		$echo04 = "<div id='scroll_everyone'><a onClick='scroll_up(2)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(2)'><img src='images/scroll_down_comment.png' /></a></div>";
		$echo05 = "<div id='everyone'><div id='everyone_case'>";

		$query_all_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id!='$user_id' AND user_id!=$manage_db->anonymous_id ORDER BY user_id DESC LIMIT 10");
		while($row = mysql_fetch_array($query_all_users)){

		$conn_user_id = $row['user_id'];
		//loop through ids in connect_user_id corresponding to this user
		$i = 0;
		$c = 0; //check for when user found in connections
		while($i<$len && $c==0){
			if($connections[$i] == $conn_user_id){
				$c = 1;
			}
			$i++;
		}

		if($c == 0){ //was not found in user connections
			$pic = $row['profile_picture'];
			$conn_user_id2 = "connect".$row['user_id'];
			$user = $row['user_id'];
			$echo01 = "<div id='users'>";
			if(strlen($pic) > 14){
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
			}else{
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
			}
			$echo03 = "<div id='users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."<br /><div id='connect'><a id='".$conn_user_id2."' onClick='connect(".$conn_user_id.")'>connect</a></div></div></div>";
			$echo_users = $echo_users.$echo01.$echo02.$echo03;
		}else if($c == 1){ //was found
			$pic = $row['profile_picture'];
			$conn_user_id2 = "connect".$row['user_id'];
			$user = $row['user_id'];
			$echo01 = "<div id='users'>";
			if(strlen($pic) > 14){
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
			}else{
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
			}
			$echo03 = "<div id='users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."</div></div>";
			$echo_users = $echo_users.$echo01.$echo02.$echo03;
		}

	}
	$echo06 = "</div>";
	$echo08 = "</div>";

	$query_more_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id!='$user_id' AND user_id!=13 ORDER BY user_id DESC LIMIT 10, 1");
	if(mysql_num_rows($query_more_users) > 0){
		$echo07 = "<div id='more_everyone'><a onClick='show_more_users(".$user_id.", 10, 10)'>more</a></div></div>";
		$echo_all = $echo04.$echo05.$echo_users.$echo06.$echo07;
	}else{
		$echo_all = $echo04.$echo05.$echo_users.$echo06.$echo08;
	}

	echo $echo_all;
}//end of function displayEveryone


//displays more users etc
function displayMoreEveryone($user_id, $num_rows, $start_from){
	$manage_db = new manage_db();
		$query_connecting = $manage_db->return_query("SELECT connect_user_id FROM $manage_db->contacts WHERE user_id='$user_id' LIMIT 1");
		$connect_user_id = mysql_result($query_connecting, 0, "connect_user_id");
		$connections = explode(",",$connect_user_id);
		$len = count($connections);
		$echo_users = "";
		$echo05 = "";

		$query_all_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id!='$user_id' AND user_id!=13 ORDER BY user_id DESC LIMIT $start_from, $num_rows");
		while($row = mysql_fetch_array($query_all_users)){

		$conn_user_id = $row['user_id'];
		//loop through ids in connect_user_id corresponding to this user
		$i = 0;
		$c = 0; //check for when user found in connections
		while($i<$len && $c==0){
			if($connections[$i] == $conn_user_id){
				$c = 1;
			}
			$i++;
		}

		if($c == 0){ //was not found in user connections
			$pic = $row['profile_picture'];
			$conn_user_id2 = "connect".$row['user_id'];
			$user = $row['user_id'];
			$echo01 = "<div id='users'>";
			if(strlen($pic) > 14){
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
			}else{
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
			}
			$echo03 = "<div id='users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."<br /><div id='connect'><a id='".$conn_user_id2."' onClick='connect(".$conn_user_id.")'>connect</a></div></div></div>";
			$echo_users = $echo_users.$echo01.$echo02.$echo03;
		}else if($c == 1){ //was found
			$pic = $row['profile_picture'];
			$conn_user_id2 = "connect".$row['user_id'];
			$user = $row['user_id'];
			$echo01 = "<div id='users'>";
			if(strlen($pic) > 14){
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
			}else{
				$echo02 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
			}
			$echo03 = "<div id='users_name'><a onClick='go_to_user(".$user.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."</div></div>";
			$echo_users = $echo_users.$echo01.$echo02.$echo03;
		}

	}

	$echo06 = "</div>";

	$echo07 = "[BRK]";

	$start_from = $start_from + $num_rows;
	$query_more_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id!='$user_id' AND user_id!=13 ORDER BY user_id DESC LIMIT $start_from, 1");
	if(mysql_num_rows($query_more_users) > 0){
		$echo08 = "<div id='more_everyone'><a onClick='show_more_users(".$user_id.", ".$num_rows.", ".$start_from.")'>more</a></div></div>";
		$echo_all = $echo05.$echo_users.$echo06.$echo07.$echo08;
	}else{
		$echo_all = $echo05.$echo_users.$echo06.$echo07;
	}

	echo $echo_all;
}//end of function displayMoreEveryone


function displaySearch($user_id, $text){
$manage_db = new manage_db();
$echo_users = "";
$echo01 = "<div id='everyone_case'>";

$len_txt = strlen($text);
$text = strtolower($text);
$query_user = $manage_db->return_query("SELECT * FROM $manage_db->contacts WHERE user_id='$user_id' LIMIT 1");
while($row = mysql_fetch_array($query_user)){
	$connections = $row['connect_user_id'];
}
$connections = explode(",", $connections);
$len = count($connections);

	$query_all_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id!='$user_id' AND user_id!=$manage_db->anonymous_id AND (LOWER(CONCAT(first_name,' ',last_name)) LIKE '$text%' OR  LOWER(CONCAT(first_name,' ',last_name)) LIKE '$text' OR LOWER(last_name) LIKE '$text%' OR LOWER(last_name) LIKE '$text') ORDER BY first_name LIMIT 30");
	while($row = mysql_fetch_array($query_all_users)){
		$conn_user_id = $row['user_id'];
		//loop through ids in connect_user_id corresponding to this user
		$i = 0;
		$c = 0; //check for when user found in connections
		while($i<$len && $c==0){
			if($connections[$i] == $conn_user_id){
				$c = 1;
			}
			$i++;
		}

		$fname = $row['first_name'];
		$lname = $row['last_name'];
		$name = $fname." ".$lname;

		if($c == 0){ //was not found in user connections
			$pic = $row['profile_picture'];
			$conn_user_id = $row['user_id'];
			$echo02 = "<div id='users'>";
			if(strlen($pic) > 14){
				$echo03 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
			}else{
				$echo03 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
			}
			$echo04 = "<div id='users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."<br /><div id='connect'><a id='connect".$conn_user_id."' onClick='connect(".$conn_user_id.")'>connect</a></div></div></div>";
			$echo_users = $echo_users.$echo02.$echo03.$echo04;
		}else if($c == 1){ //was found
			$pic = $row['profile_picture'];
			$conn_user_id = $row['user_id'];
			$echo02 = "<div id='users'>";
			if(strlen($pic) > 14){
				$echo03 = "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
			}else{
				$echo03 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
			}
			$echo04 = "<div id='users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a><br />".$row['city'].", ".$row['country']."<br /></div></div>";
			$echo_users = $echo_users.$echo02.$echo03.$echo04;
		} //end of was found

	} //while
	//end of if search text given

	$echo06 = "</div>";

//	$start_from = $start_from + $num_rows;
//	$query_more_users = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id!='$user_id' AND user_id!=13 ORDER BY user_id DESC LIMIT $start_from, 1");
//	if(mysql_num_rows($query_more_users) > 0){
//		$echo07 = "<div id='more_everyone'><a onClick='show_more_users(".$user_id.", ".$num_rows.", ".$start_from.")'>more</a></div></div>";
//		$echo_all = $echo01.$echo_users.$echo06.$echo07;
//	}else{
		$echo_all = $echo01.$echo_users.$echo06;
//	}

	if(strlen($echo_users) == 0){
		$echo_all = "";
	}

	return $echo_all;

}//end of displaySearch

}
?>
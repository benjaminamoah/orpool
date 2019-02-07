<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_contacts.php");

class manage_conversations extends manage_everyone{

/**********************************************************************
1. goToUser($user_id, $connect_user_id)
2. displayConvoContacts($user_id)
3. displayConvPublic($user_id)
4. displayConvNotice($user_id)
5. getHighestConvId($user_id)
6. TranslateTime($time_rec)
7. displayConversations($user_id, $connect_user_id)
8. displaySearchPublicConversations($user_id, $text)
9. displayParticipateConversations($user_id, $connect_user_id)
10. displayPublicConversations($user_id)
11. displayComments($conv_id, $start_from)
12. displayParticipateComments($conv_id, $start_from)
13. displayCommentSectionNums($conv_id, $start_from, $num_rows)
14. displayParticipateCommentSectionNums($conv_id)
15. displayCommentsTranscript($conv_id)
16. getCommentId($conv_id)
17. displayMyConversations($user_id)
18. refreshComments($conv_id, $comment_id)
19. newComments($conv_id, $num_rows)
20. earlierComments($conv_id, $num_rows, $start_from)
21. earlierConvs($user_id, $connect_user_id, $num_rows, $start_from)
22. earlierPublicConvs($user_id, $num_rows, $start_from)
23. earlierMyConvs($user_id, $start_from, $end_at)
24. displayChangeProfilePic($user_id)
25. makeUrlLinks($text)
26. vote($poll_option_id, $conv_id, $user_id)

**********************************************************************/

//1.
function goToUser($user_id, $connect_user_id){
	$echo_all = "";
	$manage_everyone = new manage_everyone();

	if($user_id == $connect_user_id){
		$conv_notice = $this->displayConvNotice($connect_user_id);
		$echo_conv_user = $manage_everyone->convoDisplayUser($connect_user_id, $user_id);
		$echo_all = $echo_all.$echo_conv_user;
	}else{
		$conv_notice = "";
		$echo_conv_user = $manage_everyone->convoDisplayUserOther($connect_user_id, $user_id);
		$echo_all = $echo_all.$echo_conv_user;
	}

	if($conv_notice == "<div id='conv_notice'>see 'public conversations' to your right to join a conversation...</div><br /><a id='change_profile_pic' onClick='change_profile_pic()'>change my profile picture</a>"){
		$convs = $this->displayConversations($user_id, $connect_user_id);
		$echo01 = "[BRK]";
		$echo_all = $echo_all.$echo01.$conv_notice.$echo01.$convs;
	}else{
		$convs = $this->displayConversations($user_id, $connect_user_id);
		$echo01 = "[BRK]";
		$echo_all = $echo_all.$echo01.$conv_notice.$echo01.$convs;
	}

	return $echo_all;
}


//2. displays all kinds of contacts
function displayConvoContacts($user_id){
	$manage_db = new manage_db();
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
					$echo05 = "<div id='convo_user_name'>".$row['first_name']." ".$row['last_name']."</div><div id='convo_checkbox'><input type='checkbox'  /></div></div>";
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

	$echo_all = $echo_users;

	return $echo_all;
}//end of function displayContacts


//3. displays create public conversation
function displayConvPublic($user_id){
	$manage_db = new manage_db();
	$public_post_count = '"public_post_count"';
	$first_comment = '"first_comment"';

	$echo01 = "<div id='create_public_conv'>";
	$echo02 = "<textarea id='first_comment' onKeyDown='postCount(".$public_post_count.",".$first_comment.")' onKeyUp='postCount(".$public_post_count.",".$first_comment.")'></textarea><div id='public_post_count'>250</div>
	<div id = 'first_comment_note'></div>";

	$echo_all = $echo01.$echo02;

	return $echo_all;
}//end of function displayContacts


//4.
function displayConvNotice($user_id){
	$manage_db = new manage_db();
	$query_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations where user_id='$user_id'");

	if(mysql_num_rows($query_convs) > 0){
		$result_comments = mysql_result($query_convs, 0, "num_of_comments");
		$echo_all = "";
	}else{
		$echo_all = "<div id='conv_notice'>see 'public conversations' to your right to join a conversation...</div>";
	}

	$echo01 = "<div id='conv_notice'><a id='change_profile_pic' onClick='change_profile_pic()'>change my profile picture</a></div>";
	$echo_all = $echo_all.$echo01;
	return $echo_all;
}


//5.
function getHighestConvId($user_id){
	$manage_db = new manage_db();
	$query_conv = $manage_db->return_query("SELECT MAX(convo_id) FROM $manage_db->conversations WHERE user_id='$user_id'");
	while($row = mysql_fetch_array($query_conv)){
		$conv_id = mysql_result($query_conv, 0, "MAX(convo_id)");
	}
	return $conv_id;
}


//6. translates time
function TranslateTime($time_rec){
$time_now = time();
$time_diff = $time_now - $time_rec;
	if($time_diff < 60){
		$time_display = "just now";
	}else if($time_diff < 3600 && $time_diff >= 60){
		$time_display = floor($time_diff/60);
			if($time_display == 1){
				$time_display = "a minute ago";
			}else{
				if($time_rec < strtotime("today")){
					$time_display = "yesterday at ".date("g:ia", $time_rec);
				}else{
					$time_display = $time_display." minutes ago";
				}
			}
	}else if($time_diff >= 3600 && $time_diff < 86400){
		$time_display = floor($time_diff/3600);
		if($time_display == 1){
			if($time_rec < strtotime("today")){
				$time_display = "yesterday at ".date("g:ia", $time_rec);
			}else{
				$time_display = "an hour ago";
			}
		}else{
			if($time_rec < strtotime("today")){
				$time_display = "yesterday at ".date("g:ia", $time_rec);
			}else{
				$time_display = $time_display." hours  ago";
			}
		}
	}else if($time_diff >= 86400 && $time_diff < 604800){
		$time_display = date("l", $time_rec)." at ".date("g:ia", $time_rec);
	}else if($time_diff >= 604800 && $time_diff < 2419200){
		$time_display = floor($time_diff/604800);
		if($time_display == 1){
			$time_display = "a week ago";
		}else{
			$time_display = $time_display." weeks ago";
		}
	}else if($time_diff >= 2419200 && $time_diff < 31536000){
		if(date("Y", $time_now) == date("Y",$time_rec)){
			$time_display = date("n", $time_now) - date("n",$time_rec);
		}else{
			$time_display = date("n", $time_now) + (12 -date("n",$time_rec));
		}
		if($time_display == 1){
			$time_display = "1 month ago";
		}else{
			$time_display = $time_display." months ago";
		}
	}else if($time_diff >= 31536000){
			$time_display = date("Y", $time_now) - date("Y",$time_rec);
			if($time_display == 1){
				$time_display = "1 year";
			}else{
				$time_display = $time_display." years";
			}
	}

	return $time_display;
}


//7. display conversations of currently selected user
function displayConversations($user_id, $connect_user_id){
	$manage_db = new manage_db();
	$query_convs01 = $manage_db->return_query("SELECT * FROM $manage_db->conversations WHERE user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT 1");
	$query_convs02 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$query_convs03 = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON  co.user_id=us.user_id WHERE co.user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT 10");
	$query_convs04 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$connect_user_id' LIMIT 1");
	$echo_convs = "";

	while($row = mysql_fetch_array($query_convs01)){
		$conv_id01 = mysql_result($query_convs01, 0, "convo_id");
	}

	if(mysql_num_rows($query_convs01) == 0){
		$conv_id01 = 0;
		$max_comment_id = 0;
	}else{
		$max_comment_query = $manage_db->return_query("SELECT max(comments_id) FROM $manage_db->comments WHERE convo_id='$conv_id01'");
		$max_comment_id = mysql_result($max_comment_query, 0, "max(comments_id)");
	}

	while($row = mysql_fetch_array($query_convs02)){
		$convs_tracking = $row['convos_tracking'];
		$convs_tracking = explode(",", $convs_tracking);
		$len = count($convs_tracking);
	}

	while($row = mysql_fetch_array($query_convs04)){
		$user = $row['user_id'];
		$fname = $row['first_name'];
		$lname = $row['last_name'];
		$pic = $row['profile_picture'];
	}

	$echo01 = "<script type='text/javascript'>document.getElementById('conv_id').value = '".$conv_id01."';</script>
						<script type='text/javascript'>document.getElementById('comment_id').value = '".$max_comment_id."';</script>";

	$echo05 = "<div id='current_conv_user'>";
	if(strlen($pic) > 14){
		$echo06 =  "<div id='user_pic'><img id='profile_pic' src='images/".$pic."' /></div>";
	}else{
		$echo06 = "<div id='user_pic'><img id='profile_pic' src='images/default.png' /></div>";
	}
	$echo07 = "<div id='users_name'><a onClick='go_to_user(".$user.")'>".$fname." ".$lname."</a><br /></div></div>";

	$echo008 = "<div id='scroll_convs_case'><a onClick='scroll_up(4)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(4)'><img src='images/scroll_down_comment.png' /></a></div><div id ='in_convs_case'>";
	$echo08 = "<div id='convs_case'>";
	while($row = mysql_fetch_array($query_convs03)){
			$pic = $row['profile_picture'];
			$conv_id = $row['convo_id'];
			$conversation = $row['first_comment'];
			$timestamp = $row['timestamp'];
			$time = $this->TranslateTime($timestamp);
			$type_of_conv = $row['type_of_convo'];
			$num_of_options = $row['num_of_options'];

			if($type_of_conv == "poll"){
				if($conv_id == $conv_id01){
					$echo09 = "<div id='convs".$conv_id."'><div id='convs' style='background-color: #acf'>";
				}else{
					$echo09 = "<div id='convs".$conv_id."'><div id='convs'>";
				}
				$echo10 = $conversation;

				$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
				$echo_opts = "<br />";
				while($row = mysql_fetch_array($query_options)){
					$poll_option = $row['poll_option'];
					$num_of_votes = $row['num_of_votes'];
					if($num_of_votes == 1){
						$echo_opts = $echo_opts." ".$poll_option."<br />";
					}else{
						$echo_opts = $echo_opts." ".$poll_option."<br />";
					}
				}

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><a onClick='join_current_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}else if($c == 0){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><div id='track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track </a></div></div><div id='connect'><a onClick='join_current_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}
				$echo12 = "</div></div>";

				$echo_convs = $echo_convs.$echo09.$echo10.$echo_opts.$echo11.$echo12;
			}else{
				if($conv_id == $conv_id01){
					$echo09 = "<div id='convs".$conv_id."'><div id='convs' style='background-color: #acf'>";
				}else{
					$echo09 = "<div id='convs".$conv_id."'><div id='convs'>";
				}
				$echo10 = $conversation;

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><a onClick='join_current_conv(".$conv_id.")'> join</a></div>";
				}else if($c == 0){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><div id='track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track </a></div></div><div id='connect'><a onClick='join_current_conv(".$conv_id.")'> join</a></div>";
				}
				$echo12 = "</div></div>";

			$echo_convs = $echo_convs.$echo09.$echo10.$echo11.$echo12;
		}
	}//end of while loop
	$echo13 = "</div>";

$query_more_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE co.user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT 10, 1");
if(mysql_num_rows($query_more_convs) > 0){
	$echo14 = "<div id='more_current_user_convs'><a onClick='add_earlier_convs(".$user_id.", ".$connect_user_id.", ".$conv_id.", 10, 10)'>more</a></div></div>";
}else{
	$echo14 = "</div>";
}

$echo_all = $echo05.$echo06.$echo07.$echo008.$echo08.$echo_convs.$echo13.$echo14.$echo01;

if(mysql_num_rows($query_convs03) == 0){
	$echo_all = $echo05.$echo06.$echo07.$echo008.$echo08."<center>".$fname." has not started any conversations</center>".$echo13.$echo14.$echo01;
}

return $echo_all;
}


//8. display all conversations
function displaySearchPublicConversations($user_id, $text){
	$manage_db = new manage_db();
	$query_convs01 = $manage_db->return_query("SELECT * FROM $manage_db->conversations ORDER BY num_tracking DESC LIMIT 1");
	$query_convs02 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$query_convs03 = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE co.first_comment LIKE '$text' OR co.first_comment LIKE '$text%' OR co.first_comment LIKE '%$text' OR co.first_comment LIKE '%$text%' ORDER BY num_tracking DESC LIMIT 30");
	$query_convs04 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$echo_convs = "";

	while($row = mysql_fetch_array($query_convs01)){
		$conv_id01 = mysql_result($query_convs01, 0, "convo_id");
	}

	while($row = mysql_fetch_array($query_convs02)){
		$convs_tracking = $row['convos_tracking'];
		$convs_tracking = explode(",", $convs_tracking);
		$len = count($convs_tracking);
	}

	while($row = mysql_fetch_array($query_convs04)){
		$user = $row['user_id'];
		$fname = $row['first_name'];
		$lname = $row['last_name'];
	}

	$echo01 = "<div id='scroll_pub_case'><a onClick='scroll_up(6)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(6)'><img src='images/scroll_down_comment.png' /></a></div><div id='in_public_case'>";
	$echo02 = "<div id='public_case'>";
	while($row = mysql_fetch_array($query_convs03)){
			$fname = $row['first_name'];
			$lname = $row['last_name'];
			$user_id = $row['user_id'];
			$conv_id = $row['convo_id'];
			$conversation = $row['first_comment'];
			$num_tracking = $row['num_tracking'];
			$timestamp = $row['timestamp'];
			$time = $this->TranslateTime($timestamp);
			$type_of_conv = $row['type_of_convo'];

		if($type_of_conv != "poll"){
			$echo03 = "<div id='pub_convs".$conv_id."'><div id='convs'>".$conversation;

			$i = 0;
			$c = 0;
			while($i<$len && $c==0){
				if($convs_tracking[$i] == $conv_id){
					$c = 1;
				}
				$i++;
			}

			if($c == 1){
				$echo04 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>";
			}else if($c == 0){
				$echo04 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><div id=pub_'track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track</a></div></div><div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>";
			}
			$echo05 = "</div></div>";

			$echo_convs = $echo_convs.$echo03.$echo04.$echo05;
		}
	}//end of while loop

	$echo08 = "</div></div>";

/**************
	$query_more_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id ORDER BY num_tracking DESC LIMIT 10, 1");
	if(mysql_num_rows($query_more_convs) > 0){
		$echo10 = "<div id='more_public_convs'><a onClick='add_earlier_public_convs(".$user_id.", 10, 10)'>more</a></div>";
	}else{
		$echo10 = "";
	}
**************/

	$echo_all = $echo01.$echo02.$echo_convs.$echo08;

if(mysql_num_rows($query_convs03) == 0){
	$echo_all = "";
}

return $echo_all;
}


//9. display conversations of currently selected user
function displayParticipateConversations($user_id, $connect_user_id){
	$manage_db = new manage_db();
	$query_convs01 = $manage_db->return_query("SELECT * FROM $manage_db->conversations WHERE user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT 1");
	$query_convs02 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$query_convs03 = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON  co.user_id=us.user_id WHERE co.user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT 10");
	$query_convs04 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$connect_user_id' LIMIT 1");
	$echo_convs = "";

	while($row = mysql_fetch_array($query_convs01)){
		$conv_id01 = $row['convo_id'];
	}

	if(mysql_num_rows($query_convs01) == 0){
		$conv_id01 = 0;
		$max_comment_id = 0;
	}else{
		$max_comment_query = $manage_db->return_query("SELECT max(comments_id) FROM $manage_db->comments WHERE convo_id='$conv_id01'");
		$max_comment_id = mysql_result($max_comment_query, 0, "max(comments_id)");
	}

	while($row = mysql_fetch_array($query_convs02)){
		$convs_tracking = $row['convos_tracking'];
		$convs_tracking = explode(",", $convs_tracking);
		$len = count($convs_tracking);
	}

	while($row = mysql_fetch_array($query_convs04)){
		$user = $row['user_id'];
		$fname = $row['first_name'];
		$lname = $row['last_name'];
		$pic = $row['profile_picture'];
	}

	$max_comment_query = $manage_db->return_query("SELECT comments_id FROM $manage_db->comments WHERE convo_id='$conv_id01' ORDER BY comments_id DESC LIMIT 1");
	while($row = mysql_fetch_array($max_comment_query)){
		$max_comment_id = $row['comments_id'];
	}

	$echo05 = "<div id='current_conv_user'>";
	if(strlen($pic) > 14){
		$echo06 =  "<div id='user_pic'><img id='profile_pic' src='../images/".$pic."' /></div>";
	}else{
		$echo06 = "<div id='user_pic'><img id='profile_pic' src='../images/default.png' /></div>";
	}
	$echo07 = "<div id='users_name'><a onClick='participate_signup()'>".$fname." ".$lname."</a><br /></div></div>";

	$echo08 = "<div id='convs_case'>";
	while($row = mysql_fetch_array($query_convs03)){
			$pic = $row['profile_picture'];
			$conv_id = $row['convo_id'];
			$conversation = $row['first_comment'];
			$timestamp = $row['timestamp'];
			$time = $this->TranslateTime($timestamp);

			$echo09 = "<div id='convs".$conv_id."'><div id='convs'>";
			$echo10 = $conversation;
			$echo11 = "<div id='time'>".$time."</div><div id='connect'><a onClick='participate_signup()'> join</a></div>";
			$echo12 = "</div></div>";

	$echo_convs = $echo_convs.$echo09.$echo10.$echo11.$echo12;
	}//end of while loop
	$echo13 = "</div>";

$query_more_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE co.user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT 10, 1");
if(mysql_num_rows($query_more_convs) > 0){
	$echo14 = "<div id='more_current_user_convs'><a onClick='add_earlier_convs(".$user_id.", ".$connect_user_id.", ".$conv_id.", 10, 10)'>more</a></div>";
}

$echo_all = $echo05.$echo06.$echo07.$echo08.$echo_convs.$echo13.$echo14.$echo01;

if(mysql_num_rows($query_convs03) == 0){
	$echo_all = $echo05.$echo06.$echo07.$echo08."<center>".$fname." has not started any conversations</center>".$echo13.$echo14.$echo01;
}

return $echo_all;
}


//10. display all conversations
function displayPublicConversations($user_id){
	$manage_db = new manage_db();
	$query_convs01 = $manage_db->return_query("SELECT * FROM $manage_db->conversations ORDER BY num_tracking DESC LIMIT 1");
	$query_convs02 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$query_convs03 = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id ORDER BY num_tracking DESC, convo_id  DESC LIMIT 10");
	$query_convs04 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$echo_convs = "";
	$echo_opts = "";

	while($row = mysql_fetch_array($query_convs01)){
		$conv_id01 = mysql_result($query_convs01, 0, "convo_id");
	}

	while($row = mysql_fetch_array($query_convs02)){
		$convs_tracking = $row['convos_tracking'];
		$convs_tracking = explode(",", $convs_tracking);
		$len = count($convs_tracking);
	}

	while($row = mysql_fetch_array($query_convs04)){
		$user = $row['user_id'];
		$fname = $row['first_name'];
		$lname = $row['last_name'];
	}

	$echo01 = "<div id='scroll_pub_case'><a onClick='scroll_up(6)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(6)'><img src='images/scroll_down_comment.png' /></a></div><div id='in_public_case'>";
	$echo02 = "<div id='public_case'>";
	while($row = mysql_fetch_array($query_convs03)){
			$fname = $row['first_name'];
			$lname = $row['last_name'];
			$user_id = $row['user_id'];
			$conv_id = $row['convo_id'];
			$conversation = $row['first_comment'];
			$num_tracking = $row['num_tracking'];
			$timestamp = $row['timestamp'];
			$time = $this->TranslateTime($timestamp);
			$type_of_conv = $row['type_of_convo'];
			$num_of_options = $row['num_of_options'];

			if($type_of_conv == "poll"){
				$echo03 = "<div id='pub_convs".$conv_id."'><div id='convs'>".$conversation;

				$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
				$echo_opts = "<br />";
				while($row = mysql_fetch_array($query_options)){
					$poll_option = $row['poll_option'];
					$echo_opts = $echo_opts." ".$poll_option."<br />";
				}

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo11 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><a onClick='go_to_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}else if($c == 0){
					$echo11 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><div id='pub_track".$conv_id."'><a id='".$conv_id."' onClick='pub_track(".$conv_id.")'>track</a></div></div><div id='connect'><a onClick='go_to_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}
				$echo12 = "</div></div>";

				$echo_convs = $echo_convs.$echo03.$echo_opts.$echo11.$echo12;
			}else{
				$echo03 = "<div id='pub_convs".$conv_id."'><div id='convs'>".$conversation;

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo04 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>";
				}else if($c == 0){
					$echo04 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><div id='pub_track".$conv_id."'><a id='".$conv_id."' onClick='pub_track(".$conv_id.")'>track</a></div></div><div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>";
				}
				$echo05 = "</div></div>";

		$echo_convs = $echo_convs.$echo03.$echo04.$echo05;
	}
	}//end of while loop

	$echo08 = "</div>";

	$query_more_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id ORDER BY num_tracking DESC, convo_id  DESC LIMIT 10, 1");
	if(mysql_num_rows($query_more_convs) > 0){
		$echo10 = "<div id='more_public_convs'><a onClick='add_earlier_public_convs(".$user_id.", 10, 10)'>more</a></div></div>";
	}else{
		$echo10 = "</div>";
	}

	$echo_all = $echo01.$echo02.$echo_convs.$echo08.$echo10;

return $echo_all;
}


//11.a. displays 9 comments of conversation
function displayComments($conv_id, $start_from){
	$manage_db = new manage_db();
	$query_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY cm.comments_id DESC LIMIT $start_from, 9");
	$echo_comments = "";
	$box_num = 0;

	while($row = mysql_fetch_array($query_comments)){
		$pic = $row['profile_picture'];
		$conn_user_id = $row['user_id'];
		//$user = $row['user_id'];
		$comment = $row['comment'];
		$timestamp = $row['timestamp'];
		$time = $this->TranslateTime($timestamp);
		$box_num++;

		$echo01 = "<div id='convo_comments'><div id='convo_comments_border'><div id='convo_comments_top'>";
		if(strlen($pic) > 14){
			$echo02 = "<div id='comment_profile_pic_case'><img id='comment_profile_pic' src='images/".$pic."' /></div>";
		}else{
			$echo02 = "<div id='comment_profile_pic_case'><img id='comment_profile_pic' src='images/default.png' /></div>";
		}
		if(strlen($comment) > 150){
			$echo03 = "<div id='comment_users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a></div><div id='tcomment_ime'>".$time."</div></div>";
			$echo04 = "<div id='scroll_comment'><a onClick='scroll_up_comment(".$box_num.")'><img src='images/scroll_up_comment1.png' /></a><a onClick='scroll_down_comment(".$box_num.")'><img src='images/scroll_down_comment1.png' /></a></div><div id='convo_comment".$box_num."' style='margin-top:0px; z-index: -52; position: relative; height: 100%'>".$comment." </div></div></div>";
		}else if(strlen($comment) <= 150){
			$echo03 = "<div id='comment_users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a></div><div id='comment_time'>".$time."</div></div>";
			$echo04 = "<div id='convo_comment'>".$comment."</div></div></div>";
		}
		$echo05 = "[BRK]";
		$echo_comments = $echo_comments.$echo01.$echo02.$echo03.$echo04.$echo05;
	}

	return $echo_comments;
}


//11.b. displays poll satistics
function displayPollStats($user_id, $conv_id, $start_from, $num_of_options){
	$manage_db = new manage_db();
	$query_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations WHERE convo_id='$conv_id' LIMIT 1");
	$echo_poll_stats = "";
	$echo_opts = "";
	$echo_opt_votes = "<div id='stats_heading'>so far...</div>";

	while($row = mysql_fetch_array($query_convs)){
		$first_comment = $row['first_comment'];
	}

	$query_users_taken_poll = $manage_db->return_query("SELECT * FROM $manage_db->users_taken_poll WHERE user_id='$user_id' LIMIT 1");
	while($row = mysql_fetch_array($query_users_taken_poll)){
		$conv_ids = $row['convo_ids'];
		$poll_option_ids = $row['option_ids'];
	}

	$explode_conv_ids = explode(",",$conv_ids);
	$len = count($explode_conv_ids);
	$i = 0;
	$c = 0;
	while($i<$len && $c == 0){
		if($explode_conv_ids[$i] == $conv_id){
			$c = 1;
		}
		$i++;
	}
	$i--;

	$explode_poll_option_ids = explode(",",$poll_option_ids);
	$poll_option_id = $explode_poll_option_ids[$i];

	$query_options01 = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
	while($row = mysql_fetch_array($query_options01)){
		$poll_option = $row['poll_option'];
		$poll_option_id01 = $row['poll_option_id'];
		$poll_option_id02 = "opt".$poll_option_id01;
		$conv_id = $row['convo_id'];
		if($poll_option_id01 == $poll_option_id){
			$echo_opts = $echo_opts."<div id='poll_option' onClick='vote(".$poll_option_id01.",".$conv_id.",".$num_of_options.")'><div id='".$poll_option_id02."' style='background-color: #cef; width: 12px; height: 12px; border: 1px solid #ccc; margin-right: 4px; float: left'></div>".$poll_option."</div>";
		}else{
			$echo_opts = $echo_opts."<div id='poll_option' onClick='vote(".$poll_option_id01.",".$conv_id.",".$num_of_options.")'><div id='".$poll_option_id02."' style='width: 12px; height: 12px; border: 1px solid #ccc; margin-right: 4px; float: left'></div>".$poll_option."</div>";
		}
	}

	$echo01 = "<div id='comment_users_name'><a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a></div><div id='comment_time'>".$time."</div>";
	$echo02 = "<div id='poll_case'>".$first_comment."<br /><div id='options_case'>".$echo_opts."</div></div>";

	$echo03 = "[BRK]";

	$query_options02 = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
	while($row = mysql_fetch_array($query_options02)){
		$poll_option = $row['poll_option'];
		$num_of_votes = $row['num_of_votes'];
		if($num_of_votes == 1){
			$echo_opt_votes = $echo_opt_votes." <div id='poll_option_stat'>".$poll_option."<div id='vote_stat'> 1 vote</div></div>";
		}else{
			$echo_opt_votes = $echo_opt_votes." <div id='poll_option_stat'>".$poll_option."<div id='vote_stat'>  ".$num_of_votes." votes</div></div>";
		}
	}

	$echo_poll_stats = $echo_poll_stats.$echo01.$echo02.$echo03.$echo_opt_votes;

	return $echo_poll_stats;
}


//12. displays 9 comments of conversation
function displayParticipateComments($conv_id, $start_from){
	$manage_db = new manage_db();
	$query_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY cm.comments_id DESC LIMIT $start_from, 9");
	$echo_comments = "";
	$box_num = 0;

	while($row = mysql_fetch_array($query_comments)){
		$pic = $row['profile_picture'];
		$conn_user_id = $row['user_id'];
		//$user = $row['user_id'];
		$comment = $row['comment'];
		$timestamp = $row['timestamp'];
		$time = $this->TranslateTime($timestamp);
		$box_num++;

		$echo01 = "<div id='convo_comments'><div id='convo_comments_border'><div id='convo_comments_top'>";
		if(strlen($pic) > 14){
			$echo02 = "<div id='comment_profile_pic_case'><img id='comment_profile_pic' src='../images/".$pic."' /></div>";
		}else{
			$echo02 = "<div id='comment_profile_pic_case'><img id='comment_profile_pic' src='../images/default.png' /></div>";
		}
		if(strlen($comment) > 150){
			$echo03 = "<div id='comment_users_name'><a onClick='participate_signup()'>".$row['first_name']." ".$row['last_name']."</a></div><div id='comment_time'>".$time."</div></div>";
			$echo04 = "<div id='scroll_comment'><a onClick='scroll_up_comment(".$box_num.")'><img src='../images/scroll_up_comment1.png' /></a><a onClick='scroll_down_comment(".$box_num.")'><img src='../images/scroll_down_comment1.png' /></a></div><div id='convo_comment".$box_num."' style='margin-top:0px; z-index: -52; position: relative; height: 100%'>".$comment." </div></div></div>";
		}else if(strlen($comment) <= 150){
			$echo03 = "<div id='comment_users_name'><a onClick='participate_signup()'>".$row['first_name']." ".$row['last_name']."</a></div><div id='comment_time'>".$time."</div></div>";
			$echo04 = "<div id='convo_comment'>".$comment."</div></div></div>";
		}
		$echo05 = "[BRK]";
		$echo_comments = $echo_comments.$echo01.$echo02.$echo03.$echo04.$echo05;
	}

	return $echo_comments;
}


//13.
function displayCommentSectionNums($conv_id, $start_from, $num_rows){
	$manage_db = new manage_db();
	$refresh_query = $manage_db->return_query("SELECT comments_id FROM $manage_db->comments WHERE convo_id='$conv_id' LIMIT $start_from, 46");
	$num_of_comments = mysql_num_rows($refresh_query);
	$echo_numbering = "";

	if($num_of_comments == 46){
		$num_of_comments_chk = 45;
	}else{
		$num_of_comments_chk = $num_of_comments;
	}

	$c = 0;
	$new_start_from_next = $start_from + 45;
	$new_start_from_prev = $start_from - 45;
	$quotient = ceil($num_of_comments_chk/9);
	$quotient = $quotient + ($start_from/9);
	if($start_from != 0){
		$prev = "<div id='comment_section_nums_prev_next'><a onClick='display_comment_section_nums(".$conv_id.",".$new_start_from_prev.",46)'><</a></div>   ";
	}else{
		$prev = "<div id='comment_section_nums_prev_next_pause'><a><</a></div>   ";
	}
	$echo01 = "<div id='comment_section_nums_case'>";
	$echo02 = "</div>";

	for($i=($start_from/9); $i<$quotient; $i++){
		$c = $i+1;
		$k = ($i*9);
		$echo_numbering = $echo_numbering."<a onClick='go_to_comments(".$conv_id.",".$k.")'>".$c."</a>   ";
	}

	$echo_numbering = $echo01. $echo_numbering.$echo02;

	if($num_of_comments > 45){
		$next = "<div id='comment_section_nums_prev_next'><a onClick='display_comment_section_nums(".$conv_id.",".$new_start_from_next.", 46)'>></a></div>";
	}else{
		$next = "<div id='comment_section_nums_prev_next_pause'><a>></a></div>";
	}
	$echo_numbering = $echo_numbering.$next;

	if($c == 1 || $num_of_comments == 0){
		return "";
	}else{
		$echo_numbering = $prev.$echo_numbering;
		return $echo_numbering;
	}
}


//14.
function displayParticipateCommentSectionNums($conv_id, $start_from, $num_rows){
	$manage_db = new manage_db();
	$refresh_query = $manage_db->return_query("SELECT comments_id FROM $manage_db->comments WHERE convo_id='$conv_id' LIMIT $start_from 46");
	$num_of_comments = mysql_num_rows($refresh_query);
	$echo_numbering = "";

	if($num_of_comments == 46){
		$num_of_comments_chk = 45;
	}else{
		$num_of_comments_chk = $num_of_comments;
	}

	$c = 0;
	$new_start_from_next = $start_from + 45;
	$new_start_from_prev = $start_from - 45;
	$quotient = ceil($num_of_comments_chk/9);
	$quotient = $quotient + ($start_from/9);
	if($start_from != 0){
		$prev = "<div id='comment_section_nums_prev_next'><a onClick='display_participate_comment_section_nums(".$conv_id.",".$new_start_from_prev.",46)'><</a></div>   ";
	}else{
		$prev = "<div id='comment_section_nums_prev_next_pause'><a><</a></div>   ";
	}
	$echo01 = "<div id='comment_section_nums_case'>";
	$echo02 = "</div>";

	for($i=($start_from/9); $i<$quotient; $i++){
		$c = $i+1;
		$k = ($i*9);
		$echo_numbering = $echo_numbering."<a onClick='go_to_participate_comments(".$conv_id.",".$k.")'>".$c."</a>   ";
	}

	$echo_numbering = $echo01. $echo_numbering.$echo02;

	if($num_of_comments > 45){
		$next = "<div id='comment_section_nums_prev_next'><a onClick='display_participate_comment_section_nums(".$conv_id.",".$new_start_from_next.", 46)'>></a></div>";
	}else{
		$next = "<div id='comment_section_nums_prev_next_pause'><a>></a></div>";
	}
	$echo_numbering = $echo_numbering.$next;

	if($c == 1 || $num_of_comments == 0){
		return "";
	}else{
		$echo_numbering = $prev.$echo_numbering;
		return $echo_numbering;
	}
}


//15. displays 20 comments of conversation for comments
function displayCommentsTranscript($conv_id){
	$manage_db = new manage_db();
	$query_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY comments_id DESC LIMIT 20");
	$query_conv = $manage_db->return_query("SELECT * FROM $manage_db->conversations WHERE convo_id='$conv_id'  LIMIT 1");
	$query_conv_user = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE co.convo_id='$conv_id'  LIMIT 1");
	$echo_convs = "";

	while($row = mysql_fetch_array($query_conv_user)){
		$conversation = $row['first_comment'];
		$user_id = $row['user_id'];
		$fname = $row['first_name'];
		$lname = $row['last_name'];
	}

	$echo01 = "<div id='scroll_transcript_case'><a onClick='scroll_up(5)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(5)'><img src='images/scroll_down_comment.png' /></a></div>
					<div id='convs_heading'>
						".$conversation."by
							<a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>
					</div>
					<div id='in_comments_case'><div id='comments_case'>";


	if(strlen($conversation) == 0){
	$echo01 = "
					<div id='scroll_transcript_case'><a onClick='scroll_up(5)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(5)'><img src='images/scroll_down_comment.png' /></a></div>
					<div id='in_comments_case'><div id='comments_case'>";
	}

	while($row = mysql_fetch_array($query_comments)){
		$pic = $row['profile_picture'];
		$conn_user_id = $row['user_id'];
		$comment = $row['comment'];
		$timestamp = $row['timestamp'];
		$time = $this->TranslateTime($timestamp);

		$echo02 = "<div id='convo_comment_case'>";
		$echo03= "<div id='comment_users_name_transcript'>
							<a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a>
						</div>
						<div id='convo_comment_transcript'>".$comment."</div>
						<div id='time'>".$time."</div>
						</div>";

		$echo_convs = $echo_convs.$echo02.$echo03;
	}

	$query_more_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY comments_id DESC LIMIT 20, 1");
	if(mysql_num_rows($query_more_comments) > 0){
		$echo05 = "</div><div id='more_transcript_comments'><a onClick='add_earlier_comments(".$conv_id.", 20, 20)'>more</a></div></div>";
	}else{
		$echo05 = "</div></div>";
	}

	$echo_comments = $echo01.$echo_convs.$echo05;

	return $echo_comments;
}


//16.
function getCommentId($conv_id){
	$manage_db = new manage_db();
	$query_comment_id = $manage_db->return_query("SELECT * FROM $manage_db->comments WHERE convo_id='$conv_id' ORDER BY comments_id DESC LIMIT 1");

	while($row = mysql_fetch_array($query_comment_id)){
		$comment_id = $row['comments_id'];
	}

	return $comment_id;
}


//17. display conversations user is tracking
function displayMyConversations($user_id){
	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT convos_tracking FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$conv_tracking = mysql_result($query_user, 0, "convos_tracking");
	$echo_convs = "";

	$check_conv_tracking = explode(",", $conv_tracking);
	if(count($check_conv_tracking) > 0){
		$conv_tracking = explode(",", $conv_tracking);

		while($row = mysql_fetch_array($query_user)){
			$fname = $row['first_name'];
			$lname = $row['last_name'];
		}

		$start_from = 9;
		$end_at = 0;

		$echo01 = "<div id='convs_heading'>you are tracking...</div><div id='scroll_my_convs_case'><a onClick='scroll_up(7)'><img src='images/scroll_up_comment.png' /></a><a onmousedown='scroll_down(7)'><img src='images/scroll_down_comment.png' /></a></div>";
		$echo08 = "<div id='in_my_convs_case'><div id='my_convs_case'>";
		$len = count($conv_tracking);
		if($start_from > ($len-1)){
			$start_from = ($len-1);
		}

		for($i = $start_from; $i >= $end_at; $i--){
		$conv_tracking_id = $conv_tracking[$i];
		$query_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE convo_id='$conv_tracking_id'");

			while($row = mysql_fetch_array($query_convs)){
					$conv_id = $row['convo_id'];
					$connect_user = $row['user_id'];
					$conversation = $row['first_comment'];
					$timestamp = $row['timestamp'];
					$time = $this->TranslateTime($timestamp);
					$fname = $row['first_name'];
					$lname = $row['last_name'];
			$type_of_conv = $row['type_of_convo'];
			$num_of_options = $row['num_of_options'];

			if($type_of_conv == "poll"){
				$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
				$echo_opts = "<br />";
				while($row = mysql_fetch_array($query_options)){
					$poll_option = $row['poll_option'];
					$echo_opts = $echo_opts." ".$poll_option."<br />";
				}

					$echo02 = "<div id='convs'>";
					$echo03 = $conversation;
					$echo04 = $echo_opts;
					$echo05 = "<div id='num_tracking'>by
											<a onClick='go_to_user(".$connect_user.")'>".$fname."  ".$lname."</a>
										</div>
										<div id='time'>".$time."</div>
										<div id='connect'><a onClick='go_to_poll(".$conv_id.",".$num_of_options.")'> join</a></div>
										</div>";

			$echo_convs = $echo_convs.$echo02.$echo03.$echo04.$echo05;
			}else{

					$echo02 = "<div id='convs'>";
					$echo03 = $conversation;
					$echo04 = "";
					$echo05 = "<div id='num_tracking'>by
											<a onClick='go_to_user(".$connect_user.")'>".$fname."  ".$lname."</a>
										</div>
										<div id='time'>".$time."</div>
										<div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>
										</div>";

			$echo_convs = $echo_convs.$echo02.$echo03.$echo04.$echo05;
			}
			}//end of while loop

		}
		$echo06 = "</div></div>";

		$new_start_from = $start_from+10;
		$end_at = $start_from+1;
		if($len > $end_at){
			$echo07 = "<div id='more_my_convs'><a onClick='add_earlier_my_convs(".$user_id.", ".$new_start_from.", ".$end_at.")'>more</a></div>";
		}else{
			$echo07 = "";
		}
	$echo_all = $echo01.$echo08.$echo_convs.$echo06.$echo07;
}else{
	$echo_all = "<center>you are not tracking any conversations yet</center>";
}

return $echo_all;
}


//18.
function refreshComments($conv_id, $comment_id){
	$manage_db = new manage_db();
	$refresh_query = $manage_db->return_query("SELECT max(comments_id) FROM $manage_db->comments WHERE convo_id='$conv_id'");
	$max_comment_id = mysql_result($refresh_query, 0, "max(comments_id)");

	if($max_comment_id != $comment_id){
		$query_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' AND cm.comments_id>'$comment_id' ORDER BY cm.comments_id DESC");
		$echo_num_new = mysql_num_rows($query_comments);

		return $echo_num_new;
	}
}


//19.
function newComments($conv_id, $num_rows){
	$manage_db = new manage_db();
	$query_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY cm.comments_id DESC LIMIT 0, $num_rows");
	$echo_comments = "";
	$box_num = 0;

	$echo01 = "<div id='comments_case'>";
	while($row = mysql_fetch_array($query_comments)){
		$pic = $row['profile_picture'];
		$conn_user_id = $row['user_id'];
		$comment = $row['comment'];
		$timestamp = $row['timestamp'];
		$time = $this->TranslateTime($timestamp);

		$echo02 = "<div id='convo_comment_case'>";
		$echo03= "<div id='comment_users_name_transcript'>
								<a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a>
							</div>
							<div id='convo_comment_transcript'>".$comment."</div>
							<div id='time'>".$time."</div>
							</div>";

		$echo_convs = $echo_convs.$echo02.$echo03;
	}
	$echo05 = "</div>";
	$new_comments = $echo01.$echo_convs.$echo05;

	return $new_comments;
}


//20.
function earlierComments($conv_id, $num_rows, $start_from){
	$manage_db = new manage_db();
	$query_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY cm.comments_id DESC LIMIT $start_from, $num_rows");
	$echo_comments = "";

	$echo01 = "<div id='comments_case'>";
	while($row = mysql_fetch_array($query_comments)){
		$pic = $row['profile_picture'];
		$conn_user_id = $row['user_id'];
		$comment = $row['comment'];
		$timestamp = $row['timestamp'];
		$time = $this->TranslateTime($timestamp);

		$echo02 = "<div id='convo_comment_case'>";
		$echo03= "<div id='comment_users_name_transcript'>
								<a onClick='go_to_user(".$conn_user_id.")'>".$row['first_name']." ".$row['last_name']."</a>
							</div>
							<div id='convo_comment_transcript'>".$comment."</div>
							<div id='time'>".$time."</div>
							</div>";

		$echo_convs = $echo_convs.$echo02.$echo03;
	}
	$echo05 = "</div>";
	$echo06 = "[BRK]";

	$new_start_from = $num_rows + $start_from;
	$query_more_comments = $manage_db->return_query("SELECT * FROM $manage_db->comments cm INNER JOIN $manage_db->users us ON cm.user_id=us.user_id WHERE cm.convo_id='$conv_id' ORDER BY comments_id DESC LIMIT $new_start_from, 1");
	if(mysql_num_rows($query_more_comments) > 0){
		$echo07 = "<a onClick='add_earlier_comments(".$conv_id.", ".$num_rows.", ".$new_start_from.")'>more</a>";
	}else{
		$echo07 = "";
	}

	$earlier_comments = $echo01.$echo_convs.$echo05.$echo06.$echo07;

	return $earlier_comments;
}


//21.
function earlierConvs($user_id, $connect_user_id, $num_rows, $start_from){
	$manage_db = new manage_db();
	$query_convs01 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$query_convs02 = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE co.user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT $start_from, $num_rows");
	$echo_convs = "";

	while($row = mysql_fetch_array($query_convs01)){
		$convs_tracking = $row['convos_tracking'];
		$convs_tracking = explode(",", $convs_tracking);
		$len = count($convs_tracking);
	}

	$echo01 = "<div id='convs_case'>";

	while($row = mysql_fetch_array($query_convs02)){
			$pic = $row['profile_picture'];
			$conv_id = $row['convo_id'];
			$conversation = $row['first_comment'];
			$timestamp = $row['timestamp'];
			$time = $this->TranslateTime($timestamp);
			$type_of_conv = $row['type_of_convo'];
			$num_of_options = $row['num_of_options'];

			if($type_of_conv == "poll"){
				if($conv_id == $conv_id01){
					$echo09 = "<div id='convs".$conv_id."'><div id='convs' style='background-color: #acf'>";
				}else{
					$echo09 = "<div id='convs".$conv_id."'><div id='convs'>";
				}
				$echo10 = $conversation;

				$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
				$echo_opts = "<br />";
				while($row = mysql_fetch_array($query_options)){
					$poll_option = $row['poll_option'];
					$num_of_votes = $row['num_of_votes'];
					if($num_of_votes == 1){
						$echo_opts = $echo_opts." ".$poll_option."<br />";
					}else{
						$echo_opts = $echo_opts." ".$poll_option."<br />";
					}
				}

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><a onClick='join_current_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}else if($c == 0){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><div id='track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track </a></div></div><div id='connect'><a onClick='join_current_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}
				$echo12 = "</div></div>";

				$echo_convs = $echo_convs.$echo09.$echo10.$echo_opts.$echo11.$echo12;
			}else{
				if($conv_id == $conv_id01){
					$echo08 = "<div id='convs".$conv_id."'><div id='convs' style='background-color: #acf'>";
				}else{
					$echo08 = "<div id='convs".$conv_id."'><div id='convs'>";
				}
				$echo09 = $conversation;

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo10 = "<div id='time'>".$time."</div><div id='connect'><a onClick='join_current_conv(".$conv_id.")'> join</a></div>";
				}else if($c == 0){
					$echo10 = "<div id='time'>".$time."</div><div id='connect'><div id='track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track </a></div></div><div id='connect'><a onClick='join_current_conv(".$conv_id.")'> join</a></div>";
				}
				$echo11 = "</div></div>";

				$echo_convs = $echo_convs.$echo08.$echo09.$echo10.$echo11;
			}
	}//end of while loop
	$echo05 = "</div>";
	$echo06 = "[BRK]";

	$new_start_from = $num_rows + $start_from;
	$query_more_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE co.user_id='$connect_user_id' ORDER BY convo_id DESC LIMIT $new_start_from, 1");
	if(mysql_num_rows($query_more_convs) > 0){
		$echo07 = "<a onClick='add_earlier_convs(".$conv_id.", ".$num_rows.", ".$new_start_from.")'>more</a>";
	}else{
		$echo07 = "";
	}

	$earlier_convs = $echo01.$echo_convs.$echo05.$echo06.$echo07;

	return $earlier_convs;
}


//22.
function earlierPublicConvs($user_id, $num_rows, $start_from){
	$manage_db = new manage_db();
	$query_convs01 = $manage_db->return_query("SELECT * FROM $manage_db->conversations ORDER BY num_tracking DESC LIMIT 1");
	$query_convs02 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$query_convs03 = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id ORDER BY num_tracking DESC, convo_id  DESC LIMIT $start_from, $num_rows");
	$query_convs04 = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$echo_convs = "";

	while($row = mysql_fetch_array($query_convs01)){
		$conv_id01 = mysql_result($query_convs01, 0, "convo_id");
	}

	while($row = mysql_fetch_array($query_convs02)){
		$convs_tracking = $row['convos_tracking'];
		$convs_tracking = explode(",", $convs_tracking);
		$len = count($convs_tracking);
	}

	while($row = mysql_fetch_array($query_convs04)){
		$user = $row['user_id'];
		$fname = $row['first_name'];
		$lname = $row['last_name'];
	}

	$echo01 = "<div id='public_case'>";

	while($row = mysql_fetch_array($query_convs03)){
			$fname = $row['first_name'];
			$lname = $row['last_name'];
			$user_id = $row['user_id'];
			$conv_id = $row['convo_id'];
			$conversation = $row['first_comment'];
			$num_tracking = $row['num_tracking'];
			$timestamp = $row['timestamp'];
			$time = $this->TranslateTime($timestamp);
			$type_of_conv = $row['type_of_convo'];
			$num_of_options = $row['num_of_options'];

			if($type_of_conv == "poll"){
				$echo03 = "<div id='pub_convs".$conv_id."'><div id='convs'>".$conversation;

				$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
				$echo_opts = "<br />";
				while($row = mysql_fetch_array($query_options)){
					$poll_option = $row['poll_option'];
					$echo_opts = $echo_opts." ".$poll_option."<br />";
				}

				$i = 0;
				$c = 0;
				while($i<$len && $c==0){
					if($convs_tracking[$i] == $conv_id){
						$c = 1;
					}
					$i++;
				}

				if($c == 1){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><a onClick='go_to_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}else if($c == 0){
					$echo11 = "<div id='time'>".$time."</div><div id='connect'><div id='track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track </a></div></div><div id='connect'><a onClick='go_to_poll(".$conv_id.",".$num_of_options.")'> join</a></div>";
				}
				$echo12 = "</div></div>";

				$echo_convs = $echo_convs.$echo03.$echo_opts.$echo11.$echo12;
			}else{
			$echo02 = "<div id='pub_convs".$conv_id."'><div id='convs'>".$conversation;

			$i = 0;
			$c = 0;
			while($i<$len && $c==0){
				if($convs_tracking[$i] == $conv_id){
					$c = 1;
				}
				$i++;
			}

			if($c == 1){
				$echo03 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>";
			}else if($c == 0){
				$echo03 = "<div id='num_tracking'>by <a onClick='go_to_user(".$user_id.")'>".$fname."  ".$lname."</a>, ".$num_tracking." tracking</div><div id='time'>".$time."</div><div id='connect'><div id=pub_'track".$conv_id."'><a id='".$conv_id."' onClick='track(".$conv_id.")'>track</a></div></div><div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>";
			}
			$echo04 = "</div></div>";

	$echo_convs = $echo_convs.$echo02.$echo03.$echo04;
	}
	}//end of while loop
	$echo08 = "</div>";
	$echo09 = "[BRK]";

	$new_start_from = $num_rows + $start_from;
	$query_more_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id ORDER BY num_tracking DESC, convo_id  DESC LIMIT $new_start_from, 1");
	if(mysql_num_rows($query_more_convs) > 0){
		$echo10 = "<a onClick='add_earlier_public_convs(".$user_id.", ".$num_rows.", ".$new_start_from.")'>more</a>";
	}else{
		$echo10 = "";
	}

$echo_public_convs = $echo01.$echo_convs.$echo08.$echo09.$echo10;

return $echo_public_convs;
}


//23.
function earlierMyConvs($user_id, $start_from, $end_at){
	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT convos_tracking FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$conv_tracking = mysql_result($query_user, 0, "convos_tracking");
	$echo_convs = "";

	$check_conv_tracking = explode(",", $conv_tracking);

	$conv_tracking = explode(",", $conv_tracking);

	while($row = mysql_fetch_array($query_user)){
		$fname = $row['first_name'];
		$lname = $row['last_name'];
	}

	$echo01 = "<div id='my_convs_case'>";
	$len = count($conv_tracking);
	if($start_from > ($len-1)){
		$start_from = $len-1;
	}

	for($i = $start_from; $i >= $end_at; $i--){
		$conv_tracking_id = $conv_tracking[$i];
		$query_convs = $manage_db->return_query("SELECT * FROM $manage_db->conversations co INNER JOIN $manage_db->users us ON co.user_id=us.user_id WHERE convo_id='$conv_tracking_id'");

		while($row = mysql_fetch_array($query_convs)){
				$conv_id = $row['convo_id'];
				$connect_user = $row['user_id'];
				$conversation = $row['first_comment'];
				$timestamp = $row['timestamp'];
				$time = $this->TranslateTime($timestamp);
				$fname = $row['first_name'];
				$lname = $row['last_name'];
			$type_of_conv = $row['type_of_convo'];
			$num_of_options = $row['num_of_options'];

			if($type_of_conv == "poll"){
				$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
				$echo_opts = "<br />";
				while($row = mysql_fetch_array($query_options)){
					$poll_option = $row['poll_option'];
					$echo_opts = $echo_opts." ".$poll_option."<br />";
				}

					$echo02 = "<div id='convs'>";
					$echo03 = $conversation;
					$echo04 = $echo_opts;
					$echo05 = "<div id='num_tracking'>by
											<a onClick='go_to_user(".$connect_user.")'>".$fname."  ".$lname."</a>
										</div>
										<div id='time'>".$time."</div>
										<div id='connect'><a onClick='go_to_poll(".$conv_id.",".$num_of_options.")'> join</a></div>
										</div>";

			$echo_convs = $echo_convs.$echo02.$echo03.$echo04.$echo05;
			}else{

				$echo02 = "<div id='convs'>";
				$echo03 = $conversation;
				$echo04 = "";
				$echo05 = "<div id='num_tracking'>by
										<a onClick='go_to_user(".$connect_user.")'>".$fname."  ".$lname."</a>
									</div>
									<div id='time'>".$time."</div>
									<div id='connect'><a onClick='go_to_conv(".$conv_id.")'> join</a></div>
									</div>";
		$echo_convs = $echo_convs.$echo02.$echo03.$echo04.$echo05;
		}
		}//end of while loop
	}
	$echo06 = "</div>";
	$echo09 = "[BRK]";

	$new_start_from = $start_from+10;
	$end_at = $start_from+1;
	if($len > $end_at){
		$echo10 = "<a onClick='add_earlier_my_convs(".$user_id.", ".$start_from.", ".$end_at.")'>more</a>";
	}else{
		$echo10 = "";
	}
	$echo_all = $echo01.$echo_convs.$echo06.$echo09.$echo10;

return $echo_all;
}


//24.
function displayChangeProfilePic($user_id){
	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT profile_picture FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$profile_pic = mysql_result($query_user, 0, "profile_picture");
	$profile_pic = addslashes($profile_pic);

	$echo01 = "<iframe src='includes/image_upload.php'>";
	$echo02 = "</iframe>";
	//$echo03 = "<input type='hidden' onclick='check_profile_src(".$profile_pic.")' />";
	$echo_all = $echo01.$echo02;

	return $echo_all;
}


function displayAddImage($user_id){
	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT profile_picture FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$profile_pic = mysql_result($query_user, 0, "profile_picture");
	$profile_pic = addslashes($profile_pic);

	$echo01 = "<iframe src='includes/image_module/image_upload.php'>";
	$echo02 = "</iframe>";
	//$echo03 = "<input type='hidden' onclick='check_profile_src(".$profile_pic.")' />";
	$echo_all = $echo01.$echo02;

	return $echo_all;
}


//25.
function makeUrlLinks($text){
	//separate words of $text into an array
	$text_arr = explode(" ", $text);

	//make all words that are url clickable links
	$new_text = "";
	$len = count($text_arr);
	for($i=0; $i<$len; $i++){
		if(preg_match("/^http:\/\//", $text_arr[$i])){
			$new_text = $new_text."<a href='".$text_arr[$i]."' target='_blank'>".$text_arr[$i]."</a> ";
		}else{
			$new_text = $new_text.$text_arr[$i]." ";
		}
	}

	return $new_text;
}


//26.
function vote($poll_option_id, $conv_id, $user_id, $num_of_options){
	$manage_db = new manage_db();
	$query_users_taken_poll = $manage_db->return_query("SELECT * FROM $manage_db->users_taken_poll WHERE user_id='$user_id' LIMIT 1");
	$query_options = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
	$echo_opts = "";
	$echo_opt_votes = "<div id='stats_heading'>so far...</div>";

	while($row = mysql_fetch_array($query_options)){
		$poll_option = $row['poll_option'];
		$poll_option_id01 = $row['poll_option_id'];
		$poll_option_id02 = "opt".$poll_option_id01;
		$conv_id = $row['convo_id'];
		$num_of_votes = $row['num_of_votes'];
		if($poll_option_id01 == $poll_option_id){
			$echo_opts = $echo_opts."<div id='poll_option' onClick='vote(".$poll_option_id01.",".$conv_id.",".$num_of_options.")'><div id='".$poll_option_id02."' style='background-color: #cef; width: 12px; height: 12px; border: 1px solid #ccc; margin-right: 4px; float: left'></div>".$poll_option."</div>";
			$num_of_votes_incr = $num_of_votes;
		}else{
			$echo_opts = $echo_opts."<div id='poll_option' onClick='vote(".$poll_option_id01.",".$conv_id.",".$num_of_options.")'><div id='".$poll_option_id02."' style='width: 12px; height: 12px; border: 1px solid #ccc; margin-right: 4px; float: left'></div>".$poll_option."</div>";
		}
	}

	$num_of_votes_incr = $num_of_votes_incr + 1;
	$manage_db->return_query("UPDATE $manage_db->poll_options SET num_of_votes='$num_of_votes_incr' WHERE poll_option_id='$poll_option_id' ");

	if(mysql_num_rows($query_users_taken_poll) == 0){
		$manage_db->return_query("INSERT INTO $manage_db->users_taken_poll VALUES(null, '$user_id', '$conv_id', '$poll_option_id')");
		$echo_all = $echo_opts;
	}else{
		while($row = mysql_fetch_array($query_users_taken_poll)){
			$conv_ids = $row['convo_ids'];
			$poll_option_ids = $row['option_ids'];
		}

		//search through conv_ids to find position of $conv_id i.e. $c
		$explode_conv_ids = explode(",", $conv_ids);
		$len=count($explode_conv_ids);
		$recon_option_ids = "";
		$recon_conv_ids = "";

		$i = 0;
		$c = 0;
		while($i<$len && $c == 0){
			if($explode_conv_ids[$i] == $conv_id){
				$c = 1;
			}
			$i++;
		}
		$i--;

		$explode_option_ids = explode(",", $poll_option_ids);
		if($c == 1){ //if postion $c found, replace option_id at same index i.e. same position in option_ids
			$poll_option_id_orig = $explode_option_ids[$i];
			$query_old_option = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE poll_option_id='$poll_option_id_orig' LIMIT 1");
			while($row = mysql_fetch_array($query_old_option)){
				$num_of_votes_decr = $row['num_of_votes'];
			}
			$num_of_votes_decr = $num_of_votes_decr - 1;
			$manage_db->return_query("UPDATE $manage_db->poll_options SET num_of_votes='$num_of_votes_decr' WHERE poll_option_id='$poll_option_id_orig' ");

			$explode_option_ids[$i] = $poll_option_id;
			$recon_option_ids = $explode_option_ids[0];
			for($k = 1; $k < $len; $k++){
				$recon_option_ids = $recon_option_ids.",".$explode_option_ids[$k];
			}
			$recon_conv_ids = $conv_ids;
		}else{ //if postion $c not found, add conv_id and option
				$recon_option_ids = $poll_option_ids.",".$poll_option_id;
				$recon_conv_ids = $conv_ids.",".$conv_id;
		}

		$manage_db->return_query("UPDATE $manage_db->users_taken_poll SET convo_ids='$recon_conv_ids', option_ids='$recon_option_ids'");

		$echo_all = $echo_opts;
	} //end of if(mysql_num_rows($query_con...

	$echo03 = "[BRK]";

	$query_options02 = $manage_db->return_query("SELECT * FROM $manage_db->poll_options WHERE convo_id='$conv_id' LIMIT $num_of_options");
	while($row = mysql_fetch_array($query_options02)){
		$poll_option = $row['poll_option'];
		$num_of_votes = $row['num_of_votes'];
		if($num_of_votes == 1){
			$echo_opt_votes = $echo_opt_votes." <div id='poll_option_stat'>".$poll_option."<div id='vote_stat'> 1 vote</div></div>";
		}else{
			$echo_opt_votes = $echo_opt_votes." <div id='poll_option_stat'>".$poll_option."<div id='vote_stat'>  ".$num_of_votes." votes</div></div>";
		}
	}

	$echo_all = $echo_all.$echo03.$echo_opt_votes;
	return $echo_all;
}

}
?>
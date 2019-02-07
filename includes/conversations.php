<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_conversations.php");

if(isset($_POST['go_to_user'])){
	$user_id = $_POST['user_id'];
	$connect_user_id = $_POST['connect_user_id'];

	$manage_conv = new manage_conversations();
	$echo_all = $manage_conv->goToUser($user_id, $connect_user_id);

	echo $echo_all;
}

if(isset($_POST['go_to_comments'])){
	$conv_id = $_POST['conv_id'];
	$start_from = $_POST['start_from'];
	$manage_conv = new manage_conversations();
	$comments = $manage_conv->displayComments($conv_id, $start_from);
	echo $comments;
}

if(isset($_POST['go_to_poll_stats'])){
	$user_id = $_POST['user_id'];
	$conv_id = $_POST['conv_id'];
	$start_from = $_POST['start_from'];
	$num_of_options = $_POST['num_of_options'];

	$manage_conv = new manage_conversations();
	$poll_stats = $manage_conv->displayPollStats($user_id, $conv_id, $start_from, $num_of_options);
	echo $poll_stats;
}

if(isset($_POST['go_to_participate_comments'])){
	$conv_id = $_POST['conv_id'];
	$start_from = $_POST['start_from'];
	$manage_conv = new manage_conversations();
	$comments = $manage_conv->displayParticipateComments($conv_id, $start_from);
	echo $comments;
}

if(isset($_POST['display_comment_section_nums'])){
	$conv_id = $_POST['conv_id'];
	$start_from = $_POST['start_from'];
	$num_rows = $_POST['num_rows'];
	$manage_conv = new manage_conversations();
	$numbering = $manage_conv->displayCommentSectionNums($conv_id, $start_from, $num_rows);

	echo $numbering;
}

if(isset($_POST['display_participate_comment_section_nums'])){
	$conv_id = $_POST['conv_id'];
	$start_from = $_POST['start_from'];
	$num_rows = $_POST['num_rows'];
	$manage_conv = new manage_conversations();
	$numbering = $manage_conv->displayParticipateCommentSectionNums($conv_id, $start_from, $num_rows);

	echo $numbering;
}

if(isset($_POST['go_to_conv_transcript'])){
	$conv_id = $_POST['conv_id'];
	$manage_conv = new manage_conversations();
	$comments = $manage_conv->displayCommentsTranscript($conv_id);
	echo $comments;
}

if(isset($_POST['go_to_current_conv'])){
	$conv_id = $_POST['conv_id'];
	$user_id = $_POST['user_id'];
	$manage_db = new manage_db();
	$query = $manage_db->return_query("SELECT user_id FROM $manage_db->conversations WHERE convo_id='$conv_id'  LIMIT 1");
	while($row = mysql_fetch_array($query)){
		$connect_user_id = $row['user_id'];
	}

	$manage_conv = new manage_conversations();
	$conversations = $manage_conv->displayConversations($user_id, $connect_user_id);
	echo $conversations;
}

if(isset($_POST['go_to_public_conv'])){
	$conv_id = $_POST['conv_id'];
	$manage_db = new manage_db();
	$query = $manage_db->return_query("SELECT user_id FROM $manage_db->conversations WHERE convo_id='$conv_id'  LIMIT 1");
	while($row = mysql_fetch_array($query)){
		$user_id = $row['user_id'];
	}

	$manage_conv = new manage_conversations();
	$conversations = $manage_conv->displayPublicConversations($user_id);
	echo $conversations;
}


if(isset($_POST['add_new_comments'])){
	$conv_id = $_POST['conv_id'];
	$num_rows = $_POST['num_rows'];

	$manage_conv = new manage_conversations();
	$new_comments = $manage_conv->newComments($conv_id, $num_rows);
	echo $new_comments;
}


if(isset($_POST['add_earlier_comments'])){
	$conv_id = $_POST['conv_id'];
	$num_rows = $_POST['num_rows'];
	$start_from = $_POST['start_from'];

	$manage_conv = new manage_conversations();
	$earlier_comments = $manage_conv->earlierComments($conv_id, $num_rows, $start_from);
	echo $earlier_comments;
}


if(isset($_POST['add_earlier_convs'])){
	$user_id = $_POST['user_id'];
	$connect_user_id = $_POST['connect_user_id'];
	$conv_id = $_POST['conv_id'];
	$num_rows = $_POST['num_rows'];
	$start_from = $_POST['start_from'];

	$manage_conv = new manage_conversations();
	$earlier_convs = $manage_conv->earlierConvs($user_id, $connect_user_id, $conv_id, $num_rows, $start_from);
	echo $earlier_convs;
}


if(isset($_POST['add_earlier_public_convs'])){
	$user_id = $_POST['user_id'];
	$num_rows = $_POST['num_rows'];
	$start_from = $_POST['start_from'];

	$manage_conv = new manage_conversations();
	$earlier_public_convs = $manage_conv->earlierPublicConvs($user_id, $num_rows, $start_from);
	echo $earlier_public_convs;
}


if(isset($_POST['add_earlier_my_convs'])){
	$user_id = $_POST['user_id'];
	$start_from = $_POST['start_from'];
	$end_at = $_POST['end_at'];

	$manage_conv = new manage_conversations();
	$earlier_my_convs = $manage_conv->earlierMyConvs($user_id, $start_from, $end_at);
	echo $earlier_my_convs;
}


if(isset($_POST['set_comment_id'])){
	$conv_id = $_POST['conv_id'];
	$manage_conv = new manage_conversations();
	$comment_id = $manage_conv->getCommentId($conv_id);

	echo $comment_id;
}

if(isset($_POST['post_comment'])){
	$comment = $_POST['comment'];
	if(strlen($comment) != 0){
		$conv_id = $_POST['conv_id'];
		$user_id = $_POST['user_id'];
		$timestamp = time();
		$manage_conv = new manage_conversations();
		$comment = $manage_conv->makeUrlLinks($comment);
		$comment = addslashes($comment);

		$manage_db = new manage_db();
		$manage_db->return_query("INSERT INTO $manage_db->comments VALUES(null, '$conv_id', '$user_id', '$comment', '$timestamp', 'n')");

		$comments = $manage_conv->displayComments($conv_id);
		echo $comments;
	}else{
		echo "sorry! try again...";
	}
}

if(isset($_POST['private_conv'])){
		//displays updated new requests' list, i.e. request is ignored and user removed from new requests
		$user_id = $_POST['connect_user_id'];
		$manage_conv = new manage_conversations();
		$contacts = $manage_conv->displayConvoContacts($user_id);
		$echo01 = "<div id='convo_select_contacts'>select contacts</div><div id='return' onClick='go_to_user(".$user_id.")' title='return'>x</div>";
		$echo02 = "<div id='start_convo_contacts'>";
		$echo03 = "</div>";
		$echo04 = "<div id='start_convo'><a href='#'>start ok</a></div>";

		$echo_all = $echo01.$echo02.$contacts.$echo03.$echo04;

		echo $echo_all;
}

if(isset($_POST['refresh_comments'])){
	$conv_id = $_POST['conv_id'];
	$comment_id = $_POST['comment_id'];
	if($comment_id == ""){
		echo "";
	}else{
		$manage_conv = new manage_conversations();
		$comments = $manage_conv->refreshComments($conv_id, $comment_id);
		echo $comments;
	}
}

if(isset($_POST['public_conv'])){
		//displays updated new requests' list, i.e. request is ignored and user removed from new requests
		$user_id = $_POST['connect_user_id'];
		$manage_conv = new manage_conversations();
		$contacts = $manage_conv->displayConvPublic($user_id);
		$echo01 = "<div id='convo_select_contacts'>let's talk about:</div>
		<div id='return' onClick='go_to_user(".$user_id.")' title='return'></div>";
		$echo02 = "<div id='add_image'><a href='#' onClick='add_image()'>+ add image</a></div>";
		$echo03 = "<div id='start_convo'><a href='#' onClick='start_public_conv()'>start conversation</a></div>";
		$echo04 = "<div id='conv_option'><a onClick='how_to_take_poll()'>see: how to take a poll...</a></div>";

		$echo_all = $echo01.$contacts.$echo02.$echo03.$echo04;

		echo $echo_all;
}


if(isset($_POST['start_public_conv'])){
	$user_id = $_POST['user_id'];
	$first_comment = $_POST['first_comment'];
	$manage_conv = new manage_conversations();
	$first_comment = $manage_conv->makeUrlLinks($first_comment);
	$first_comment = addslashes($first_comment);
	$timestamp = time();
	$manage_db = new manage_db;

	$poll_check_arr = explode(":", $first_comment);
	$poll_check = substr($poll_check_arr[0], -4, 4);
	$opt_arr = explode("##", $first_comment);
	$num_of_opt = count($opt_arr);

	if($poll_check == "poll" && $num_of_opt > 2){
		$type_of_conv = "poll";
		$first_comment = $poll_check_arr[1];
		$first_comment_arr = explode("##", $first_comment);
		$first_comment = $first_comment_arr[0];
		if($manage_db->return_query("INSERT INTO $manage_db->conversations VALUES(null, '$user_id', '', '', '$type_of_conv', '$first_comment', '$num_of_opt', 'y', 'n', 1, '$timestamp', 0)")){
			$query_conv = $manage_db->return_query("SELECT convo_id FROM $manage_db->conversations WHERE user_id='$user_id' ORDER BY convo_id DESC LIMIT 1");
			$conv_id = mysql_result($query_conv, 0, 'convo_id');
			for($i=1; $i<$num_of_opt; $i++){
				$poll_option = $opt_arr[$i];
				$manage_db->return_query("INSERT INTO $manage_db->poll_options VALUES(null, '$conv_id', '$poll_option', 0)");
			}
		}
	}else{
		$type_of_conv = "public_convo";
		if($manage_db->return_query("INSERT INTO $manage_db->conversations VALUES(null, '$user_id', '', '', '$type_of_conv', '$first_comment', '$num_of_opt', 'y', 'n', 1, '$timestamp', 0)")){
			$query_conv = $manage_db->return_query("SELECT convo_id FROM $manage_db->conversations WHERE user_id='$user_id' ORDER BY convo_id DESC LIMIT 1");
			$conv_id = mysql_result($query_conv, 0, 'convo_id');
		}
	}//end of if($poll_check == "poll" && $nu...

	echo $conv_id;
}


if(isset($_POST['track'])){
	$conv_id = $_POST['conv_id'];
	$user_id = $_POST['user_id'];

	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT convos_tracking FROM $manage_db->users WHERE user_id='$user_id'");
	$query_convs = $manage_db->return_query("SELECT num_tracking FROM $manage_db->conversations WHERE convo_id='$conv_id'");
	$num_tracking = mysql_result($query_convs, 0, "num_tracking");
	$convs_tracking = mysql_result($query_user, 0, "convos_tracking");

	$num_tracking++;

	if(strlen($convs_tracking) == 0){
		$convs_tracking = $conv_id;
	}else if(strlen($convs_tracking) > 0){
		$convs_tracking = $convs_tracking.",".$conv_id;
	}

	if($manage_db->return_query("UPDATE $manage_db->conversations SET num_tracking='$num_tracking' WHERE convo_id='$conv_id'") && $manage_db->return_query("UPDATE $manage_db->users SET convos_tracking='$convs_tracking' WHERE user_id='$user_id'")){
		echo "<div style='color: #0f5; min-width:20px '>tracking</div>";
	}
}


if(isset($_POST['pub_track'])){
	$conv_id = $_POST['conv_id'];
	$user_id = $_POST['user_id'];

	$manage_db = new manage_db();
	$query_user = $manage_db->return_query("SELECT convos_tracking FROM $manage_db->users WHERE user_id='$user_id'");
	$query_convs = $manage_db->return_query("SELECT num_tracking FROM $manage_db->conversations WHERE convo_id='$conv_id'");
	$num_tracking = mysql_result($query_convs, 0, "num_tracking");
	$convs_tracking = mysql_result($query_user, 0, "convos_tracking");

	$num_tracking++;

	if(strlen($convs_tracking) == 0){
		$convs_tracking = $conv_id;
	}else if(strlen($convs_tracking) > 0){
		$convs_tracking = $convs_tracking.",".$conv_id;
	}

	if($manage_db->return_query("UPDATE $manage_db->conversations SET num_tracking='$num_tracking' WHERE convo_id='$conv_id'") && $manage_db->return_query("UPDATE $manage_db->users SET convos_tracking='$convs_tracking' WHERE user_id='$user_id'")){
		echo "<div style='color: #0f5; min-width:20px '>tracking</div>";
	}
}


if(isset($_POST['change_profile_pic'])){
	$user_id = $_POST['user_id'];
	$return = "<div id='return' onClick='go_to_user(".$user_id.")' title='return'></div>";
	$echo01 = "<div id='convo_select_contacts'>choose an image</div>";
	$manage_conv = new manage_conversations();
	$change_profile_pic = $manage_conv->displayChangeProfilePic($user_id);

	$change_profile_pic = $echo01.$return.$change_profile_pic;

	echo $change_profile_pic;
}


if(isset($_POST['check_profile_src'])){
	$user_id = $_POST['user_id'];
	$profile_pic = $_POST['profile_pic'];

	$manage_db = new manage_db();
	$query_profile_pic = $manage_db->return_query("SELECT profile_picture FROM $manage_db->users WHERE user_id='$user_id' LIMIT 1");
	$result_profile_pic = mysql_result($query_profile_pic, 0, "profile_picture");

	if($profile_pic != $result_profile_pic){
		$manage_everyone = new manage_everyone();
		$manage_conv = new manage_conversations();
		$profile = $manage_everyone->convoDisplayUser($user_id, $user_id);
		$conv_notice = $manage_conv->displayConvNotice($connect_user_id);

		echo $profile.$conv_notice;
	}else if($profile_pic == $result_profile_pic){
		echo "";
	}
}


if(isset($_POST['add_image'])){
	$user_id = $_POST['user_id'];
	$return = "<div id='return' onClick='go_to_user(".$user_id.")' title='return'></div>";
	$echo01 = "<div id='convo_select_contacts'>choose an image</div>";
	$manage_conv = new manage_conversations();
	$add_image = $manage_conv->displayAddImage($user_id);

	$add_image = $echo01.$return.$add_image;

	echo $add_image;
}


if(isset($_POST['vote'])){
	$user_id = $_POST['user_id'];
	$poll_option_id = $_POST['poll_option_id'];
	$conv_id = $_POST['conv_id'];
	$num_of_options = $_POST['num_of_options'];

	$manage_conv = new manage_conversations();
	$stats = $manage_conv->vote($poll_option_id, $conv_id, $user_id, $num_of_options);
	echo $stats;
}
?>
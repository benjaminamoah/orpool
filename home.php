<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_conversations.php");

session_start();
if(isset($_SESSION['username_or_pool'])){
	$user_email = $_SESSION['username_or_pool'];
}else{
	header("location:index.php");
}
$manage_db = new manage_db();

$query_user = $manage_db->return_query("SELECT * FROM $manage_db->users where username = '$user_email' or email = '$user_email'");
$user_id = mysql_result($query_user, 0, 'user_id');
$user = mysql_result($query_user, 0, 'username');
$fname = mysql_result($query_user, 0, 'first_name');
$lname = mysql_result($query_user, 0, 'last_name');
$name = $fname." ".$lname;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>
	Orpool | <?php echo $name; ?>
	</title>

	<link type="text/css" rel="stylesheet" href="orpool.css" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<script type="text/javascript">
		var user_id_js = <?php echo $user_id; ?>;
	</script>

	<script type="text/javascript" src="home_funct.js">
	</script>

	<script type="text/javascript">
		window.onload = function(){
			refresh_comments();
			<?php
			if(isset($_SESSION['conv_id'])){
				$conv_id = $_SESSION['conv_id'];
				echo "go_to_conv(".$conv_id.");";
				unset($_SESSION['conv_id']);
			}
			?>
		}
	</script>

	<meta name="description" content="Orpool is a platform for discussing issues and learning from others with different perspectives than your own." />
	<meta name="keywords" content="orpool,discussion,dialog,debate,social,media,network" />
</head>

<body>

<div id="main_top">
<a href="home.php"><img src="images/orpool_logo.png" border="0" /></a>
<div id="sign_out"><a href="includes/signout.php">sign out!</a></div>
</div>

<div id="main">

<div id="main_msgs">
<div id="main_user_comments">
<?php
echo "<div id='main_user_name'><a onClick='go_to_user(".$user_id.")'>
		Me (".$fname." ".$lname.")
</a></div>";
?>
<div id="comment_textarea"><textarea id="comment" onClick="incHeight('comment')" onblur="redHeight('comment')"></textarea></div>
<div id="comment_post"><input type="submit" name="post" id="post"  value="post"onClick="post_comment()" /></div>
<div id="new_and_numbering"><div id="new_comments_alert"></div><div id="comment_section_nums"></div></div>
<input type="hidden" id="conv_id" value="" />
<input type="hidden" id="comment_id" value="" />
</div>
</div>

<div id="table_case">
<table>
<tr>
<td>
<div id="main_friends">
	<div id="main_friends_lbl01" onClick="switch_to_contact()">my contact list</div>
	<div id="contact_list">

		<div id="contact_list_case">
			<?php
			$manage_contacts = new manage_contacts();
			//displays updated new requests' list, i.e. request is ignored and user removed from new requests
			$new_requests = $manage_contacts->displayContacts($user_id, "new requests");
			echo $new_requests;

			//displays updated new requests' list, i.e. request is ignored and user removed from new requests
			$contacts = $manage_contacts->displayContacts($user_id, "contacts");
			echo $contacts;
			?>
		</div><!--end of contact_list_case-->
	</div>

	<div id="main_friends_lbl02" onClick="switch_to_everyone()">everyone</div>
	<div id="all_users">

	<div id="search_box">
		<div id="search">
			<input type="text" name="search" id="search_input" value="search" onClick="textVanish('search_input')" onBlur="returnSearchText()"  onKeyUp="suggestSearchEveryone()" />
		</div>
	</div>

	<div id="all_users_case">
		<div id="request_sent_msg">
		</div>
			<?php
			//displays all users
			$manage_everyone = new manage_everyone();
			$all_users = $manage_everyone->displayEveryone($user_id);
			echo $all_users;
			?>
	</div><!--end of all_users_case-->
	</div><!--end of all_users-->

	<div id="main_friends_lbl03" onClick="switch_to_tell_a_friend()">share this conversation</div>
	<div id="invite_new">
	<div id="invite_others_note">others can join this conversation through the link below. you may copy and share wherever...</div>
	<textarea id="invite_url"></textarea>
	</div>
</div>

</td><td>
<div id="main_boxes">

	<div id="main_box01">
		<?php
		//display current user
		$manage_conv = new manage_conversations();
		$current_user = $manage_everyone->convoDisplayUser($user_id);
		echo $current_user;

		$conv_notice = $manage_conv->displayConvNotice($user_id);
		echo $conv_notice;
		?>
	</div>

	<div id="main_box02">

	</div>

	<div id="main_box03">

	</div>

	<div id="main_box04">

	</div>

	<div id="main_box05">

	</div>

	<div id="main_box06">

	</div>

	<div id="main_box07">

	</div>

	<div id="main_box08">

	</div>

	<div id="main_box09">

	</div>

</div><!-- end of main_boxes-->

</td><td>

<div id="main_conversations">

	<div id="conversations_lbl01" onClick="switch_to_conversations()">conversations by</div>

	<div id="conversations">
		<div id="conversations_case_scroll">
			<?php
				$manage_conv = new manage_conversations();
				$convs = $manage_conv->displayConversations($user_id, $user_id);
				echo $convs;
			?>
		</div><!--end conversations_case_scroll-->
	</div><!--end of conversations-->

	<div id="conversations_lbl02" onClick="switch_to_transcript()">comments</div>
	<div id="transcript">
		<div id="transcript_case_scroll">
			<?php
				$manage_conv = new manage_conversations();
				$conv_id = $manage_conv->getHighestConvId($user_id);
				$comments = $manage_conv->displayCommentsTranscript($conv_id);
				echo $comments;
			?>
		</div>
	</div>
</div><!--end of main_conversations-->

</td><td>

<div id="main_public">

	<div id="pub_conversations_lbl01" onClick="switch_to_pub_conversations()">public conversations</div>

	<div id="pub_conversations">

	<div id="search_box_conv">
		<div id="search_conv">
			<input type="text" name="search" id="search_input_conv" value="search" onClick="textVanish('search_input_conv')" onBlur="returnSearchText()"  onKeyUp="suggestSearchConversations()" />
		</div>
	</div>

		<div id="pub_conversations_case_scroll">
			<?php
				$manage_conv = new manage_conversations();
				$convs = $manage_conv->displayPublicConversations($user_id);
				echo $convs;
			?>
		</div><!--end pub_conversations_case_scroll-->
	</div><!--end of pub_conversations-->

	<div id="pub_conversations_lbl02" onClick="switch_to_my_conversations()">conversations i'm tracking</div>
	<div id="my_conversations">

		<div id="my_conversations_case_scroll">
			<?php
				$manage_conv = new manage_conversations();
				$convs = $manage_conv->displayMyConversations($user_id);
				echo $convs;
			?>
		</div><!--end conversations_case_scroll-->

	</div><!--end of my_conversations-->
</div><!--end of main_public-->

</div><!--end of main_public-->

</td>
</tr>
</table>
</div><!--end of table_case-->
</div><!--end of main-->

<div id="main_bottom">
    	<br />
		<center>Orpool Production - Copyright &#169; <?php echo date("Y"); ?><center>
</div>

</body>

</html>
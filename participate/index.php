<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_conversations.php");

session_start();
$manage_db = new manage_db();
if($_GET['parti'] == 1){
	$conv_id = $_GET['nv'];
	$query_user_id = $manage_db->return_query("SELECT * FROM $manage_db->conversations WHERE convo_id='$conv_id' LIMIT 1");
	while($row = mysql_fetch_array($query_user_id)){
		$user_id = $row['user_id'];
		$first_comment = $row['first_comment'];
	}
	$_SESSION['conv_id'] = $conv_id;
	$_SESSION['user_id'] = $user_id;
	$_SESSION['first_comment'] = $first_comment;
}

if(isset($_SESSION['conv_id'])){
	$conv_id = $_SESSION['conv_id'];
	$user_id = $_SESSION['user_id'];
	$first_comment = $_SESSION['first_comment'];
}

	$err = "";

if(isset($_POST['signin'])){
	$user_email = $_POST['username_email'];
	$pass = $_POST['password'];
	$query = $manage_db->return_query("SELECT * FROM $manage_db->users where username = '$user_email' or email = '$user_email'");

	if(mysql_num_rows($query) > 0){
		$result_pass = mysql_result($query, $k, 'password') or die(mysql_error());
		if($result_pass==$pass){
			$_SESSION['username_or_pool'] = $user_email;
			header("location:../home.php");
		}else{
			$err = "wrong username or password";
		}
	}else{
		$err = "wrong username or password";
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>
	joining in the conversation from outside orpool
	</title>

	<link type="text/css" rel="stylesheet" href="../orpool.css" />
	<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="../images/favicon.ico" type="image/x-icon">
	<script type="text/javascript">
		var conv_id_js = <?php echo $conv_id; ?>;
		var anonymous_id_js = <?php
				$manage_conv = new manage_conversations();
				$anonymous_id = $manage_conv->anonymous_id;
		echo $anonymous_id; ?>;
	</script>

	<script type="text/javascript" src="participate_index.js">
	</script>

	<script type="text/javascript">
		window.onload = function(){
			var start_from = 0;
			var conv_id = <?php echo $conv_id; ?>;
			document.getElementById('conv_id').value = conv_id;
			set_comment_id(conv_id);
			go_to_participate_comments(conv_id, start_from);
			display_participate_comment_section_nums(conv_id,0,46);
			refresh_comments();
			<?php
			if(strlen($err) > 0){
				echo "participate_signin_err();";
			}
			?>
		}
	</script>

	<meta name="description" content="Orpool is a platform for discussing issues and learning from others with different perspectives than your own." />
	<meta name="keywords" content="orpool,discussion,dialog,debate,social,media,network" />
</head>

<body id="participate_body">

<div id="main_top">
<a onClick="participate_signup()"><img src="../images/orpool_logo.png" border="0" /></a>
</div>

<div id="main">

<div id="main_msgs">
<div id="main_user_comments">
<?php
echo "<div id='main_user_name'><a onClick='participate_signup()'>
		anonymous user...
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
<div id="participate_first_comment">
	<?php echo $first_comment; ?>
</div><!--end of all_users_case-->

</td><td>
<div id="main_boxes">

	<div id="main_box01_participate">

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

</div><!-- end of boxes-->

</td><td>

	<div id="pub_conversations_lbl01" onClick="switch_to_pub_conversations()">other conversations by</div>
	<div id="conversations">
		<div id="conversations_case_scroll">
			<?php
				$manage_conv = new manage_conversations();
				$anonymous_id = $manage_conv->anonymous_id;
				$convs = $manage_conv->displayParticipateConversations($anonymous_id, $user_id);
				echo $convs;
			?>
		</div><!--end conversations_case_scroll-->
	</div><!--end of conversations-->

</td><td>
<div id="participate_signup_signin">
	<div id="participate_signup_lbl"><a onClick="participate_signup()">sign up</a></div>
		or
	<div id="participate_signin_lbl"><a onClick="participate_signin()">sign in</a></div>
</div>
</td>
</tr>
</table>
</div>
</div>

<div id="main_bottom">
    	<br />
		<center>Orpool Production - Copyright &#169; <?php echo date("Y"); ?><center>
</div>

</body>

</html>
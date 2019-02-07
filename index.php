<?php
//signin script
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_signin.php");
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/mobile_redirect.php");

$mobile_red = new mobile_redirect();
if($mobile_red->checkMobile()){
	header("location:mobile/index.php");
}

session_start();
if(isset($_SESSION['username_or_pool'])){
	$username = $_SESSION['username_or_pool'];
	$manage_db = new manage_db();
	$query_user_id = $manage_db->return_query("SELECT user_id FROM $manage_db->users WHERE username='$username' LIMIT 1");
	while($row = mysql_fetch_array($query_user_id)){
		$user_id = $row['user_id'];
	}
	$timestamp = time();
	$manage_db->query("INSERT INTO $manage_db->signin_log VALUES(null, '$user_id', '$timestamp')");
	header("location:home.php");
}

	$err = "";

	if(isset($_POST['signin'])){
	$user_email = $_POST['username_email'];
	$pass = $_POST['password'];
		$manage_signin = new manage_signin();
		$manage_db = new manage_db();

		if($manage_signin->signin_user($user_email, $pass)){
			session_start();
			$_SESSION['username_or_pool'] = $user_email;
			$query_user_id = $manage_db->return_query("SELECT user_id FROM $manage_db->users WHERE username='$user_email' OR email='$user_email' LIMIT 1");
			while($row = mysql_fetch_array($query_user_id)){
				$user_id = $row['user_id'];
			}
			$timestamp = time();
			$manage_db->query("INSERT INTO $manage_db->signin_log VALUES(null, '$user_id', '$timestamp')");
			header("location:home.php");
		}else{
			$err = "<div id='err'>Incorrect username or password!</div>";
		}
	}

	if(isset($_GET['dn'])){
		$user = $_GET['ur'];
		$id = $_GET['i'];
		$manage_db = new manage_db();
		$query_url = $manage_db->return_query("SELECT * FROM $manage_db->temp_register WHERE username='$user'");
		$url = mysql_result($query_url, 0, "confirm_url");
		$pos = strpos($url, "&i=");
		$pos = $pos +3;
		$confirm_id = substr($url, $pos, 6);

		if($id == $confirm_id){
			$user = mysql_result($query_url, 0, "username");
			$pass = mysql_result($query_url, 0, "password");
			$fname = mysql_result($query_url, 0, "first_name");
			$lname = mysql_result($query_url, 0, "last_name");
			$gender = mysql_result($query_url, 0, "gender");
			$date = mysql_result($query_url, 0, "date");
			$city = mysql_result($query_url, 0, "city");
			$country = mysql_result($query_url, 0, "country");
			$email = mysql_result($query_url, 0, "email");

			if($manage_db->return_query("INSERT INTO $manage_db->users VALUES(null, '$user', '$pass' , '$fname', '$lname', '$gender', '$date', '$city', '$country', '$email', '', 'y', '')")){
				$query_user_id = $manage_db->return_query("SELECT user_id FROM $manage_db->users WHERE username='$user'");
				$user_id = mysql_result($query_user_id, 0, "user_id");
				if($manage_db->return_query("INSERT INTO $manage_db->contacts VALUES(null, '$user_id', '$user_id' ,'y', '')")){
					session_start();
					if(isset($_GET['cnv'])){
						$_SESSION['conv_id'] = $_GET['cnv'];
					}
					$_SESSION['username_or_pool'] = $user;
					header("location:home.php");
				}
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>
	Welcome to Orpool
	</title>

	<link type="text/css" rel="stylesheet" href="orpool.css" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<script type="text/javascript" src="index_funct.js">
	</script>

	<meta name="description" content="Orpool is a platform for discussing issues and learning from others with different perspectives than your own." />
	<meta name="keywords" content="orpool,discussion,dialog,debate,social,media,network" />
</head>

<body>

<div id="welcome_top">
<a href="home.php"><img src="images/orpool_logo.png" border="0" /></a>
</div>

<div id="welcome">

<div id="welcome_box01">

</div>

<div id="welcome_box02">

</div>

<div id="welcome_box03">

</div>


<div id="welcome_box04">

</div>

<div id="welcome_box05">

<div id="welcome_box05_signin">
<?php echo $err; ?>

<div id="signin_lbl">sign in</div>
<br />
<form action="index.php" method="POST">
<input type="text" name="username_email" id="username_email" value="username or email" onClick="textVanish('username_email')" onBlur="ueTextAppear()" /><br />
<input type="text" name="password" id="password" value="password" onClick="textVanish('password')" onBlur="ueTextAppear()" /><br />
<div id="signin_div"><input type="submit" name="signin" id="signin"  value="sign in" /><div id="forgot_pass"><a onClick="forget_pass()">forgot your password?</a></div></div>
</form>
</div>

</div>

<div id="welcome_box06">
<div id="or">or, create an account</div>

<div id="signup_div"><input type="submit" name="signup" id="signup"  value="sign up" onClick="signup()" /></div>

<div id="signup_note">signup and share and discuss ideas with everyone here.. :)</div>

<!--<div id="forgot_pass"><ul><li>talk with the world</li>
<li>share pictures with friends</li>
<li>find out what others think</li>
<li>have fun doing it...</li></ul></div>-->

<div id="or"></div>
</div>

<div id="welcome_box07">

</div>

<div id="welcome_box08">

</div>

<div id="welcome_box09">

</div>

</div>

<div id="welcome_bottom">
    	<br />
		<center>Orpool Production - Copyright &#169; <?php echo date("Y"); ?><center>
</div>

</body>

</html>
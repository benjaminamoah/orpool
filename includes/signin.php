<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_signin.php");

if(isset($_POST['participate_signin'])){
	$username = "'username_email'";
	$password = "'password'";

echo $err;
echo '<div id="participate_signin"><div id="err_div"></div>
<div id="signin_lbl">sign in</div><div id="close_signup" onClick="participate_close_signup()"></div>
<br />
<form action="index.php" method="POST">
<input type="text" name="username_email" id="username_email" value="username or email" onClick="textVanish('.$username.')" onBlur="ueTextAppear()" /><br />
<input type="text" name="password" id="password" value="password" onClick="textVanish('.$password.')" onBlur="ueTextAppear()" /><br />
<div id="signin_div"><input type="submit" name="signin" id="signin"  value="sign in" /><div id="forgot_pass"><a onClick="forget_pass()">forgot your password?</a></div></div>
</form></div>';
}

if(isset($_POST['participate_signin_err'])){
	$username = "'username_email'";
	$password = "'password'";

echo '<div id="participate_signin">
<div id="signin_lbl">sign in</div><div id="close_signup" onClick="participate_close_signup()"></div>
<br /><div id="err">Incorrect username or password!</div>
<form action="index.php" method="POST">
<input type="text" name="username_email" id="username_email" value="username or email" onClick="textVanish('.$username.')" onBlur="ueTextAppear()" /><br />
<input type="text" name="password" id="password" value="password" onClick="textVanish('.$password.')" onBlur="ueTextAppear()" /><br />
<div id="signin_div"><input type="submit" name="signin" id="signin"  value="sign in" /><div id="forgot_pass"><a href="#">forgot your password?</a></div></div>
</form></div>';
}


if(isset($_POST['forget_pass'])){
	$user_email = "'username_email'";

echo '<div id="welcome_box05_signin">
<div id="remind_me">retrieve my password</div>
<input type="text" name="username_email" id="username_email" value="username or email" onClick="textVanish('.$user_email.')" onBlur="ueTextAppear()" /><br />
<div id="signin_div"><input type="submit" name="signin" id="signin"  value="done" onClick="retrieve_pass('.$user_email.')" /></div>
</div>';
}


if(isset($_POST['retrieve_pass'])){
	$user_email = $_POST['user_email'];
	$manage_db = new manage_db();
	$query = $manage_db->return_query("SELECT * FROM $manage_db->users WHERE email='$user_email' OR username='$user_email' LIMIT 1");
	while($row = mysql_fetch_array($query)){
		$fname = $row['first_name'];
		$user = $row['username'];
		$pass = $row['password'];
		$email = $row['email'];
	}

	$to = $email;
	$subject = "retrieved username and password";
	$message = "hi ".$fname.", \n\n\ryour username is: ".$user."\n\n\r and your password is: ".$pass." \n\n\rplease do not reply to this email!";
	$headers = "from: passwords@orpool.com";
	if(mail($to, $subject, $message, $headers)){
		echo '<div id="welcome_box05_signin">
		<div id="remind_me_note">you username and password have<br /> been sent to your email...<br /><br />see you soon ;)</div>
		</div>';
}
}
?>
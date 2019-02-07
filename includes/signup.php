<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_signup.php");

if(isset($_POST['signup'])){

$username = "'username'";
$username_err = "'username_err'";
$password = "'password'";
$password_err = "'password_err'";
$fname = "'fname'";
$first_name = "'first name'";
$name_err = "'name_err'";
$lname = "'lname'";
$last_name = "'last name'";
$email = "'email'";
$gender = "'gender'";
$day = "'day'";
$month = "'month'";
$year = "'year'";
$city = "'city'";
$country = "'country'";
$city_country_err = "'city_country_err'";

	echo '<div id="welcome_box05_signin">
		<div id="signup_err_top"></div>
		<div id="signin_lbl">signing up</div><div id="close_signup" onClick="close_signup()"></div><br />
		<input type="text" name="fname" id="fname" value="first name" onClick="signupdetail('.$fname.'); textVanish('.$fname.');" onBlur="textAppear();checkChars('.$fname.','.$name_err.');" />
		<input type="text" name="lname" id="lname" value="last name" onClick="signupdetail('.$lname.'); textVanish('.$lname.')" onBlur="textAppear();checkChars('.$lname.','.$name_err.');" />
		<div id="name_err"></div>
		<input type="text" name="username" id="username" value="username" onClick="signupdetail('.$username.'); textVanish('.$username.')" onBlur="textAppear();checkUsername();" /><br />
		<input type="hidden" id="check_username" value="no" />
		<div id="username_err"></div>
		<input type="text" name="password" id="password" value="password" onClick="signupdetail('.$password.'); textVanish('.$password.')" onBlur="textAppear();checkChars('.$password.','.$password_err.');" /><br />
		<div id="password_err"></div>
		<input type="text" name="email" id="email" value="email" onClick="signupdetail('.$email.'); textVanish('.$email.')" onBlur="textAppear();checkEmail();" /><br />
		<input type="hidden" id="check_email" value="no" />
		<div id="email_err"></div>

		<select name="gender" id="gender" onClick="signupdetail('.$gender.')">
			<option id="gender_option">I am..</option>
			<option></option>
			<option>male</option>
			<option>female</option>
		</select><br />

		<select name="day" id="day" onClick="signupdetail('.$day.')">
			<option id="date_option">Day</option>
			<option></option>
			<option>01</option>
			<option>02</option>
			<option>02</option>
			<option>04</option>
			<option>05</option>
			<option>06</option>
			<option>07</option>
			<option>08</option>
			<option>09</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option>13</option>
			<option>14</option>
			<option>15</option>
			<option>16</option>
			<option>17</option>
			<option>18</option>
			<option>19</option>
			<option>20</option>
			<option>21</option>
			<option>22</option>
			<option>23</option>
			<option>24</option>
			<option>25</option>
			<option>26</option>
			<option>27</option>
			<option>28</option>
			<option>29</option>
			<option>30</option>
			<option>31</option>
		</select>
		<select name="month" id="month" onClick="signupdetail('.$month.')">
			<option id="date_option">Month</option>
			<option></option>
			<option>January</option>
			<option>February</option>
			<option>March</option>
			<option>April</option>
			<option>May</option>
			<option>June</option>
			<option>July</option>
			<option>August</option>
			<option>September</option>
			<option>October</option>
			<option>November</option>
			<option>December</option>
		</select>';

		$n = date("Y")-100;
		echo '<select name="year"id="year" onClick="signupdetail('.$year.')">
			<option id="date_option">Year</option>
			<option></option>';
		for($i = 0; $i<85; $i++){
			$n = $n+1;
			echo '<option>'.$n.'</option>';
		}
		echo '</select>';

		echo '<input type="text" name="city" id="city" value="city" onClick="signupdetail('.$city.'); textVanish('.$city.')" onBlur="textAppear();checkChars('.$city.','.$city_country_err.');" />
		<input type="text" name="country" id="country" value="country" onClick="signupdetail('.$country.'); textVanish('.$country.')" onBlur="textAppear();checkChars('.$country.','.$city_country_err.');" /><br />
		<div id="city_country_err"></div>
		<div id="terms_text"><input type="checkbox" name="agreeterms" id="agreeterms" />I have read and agree to the <a href="termsofuse/terms_of_use.php" target="_blank">terms of use</a> and <a href="privacypolicy/privacy_policy.php" target="_blank">privacy policy</a> of this site.</div>
		<div id="agreeterms_err"></div>
		<div id="signin_div"><input type="submit" name="register" id="finish"  value="finish" onClick="register()" /></div>

		</div>';
}


if(isset($_POST['register'])){
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$date_day = $_POST['day'];
	$date_month = $_POST['month'];
	$date_year = $_POST['year'];
	$gender = $_POST['gender'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$email = $_POST['email'];
	$agreeterms = $_POST['agreeterms'];

	$manage_signup = new manage_signup();
	if($gender == "I am..."){
		$gender == "";
	}
	$date = $date_day." ".$date_month." ".$date_year;
	$date = strtotime($date);
	$random_id = rand(10000,100000);
	$url = $manage_signup->root_dir."shuffle/index.php?dn=true&ur=".$user."&i=".$random_id;
	$url = addslashes($url);

	if($manage_signup->temp_register_user(null, $user, $pass, $fname, $lname, $gender, $date, $city, $country, $email,  "", $url)){
	$to = $email;
	$subject = "orpool final step: click on this link";
	$url_link = "<a href='".$url."'>".$url."</a>";
	$message = "thanks for joining us ".$fname.". welcome to the orpool community. you may click on the link below to complete signing-up.\n\n\r".$url_link."\n\n\ryour username is <b>".$user."</b> and your password is <b>".$pass."</b>";
	$headers = "From: welcome@orpool.com";
		mail($to, $subject, $message, $headers);
		echo $email;
	}

}


if(isset($_POST['participate_register'])){
	$conv_id = $_POST['conv_id'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$date_day = $_POST['day'];
	$date_month = $_POST['month'];
	$date_year = $_POST['year'];
	$gender = $_POST['gender'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$email = $_POST['email'];
	$agreeterms = $_POST['agreeterms'];

	$manage_signup = new manage_signup();
	$date = $date_day." ".$date_month." ".$date_year;
	$date = strtotime($date);
	$random_id = rand(10000,100000);
	$url = $manage_signup->root_dir."shuffle/index.php?dn=true&ur=".$user."&cnv=".$conv_id."&i=".$random_id;
	$url = addslashes($url);

	if($manage_signup->temp_register_user(null, $user, $pass, $fname, $lname, $gender, $date, $city, $country, $email,  "", $url)){
	$to = $email;
	$subject = "click on this link";
	$url_link = "<a href='".$url."'>".$url."</a>";
	$message = "thanks for joining us ".$fname.". welcome to the orpool community. you may click on the link below to complete signing-up.\n\n\r".$url_link."\n\n\ryour username is <b>".$user."</b> and your password is <b>".$pass."</b>";
	$headers = "From: welcome@orpool.com";
		mail($to, $subject, $message, $headers);
		echo $email;
	}

}


if(isset($_POST['close_signup'])){
$username_email = "'username_email'";
$password = "'password'";

echo '<div id="welcome_box05_signin">
		<div id="signin_lbl">sign in</div>
		<br />
		<form action="index.php" method="POST">
		<input type="text" name="username_email" id="username_email" value="username or email" onClick="textVanish('.$username_email.')" onBlur="textAppear()" /><br />
		<input type="text" name="password" id="password" value="password" onClick="textVanish('.$password.')" onBlur="textAppear()" /><br />
		<div id="signin_div"><input type="submit" name="signin" id="signin"  value="sign in" /><div id="forgot_pass"><a onClick="forget_pass()">forgot your password?</a></div></div>
		</form>
		</div>';

echo "[BRK]";

echo '<div id="or">or, create an account</div>
<div id="signup_div"><input type="submit" name="signup" id="signup"  value="sign up" onClick="signup()" /></div>
<div id="signup_note">signup and share with everyone.. :)</div>';
}

if(isset($_POST['participate_signup'])){
	$username = "'username'";
	$username_err = "'username_err'";
	$password = "'password'";
	$password_err = "'password_err'";
	$fname = "'fname'";
	$first_name = "'first name'";
	$name_err = "'name_err'";
	$lname = "'lname'";
	$last_name = "'last name'";
	$email = "'email'";
	$gender = "'gender'";
	$day = "'day'";
	$month = "'month'";
	$year = "'year'";
	$city = "'city'";
	$country = "'country'";
	$city_country_err = "'city_country_err'";

	echo '<div id="welcome_box05_signin">
		<div id="signup_err_top"></div>
		<div id="signin_lbl">signing up</div><div id="participate_close_signup" onClick="participate_close_signup()"></div><br />
		<input type="text" name="fname" id="fname" value="first name" onClick="textVanish('.$fname.');" onBlur="textAppear();checkChars('.$fname.','.$name_err.');" />
		<input type="text" name="lname" id="lname" value="last name" onClick="textVanish('.$lname.')" onBlur="textAppear();checkChars('.$lname.','.$name_err.');" />
		<div id="name_err"></div>
		<input type="text" name="username" id="username" value="username" onClick="textVanish('.$username.')" onBlur="textAppear();checkUsername();" /><br />
		<input type="hidden" id="check_username" value="no" />
		<div id="username_err"></div>
		<input type="text" name="password" id="password" value="password" onClick="textVanish('.$password.')" onBlur="textAppear();checkChars('.$password.','.$password_err.');" /><br />
		<div id="password_err"></div>
		<input type="text" name="email" id="email" value="email" onClick="textVanish('.$email.')" onBlur="textAppear();checkEmail();" /><br />
		<input type="hidden" id="check_email" value="no" />
		<div id="email_err"></div>

		<select name="gender" id="gender">
			<option id="gender_option">I am..</option>
			<option></option>
			<option>male</option>
			<option>female</option>
		</select><br />
		<select name="day" id="day">
			<option id="date_option">Day</option>
			<option></option>
			<option>01</option>
			<option>02</option>
			<option>02</option>
			<option>04</option>
			<option>05</option>
			<option>06</option>
			<option>07</option>
			<option>08</option>
			<option>09</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option>13</option>
			<option>14</option>
			<option>15</option>
			<option>16</option>
			<option>17</option>
			<option>18</option>
			<option>19</option>
			<option>20</option>
			<option>21</option>
			<option>22</option>
			<option>23</option>
			<option>24</option>
			<option>25</option>
			<option>26</option>
			<option>27</option>
			<option>28</option>
			<option>29</option>
			<option>30</option>
			<option>31</option>
		</select>
		<select name="month" id="month">
			<option id="date_option">Month</option>
			<option></option>
			<option>January</option>
			<option>February</option>
			<option>March</option>
			<option>April</option>
			<option>May</option>
			<option>June</option>
			<option>July</option>
			<option>August</option>
			<option>September</option>
			<option>October</option>
			<option>November</option>
			<option>December</option>
		</select>';

		$n = date("Y")-100;
		echo '<select name="year" id="year">
			<option id="date_option">Year</option>
			<option></option>';
		for($i = 0; $i<85; $i++){
			$n = $n+1;
			echo '<option>'.$n.'</option>';
		}
		echo '</select>';
		echo '<input type="text" name="city" id="city" value="city" onClick="textVanish('.$city.')" onBlur="textAppear();checkChars('.$city.','.$city_country_err.');" />
		<input type="text" name="country" id="country" value="country" onClick="textVanish('.$country.')" onBlur="textAppear();checkChars('.$country.','.$city_country_err.');" /><br />
		<div id="city_country_err"></div>
		<div id="terms_text"><input type="checkbox" name="agreeterms" id="agreeterms" />I have read and agree to the <a href="../termsofuse/terms_of_use.php" target="_blank">terms of use</a> and <a href="../privacypolicy/privacy_policy.php" target="_blank">privacy policy</a> of this site.</div>
		<div id="agreeterms_err"></div>
		<div id="signin_div"><input type="submit" name="register" id="finish"  value="finish" onClick="participate_register()" /></div>
		</div>';
}

?>
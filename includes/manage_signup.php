<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_db.php");

class manage_signup extends manage_db{
function temp_register_user($user_id, $user, $pass, $fname, $lname, $gender, $date, $city, $country, $email,  $profile_pic, $url){
$manage_db =  new manage_db();

if($gender == "male"){
	$gender = "m";
}else if($gender == "female"){
	$gender = "f";
}else{
	$gender = "";
}

	if($manage_db->return_query("insert into temp_register values('$user_id', '$user', '$pass' , '$fname', '$lname', '$gender', '$date', '$city', '$country', '$email', '$profile_pic', '$url')")){
		return true;
	}
}

}


?>
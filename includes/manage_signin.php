<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_db.php");

class manage_signin extends manage_db{
//1.
		function signin_user($user_email, $pass){
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM $manage_db->users where username = '$user_email' or email = '$user_email'");

			if(mysql_num_rows($query) > 0){
				$result_pass = mysql_result($query, $k, 'password') or die(mysql_error());

				if($result_pass==$pass){
					$fdata = true;
				}else{
					$fdata = false;
				}
			}else{
				$fdata = false;
			}

			return $fdata;
		}

}
?>
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

	if(isset($_POST['save_profile_pic'])){
		//upload pic
		$maxsize=528480; //set the max upload size in bytes
		if(strlen($_FILES['profile_pic']['name']) > 0){
			if (!is_uploaded_file($_FILES['profile_pic']['tmp_name']) AND !isset($error)) {
				$error = "<b>You must upload a file!</b><br /><br />";
				//unlink($_FILES['profile_pic']['tmp_name']);
			}

			if ($_FILES['profile_pic']['size'] > $maxsize AND !isset($error)) {
				$error = "<b>Error, file must be less than $maxsize bytes.</b><br /><br />";
				unlink($_FILES['profile_pic']['tmp_name']);
			}

			if($_FILES['profile_pic']['type'] != "image/gif" AND
				$_FILES['profile_pic']['type'] != "image/pjpeg" AND
				$_FILES['profile_pic']['type'] !="image/jpeg" AND !isset($error)) {
				$error = "<b>You may only upload .gif or .jpeg files.</b><br /><br />";
				unlink($_FILES['profile_pic']['tmp_name']);
			}

			if (!isset($error)) {
				$type = $_FILES['profile_pic']['type'];
				$pos= strpos($type, "/");
				$ext = substr($type, $pos+1, strlen($type));
				$date = time();
				$_FILES['profile_pic']['name'] = "../images/profile_pics/".$date.".".$ext;
				$profile_pic = $_FILES['profile_pic']['name'];
				$profile_pic = addslashes($profile_pic);

				if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic)){
					$manage_db = new manage_db();
					$manage_db->query("UPDATE $manage_db->users SET profile_picture='$profile_pic' WHERE user_id='$user_id'");
				}
			}
		}else{}
	}//end submit check
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<link type="text/css" rel="stylesheet" href="../orpool.css" />
	<script type="text/javascript">
		function getXMLHttp(){
			if(window.XMLHttpRequest){
				return new XMLHttpRequest();
			}else if(window.ActiveXObject){
				return new ActiveXObject("Microsoft.XMLHTTP");
			}else{
				alert("Sorry, Your browser does not support Ajax!");
			}
		}

	</script>
</head>

<body>
<form action="image_upload.php" method="POST" enctype="multipart/form-data">
<div id='profile_pic_upload_field'><input type='file' name='profile_pic' size='12' /></div>
<!--<input type="hidden" id="user_id" value="<?php echo $user_id; ?>" />-->
<input type="submit" id="save_profile_pic" name="save_profile_pic" value="save"  />
</form>
</body>

</html>
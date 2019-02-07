<?php
class db{
	var $db_host = "localhost";
	var $db_user = "root";
	var $db_pass = "utopiamaya3";
	var $db_name = "shuffle";

    //tables
    var $users = "users";
    var $temp_register = "temp_register";
    var $contacts = "contacts";
    var $conversations = "conversations";
    var $comments = "comments";
    var $poll_options = "poll_options";
    var $users_taken_poll = "users_taken_poll";
    var $signin_log = "signin_log";
	var $root_dir = "http://localhost";

	var $anonymous_id = 13;
}
?>
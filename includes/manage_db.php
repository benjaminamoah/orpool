<?php
	require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/db.php");

	class manage_db extends db{

/**************************************
functions here
1. connect()
2. select_db()
3. query()
4. return_query()
****************************************/
//1.
		function connect(){
			$db_info = new db();
			$connect = mysql_connect($db_info->db_host, $db_info->db_user, $db_info->db_pass) or die(mysql_error());
			return $connect;
		}

//2.
		function select_db(){
			$db_info = new db();
			$connect = $this->connect();
			mysql_select_db($db_info->db_name, $connect);
		}

//3.
		function query($sql){
			$connect = $this->connect();
			$this->select_db();
			mysql_query($sql, $connect) or die(mysql_error());
		}

//4.
		function return_query($sql){
			$connect = $this->connect();
			$this->select_db();
			$query = mysql_query($sql, $connect) or die(mysql_error());
			return $query;
		}

	}

?>
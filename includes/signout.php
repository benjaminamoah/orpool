<?php
session_start();
unset($_SESSION['username_or_pool']);
header("location:../index.php");
?>
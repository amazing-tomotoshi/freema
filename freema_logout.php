<?php
session_start();
$_SESSION=array();

session_destroy();

header('Location:freema_login.php');
exit();


?>
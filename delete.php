<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
	header('Location: index.php');
	exit();
}

if(!$_SESSION['is_admin']) {
	die();
}

$db = mysqli_connect("localhost", "root", "", "zadanie");

$uid = $_GET["uid"];

if (is_numeric($uid)) {
	$db->query("delete from users where id = " . $uid);
	header('Location: panel.php');
	exit();
} else {
	die();
}

?>
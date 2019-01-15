<?php 
session_start();
require_once ('top.php');
require_once ('config.php');


if (!isset($_SESSION['zalogowany'])) 
{
	header('Location: index.php');
	exit();
}

if (isset($_GET['login'])) 
{
	if ($_GET['login']=='konrad') {
		$_SESSION['login'] = true;
		$_SESSION['login'] = 'Konrad';
		$wybrany_login = $_SESSION['login'];
		$sql = mysql_query("INSERT INTO logi SET tresc='Po poprawnym zalogowaniu wybrano login: ', zalogowany='$wybrany_login'");
		header('Location: zalogowany.php?sort_user=asc');
	}
	else{
		$_SESSION['login'] = true;
		$_SESSION['login'] = 'Gosia';
		$wybrany_login = $_SESSION['login'];
		$sql = mysql_query("INSERT INTO logi SET tresc='Po poprawnym zalogowaniu wybrano login: ', zalogowany='$wybrany_login'");
		header('Location: zalogowany.php?sort_user=asc');
	}
}

?>

<?php 

// sprawdzenie czy jest zalogowany
if (!isset($_SESSION['zalogowany'])) 
{
	header('Location: index.php');
	exit();
}
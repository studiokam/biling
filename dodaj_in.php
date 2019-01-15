<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');


$ad_user = $_POST['ad_user'];
$ad_usluga = $_POST['ad_usluga'];
$ad_sciezka = $_POST['ad_sciezka'];
$ad_email = $_POST['ad_email'];
$ad_dodaje = $_POST['ad_dodaje'];

if ($ad_usluga === 'Wybierz') 
{
	header('Location: dodaj.php?blad');
	exit();
}


$_SESSION['ad_user'] = $ad_user;

//sprawdzenie czy sa wymagane zmienne z formularza dodania
if (!$ad_user || !$ad_email || !$ad_dodaje || !$ad_usluga) 
{
	header('Location: dodaj.php?blad');
	exit();
}

    
// dodajemy rekord do bazy
$ins = @mysql_query("INSERT INTO klienci SET user='$ad_user', usluga='$ad_usluga', sciezka='$ad_sciezka', email='$ad_email', dodal='$ad_dodaje'");
    
if($ins) 
{
	$sql = mysql_query("INSERT INTO logi SET tresc='Dodano usera o nicku $ad_user. Dodał:  ', zalogowany='$ad_dodaje'");
	header('Location: zalogowany.php?dodano_usera&sort_user=asc');
}


require_once ('footer.php');
 ?>
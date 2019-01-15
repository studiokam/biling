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


$id = $_SESSION['edytuj_id'];

//sprawdzenie czy sa wymagane zmienne z formularza dodania
if (!$ad_user || !$ad_email || !$ad_dodaje) 
{
header('Location: edytuj_usera.php?nie_podano_wszystkich_danych_edycja&id='.$id);
	exit();
}

    
// dodajemy rekord do bazy
if (isset($_SESSION['edytuj_id'])) 
{
	$ins = @mysql_query("UPDATE klienci SET user='$ad_user', usluga='$ad_usluga', sciezka='$ad_sciezka', email='$ad_email', dodal='$ad_dodaje' WHERE id='$id'");
	unset($_SESSION['edytuj_id']);
}


if($ins) 
header('Location: zalogowany.php?poprawna_edycja_usera&sort_user=asc');



require_once ('footer.php');
 ?>
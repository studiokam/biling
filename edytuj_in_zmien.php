<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');


$zmien_data = $_POST['zmien_data'];
$zmien_data_old = $_POST['zmien_data_hide'];
$zmien_powod = $_POST['zmien_powod'];
$zmien_kto = $_POST['zmien_kto'];


$id = $_SESSION['edytuj_id'];

//sprawdzenie czy sa wymagane zmienne z formularza dodania
if (!$zmien_data || !$zmien_powod || !$zmien_kto ) 
{
header('Location: edytuj_usera.php?blad_zmien_data&id='.$id);
	exit();
}


$zapytanie = "SELECT * FROM klienci WHERE id='$id'"; 
$wynik = mysql_query($zapytanie);
$wiersz = mysql_fetch_array($wynik);

$nowaData = strtotime($zmien_data);
$user = $wiersz['user'];


// dodajemy rekord do bazy
if (isset($_SESSION['edytuj_id'])) 
{
	
		$ins = @mysql_query("UPDATE klienci SET do_kiedy_data='$nowaData' WHERE id='$id'");
		$sqlLogi = mysql_query("INSERT INTO logi SET tresc='Zmieniono datę ($zmien_data_old) na datę ($zmien_data) dla usera $user . Powód zmiany: $zmien_powod.  Zmienił:  ', zalogowany='$zmien_kto', id_usera='$id'");

		unset($_SESSION['edytuj_id']);
	
}



if($ins) 
header('Location: zalogowany.php?poprawnie_zmieniono_date&sort_user=asc');



require_once ('footer.php');
 ?>
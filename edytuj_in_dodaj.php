<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');


$dodaj_dni_ile = $_POST['dodaj_dni_ile'];
$dodaj_dni_kto = $_POST['dodaj_dni_kto'];


$id = $_SESSION['edytuj_id'];

//sprawdzenie czy sa wymagane zmienne z formularza dodania
if (!$dodaj_dni_ile || !$dodaj_dni_kto ) 
{
header('Location: edytuj_usera.php?blad_dodaj_dni&id='.$id);
	exit();
}


$zapytanie = "SELECT * FROM klienci WHERE id='$id'"; 
$wynik = mysql_query($zapytanie);
$wiersz = mysql_fetch_array($wynik);

$user = $wiersz['user'];
//$ddd = date("Y-m-d H:i:s", $wiersz['do_kiedy_data']);

$ile = 60*60*24*$dodaj_dni_ile;

$dkd = $wiersz['do_kiedy_data'];
$odkodowanaData = date("Y-m-d" , $dkd);

$data = date("Y-m-d".' 02:00:06');
$dataUnix = strtotime($data);

// dodawanie dni - pokazuje ile dni dodano
$sumowanieDat = $dkd + $ile;
$sumowanieDat2 = $dataUnix + $ile;




if ($wiersz['do_kiedy_data'] > 1) 
	{
		$dkd = $wiersz['do_kiedy_data'];
		$data = date("Y-m-d");
		$dataUnix = strtotime($data);

		// odejmowanie dni - pokazuje ile dni zostalo
		$roznicaDat = $dkd - $dataUnix; 
		$ileDniZostalo = $roznicaDat/60/60/24;
	}
 else 
	{
		$ileDniZostalo = 0;
	}


// dodajemy rekord do bazy
if (isset($_SESSION['edytuj_id'])) 
{
	if ($dkd > 1) 
	{
		$ins = @mysql_query("UPDATE klienci SET do_kiedy_data = '$sumowanieDat' WHERE id='$id'");
		mysql_query("INSERT INTO zakupy SET id_usera='$id', ile_dni='$dodaj_dni_ile', kto_dodal='$dodaj_dni_kto', data_wygasania_przed_zakupem='$dkd', ilosc_dni_przed_zakupem='$ileDniZostalo' ");
		$sql = mysql_query("INSERT INTO logi SET tresc='Dla usera $user dodano $dodaj_dni_ile dni. Dodał:  ', zalogowany='$dodaj_dni_kto'");
		unset($_SESSION['edytuj_id']);
	}
	else
	{
		$ins = mysql_query("UPDATE klienci SET do_kiedy_data='$sumowanieDat2' WHERE id='$id'");
		mysql_query("INSERT INTO zakupy SET id_usera='$id', ile_dni='$dodaj_dni_ile', kto_dodal='$dodaj_dni_kto', data_wygasania_przed_zakupem='0', ilosc_dni_przed_zakupem='$ileDniZostalo' ");
		$sql = mysql_query("INSERT INTO logi SET tresc='Dla usera $user dodano $dodaj_dni_ile dni. Dodał:  ', zalogowany='$dodaj_dni_kto'");
		unset($_SESSION['edytuj_id']);
	}
}



if($ins) 
header('Location: zalogowany.php?poprawnie_dodano_dni&sort_user=asc');



require_once ('footer.php');
 ?>
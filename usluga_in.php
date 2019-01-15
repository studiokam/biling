<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('config.php');
require_once ('top.php');


$wybrany_login = $_SESSION['login'];


$nowa_usluga = $_POST ['ad_usluga'];
$nowy_opis = $_POST['ad_opis'];

if (!$_POST['ad_usluga'] || !$_POST['ad_opis']) 
{
	header('Location: uslugi.php?brak_danych');
	exit();
}


$q = mysql_query( "SELECT * FROM uslugi WHERE usluga = '$nowa_usluga'"); 

if(mysql_num_rows($q) >0) 
{																																
 	header('Location: uslugi.php?jest_podana');
	exit();

} 
else 
	{
		$dodaj = @mysql_query("INSERT INTO uslugi SET usluga='$nowa_usluga', opis='$nowy_opis'");

		if ($dodaj) 
		{
			$log = mysql_query("INSERT INTO logi SET tresc='Dodano usługę: $nowa_usluga. Treść nowej usługi: $nowy_opis. Dodał:  ', zalogowany='$wybrany_login'");
			header('Location: uslugi.php?dodano_usluge');

		}
	}


	require_once ('footer.php');
  ?>
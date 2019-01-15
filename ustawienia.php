<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');

echo '<div class="lewa">';
require_once('menu.php');

if (isset($_GET['wykasowanologi'])) echo "Wykasowano logi";


echo '</div>';
// info o wkonaniu kopii bazy danych
if (isset($_GET['bakup_ok'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie wykonano backup Bazy Danych i zapisano na serwerze</div>';

// info o wkonaniu kopii plików
if (isset($_GET['poprawnyBackupPlikow'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie wykonano backup Plików i zapisano na serwerze</div>';

//zapytanie
$query = mysql_query("SELECT logi_countilosc FROM ustawienia");
$row = mysql_fetch_array($query);


if (isset($_GET['logi_countIlosc'])) 
{
	$x = $_GET['logi_countIlosc'];
}
else
{
	if (mysql_num_rows($query) > 0) {
		$x = $row['logi_countilosc'];
	}
	else
	{
		mysql_query("INSERT INTO ustawienia SET logi_countilosc='50'");
		$logi_countilosc = 50;
	}
}


if(mysql_num_rows($query) > 0) 
{
	
 	mysql_query("UPDATE ustawienia SET logi_countilosc='$x'");
 	$logi_countilosc = $x;
} 

$data = date("Y-m-d H:i:s");
$dataUnix = strtotime($data);
$dataUnixPlus14 = $dataUnix + (60*60*24*14);
$dataUnixPlus15 = $dataUnix + (60*60*24*15);

$ilosc = mysql_num_rows(mysql_query("SELECT * FROM klienci WHERE do_kiedy_data < $dataUnix AND do_kiedy_data > 1 "));
$ilosc15 = mysql_num_rows(mysql_query("SELECT * FROM klienci WHERE do_kiedy_data > $dataUnixPlus14 AND do_kiedy_data < $dataUnixPlus15"));

?>		

<form action="ustawienia.php"> 
	<table class="table table-hover table-bordered">
 		<tr class="active">
 			<td colspan="3">Logi - Ustawienia</td>
 		</tr>
 		<tr>
 			<td colspan="3">Ilość logów na stronie: <?php echo $logi_countilosc; ?></td>
		</tr>
		<tr>
 			<td class="col-sm-3">Podaj ile ma być logów na stronie:</td>
			<td class="col-sm-2"><input type="text" class="form-control" name="logi_countIlosc"></td>
			<td><button type="submit" class="btn btn-success">Ustaw</button></td>
		</tr>

	</table>
</form>
 
	<table class="table table-hover table-bordered">
 		<tr class="active">
 			<td>Backup</td>
 		</tr>
 		<tr>
			<td>
				<a class="btn btn-success " type="submit" href="backup.php">Wykonaj backup Bazy Danych</a>
				<a class="btn btn-success " type="submit" href="backup_plikow.php">Wykonaj backup PLIKÓW</a>
			</td>
		</tr>
	</table>

	<table class="table table-hover table-bordered">
 		<tr class="active">
 			<td>Sprawdzenie abonamentów userów ręcznie</td>
 		</tr>
 		<tr>
			<td>
				<a class="btn btn-success " type="submit" href="sprawdzenie_abonamentow.php?zalogowany"> W ciągu 24h</a>
				<a class="btn btn-success " type="submit" href="sprawdzenie_abonamentow15.php?zalogowany">Za 15 dni</a>
				<a class="btn btn-success " type="submit" href="sprawdzenie_abonamentow_blad_crona.php?zalogowany">Sprawdzanie wszystkich od teraz do tyłu (gdyby nie zadziałał cron)</a>
			</td>
		</tr>
		<tr>
			<td><?php echo 'W bazie jest błędnych rekordów (nie wysłanych wiadomości email np. w wyniku błędu cron): <b>'.$ilosc.'</b>'; ?></td>
		</tr>
		<tr>
			<td><?php echo 'Abonamenty kończące się za 15 dni. W bazie jest rekordów: <b>'.$ilosc15.'</b>'; ?></td>
		</tr>
	</table>
	
	<?php 
		if (isset($_GET['oznacz']) AND $_GET['oznacz'] =='tak') 
		{
			mysql_query("UPDATE ustawienia SET oznacz='tak'");
			$sql_log = mysql_query("INSERT INTO logi SET tresc='Zmieniono pokazywanie przycisku Oznacz na POKAZUJ. Dodał:  ', zalogowany='$wybrany_login'");
			echo "Zmieniono ustawienie na POKAZUJ";
		}
		if (isset($_GET['oznacz']) AND $_GET['oznacz'] =='nie') 
		{
			mysql_query("UPDATE ustawienia SET oznacz='nie'");
			$sql_log = mysql_query("INSERT INTO logi SET tresc='Zmieniono pokazywanie przycisku Oznacz na NIE POKAZUJ. Dodał:  ', zalogowany='$wybrany_login'");
			echo "Zmieniono ustawienie na NIE POKAZUJ";
		}
	 ?>

	<table class="table table-hover table-bordered">
 		<tr class="active">
 			<td>Czy pokazywać przycisk "Oznacz usera" w opcji edycji usera</td>
 		</tr>
 		<tr>
			<td>
				<?php 
					$sql_oznacz = mysql_query("SELECT oznacz FROM ustawienia");
					$wynik_oznacz = mysql_fetch_array($sql_oznacz);

					$oznacz = $wynik_oznacz['oznacz'];
					if ($oznacz == 'tak') 
					{
						echo '<a class="btn btn-success " type="submit" href="ustawienia.php?oznacz=nie">TAK - kliknij aby zmienić na NIE</a>';
					}
					elseif ($oznacz == 'nie')
					{
						echo '<a class="btn btn-danger " type="submit" href="ustawienia.php?oznacz=tak">NIE - kliknij aby zmienić na TAK</a>';
					}
					else
					{
						echo "Błąd odczytu wartości";
					}
				 ?>
			</td>
		</tr>
	</table>


<?php 


	// przycisk kasowania wszsytkich logów (jeśli zalogowany jest "Konrad")
	if ($_SESSION['login'] == 'Konrad') 
	{
		
		echo 
		'<tr>
				<td colspan="3"><a class="btn btn-danger" type="submit" href="ustawienia.php?kasujlogi">Wykasuj wszystkie logi</a></td>
		</tr>';
	}
	if (isset($_GET['kasujlogi'])) 
	{
		$kasowanieLogow = mysql_query("DELETE FROM logi");
	
		if ($kasowanieLogow) 
		{
			header('Location:ustawienia.php?wykasowanologi');
		}
	}
 require_once ('footer.php'); ?>
<?php 
//
//
//
// plik ten sprawdza dostepność abonmentów w chwili sprawdzenia plus 24h
// ma on być wywoływany w cron raz na dobę aby codziennie sprawdził czy są 
// abonenci których trzeba poinformować o konieczności oplaty i ich zablokować
//
//
//
session_start();
require_once ('top.php');
require_once ('config.php');

// sprawdzenie czy zalogowany
if (!isset($_SESSION['zalogowany'])) 
{
	header('Location: index.php');
	exit();
}

try {
	$dbConnect = new PDO("mysql:dbname=$db_name; host=$host", $db_user, $db_pass);
	//$db_connect = new PDO('mysql:host=localhost;dbname=biling','root','');
	$dbConnect->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbConnect->setAttribute (PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
	$dbConnect->query('SET NAMES utf8');
	$dbConnect->query('SET CHARACTER SET utf8');
	//echo "Połączono z bazą x";
} catch (PDOException $e) {
	echo ($e->getMessage());
}

$id_usera = $_GET['id'];

// zmienne tematów do pola tytuł (poprawne kodowanie polskich znaków)
$temat= "=?UTF-8?B?".base64_encode("Utrzymanie plików/konta na serwerze.")."?=";
$tematAdmin= "=?UTF-8?B?".base64_encode("Utrzymanie plików/konta na serwerze. (kopia).")."?=";

// zmienne tematów do zapytań (logi) aby nie wstawialo z tymi znakami specjalnymi
$tematLogi = "Utrzymanie plików/konta na serwerze. Ostatni dzień.";
$tematAdminLogi = "Utrzymanie plików/konta na serwerze. (kopia).";

$emailAdmin = 'alle@cayman23.pl';
$headers = 'From: alle@cayman23.pl'."\r\n" .'Content-type: text/html; charset=utf-8';


$tresc_zapytania = "SELECT * FROM klienci WHERE id='$id_usera'";
$zapytanie = mysql_query($tresc_zapytania);
$wiersze = mysql_fetch_array($zapytanie);

$user = $wiersze['user'];
$email = $wiersze['email'];
$usluga = $wiersze['usluga'];

//wyświetlenie menu (potrzebne głownie do pobrania zalogowanego ale i aby lepiej wyglądało)
echo '<div class="lewa">';
require_once('menu.php');
echo '</div>';


if (!isset($_POST['data'])) 
{
echo '
<br>
<table class="table table-hover table-bordered">
	<tr>
		<td>Wysyłasz wiadomość do:</td>
	</tr>
	<tr class="active">
 		<td> '.$user. ' / ' .$email.'</td>
 	</tr>	
</table>

<form class="form-horizontal" method="post">
  <div class="form-group">
    <label class="col-sm-3 control-label">* Data do kiedy było opłacone:</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" name="data" placeholder="Podaj date koniecznie w formacie: 2015-05-30 (rok/miesiąc/dzień przedzielone myślnikiem)">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" class="btn btn-success">Wyślij</button>
    </div>
  </div>
</form>';
}
else
{
	$data = $_POST['data'];
	mail($email, $temat, wiadomosc ($user, $data, $usluga), $headers); // wysłanie wiadomosci
	mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $usluga), $headers); // dodatkowy email do admina

	// dodanie logow do bazy
	if (mail) 
	{
		$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. Dodał:  ', zalogowany='$wybrany_login', id_usera='$id_usera'");
		$sqlUpdate = mysql_query("UPDATE klienci SET oznaczenie='wyslano', oznaczenie_data='$data', oznaczenie_data_dodania=NOW() WHERE id='$id_usera'");
	}

	echo '<br><br>
	<table class="table table-hover table-bordered">
		<tr>
			<td>Wysłano wiadomość do:</td>
		</tr>
		<tr class="active">
	 		<td>'.$user.' / '.$email. '  / '. $data.'<br></td>
	 	</tr>	
		<tr>
			<td>
				<a class="btn btn-success " type="submit" href="edytuj_usera.php?id='.$id_usera.'&oznaczenieWyslano">Wróć do usera</a>
			</td>
		</tr>
	</table>';
}


function wiadomosc ($user, $data, $usluga )
{
	//treść wiadomości wysyłanej do klienta
			return '<!DOCTYPE HTML>
						<html>
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<style type="text/css">
						div, span, p, a, font, img, strong, u, i, table, tbody, tr, th, td {
							margin: 0;
							padding: 0;
							border: 0;
							background: transparent;
						}
						body,td,th {
							font-family: Calibri;
							font-size: 15px;
						}
						body {
							margin-left: 0px;
							margin-top: 0px;
							margin-right: 0px;
							margin-bottom: 0px;
						}
						.center {
							margin: 0px;
							padding-top: 50px;
							padding-right: 0px;
							padding-bottom: 0px;
							padding-left: 100px;
						}
						</style>
						</head>
						    <body>
						    <div class="center">
						      <p>Witamy,	</p>
						      <p>&nbsp;</p>
						      <p>W wyniku błędnie działającego programu monitorującego utrzymanie plików klientów na serwerze cześć osób nie otrzymała wiadomości o terminie opłaty. </p>
						      <p>Nie mamy pewności kto taką wiadomość otrzymał a kto nie chiał już dokonywać opłaty i aby nie dokonować błędnego wykasowania plików przesyłamy wiadomość. </p>
						      <p>Jeśli wcześniej otrzymali Państwo wiadomość i postanowili nie przedłużać usługi wtedy prosimy o zignorowanie tej wiadomości. </p>
						      <p>W innym wypadku prosimy o sprawdzenie i ewentualną opłatę lub kontakt.</p>
						      <p>&nbsp;</p>
						      <p>&nbsp;</p>
						      <p>Informacje </p>
						      <p>Państwa usługa to: '.$usluga.'</p>
						      <p>Opłacona  była do dnia: '.$data.'</p>
						      <p>&nbsp;</p>
						      <p>Szablon Allegro --- 30 zł (365 dni)</p>
						      <p>N1 --- 15 zł na 180 dni lub 25 zł na rok</p>
						      <p>N2 --- 25 zł na 180 dni lub 39 zł na rok</p>
						      <p>N3 --- 40 zł na 180 dni lub 69 zł na rok</p>
						      <p>N4 --- 80 zł na 180 dni lub 150 zł na rok</p>
						      <p>N5 --- 160 zł na 180 dni lub 299 zł na rok</p>
						      <p>N6 --- 300 zł na 180 dni lub 499 zł na rok</p>
						      <p>&nbsp;</p>
						      <p>Konto zostało zablokowane (co wcześniej powinien zrobić system).<br>
						        Po przesłaniu tej wiadomości odczekamy 7 dni na wpłatę lub kontakt po czy przy jej braku wykasujemy pliki - ponowne przywrócenie konta wraz z plikami to dodatkowy koszt 25 zł.	  <br>
						        <br>
						        Prosimy o dokonanie wpłaty na dane:<br>
						        Sellhelp Konrad Kamiński
						        <br>
						        246 Conway Crescent 
						        <br>
						        UB6 8JG Greenford
						        Londyn
						        
						        <br>
						        <br>
					          <strong>Numer konta bankowego:	  </strong></p>
						      <p><strong>19 1090 2776 0000 0001 0687 1550 - Bank Zachodni WBK </strong><br>
					          Jako tytuł wpłaty proszę podać '.$user.' /  '.$usluga.'</p>
						      <p>Kwota: kwotę proszę pobrać zależnie od państwa usługi i wybranego czasu.</p>
						      <p><br>
						        Uwaga, podanie innego tytułu wpłaty lub nie podanie żadnego może uniemożliwić dokonanie ustalenia za co jest płatność i wtedy nie zostanie ona poprawnie oznaczona w systemie.
						        <br>
						        Jeśli wiadomość została wysłana mimo uiszczenia opłaty prosimy o kontakt celem wyjaśnienia - sprawdzimy i zapiszemy poprawnie.
						        
						        <br>
						        <br>
						        Kontakt z obsługą:
					          email - alle@cayman23.pl	  </p>
						      <p>Pozdrawiamy serdecznie </p>
						    </div>
							<body>
						</body>
						</html>';
}


require_once ('footer.php');
?>
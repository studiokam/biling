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

// zmienne tematów do pola tytuł (poprawne kodowanie polskich znaków)
$temat= "=?UTF-8?B?".base64_encode("Przypomnienie o abonamencie za serwer. Ostatni dzień.")."?=";
$tematAdmin= "=?UTF-8?B?".base64_encode("Przypomnienie o abonamencie za serwer. Ostatni dzień. (kopia).")."?=";
$temat15= "=?UTF-8?B?".base64_encode("Przypomnienie o abonamencie za serwer. Koniec abonamentu za 15 dni.")."?=";
$temat15Admin= "=?UTF-8?B?".base64_encode("Przypomnienie o abonamencie za serwer. Koniec abonamentu za 15 dni.")."?=";

// zmienne tematów do zapytań (logi) aby nie wstawialo z tymi znakami specjalnymi
$tematLogi = "Przypomnienie o abonamencie za serwer. Ostatni dzień.";
$tematAdminLogi = "Przypomnienie o abonamencie za serwer. Ostatni dzień. (kopia).";
$temat15Logi = "Przypomnienie o abonamencie za serwer. Koniec abonamentu za 15 dni.";
$temat15AdminLogi = "Przypomnienie o abonamencie za serwer. Koniec abonamentu za 15 dni.";


$emailAdmin = 'alle@cayman23.pl';
$headers = 'From: alle@cayman23.pl'."\r\n" .'Content-type: text/html; charset=utf-8';

$data = date("Y-m-d H:i:s");
$dataUnix = strtotime($data);
$dataUnixPlusDoba = $dataUnix + (60*60*24);
$dataDodania = $data;

$tresc_zapytania = "SELECT * FROM klienci WHERE do_kiedy_data < $dataUnix AND do_kiedy_data > 1";
//$tresc_zapytania = "SELECT * FROM klienci WHERE do_kiedy_data > $dataUnix AND do_kiedy_data < $dataUnixPlusDoba";
$zapytanie = mysql_query($tresc_zapytania);
$wiersze = mysql_num_rows($zapytanie);


function wiadomosc ($user, $data, $infoUslugi, $usluga )
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
						      <p>Witamy	</p>
						      <p>&nbsp;</p>
						      <p>Przypominamy, że Pana/Pani usługa hostingowa przypisana do konta '.$user.' jest opłacona do dnia: '.date("<b>Y.m.d</b>", $data).' (Ostatni dzień). <br>
						        Jeśli otrzymał Pan/Pani tą wiadomość, oznacza to, że nie zaksięgowaliśmy wpłaty i prosimy o realizację przelewu za kolejny okres rozliczeniowy.
						        <br>
						        Do końca okresu pozostał 1 dzień - w dniu jutrzejszym usługa zostanie wyłączona. Po wyłączeniu usługi możliwe będzie jeszcze jej przywrócenie przez okres 14 dni. 
						        <br>
						        Po tym okresie wszystkie pliki zostaną wykasowane a konto usunięte z systemu - ponowne przywrócenie konta wraz z plikami to dodatkowy koszt 25 zł.	  </p>
						      <p><br>
						        Państwa usługa oznaczona jest jako:<br>
						        '.$infoUslugi.'<br>
						        Prosimy o dokonanie wpłaty na dane:<br>
						        STUDIOKAM Małgorzata Kamińska
						        <br>
						        ul. Fabryczna 8/14 
						        <br>
						        16-424 Filipów
						        
						        <br>
						        <br>
						      <strong>Numer konta bankowego:	  </strong></p>
						      <p><strong>44 1140 2004 0000 3502 7683 8878 - mBank </strong><br>
						        Jako tytuł wpłaty proszę podać '.$user.' /  '.$usluga.'</p>
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



if ($wiersze > 0) 
{
echo '<br><br>
	<table class="table table-hover table-bordered">
			<tr>
				<td>
					Wysłano wiadomość do:
				</td>
			</tr>
		';

	foreach ($dbConnect->query("SELECT user,email,usluga,do_kiedy_data,id  FROM klienci WHERE do_kiedy_data < $dataUnix AND do_kiedy_data > 1") as $k) 
	{
		$szukajN1 = 'N1';
		$szukajN2 = 'N2';
		$szukajN3 = 'N3';
		$szukajN4 = 'N4';
		$szukajN5 = 'N5';
		$szukajN6 = 'N6';
		$szukajSzablonAllegro = 'Szablon Allegro';

		//sprawdzanie jaką uslugę ma dany user
		if (in_array($szukajN1, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajN1, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}

		elseif (in_array($szukajN2, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajN2, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}




		elseif (in_array($szukajN3, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajN3, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}


		elseif (in_array($szukajN4, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajN4, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}


		elseif (in_array($szukajN5, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajN5, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}


		elseif (in_array($szukajN6, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajN6, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}


		elseif (in_array($szukajSzablonAllegro, $k)) 
		{
			//wybranie z bazy opisu danej uslugi (cena)
			foreach ($dbConnect->query("SELECT opis,usluga FROM uslugi") as $s) 
			{
				if (in_array($szukajSzablonAllegro, $s)) 
				{
					$infoUslugi = $s[0];
				}
			}

			$user = $k[0]; 
			$email = $k[1];
			$usluga = $k[2];
			$data = $k[3];
			$idUsera = $k[4];
			
			mail($email, $temat, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // wysłanie wiadomosci
			mail($emailAdmin, $tematAdmin, wiadomosc ($user, $data, $infoUslugi, $usluga), $headers); // dodatkowy email do admina

			// dodanie logow do bazy
			if (mail) 
			{
				$sql = mysql_query("INSERT INTO logi SET tresc='Wysłano wiadomość do usera $user. Temat: $tematLogi. <br>Zmieniono datę na BRAK, data ostatniej uslugi: $dataNormalnyFormat. <br>Dodał:  ', zalogowany='automat', id_usera='$idUsera'");
				$sqlUpdate = mysql_query("UPDATE klienci SET do_kiedy_data=0 WHERE id='$idUsera'");
				$sqlDataOstatniejUslugi = mysql_query("UPDATE klienci SET data_ostatniej_uslugi='$data' WHERE id='$idUsera'");
			}
			echo '
				<tr class="active">
	 				<td> '.$usluga.' / '.$user.' / '.$email.'<br></td>
	 			</tr>';
		}
	}
echo '<tr>
				<td>
					<a class="btn btn-success " type="submit" href="ustawienia.php">Przejdź do ustawień</a>
				</td>
			</tr>
		</table>
	';
}

// jesli brak wyników dodanie do logów info o braku danego dnia
else 
{
	echo "<br><br>";
	echo '
		<table class="table table-hover table-bordered">
	 		<tr class="active">
	 			<td>Dnia <b>('.$dataDodania.')</b> nie było userów spełniających warunek ostatniego dnia abonamentu.</td>
	 		</tr>
	 		<tr>
				<td>
					<a class="btn btn-success " type="submit" href="ustawienia.php">Przejdź do ustawień</a>
				</td>
			</tr>
		</table>
	';
	mysql_query("INSERT INTO logi SET tresc='Dnia <b>($data)</b> nie było userów spełniających warunek ostatniego dnia abonamentu. Dodał:  ', zalogowany='automat'");
}

mysql_query("INSERT INTO logi SET tresc='SPRAWDZENIEpoBŁĘDZIE', zalogowany=' - ręcznie'");

require_once ('footer.php');
?>
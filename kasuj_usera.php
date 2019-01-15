<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');

$kasuj_id = $_GET['id'];

$sth = $dbPDO->prepare("SELECT * FROM klienci WHERE id='$kasuj_id'");
$sth->execute();
$row = $sth->fetch(PDO::FETCH_ASSOC);

$user = $row['user'];
$email = $row['email'];
$sciezka = $row['sciezka'];
$usluga = $row['usluga'];





// sprawdzenie czy miał abonament przed kasowaniem
if ($row['do_kiedy_data'] > 1) {

	$do_kiedy = date("Y.m.d H:i:s", $row["do_kiedy_data"]);
}
else{

	$do_kiedy = 'W tym czasie nie miał dostępnego abonamentu';
}

$logTresc = 'Wykasowano usera o nicku: '.$user.'. Mial on w danej chwili parametry: </br>(email: '.$email.'), (sciezka: '.$sciezka.'), (usluga: '.$usluga.'), (data wygasania uslugi: '.$do_kiedy.'). Wykasowal:  ';


require_once('menu.php'); 


// sprawdzenie czy potwierdzone kasowanie
if (isset($_GET['kasuj']) AND $_GET['kasuj'] == 'yes') {
		
	// wykasowanie usera z bazy
	$dbPDO->query("DELETE FROM klienci WHERE id='$kasuj_id'");

	// dodanie logu o wykasowaniu
	$stmt = $dbPDO->prepare("INSERT INTO logi(tresc, zalogowany) VALUES (:tresc, :zalogowany)");
	$stmt->bindValue (':tresc', $logTresc);
	$stmt->bindParam (':zalogowany', $wybrany_login);
	$stmt->execute();

	header('Location: zalogowany.php?user_wykasowany');
}
else{
	echo '<div class="alert alert-danger" role="alert"> Czy na pewno chcesz wykasować klienta <b>'.$row["user"].'</b>'.'?</div>';
	echo '<a class="btn btn-danger" type="submit" href="kasuj_usera.php?id='.$kasuj_id.'&kasuj=yes">Tak, kasuj tego klienta z bilingu</a>';
}


require_once ('footer.php');
?>
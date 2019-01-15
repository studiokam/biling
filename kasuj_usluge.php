<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('config.php');
require_once ('top.php');


$id_uslugi = $_GET ['id'];


$sth = $dbPDO->prepare ("SELECT * FROM uslugi WHERE id='$id_uslugi'");
$sth->execute();
$row = $sth->fetch(PDO::FETCH_ASSOC);

$poprzedni_nazwa_uslugi = $row['usluga'];
$poprzedni_zapis_uslugi = $row['opis'];

$tresc = 'Wykasowano usługę: '.$poprzedni_nazwa_uslugi.'. Zapis opisu usługi '.$poprzedni_nazwa_uslugi.' w bazie przed wykasowaniem: '.$poprzedni_zapis_uslugi.'.  Wykasował:  ';


require_once ('menu.php'); 

if (isset($_GET['kasujUsluge']) AND $_GET['kasujUsluge'] == 'yes') {
	
	// wykasowanie uslugi z bazy
	$dbPDO->query("DELETE FROM uslugi WHERE id='$id_uslugi'");

	$stmt = $dbPDO->prepare("INSERT INTO logi(tresc, zalogowany) VALUES (:tresc, :zalogowany)");
	$stmt->bindParam (':tresc', $tresc);
	$stmt->bindParam (':zalogowany', $wybrany_login);
	$stmt->execute();

	header('Location: uslugi.php?usunieto');


}
else{

	echo '<div class="alert alert-danger" role="alert">Czy na pewno chcesz wykasować usługę <b>'. $row['usluga'] . '</b>?</div>';
	echo '<a type="button" class="btn btn-danger" href="kasuj_usluge.php?id='.$id_uslugi.'&kasujUsluge=yes">Tak usuń tą usługę!</a>';
}

require_once ('footer.php');
?>
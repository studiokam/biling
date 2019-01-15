<?php 

$katalog = "./"; 
$plik = 'spakowany_plik_'.date("Y_m_d_H_i_s").'.zip'; 
     
ini_set("max_execution_time", 300); 
$zip = new ZipArchive(); 
if ($zip->open($plik, ZIPARCHIVE::CREATE) !== TRUE) { 
die ("Nie mogę stworzyć archiwum"); 
}	 
     
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($katalog)); 
foreach ($iterator as $key=>$value) { 
$zip->addFile(realpath($key), $key) or die ("ERROR: brak plików: $key"); 
} 
$zip->close(); 	

// dodanie logu o kopii
mysql_query("INSERT INTO logi SET tresc='Poprawnie wykonano i zapisano kopię plików. Dodał:  ', zalogowany='automat'");

header('Location:ustawienia.php?poprawnyBackupPlikow');

?>
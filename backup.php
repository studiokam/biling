<?php 

header('Content-type: text/html; charset=utf8');
require_once ('config.php');


try{

    // KONFIGURACJA - START (przypisanie nazw zrobiłem powielone aby działao na localhost i serwerze, ustawienie jest w config.php)
    $dbName = $db_name; // Nazwa bazy danych do zbackupowania
    $dbHost = $host; // Nazwa serwera baz danych
    $dbUser = $db_user; // Nazwa użytkownika
    $dbPass = $db_pass; // Hasło

    $backupsDir = 'backups'; // Katalog do którego będą zapisywane backupy. Bez końcowego ukośnika!
    // KONFIGURACJA - STOP
    // Dalej lepiej nie ruszać!

    // Stworzenie nowego obiektu klasy PDO
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlResult = $pdo -> query("SHOW tables FROM $dbName");

    // Stworzenie nagłówka informacyjnego
    $sqlData = "-- Data wykonania kopii: ".date('d.m.Y')." r. o godzinie ".date('H:i')."
    -- Baza: $dbName
    SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";";

    while ($queryTable = $sqlResult -> fetch(PDO::FETCH_ASSOC)){
        $sqlTable = $queryTable['Tables_in_'.$dbName];
        $sqlResultB = $pdo -> query("SHOW CREATE TABLE $sqlTable");
        $queryTableInfo = $sqlResultB -> fetch(PDO::FETCH_ASSOC);

        // Dodanie nagłówków dla konkretnych tabel
        $sqlData .= "\n\n--
        -- Struktura dla tabeli `$sqlTable`
        --\n\n";
        $sqlData .= $queryTableInfo['Create Table'] . ";\n";
        $sqlData .= "\n\n--
        -- Wartości tabeli `$sqlTable`
        --\n\n";

        $sqlResultC = $pdo -> query("SELECT * FROM $sqlTable");

        // Stworzenie INSERT-a dla każdego rekordu
        while ($queryRecord = $sqlResultC -> fetch(PDO::FETCH_ASSOC)) {
            $sqlData .= "INSERT INTO `$sqlTable` VALUES (";
            $sqlRecord = '';
            foreach( $queryRecord as $sqlField => $sqlValue ) {
                $sqlRecord .= "'$sqlValue',";
            }
            $sqlData .= substr($sqlRecord, 0, -1);
            $sqlData .= ");\n";
        }
    }

    // Zapisujemy wynik do pliku
    file_put_contents($backupsDir.'/backup_'.$dbName.'_'.date('d_m_Y___H_i_s').'.sql', $sqlData);
    //dodanie logu o zrobionej kopii
    mysql_query("INSERT INTO logi SET tresc='Poprawnie wykonano i zapisano kopię bazy danych. Dodał:  ', zalogowany='automat'");
    header('Location:ustawienia.php?bakup_ok');
}

catch(PDOException $e){
    echo 'Połączenie nie mogło zostać utworzone: '.$e->getMessage();
}
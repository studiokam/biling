<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');


$edytuj_id = $_GET['id'];


// wyswietlenie wynikow tabeli pytania
$query  = "SELECT * FROM klienci WHERE id='$edytuj_id'";
$result = mysql_query($query)
    or die("Query failed");
$row = mysql_fetch_array($result);
$notatka = $row['notatka'];
require_once('menu.php');

echo '  
	
		<table class="table table-hover table-bordered">
			<tr>
				<td class="col-md-2">Id w bazie danych</td>
				<td class="col-md-10">'.$row["id"].'</td>
			</tr>
 			<tr>
				<td>Nazwa usera</td>
				<td>'.$row["user"].'</td>
			</tr>
			<tr>
				<td>Usługa</td>
				<td>'.$row["usluga"].'</td>
			</tr>
			<tr>
				<td>Scieżka</td>
				<td><a href="'.$row["sciezka"].'" target="_blank">'.$row["sciezka"].'</a></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td>'.$row["email"].'</td>
			</tr>
			<tr>
				<td>Dodał</td>
				<td>'.$row["dodal"].'</td>
			</tr>
			<tr>
				<td>Data dodania</td>
				<td>'.date("Y.m.d",strtotime($row['dodano_data'])).'</td>
			</tr>
			<tr>
				<td>Data do kiedy</td>
				<td>';
					  if ($row['do_kiedy_data'] > 1) 
					  {
					  	echo date("Y.m.d", $row["do_kiedy_data"]);
					  	
					  }
					  else
					  {
					  	echo "Brak";
					  }
					  echo '</td>

			</tr>
			<tr>
				<td>Dni do końca</td>
				<td>';

					if ($row['do_kiedy_data'] > 1) 
					  {
					  	$dkd = $row['do_kiedy_data'];
					  	$data = date("Y-m-d");
						$dataUnix = strtotime($data);

						// odejmowanie dni - pokazuje ile dni zostalo
						$roznicaDat = $dkd - $dataUnix; 
						$ileDniZostalo = $roznicaDat/60/60/24;
					  	echo round($ileDniZostalo);
					  }
					  else 
					  {
					  	echo "Brak";
					  }


				echo '</td>
			</tr>
			<tr>
				<td>Data ostatniej usługi</td>
				<td>';

					if ($row['data_ostatniej_uslugi'] > 1) 
					  {
					  	
					  	echo date("Y-m-d H:i:s", $row["data_ostatniej_uslugi"]);
					  }
					  else 
					  {
					  	echo "Brak informacji";
					  }


				echo '</td>
			</tr>
			<tr>
				<td>Data ostatniej usługi (zaległy)</td>
				<td>'.$row["oznaczenie_data"].'</td>
			</tr>
		</table>
	';		
?>

<a class="btn btn-success " type="submit" href="zalogowany.php?sort_user=asc">Wróc</a>
<a class="btn btn-success " type="submit" href="edytuj_usera.php?id=<?php echo $edytuj_id; ?>">Edytuj</a>

<?php  
	if (isset($_GET['zablokowanydel'])) 
	{
		$sql_usun_oznaczenie_zablokowany  = "UPDATE klienci SET zablokowany='' WHERE id='$edytuj_id'";
		$wykonaj = mysql_query($sql_usun_oznaczenie_zablokowany);
		echo " Wykonano poprawnie ";
	}
	if (isset($_GET['zablokowany'])) 
	{
		$sql_usun_oznaczenie_zablokowany  = "UPDATE klienci SET zablokowany='tak' WHERE id='$edytuj_id'";
		$wykonaj = mysql_query($sql_usun_oznaczenie_zablokowany);
		echo " Wykonano poprawnie ";
	}

	if ($row['do_kiedy_data'] == 0) 
		{
			if ($row['zablokowany'] == 'tak') 
			{
				echo '<a class="btn btn-info " type="submit" href="zobacz_usera.php?zablokowanydel=tak&id='.$edytuj_id.'">Usuń oznaczenie o zablokowaniu</a>';
			}
			else 
			{
				echo '<a class="btn btn-warning " type="submit" href="zobacz_usera.php?zablokowany=tak&id='.$edytuj_id.'">Oznacz jako zablokowany</a>';
			}
			
		}
		
?>

<a class="btn btn-danger " type="submit" href="kasuj_usera.php?id=<?php echo $edytuj_id; ?>">Usuń</a>
</br></br>



<?php 

$query_zakupy  = "SELECT * FROM zakupy WHERE id_usera='$edytuj_id'";
$result_zakupy = mysql_query($query_zakupy)
    or die("Query failed");
//$row_zakupy = mysql_fetch_array($result_zakupy);


$query_logi = "SELECT * FROM logi WHERE id_usera='$edytuj_id'";
$result_logi = mysql_query($query_logi)
	or die("Query failed");


echo '<table class="table table-hover table-bordered">
		<tr class="active">
			<td colspan="2">Notatki</td>
		</tr>
		<tr>
			<td  class="col-md-2">'.$notatka.'</td>
		</tr>
	</table>
<a class="btn btn-success " type="submit" href="edytuj_notatke.php?id='.$edytuj_id.'">Edytuj notatkę</a>
</br></br>
	';



echo '<table class="table table-hover table-bordered">
		<tr class="active">
	 		<td colspan="2">Zakupy /  Dodawanie abonamentu  /  Dokonywanie zmian</td>
	 	</tr>
';
while ($row_zakupy = mysql_fetch_array($result_zakupy))
	{
		
		echo '  
			
					<tr>
						<td class="col-md-2">'.$row_zakupy["data_zakupu"].'</td>
						<td class="col-md-10">Dodano dni: <b> '.$row_zakupy["ile_dni"].' </b>. ' ;
						

						if ($row_zakupy['data_wygasania_przed_zakupem'] > 1) 
							{
								echo'Data wygasania przed dodaniem to: '. date("Y.m.d H:i:s", $row_zakupy["data_wygasania_przed_zakupem"]). ', / było wtedy '.$row_zakupy["ilosc_dni_przed_zakupem"].' do końca abonamentu.' ;

							}
							else
							{
								echo "Przed dodaniem nie było abonamentu.";
							}

						echo 
						' Osoba dodająca: '.$row_zakupy["kto_dodal"].'</td>
					</tr>
		 			
				';	

	}	
echo '</table>';

echo '<table class="table table-hover table-bordered">
		<tr class="active">
			<td colspan="2">Logi usera</td>
		</tr>
		<tr>
			<td  class="col-md-2">Data dodania wpisu</td>
			<td  class="col-md-10">Logi usera</td>
		</tr>';

		while ($row_logi = mysql_fetch_array($result_logi)) 
		{
			echo '
				<tr>
					<td>'.date("Y.m.d H:i:s",strtotime($row_logi['data_wpisu'])).'</td>
					<td>'.$row_logi["tresc"].'<b>'.$row_logi["zalogowany"].'</b></td>
				</tr>';
		}
		echo '  </table>';
 ?>


<?php
require_once ('footer.php');
 ?>
<?php 


// info o usunieciu usera
if (isset($_GET['user_wykasowany'])) 
	echo '<div class="alert alert-success" role="alert">User został wykasowany z bazy</div>';

// info o poprawnym dodaniu nowego usera do bazy
if (isset($_GET['dodano_usera'])) 
	echo '<div class="alert alert-success" role="alert">Nowy user został dodany poprawnie do bilingu</div>';

// info o zmianie rekordu
if (isset($_GET['poprawna_edycja_usera'])) 
	echo '<div class="alert alert-success" role="alert">Rekord został zmieniony poprawnie</div>';

// info o dodaniu dni (abonamentu)
if (isset($_GET['poprawnie_dodano_dni'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie dodano dni abonamentu dla edytowanego usera</div>';

// info o zmianie data (abonamentu)
if (isset($_GET['poprawnie_zmieniono_date'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie zmieniono datę abonamentu dla edytowanego usera</div>';


// sortowanie usera
if (isset($_GET['sort_user'])) 
	{
		if ($_GET['sort_user']==='asc') {
			$sortowanie_by = 'user asc';
		}
		else {
			$sortowanie_by = 'user desc';
		}
	}
// tu jest błąd do poprawy bo sprawdza czy nie istnieje sort_user ale przy sortowaniu po email tez nie ma tego sort_user i podswietla pierwszy
if (!isset($_GET['sort_user'])) {
		
	$sortowanie_by = 'user asc';
}



// sortowanie uslugi
if (isset($_GET['sort_usluga'])) 
	{
		if ($_GET['sort_usluga']==='asc') {
			$sortowanie_by = 'usluga asc';
		}
		else {
			$sortowanie_by = 'usluga desc';
		}
	}

// sortowanie po adresie email
if (isset($_GET['sort_email'])) 
	{
		if ($_GET['sort_email']==='asc') {
			$sortowanie_by = 'email asc';
		}
		else {
			$sortowanie_by = 'email desc';
		}
	}

// sortowanie po dacie dodania
if (isset($_GET['sort_dodano_data'])) 
	{
		if ($_GET['sort_dodano_data']==='asc') {
			$sortowanie_by = 'dodano_data asc';
		}
		else {
			$sortowanie_by = 'dodano_data desc';
		}
	}

// sortowanie po dacie do końca
if (isset($_GET['sort_do_kiedy_data'])) 
	{
		if ($_GET['sort_do_kiedy_data']==='asc') {
			$sortowanie_by = 'do_kiedy_data asc';
		}
		else {
			$sortowanie_by = 'do_kiedy_data desc';
		}
	}

// sortowanie po ilości dni do końca
if (isset($_GET['sort_dni_do_konca'])) 
	{
		if ($_GET['sort_dni_do_konca']==='asc') {
			$sortowanie_by = 'do_kiedy_data asc';
		}
		else {
			$sortowanie_by = 'do_kiedy_data desc';
		}
	}

if (isset($_GET['wyszukaj'])) 
{
	$szukane = $_GET['wyszukaj'];
	$query  = "SELECT * FROM klienci WHERE user LIKE '$szukane%'";
}
else
{
	$query  = "SELECT * FROM klienci ORDER BY $sortowanie_by ";
}

if (isset($_GET['oznaczeni'])) 
{
	$query = "SELECT * FROM klienci WHERE do_kiedy_data = 0 AND zablokowany!='tak' AND oznaczenie!='wyslano'";
}

if (isset($_GET['oznaczeni_7'])) 
{
	$query = "SELECT * FROM klienci WHERE do_kiedy_data < 1 AND oznaczenie!='wyslano'  ORDER BY oznaczenie='wyslano' desc";
}

if (isset($_GET['oznaczeni_all'])) 
{
	$query = "SELECT * FROM klienci WHERE oznaczenie='wyslano' ";
}


$result = mysql_query($query)
    or die("Query failed");




function asc ($sort)
{
	if (isset($_GET[''.$sort.'']) AND $_GET[''.$sort.'']==='asc') 
	{ echo '<a class="btn btn-success btn-xs" type="submit" href="zalogowany.php?'.$sort.'=asc"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a>'; } 
		
	else
	{ echo '<a class="btn btn-default btn-xs" type="submit" href="zalogowany.php?'.$sort.'=asc"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a>'; } 
}

function desc ($sort)
{
	if (isset($_GET[''.$sort.'']) AND $_GET[''.$sort.'']==='desc') 
	{ echo '<a class="btn btn-success btn-xs" type="submit" href="zalogowany.php?'.$sort.'=desc"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>'; } 
		
	else
	{ echo '<a class="btn btn-default btn-xs" type="submit" href="zalogowany.php?'.$sort.'=desc"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>'; } 
}


?>


<table class="table table-hover table-bordered">
			
				<tr class="active">
				  <td>Nazwa usera (<?php echo $iloscWszystkichUserow; ?>)</td>
				  <td>Usługa</td>
				  <td>E-mail</td>
				  <td>Dodał</td>
				  <td>Data dodania</td>
				  <td>Data do kiedy</td>
				  <td>Dni do końca</td>
				  <td><span class="glyphicon glyphicon-pencil" aria-hidden="true" alt="Status"></span></td>
				  <td>Opcje</td>
				</tr>

				<tr>
				  <td><?php asc("sort_user"); desc("sort_user"); ?></td>
				  <td><?php asc("sort_usluga"); desc("sort_usluga"); ?></td>
				  <td><?php asc("sort_email"); desc("sort_email"); ?></td>
				  <td></td>
				  <td><?php asc("sort_dodano_data"); desc("sort_dodano_data"); ?></td>
				  <td><?php asc("sort_do_kiedy_data"); desc("sort_do_kiedy_data"); ?></td>
				  <td><?php asc("sort_dni_do_konca"); desc("sort_dni_do_konca"); ?></td>
				  <td></td>
				  <td></td>
				</tr>

<?php  
while ($row = mysql_fetch_array($result)) {
	
	
	// obliczenie ile dni minelo od wyslania wiadomosci przy braku abonamentu
	$data = date("Y-m-d H:i:s");
	$dataUnix = strtotime($data);

	$oznaczenie_data = $row['oznaczenie_data_dodania'];
	$ozDataUnix = strtotime($oznaczenie_data);

	$doba = 60*60*24;

	$ile_dni_minelo_od_wyslania_zalegly = ($dataUnix - $ozDataUnix)/ $doba;

	// obliczenie ile dni minelo od wyslania wiadomosci (miał abonament)
	$data_ostatniej_uslugi = $row['data_ostatniej_uslugi'];

	$ile_dni_minelo_od_wyslania = ($dataUnix - $data_ostatniej_uslugi)/ $doba;


	echo '  
 				<tr';
					if ($row['oznaczenie'] == 'tak') 
					{
						echo ' class="info"';
					}
					if ($row['oznaczenie'] == 'wyslano') 
					{
						echo ' class="danger"';
					}
					if ($row['do_kiedy_data'] < 1) 
					{
						echo ' class="warning"';
					}
 			echo'>';
				 
			echo'	  
				  <td>'.$row["user"].'</td>
				  <td>'.$row["usluga"].'</td>
				  
				  <td>'.$row["email"].'</td>
				  <td>'.$row["dodal"].'</td>
				  <td>'.date("<b>Y.m.d</b> H:i:s",strtotime($row['dodano_data'])).'</td>
				  <td>';
					  if ($row['do_kiedy_data'] > 1) 
					  {
					  	echo date("<b>Y.m.d</b> H:i:s", $row["do_kiedy_data"]);
					  	echo '</td><td>';
					  }
					  else
					  {
					  	echo "Brak";
					  	echo '</td><td>';
					  }

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
				  <td>
					';
					if ($row['oznaczenie'] == 'wyslano') 
					{
						echo round ($ile_dni_minelo_od_wyslania_zalegly);
					}
					if (($row['do_kiedy_data'] < 1) && ($row['oznaczenie'] !== 'wyslano'))
					{
						//echo round ($ile_dni_minelo_od_wyslania_zalegly);
						echo round ($ile_dni_minelo_od_wyslania);
						if ($row['zablokowany'] == 'tak') 
						{
							echo ' z';
						}
					}

				  echo '</td>
				  <td>
					<form class="pull-right">
					    <a class="btn btn-success btn-xs" type="submit" href="zobacz_usera.php?id='.$row['id'].'">Zobacz</a>
					    <a class="btn btn-success btn-xs" type="submit" href="edytuj_usera.php?id='.$row['id'].'">Edytuj</a>
					    <a class="btn btn-danger btn-xs" type="submit" href="kasuj_usera.php?id='.$row['id'].'">Kasuj</a>
					</form>
				  </td>
				</tr>
			';
			
}
echo '  </table></br>';

echo '
		<table>
			<td>
				<tr>Legenda kolorów: </br>Żółty (brak abonamentu), </br>Czerwony (Wysłano wiadomość do oznaczonego usera), </br>Niebieski (User oznaczony, ozacza to, że przy dodawaniu nie miał opłaconego a używa już uslugi)</tr>
			</td>
		</table>';


?>
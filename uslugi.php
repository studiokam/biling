<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');



?>

<div class="lewa">
	<?php 
		require_once('menu.php');
	 ?>
</div>


<?php 
// usunieto usługę
if (isset($_GET['usunieto'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie usunięto usługę</div>';

// dodano usługę
if (isset($_GET['dodano_usluge'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie dodano nową usługę</div>';

// edytowano usługę poprawnie
if (isset($_GET['poprawna_edycja'])) 
	echo '<div class="alert alert-success" role="alert">Poprawnie zapisano edytowaną usługę</div>';

// jest juz taka nazwa uslugi - nazwy musza byc unikalne
if (isset($_GET['jest_podana'])) 
	echo '<div class="alert alert-danger" role="alert">Uwaga - usługa nie została dodana ponieważ w bazie znajduje się taka nazwa usługi. Nazwa musi być unikalna.</div>';

// nie podano danych w polach
if (isset($_GET['brak_danych'])) 
	echo '<div class="alert alert-danger" role="alert">Uwaga - usługa nie została dodana - nie wszystkie pola zostały wypełnione</div>';

// nie podano danych w polach podczas edycji
if (isset($_GET['edycja_brak_danych'])) 
	echo '<div class="alert alert-danger" role="alert">Uwaga - usługa nie została zmieniona - nie wszystkie pola zostały wypełnione</div>';



 ?>

<div class="prawa">

<div class="row">
	<div class="col-sm-6">
		
<?php 

// wyświetlenie listy usług

$query  = "SELECT * FROM uslugi ORDER BY usluga asc";
$result = mysql_query($query)
    or die("Query failed");


echo '  <table class="table table-hover table-bordered">
			<tr class="active">
				  
				  <td>Usługa</td>
				  <td>Opis</td>
				  <td><p class="pull-right">Opcje</p></td>
				</tr>
';
while ($row = mysql_fetch_array($result)) {

	
	echo '  
 				<tr>
				  
				  <td>'.$row["usluga"].'</td>
				  <td>'.substr($row['opis'], 0, 27).' ...'.'</td>
				  <td>
					<form class="pull-right">
					    <a class="btn btn-success btn-xs" type="submit" href="zobacz_usluge.php?id='.$row['id'].'">Zobacz</a>
					    <a class="btn btn-success btn-xs" type="submit" href="edytuj_usluge.php?id='.$row['id'].'">Edytuj</a>
					    <a class="btn btn-danger btn-xs" type="submit" href="kasuj_usluge.php?id='.$row['id'].'">Kasuj</a>
					</form>
				  </td>
				</tr>
			';
			
}
echo '  </table>';
 ?>

	</div>
	<div class="col-md-6">
		<div class="alert alert-success" role="alert">Dodaj nową usługę</div> </br>
<form class="form-horizontal" action="usluga_in.php" method="post">
  <div class="form-group">
    <label class="col-md-2 control-label">Usługa</label>
    <div class="col-md-8">
      <input type="text" class="form-control" name="ad_usluga" placeholder="Nazwa usługi">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-md-2 control-label">Opis</label>
    <div class="col-md-8">
      <input type="text" class="form-control" name="ad_opis" placeholder="Opis danej usługi">
    </div>
  </div>

  
  <div class="form-group">
    <div class="col-md-offset-2 col-md-8">
      <button type="submit" class="btn btn-default">Dodaj</button>
    </div>
  </div>
</form>

	</div>
</div>


</div>
<div class="clearboth"></div>



<?php 
require_once ('footer.php');
 ?>

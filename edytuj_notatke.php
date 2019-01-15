<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');

require_once('menu.php');

$edytuj_id = $_GET['id'];


// wyswietlenie wynikow tabeli pytania
$query  = "SELECT * FROM klienci WHERE id='$edytuj_id'";
$result = mysql_query($query)
    or die("Query failed");
$row = mysql_fetch_array($result);

$notatka = $row['notatka'];
$user = $row['user'];



// jesli jest notatka - dodanie do bazy
if (isset($_POST['notatka'])) 
{
	$nowaNotatka = $_POST['notatka'];
	$updatenotatki = mysql_query("UPDATE klienci SET notatka='$nowaNotatka' WHERE id='$edytuj_id'");
	$sql = mysql_query("INSERT INTO logi SET tresc='Zaktualizowano notatkę dla $user. Dodano: $nowaNotatka , Dodał: ', zalogowany='$wybrany_login', id_usera='$edytuj_id'");
	echo '<div class="alert alert-success" role="alert">Uaktualniono notatkę.</div>';
}

 ?>






  <form class="form-horizontal" method="post">
    <div class="form-group">
      <label class="col-sm-1 control-label">Treść notatki</label>
      <div class="col-sm-11">
        <textarea class="form-control" name="notatka" rows="10" value="<?php echo $notatka; ?>"><?php 
				if (isset($_POST['notatka'])) 
				{
					echo $nowaNotatka;
				}
				else
				{
					echo $notatka;
				}
			?></textarea>
      </div>
    </div>
   
    
    
    <div class="form-group">
      <div class="col-sm-offset-1 col-sm-11">
        <button type="submit" class="btn btn-success">Zapisz</button>
      </div>
    </div>
  </form>

</br>

<?php
require_once ('footer.php');
 ?>
<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');

$wybrany_login = $_SESSION['login'];



$edytuj_id = $_GET['id'];
$query = "SELECT * FROM klienci WHERE id='$edytuj_id'";
$rezultat = mysql_query($query);
$row = mysql_fetch_assoc($rezultat);

$_SESSION['edytuj_id'] = $edytuj_id;


$zapytanie = "SELECT * FROM klienci WHERE id='$edytuj_id'"; 
$wynik = mysql_query($zapytanie);
$wiersz = mysql_fetch_array($wynik);

//$ddd = date("Y-m-d H:i:s", $wiersz['do_kiedy_data']);


$dkd = $wiersz['do_kiedy_data'];
$odkodowanaData = date("Y-m-d" , $dkd);

$data = date("Y-m-d H:i:s");
//$data = date("Y-m-d");
$dataUnix = strtotime($data);


// odejmowanie dni - pokazuje ile dni zostalo
$roznicaDat = $dkd - $dataUnix; 
$ileDniZostalo = $roznicaDat/60/60/24;

//data z pola oznaczenie
$oznaczenie_data = $wiersz['oznaczenie_data_dodania'];
$doba = 60*60*24;
$ozDataUnix = strtotime($oznaczenie_data);
$oz_roznica_dat = $dataUnix - $ozDataUnix;
$ozIloscDni = $oz_roznica_dat / $doba;
$oz_mineloDni = round($ozIloscDni, 0);

$tydzien = 60*60*24*7;


?>

<div class="lewa">
	<?php 
		require_once('menu.php');
    //błąd wypełnienia pól dodania usera
      if (isset($_GET['nie_podano_wszystkich_danych_edycja'])) 
          echo '<div class="alert alert-danger" role="alert">Nie wypełniono wszystkich wymaganych pól w polu edycji. Wymagane są: <b>Nazwa urzytkownika, E-mail, Dodaje </div>';
    
      // informacja o posiadanym abonamencie
        if ($dkd == 0 ) 
          {
             echo '<div class="alert alert-danger" role="alert">User nie posiada obecnie abonamentu</div>';
          }
        else {
          
        echo '<div class="alert alert-success" role="alert">User posiada obecnie abonament jeszcze przez <b>'.round($ileDniZostalo).' dni</b> tj. do dnia <b> '.$odkodowanaData.'</b></div>';
      }
	 ?>
</div>
<div class="prawa">

<?php 
 $oznaczenie = $wiersz['oznaczenie'];

  if (isset($_GET['oznacz'])) 
  {
    $updateoznacz = mysql_query("UPDATE klienci SET oznaczenie='tak' WHERE id='$edytuj_id'");
    $oznaczenie = 'tak';
  }
  if (isset($_GET['odznacz'])) 
  {
    $updateoznacz = mysql_query("UPDATE klienci SET oznaczenie='nie' WHERE id='$edytuj_id'");
    $oznaczenie = 'nie';
  }

  $sql_oznacz = mysql_query("SELECT oznacz FROM ustawienia");
  $wynik_oznacz = mysql_fetch_array($sql_oznacz);
  $oznacz = $wynik_oznacz['oznacz'];




  if ($oznacz == 'tak') 
  {
    if ($oznaczenie == 'tak') 
    {
    echo '
      <table class="table table-hover table-bordered">
        <tr class="active">
          <td>
            <a class="btn btn-warning " type="submit" href="edytuj_usera.php?id='.$edytuj_id.'&odznacz">Odznacz usera</a>
            <a class="btn btn-warning " type="submit" href="email_oznaczony.php?id='.$edytuj_id.'">Wyślij wiadomość z informacją</a>
          </td>
          <td>Odznacz zaznaczonogo usera lub wyślij wiadomość</td>
        </tr>
      
      </table>';
    }
    elseif ($oznaczenie == 'wyslano') 
    {
      echo '
      <table class="table table-hover table-bordered">
        <tr class="active">
          <td>
            <a class="btn btn-warning " type="submit" href="edytuj_usera.php?id='.$edytuj_id.'&odznacz">Odznacz usera</a>
          </td>
          <td>Odznacz zaznaczonogo usera lub wyślij wiadomość</td>
        </tr>
        <tr>';
        if ($oz_roznica_dat > $tydzien) 
        {
          echo '<td class="danger" colspan="2">Data wysłania przypomnienia: '.$oznaczenie_data.' ('.$oz_mineloDni.' dni). </br><b>Już ponad 7 dni.</b></td>';
        }
        else
        {
          echo '<td colspan="2">Data wysłania przypomnienia: '.$oznaczenie_data.' ('.$oz_mineloDni.' dni)</td>';
        }
          
        echo '</tr>
      </table>';
    }
    else
    {
    echo '
      <table class="table table-hover table-bordered">
        <tr class="active">
          <td>
            <a class="btn btn-danger " type="submit" href="edytuj_usera.php?id='.$edytuj_id.'&oznacz">Oznacz usera</a>
          </td>
          <td>Oznacz gdy podczas dodawania usera trzeba będzie mu wysłać wiadomość, że z powodu awarii nie dostał wiadomości email o abonamencie i musi to oplacić lub zostanie zablokowany. Oznaczenie nie skutkuje wysłaniem wiadomości a tylko zmianą statusu. Wysłanie wiadomości w drugim kroku.</td>
        </tr>
      </table>';
    }
  }
 
 ?>


<div class="bs-example">
  <form class="form-horizontal" action="edytuj_in.php" method="post">
    <div class="form-group">
      <label class="col-sm-3 control-label">* Nazwa urzytkownika:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="ad_user" value="<?php echo $row['user'];?>">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">Usługa:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="ad_usluga" value="<?php echo $row['usluga'];?>">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">Ścieżka do plików:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="ad_sciezka" value="<?php echo $row['sciezka'];?>">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">* E-mail:</label>
      <div class="col-sm-5">
        <input type="email" class="form-control" name="ad_email" value="<?php echo $row['email'];?>">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">* Dodaje:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="ad_dodaje" value="<?php echo $row['dodal'];?>">
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-default">Zapisz zmiany</button>
      </div>
    </div>
  </form>
</div>
</br>



<?php 



    //błąd nie podanie pol w dodaniu dni
    if (isset($_GET['blad_dodaj_dni'])) 
          echo '<div class="alert alert-danger" role="alert">Nie wypełniono wszystkich wymaganych pól. Wymagane są: <b>Ile dodać dni, Dodaje </div>';

 ?>




<div class="bs-example">
  <form class="form-horizontal" action="edytuj_in_dodaj.php" method="post">
    <div class="form-group">
      <label class="col-sm-3 control-label">Ile dni dodać?</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="dodaj_dni_ile" >
      </div>
    </div>
   
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">* Kto Dodaje:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="dodaj_dni_kto" value="<?php echo $wybrany_login;?>" >
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-success">Dodaj</button>
      </div>
    </div>
  </form>
</div>
</br>



<?php  
//błąd nie podanie pol w dodaniu dni
    if (isset($_GET['blad_zmien_data'])) 
          echo '<div class="alert alert-danger" role="alert">Nie wypełniono wszystkich wymaganych pól. Wymagane są: <b>Ustaw nową datę na:, Podaj powód zmiany daty, Datę zmienia: </div>';
?>
<div class="bs-example">
  <form class="form-horizontal" action="edytuj_in_zmien.php" method="post">
    
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">Ustaw nową datę na:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="zmien_data" value="<?php echo $odkodowanaData.' 02:00:06';?>">
        <input type="text" class="form-control hide" name="zmien_data_hide" value="<?php echo $odkodowanaData;?>">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">Podaj powód zmiany daty</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="zmien_powod" value="">
      </div>
    </div>
   
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 control-label">Datę zmienia:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="zmien_kto" value="<?php echo $wybrany_login;?>">
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-success">Zmień datę</button>
      </div>
    </div>
  </form>
</div>

</div>
<div class="clearboth"></div>

<?php 
require_once ('footer.php');
 ?>
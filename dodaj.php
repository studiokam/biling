<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');

$wybrany_login = $_SESSION['login'];

$query = "SELECT usluga FROM uslugi ORDER BY usluga asc";
$wynik = mysql_query($query);
 

require_once('menu.php');

if(isset($_GET['blad'])) //sprawdzamy czy zmienne istnieją (czy formularz został wysłany)
  echo '<div class="alert alert-danger" role="alert">Nie wypełniono wszystkich wymaganych pól. Wymagane są: <b>Nazwa urzytkownika, Usługa, E-mail, Dodaje </div>';
?>


<form class="form-horizontal" action="dodaj_in.php" method="post">
  <div class="form-group">
    <label class="col-sm-3 control-label">* Nazwa urzytkownika:</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" name="ad_user" placeholder="Nazwa">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">* Usługa:</label>
    <div class="col-sm-5">
      <select class="form-control" name="ad_usluga">
        <option>Wybierz</option>
        <?php
          while ($row = mysql_fetch_array($wynik)) 
          {
            echo '<option>'.$row["usluga"].'</option>';
          }
          ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Ścieżka do plików:</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" name="ad_sciezka" placeholder="Ścieżka do plików szablonu na serwerze">
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-3 control-label">* E-mail:</label>
    <div class="col-sm-5">
      <input type="email" class="form-control" name="ad_email" placeholder="E-mail">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">* Dodaje:</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" name="ad_dodaje" value="<?php echo $wybrany_login;?>">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" class="btn btn-default">Dodaj nowego usera do Bilingu</button>
    </div>
  </div>
</form>

</div>
<div class="clearboth"></div>

<?php 
require_once ('footer.php');
 ?>
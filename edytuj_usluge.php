<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('config.php');
require_once ('top.php');

$id = $_GET ['id'];

$stmt = $dbPDO->prepare("SELECT * FROM uslugi WHERE id='$id'");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$usluga = $row['usluga'];
$opis = $row['opis'];
$poprzedni_zapis_uslugi = $row['opis'];

require_once ('menu.php');

if (isset($_GET['true'])) {
  //sprawdzenie czy sa wymagane zmienne z formularza dodania
  if (!empty($_GET['edit_usluga']) AND !empty($_GET['edit_opis'])) { 
    // sprawzdenie czy jest juz taka nazwa uslugi w bazie
    $nowausluga = $_GET['edit_usluga'];
    $q = "SELECT * FROM uslugi WHERE usluga = '$nowausluga' AND id != $id"; 
    $res = $dbPDO->query($q);
    $record = $res->fetchAll();

    if (count($record) > 0) {
        header('Location: uslugi.php?jest_podana');
        exit();
    }
    else{
        $nowausluga = $_GET['edit_usluga'];
        $nowyopis = $_GET['edit_opis'];
        $id = $_GET ['id'];
        $sql = mysql_query("UPDATE uslugi SET usluga='$nowausluga', opis='$nowyopis' WHERE id='$id'");
        $log = mysql_query("INSERT INTO logi SET tresc='Edytowano usługę: $nowausluga. Poprzedni zapis w bazie: $poprzedni_zapis_uslugi.  Edytował:  ', zalogowany='$wybrany_login'");
        header('Location: uslugi.php?poprawna_edycja');
    }
  }
  else{
    header('Location: uslugi.php?edycja_brak_danych');
    exit();
  }
}


 ?>


<div class="row">
  <div class="col-md-12">
		<div class="alert alert-success" role="alert">Edycja usługi</div> </br>
      <form class="form-horizontal" action="edytuj_usluge.php" method="get">
        <div class="form-group">
          <label class="col-md-2 control-label">Usługa</label>
            <div class="col-md-8">
              <input type="text" class="form-control hide " name="id" value="<?php echo $id; ?>">
              <input type="text" class="form-control hide " name="true" value="yes">
              <input type="text" class="form-control" name="edit_usluga" value="<?php echo "$usluga"; ?>">
            </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-md-2 control-label">Opis</label>
            <div class="col-md-8">
              <textarea class="form-control" name="edit_opis" rows="10" value="<?php echo "$opis"; ?>"><?php echo "$opis"; ?></textarea>
            </div>
        </div>
        <div class="form-group">
          <div class="col-md-offset-2 col-md-8">
            <button type="submit" class="btn btn-default">Zapisz</button>
          </div>
        </div>
      </form>
	</div>
</div>


 <?php require_once ('footer.php'); ?>
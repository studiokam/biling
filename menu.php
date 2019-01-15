<?php 

$wybrany_login = $_SESSION['zalogowany'];

// sprawdzenie ilu jest wszsytkich userow
	$iloscWszystkichUserow = mysql_num_rows(mysql_query("SELECT * FROM klienci"));
	$iloscWszystkichUserowWygaslo = mysql_num_rows(mysql_query("SELECT * FROM klienci WHERE do_kiedy_data < 1 AND oznaczenie!='wyslano'"));
	$iloscWszystkichUserowBrak = mysql_num_rows(mysql_query("SELECT * FROM klienci WHERE oznaczenie='wyslano'"));
	$iloscWszystkichUserowWygasloBrakBlokady = mysql_num_rows(mysql_query("SELECT * FROM klienci WHERE do_kiedy_data = 0 AND zablokowany!='tak' AND oznaczenie!='wyslano'"));


?>


<div class="lewa">
	<div class="row">
		<div class="col-md-12">
			<nav class="navbar navbar-inverse">
				<ul class="nav navbar-nav">
			        <li class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'zalogowany.php?sort_user=asc'){echo 'active'; }else { echo ''; } ?>"><a href="zalogowany.php?sort_user=asc">Lista userów</a></li>
			        <li class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'dodaj.php'){echo 'active'; }else { echo ''; } ?>"><a href="dodaj.php">Dodaj usera</a></li>
			        <li class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'ustawienia.php'){echo 'active'; }else { echo ''; } ?>"><a href="ustawienia.php">Ustawienia</a></li>
			        <li class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'uslugi.php'){echo 'active'; }else { echo ''; } ?>"><a href="uslugi.php">Usługi</a></li>
			        <li class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'logi.php'){echo 'active'; }else { echo ''; } ?>"><a href="logi.php">Logi</a></li>
<!-- 			        <li class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'alerty.php'){echo 'active'; }else { echo ''; } ?>"><a href="alerty.php">Alerty</a></li>
 -->			    </ul>
			    <ul class="nav navbar-nav pull-right">
					<li><a href="wyloguj.php"><?php echo '<b><font color="white">'.$wybrany_login.'</font></b>'; ?> - Wyloguj się</a></li>
				</ul>
			</nav>
		</div>
	</div>
</div>


<form class="form-horizontal" action="zalogowany.php">
  <div class="form-group">
    
    <div class="col-sm-5">
      <input type="text" class="form-control" name="wyszukaj" placeholder="Wpisz co wyszukać">
    </div>
    <div class="col-sm-7">
      <button type="submit" class="btn btn-default">Szukaj</button>
      <a class="btn btn-info btn-xs" type="submit" href="zalogowany.php?oznaczeni">Wygasło - bez blokady (<?php echo $iloscWszystkichUserowWygasloBrakBlokady; ?>)</a>
      <a class="btn btn-success btn-xs" type="submit" href="zalogowany.php?oznaczeni_7">Wygasło (<?php echo $iloscWszystkichUserowWygaslo; ?>)</a>
      <a class="btn btn-danger btn-xs" type="submit" href="zalogowany.php?oznaczeni_all">Brak - (po awarii) (<?php echo $iloscWszystkichUserowBrak; ?>)</a>
      Wszystkich userów: <?php echo $iloscWszystkichUserow; ?>
    </div>
  </div>
</form>
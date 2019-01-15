<?php 

session_start();
require_once ('top.php');
require_once ('config.php');

$polaczenie = new mysqli($host, $db_user, $db_pass, $db_name);

echo "<br/>";

// info o blednych danych logowania
if (isset($_GET['blad'])) 
echo '<div class="alert alert-danger" role="alert">Błedne dane logowania </br></div>';

if (isset($_SESSION['zalogowany'])) 
{
	header('Location: zalogowany.php?sort_user=asc');
}


if (isset($_POST['login']) && ($_POST['haslo'])) 
{
	$login = $_POST['login'];
	$haslo = $_POST['haslo'];


	$sql = "SELECT * FROM admin WHERE login='$login' AND haslo='$haslo'";

	if ($rezultat = @$polaczenie->query($sql))
	{

		$ilu_userow = $rezultat->num_rows;
		if ($ilu_userow > 0) 

		{
			$wiersz = $rezultat->fetch_assoc();
			$user = $wiersz['login'];
			echo $user;

			$_SESSION['zalogowany'] = true;
			$_SESSION['zalogowany'] = $user;
			$sql = mysql_query("INSERT INTO logi SET tresc='Poprawnie zalogowano do bilingu na login: ', zalogowany='$user'");


			$rezultat->close();
			header('Location: zalogowany.php?sort_user=asc');
		}
		elseif ($ilu_userow == 0 ) 
		{
			
			header('Location:index.php?blad=dane');
			
    	$sql = mysql_query("INSERT INTO logi SET tresc='Błąd logowania, podawany login to: ', zalogowany='$login'");

		}

	}
}
else
{
echo '
<table class="table table-hover table-bordered">
 		<tr class="active">
 			<td>Biling - Panel Administracyjny</td>
 		</tr>
 		<tr>
			<td></br>
				<form class="form-horizontal" method="post">
				    <div class="form-group">
				      <label class="col-sm-3 control-label">Login:</label>
				      <div class="col-sm-5">
				        <input type="text" class="form-control" name="login" value="adam">
				      </div>
				    </div>
				   
				    <div class="form-group">
				      <label for="inputPassword3" class="col-sm-3 control-label">Hasło:</label>
				      <div class="col-sm-5">
				        <input type="password" class="form-control" name="haslo" value="adam" >
				      </div>
				    </div>
				    
				    <div class="form-group">
				      <div class="col-sm-offset-3 col-sm-9">
				        <button type="submit" class="btn btn-success">Zaloguj się</button>
				      </div>
				    </div>
				  </form>
			</td>
		</tr>
	</table>
';

}

require_once ('footer.php');
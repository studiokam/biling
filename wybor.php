<?php 
session_start();
require_once ('top.php');
require_once ('config.php');


if (!isset($_SESSION['zalogowany'])) 
{
	header('Location: index.php');
	exit();
}


echo "</br></br>";
?>



<table class="table table-hover table-bordered">
 	<tr class="active"><td class="col-md-5">Wybierz konto na jakie chcesz się zalogować:</td></tr>
 	<tr>
		<td>
			<a class="btn btn-success " type="submit" href="wybor_sprawdzenie.php?login=konrad">Konrad</a>
			<a class="btn btn-success " type="submit" href="wybor_sprawdzenie.php?login=gosia">Gosia</a>
		</td>
	</tr>
</table>


<?php 
require_once ('footer.php');
 ?>

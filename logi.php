<?php 
session_start();
require_once ('sprawdzenia.php');
require_once ('top.php');
require_once ('config.php');




// wyswietlenie wynikow tabeli pytania
$query  = "SELECT * FROM logi ORDER BY data_wpisu desc ";
$result = mysql_query($query)
    or die("Query failed");

echo '<div class="lewa">';
	require_once('menu.php');
echo '</div>

<div class="prawa">';



?>

    <!-- wyszukiwarka - logi -->
    <form class="form-horizontal" method="get">
      <div class="form-group">
        <div class="col-sm-5">
          <input type="text" class="form-control" name="wyszukaj" 
            <?php 
                if (isset($_GET['wyszukaj'])) 
                { 
                    echo 'value="'.$_GET['wyszukaj'].'"';
                } 
                else
                {
                    echo 'placeholder="Wpisz co wyszukać w logach"';
                }
            ?>
            >
        </div>

        <div class="col-sm-3">
          <button type="submit" class="btn btn-default">Szukaj</button>
        </div>

      </div>
    </form>

<?php


// wyszukiwarka logow
if (isset($_GET['wyszukaj'])) 
{
    
    $szukane_w_logach = $_GET['wyszukaj'];
    $log_szukaj = "SELECT * FROM logi WHERE tresc LIKE '%$szukane_w_logach%'";
    $iloscWyszukanychLogow = mysql_num_rows(mysql_query("SELECT * FROM logi WHERE tresc LIKE '%$szukane_w_logach%'"));



    
    echo'<table class="table table-hover table-bordered">
            <tr class="active">
              <td  class="col-md-2">Data dodania wpisu</td>
              <td  class="col-md-10">Wszystkie logi bilingu (łączna ilość: '.$iloscWyszukanychLogow.')</td>
            </tr>';

            $qx=mysql_query($log_szukaj)or die(mysql_error());
            while($row=mysql_fetch_array($qx))
            {
               echo '  
                    <tr>
                      <td>'.date("Y.m.d H:i:s",strtotime($row['data_wpisu'])).'</td>
                      <td>'.$row["tresc"].'<b>'.$row["zalogowany"].'</b></td>
                    </tr>
                ';
            }
            echo '  </table>';


}


 
// sprawdzenie ile jest wszystkich logów
$iloscWszystkichLogow = mysql_num_rows(mysql_query("SELECT * FROM logi"));


$query = mysql_query("SELECT logi_countilosc FROM ustawienia");
$row = mysql_fetch_array($query);

$count = $row['logi_countilosc']; //wyników na strone



    $offset=0; //obecnie wyświetlana strona

         if(isset($_GET['count'])) //jeśli wybrano za pomocą GET ilość wyników
         {
            $count = $_GET['count'];
         }
         if(isset($_GET['offset'])) //jeśli wybrano kolejne strony z wynikami
        {
            $offset = $count*$_GET['offset'];
        }

                 // zapytanie zwracające ilosc rekordów z tabeli
         $sql = 'SELECT COUNT(*) FROM `logi`'; 
         $result = mysql_query($sql);
         $r = mysql_fetch_array($result);
                 //podział wyników na strony
         $pages = ceil($r[0]/$count);
                 //wybranie wyników dla bieżących parametrów offset
         $select = 'SELECT * FROM `logi` ORDER BY `id` desc LIMIT '.$count.' offset '.$offset.';';


echo '<nav><ul class="pagination">';

           for($i=0;$i<$pages;$i++) //wyswietlanie numerów stron
            {
                if($i*$count==$offset)
                {
                    echo ' <li class="active"><a href="#">'.$i.' <span class="sr-only">(current)</span></a></li> ';
                }else{
                    echo '<li ><a href="logi.php?count='.$count.'&amp;offset='.$i.'">'.$i.' <span class="sr-only">(current)</span></a></li>'; //tworzenie odnośnika z odpowiednimi parametrami offset i count
                }
            } 

echo '</ul></nav>';




         
        echo '  <table class="table table-hover table-bordered">
			
				<tr class="active">
				  <td  class="col-md-2">Data dodania wpisu</td>
				  <td  class="col-md-10">Wszystkie logi bilingu (łączna ilość: '.$iloscWszystkichLogow.')</td>
				</tr>

				
';
        $q=mysql_query($select)or die(mysql_error());
        while($row=mysql_fetch_array($q))
        {
           echo '  
 				<tr>
				  <td>'.date("Y.m.d H:i:s",strtotime($row['data_wpisu'])).'</td>
				  <td>'.$row["tresc"].'<b>'.$row["zalogowany"].'</b></td>
				</tr>
			';
        }
        echo '  </table>';

	 
            
echo '<nav><ul class="pagination">';

           for($i=0;$i<$pages;$i++) //wyswietlanie numerów stron
            {
                if($i*$count==$offset)
                {
                    echo ' <li class="active"><a href="#">'.$i.' <span class="sr-only">(current)</span></a></li> ';
                }
                else{
                    echo '<li ><a href="logi.php?count='.$count.'&amp;offset='.$i.'">'.$i.' <span class="sr-only">(current)</span></a></li>'; //tworzenie odnośnika z odpowiednimi parametrami offset i count
                }
            } 
            
echo '</ul></nav>';

 
echo 
'	</div>
	<div class="clearboth"></div>';

require_once ('footer.php');
 ?>
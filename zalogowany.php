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
<div class="prawa">
	<?php 
		require_once('content.php');
	 ?>
</div>
<div class="clearboth"></div>



<?php 
require_once ('footer.php');
 ?>
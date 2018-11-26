<?php
    require("functions.php");
  //kui pole sisse loginud
  
  //kui pole sisselogitud
  if(!isset($_SESSION["userId"])){
	header("Location: index_3.php");
    exit();	
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location:  index_3.php");
	exit();
  }
  
  $notice = readallunvalidatedmessages();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
  <style>
	  <?php
        echo "body{background-color: " .$_SESSION["bgColor"] ."; \n";
		echo "color: " .$_SESSION["txtColor"] ."} \n";
	  ?>
	</style>
</head>
<body>
  <h1>Sõnumid</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
	<ul>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	  <li><a href="main.php">Tagasi pealehele</a></li>
	</ul>
  <hr>
  
  <?php echo $notice; ?>

</body>
</html>








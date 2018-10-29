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

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Pealeht</title>
	<style>
	  <?php
        echo "body{background-color: " .$_SESSION["bgColor"] ."; \n";
		echo "color: " .$_SESSION["txtColor"] ."} \n";
	  ?>
	</style>
  </head>
  <body>
    <h1>Pealeht</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisse loginud nimega: <?php echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"]; ?>. <b><a href="?logout=1">Logi välja!</a></b></p>
	<ul>
	  <li>Minu <a href="userprofile.php">kasutajaprofiil</a>.</li>
	  <li>Süsteemi <a href="users.php">kasutajad</a>.</li>
	  <li>Valideeri anonüümseid <a href="validatemsg.php">sõnumeid</a></li>
	  <li>Valideeritud sõnumid <a href="validatedmessages.php">kasutajate kaupa</a>.</li>
	</ul>
	
  </body>
</html>
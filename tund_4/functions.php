<?php
require("../../../../vp2018config.php");
//echo $GLOBALS["serverHost"];
//echo $GLOBALS["serverUsername"];
//echo $GLOBALS["serverPassword"];
$database = "if18_rinde";

function saveAMsg($msg){
  //echo "Töötab!";
  $notice = ""; //see on teade, mis antakse salvestamise kohta
  //loome ühenduse andmebaasiserveriga
  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  //Valmistame ette SQL päringu
  $stmt = $mysqli->prepare("INSERT INTO vpamsg3 (message) VALUES(?)");
  echo $mysqli->error;
  $stmt->bind_param("s", $msg);//s - string, i - integer, d - decimal
  if ($stmt->execute()){
	$notice = 'Sõnum: "' .$msg .'" on salvestatud!';  
  } else {
	$notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
  }
  $stmt->close();
  $mysqli->close();
  return $notice;
}

  function readallmessages(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg3");
	echo $mysqli->error;
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
?>




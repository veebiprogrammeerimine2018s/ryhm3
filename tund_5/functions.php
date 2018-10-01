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

  //funktsioon, mis loeb kõiki salvestatud sõnumeid (seda kutsub readmsg.php)
  function readallmessages(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistame ette sõnumite lugemise SQL käsu
	//NB! minul on tabeli nime lõpus number 3 (vpamsg3)
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg3");
	echo $mysqli->error;
	//seon loetavad andmed muutujatega, siin praegu iga kirjapandud sõnumi kohta küsisingi vaid sõnumit ennas ja seon selle muutujaga $msg
	$stmt->bind_result($msg);
	//täidan käsu
	$stmt->execute();
	//järgnevalt saab iga järgmise loetud sõnumi käsuga $stmt->fetch()
	//kasutan "while" tsüklit, mida täidetakse siinkohal kuni veel on midagi võtta ehk fetchida
	while($stmt->fetch()){
		//iga kord järjekordset sõnumit võttes panen selle eespool loodud muutuja $notice väärtusele juurde ( .= nagu arvudega oleks += )
		//siinkohal moodustan iga sõnumi jaoks html lõigu
		$notice .= "<p>" .$msg ."</p> \n";
	}
	//sulgen käsu
	$stmt->close();
	//sulgen andmebaasiühenduse
	$mysqli->close();
	//tagastan funktsiooni väljakutsujale kokku pandud html-koodi
	return $notice;
  }
     
  function test_input($data) {
    //echo "Koristan!\n";
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>




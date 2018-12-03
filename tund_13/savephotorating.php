<?php
  //GET meetodiga saadetavad parameetrid
  $id = $_REQUEST["id"];
  $rating = $_REQUEST["rating"];
  
  require("../../../../vp2018config.php");
  $database = "if18_rinde";
  //sessioon
  session_start();
  
  $mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
  $stmt = $mysqli->prepare("INSERT INTO vpphotoratings3 (photoid, userid, rating) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $id, $_SESSION["userId"], $rating);
  $stmt->execute();
  $stmt->close();
  //küsime uue keskmise hinde
  $stmt=$mysqli->prepare("SELECT AVG(rating)FROM vpphotoratings3 WHERE photoid=?");
  $stmt->bind_param("i", $id);
  $stmt->bind_result($score);
  $stmt->execute();
  $stmt->fetch();
  $stmt->close();
  $mysqli->close();
  //ümardan keskmise hinde kaks kohta pärast koma ja tagastan
  echo round($score, 2);
?>
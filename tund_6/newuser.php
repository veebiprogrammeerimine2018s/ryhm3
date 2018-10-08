<?php
  require("functions.php");
  $notice = "";
  $name = "";
  $surname = "";
  $email = "";
  $gender = "";
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthDate = null;
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  //muutujad võimalike veateadetega
  $nameError = "";
  $surnameError = "";
  $birthMonthError = "";
  $birthYearError = "";
  $birthDayError = "";
  $birthDateError = "";
  $genderError = "";
  $emailError = "";
  $passwordError = "";
  $confirmpasswordError = "";
  
  //kui on uue kasutaja loomise nuppu vajutatud
  if(isset($_POST["submitUserData"])){
  
  if (isset($_POST["firstName"]) and !empty($_POST["firstName"])){
	$name = test_input($_POST["firstName"]);
  } else {
	  $nameError = "Palun sisesta eesnimi!";
  }
  
  if (isset($_POST["surName"]) and !empty($_POST["surName"])){
	$surname = test_input($_POST["surName"]);
  } else {
	  $surnameError = "Palun sisesta perekonnanimi!";
  }
  
  if(isset($_POST["gender"])){
	$gender = intval($_POST["gender"]);
  } else {
	  $genderError = "Palun märgi sugu!";
  }
  
  //kontrollime, kas sünniaeg sisestati ja kas on korrektne
  if(isset($_POST["birthDay"]) and !empty($_POST["birthDay"])){
	  $birthDay = intval($_POST["birthDay"]);
  } else {
	  $birthDayError = "Palun vali sünnikuupäev!";
  }
  
  if(isset($_POST["birthMonth"]) and !empty($_POST["birthMonth"])){
	  $birthMonth = intval($_POST["birthMonth"]);
  } else {
	  $birthMonthError = "Palun vali sünnikuu!";
  }
  
  if(isset($_POST["birthYear"]) and !empty($_POST["birthYear"])){
	  $birthYear = intval($_POST["birthYear"]);
  } else {
	  $birthYearError = "Palun vali sünniaasta!";
  }
  
  //kontrollin kuupäeva õigsust
  if(!empty($birthDay) and !empty($birthMonth) and !empty($birthYear)){
	//checkdate(päev, kuu, aasta)
	if(checkdate($birthMonth, $birthDay, $birthYear)){
	  $makebirthDate = date_create($birthMonth ."/" .$birthDay ."/" .$birthYear);
	  $birthDate = date_format($makebirthDate, "Y-m-d");
	  //echo $birthDate;
	} else {
	  $birthDateError = "Kuupäev on vigane!";
    }
  }//kui kõik kuupäeva osad on olmemas
  
  if (isset($_POST["email"]) and !empty($_POST["email"])){
	//$name = $_POST["firstName"];
	$email = test_input($_POST["email"]);
  } else {
	  $emailError = "Palun sisesta e-postiaadress!";
  }
  
  if (!isset($_POST["password"]) or empty($_POST["password"])){
	$passwordError = "Palun sisesta salasõna!";
  } else {
	  if(strlen($_POST["password"]) < 8){
		  $passwordError = "Liiga lühike salasõna (sisestasite ainult " .strlen($_POST["password"]) ." märki).";
	  }
  }
  
  if (!isset($_POST["confirmpassword"]) or empty($_POST["confirmpassword"])){
	$confirmpasswordError = "Palun sisestage salasõna kaks korda!";  
  } else {
	  if($_POST["confirmpassword"] != $_POST["password"]){
		  $confirmpasswordError = "Sisestatud salasõnad ei olnud ühesugused!";
	  }
  }
  
  //kui kõik on korras, siis salvestame kasutaja
  if(empty($nameError) and empty($surnameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and empty($genderError) and empty($emailError) and empty($passwordError) and empty($confirmpasswordError)){
    $notice = signup($name, $surname, $email, $gender, $birthDate, $_POST["password"]);
  }
  
  }//kui on nuppu vajutatud - lõppeb
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Katselise veebi uue kasutaja loomine</title>
  </head>
  <body>
    <h1>Loo endale kasutajakonto</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Eesnimi:</label><br>
	  <input name="firstName" type="text" value="<?php echo $name; ?>"><span><?php echo $nameError; ?></span><br>
      <label>Perekonnanimi:</label><br>
	  <input name="surName" type="text" value="<?php echo $surname; ?>"><span><?php echo $surnameError; ?></span><br>
	  
	  <input type="radio" name="gender" value="2" <?php if($gender == "2"){		echo " checked";} ?>><label>Naine</label>
	  <input type="radio" name="gender" value="1" <?php if($gender == "1"){		echo " checked";} ?>><label>Mees</label><br>
	  <span><?php echo $genderError; ?></span><br>
	  
	  <label>Sünnipäev: </label>
		  <?php
			echo '<select name="birthDay">' ."\n";
			echo '<option value="" selected disabled>päev</option>' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $birthDay){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		  ?>
	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		echo '<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthMonth){
				echo " selected ";
			}
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <?php
	    echo '<select name="birthYear">' ."\n";
		echo '<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <br>
	  <span><?php echo $birthDateError ." " .$birthDayError ." " .$birthMonthError ." " .$birthYearError; ?></span>
	  <br>
	  
	  <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	  <label>Salasõna (min 8 tähemärki):</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
	  <label>Korrake salasõna:</label><br>
	  <input name="confirmpassword" type="password"><span><?php echo $confirmpasswordError; ?></span><br>
	  <input name="submitUserData" type="submit" value="Loo kasutaja"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	<p><a href="index_3.php">Tagasi</a> avalehele!</p>
	
  </body>
</html>
<?php
  require("functions.php");
  
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
  $genderError = "";
  $emailError = "";
  $passwordError = "";
  
  //kui on uue kasutaja loomise nuppu vajutatud
  if(isset($_POST["submitUserData"])){
  
  if (isset($_POST["firstName"]) and !empty($_POST["firstName"])){
	//$name = $_POST["firstName"];
	$name = test_input($_POST["firstName"]);
  } else {
	  $nameError = "Palun sisesta eesnimi!";
  }
  
  if (isset($_POST["surName"])){
	//$surname = $_POST["surName"];
	$surname = test_input($_POST["surName"]);
  }
  
  if(isset($_POST["gender"])){
	$gender = intval($_POST["gender"]);
  } else {
	  $genderError = "Palun märgi sugu!";
  }
  
  //kontrollime, kas sünniaegs isestati ja kas on korrektne
  if(isset($_POST["birthDay"])){
	  $birthDay = $_POST["birthDay"];
  } 
  
  if(isset($_POST["birthMonth"])){
	  $birthMonth = $_POST["birthMonth"];
  }
  
  if(isset($_POST["birthYear"])){
	  $birthYear = $_POST["birthYear"];
  }
  
  //kontrollin kuupäeva õigsust
  if(isset($_POST["birthDay"]) and isset($_POST["birthMonth"]) and isset($_POST["birthYear"])){
	//checkdate(päev, kuu, aasta)
	if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))){
	  $makebirthDate = date_create($_POST["birthMonth"] ."/" .$_POST["birthDay"] ."/" .$_POST["birthYear"]);
	  $birthDate = date_format($makebirthDate, "Y-m-d");
	  echo $birthDate;
	} else {
	  $birthYearError = "Kuupäev on vigane!";
    }
  }//kui kõik kuupäeva osad on olmemas
  
  //echo "Kuupäev tuli: " .$birthDate;
  
  //kui kõik on korras, siis salvestame kasutaja
  if(empty($nameError) and empty($surnameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and empty($genderError) and empty($emailError) and empty($passwordError)){
    $notice = signup($name, $surname, $email, $gender, $birthDate, $_POST["password"]);
    echo $notice;
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
	  <input name="surName" type="text"><span><?php echo $surnameError; ?></span><br>
	  
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
	  
	  <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email"><span><?php echo $emailError; ?></span><br>
	  
	  <label>Salasõna:</label><br>
	  <input name="password" type="text"><span><?php echo $passwordError; ?></span><br>
	  
	  <input name="submitUserData" type="submit" value="Loo kasutaja">
	</form>
	
  </body>
</html>
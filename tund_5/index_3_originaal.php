<?php
  //echo "Siin on minu esimene PHP!";
  $name = "Andrus";
  $surname = "Rinde";
  //$todayDate = date("d.m.Y");
  $dateToday = date("d");
  $monthToday = date("m");
  $yearToday = date("Y");
  $weekDayNow = date("N");
  //echo $weekDayNow;
  $weekDayNamesET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  //var_dump($weekDayNamesET);
  //echo $weekDayNamesET[0];
  $hourNow = date("H");
  //echo $hourNow;
  $partOfDay = "";
  if ($hourNow < 8){
	  $partOfDay = "varajane hommik";
  }
  if ($hourNow >= 8 and $hourNow < 16){
	  $partOfDay = "kooliaeg";
  }
  if ($hourNow >= 16){
	  $partOfDay = "vaba aeg";
  }
  
  //loosime juhusliku pildi
  $picNum = mt_rand(2, 43);//random
  //echo $picNum;
  $picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
  $picEXT = ".jpg";
  $picFileName = $picURL .$picNum .$picEXT;
  //echo $picFileName;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>
	  <?php
	    echo $name;
		echo " ";
		echo $surname;
	  ?>
	, õppetöö</title>
  </head>
  <body>
    <h1>
	  <?php
	    echo $name ." " .$surname;
	  ?>
	</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<?php
	  //echo "<p>Täna on " .$weekDayNamesET[$weekDayNow - 1] .", " .$todayDate ."</p> \n";
	  echo "<p>Täna on " .$weekDayNamesET[$weekDayNow - 1] .", " .$dateToday .". " .$monthNamesET[$monthToday - 1] ." " .$yearToday .".</p> \n";
	  echo "<p>Lehe avamise hetkel oli kell " .date("H:i:s") .", käes on " .$partOfDay .".</p> \n";
	?>
	<!--<img src="http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">-->
	<img src="../../tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">
	<p>Mul on sõber, kes ka teeb <a href="../../../../~merivor">veebi</a>.</p>
	
	<img src="<?php echo $picFileName; ?>" alt="juhuslik pilt Tallinna Ülikoolist">
	
  </body>
</html>
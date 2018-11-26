<?php
  require("functions.php");
  require("classes/Photoupload.class.php");
  //kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	  header("Location: index_3.php");
	  exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index_3.php");
	exit();
  }
  
  $notice = "";
  
  $mydescription = "Pole tutvustust lisanud!";
  $mybgcolor = "#FFFFFF";
  $mytxtcolor = "#000000";
  $profilePic = "../vp_picfiles/vp_user_generic.png";//asendada reaalse pildi lugemisega
  $profilePicId = NULL;
  $picSize = 300;
  $imageNamePrefix = "vpuser_";
  //pildi üleslaadimise osa
  //$profilePicDirectory = "../vpuser_picfiles/";
  //Tuleb config failist: $profilePicDir
  $addedPhotoId = null;
  
  $target_file = "";
  $uploadOk = 1;
  //$imageFileType = "";
  
  if(isset($_POST["submitProfile"])){
	//$notice = storeuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);
	
	//kohe uued väärtused näitamiseks kasutusele
	if(!empty($_POST["description"])){
	  $mydescription = $_POST["description"];
	}
	$mybgcolor = $_POST["bgcolor"];
	$mytxtcolor = $_POST["txtcolor"];
	//profiilipildi laadimine
	if(!empty($_FILES["fileToUpload"]["name"])){
	  //foto laadimiseks kasutame klassi
	  $myPhoto = new Photoupload($_FILES["fileToUpload"]);
	  $myPhoto->makeFileName($imageNamePrefix);
	  $target_file = $profilePicDir .$myPhoto->fileName;
	  //kas on pilt
		$uploadOk = $myPhoto->checkForImage();
		if($uploadOk == 1){
		  // kas on sobiv tüüp
		  $uploadOk = $myPhoto->checkForFileType();
		}
		
		if($uploadOk == 1){
		  // kas on sobiv suurus
		  $uploadOk = $myPhoto->checkForFileSize($_FILES["fileToUpload"], 2500000);
		}
		
		if($uploadOk == 1){
		  // kas on juba olemas
		  $uploadOk = $myPhoto->checkIfExists($target_file);
		}
					
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				$notice = "Vabandame, profiilipildi faili ei laetud üles!";
			
			} else {
				$saveSuccess = $myPhoto->createThumbnail($profilePicDir, $picSize);
				$addedPhotoId = addUserPhotoData($myPhoto->fileName);
				$profilePic = $target_file;
			}
		} else {
		  $profilePic = $_POST["profilepic"];
		}//pildi laadimine lõppes
		//profiili salvestamine koos pildiga
		$notice = storeuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"], $addedPhotoId);
		
	
  } else {
	$myprofile = showmyprofile();
	if($myprofile->description != ""){
	  $mydescription = $myprofile->description;
    }
    if($myprofile->bgcolor != ""){
	  $mybgcolor = $myprofile->bgcolor;
    }
    if($myprofile->txtcolor != ""){
	  $mytxtcolor = $myprofile->txtcolor;
    }
	if($myprofile->picture != ""){
	  $profilePic = $profilePicDir .$myprofile->picture;
	}
	if($myprofile->pictureId != ""){
	  $profilePicId = $myprofile->pictureId;
	}
  }

  if(!empty($profilePicId)){
    $previousProfilePics = readPreviousProfilePics($profilePicId);
  } else {
	$previousProfilePics = "<p>Profiilipilte pole.</p> \n";
  }
  
  
  $pageTitle = "Kasutajaprofiil";
  //echo $profilePic;
  require("header.php");
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<h2>
	  <?php
	    echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	  ?>
	</h2>
	<ul>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	  <li><a href="main.php">Tagasi pealehele</a></li>
	</ul>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	  <div style="float: left">
	  <img src="<?php
	    echo $profilePic;
	  ?>" alt="<?php
	    echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	  ?>">
	  </div>
	  <input type="hidden" name="profilepic" value="<?php echo $profilePic; ?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	  <label>Vali üleslaetav profiilipilt: </label>
      <input type="file" name="fileToUpload" id="fileToUpload">
	  <br>
	  <input name="submitProfile" type="submit" value="Salvesta profiil">
	</form>
	<p><?php echo $notice; ?>
	<hr>
	<?php
	  echo $previousProfilePics;
	?>
	
  </body>
</html>
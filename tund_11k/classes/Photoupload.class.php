<?php
  class Photoupload {
	private $tempName;
	public $imageFileType;
    public $imageSize;
	public $fileName;
	private $myTempImage;
	private $myImage;
	public $errorsForUpload;
	private $uploadOk;
	public $photoDate;
    
	
	function __construct($tmpPic){
	  //$this->tempName = $tmpPic;
      $this->tempName = $tmpPic["tmp_name"];
	  $this->imageFileType = strtolower(pathinfo($tmpPic["name"], PATHINFO_EXTENSION));
      $this->imageSize = $tmpPic["size"];
	  $this->createImageFromFile();
	  $this->uploadOk = 1;
	}
	
	//destructor, mis käivitub klassi eemaldamisel
	function __destruct(){
	  imagedestroy($this->myTempImage);
	  @imagedestroy($this->myImage);
	}
	
	private function createImageFromFile(){
	  if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
		$this->myTempImage = imagecreatefromjpeg($this->tempName);
	  }
	  if($this->imageFileType == "png"){
		$this->myTempImage = imagecreatefrompng($this->tempName);
	  }
	  if($this->imageFileType == "gif"){
		$this->myTempImage = imagecreatefromgif($this->tempName);
	  }
	}
	
	public function makeFileName($prefix){
		$timeStamp = microtime(1) * 10000;
		$this->fileName = $prefix .$timeStamp ."." .$this->imageFileType;
	}
	
	
	public function readExif(){
		// kõige ees on "@" märk, et vältida hoiatust
		@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
		//var_dump($exif);
		//echo $exif["DateTimeOriginal"];
		//echo $exif;
		if(!empty($exif["DateTimeOriginal"])){
			$this->photoDate = $exif["DateTimeOriginal"];
		} else {
			$this->photoDate = NULL;
		}
	}
	
	public function checkForImage(){
		$this->errorsForUpload = "";
		// kas on pilt, kontrollin pildi suuruse küsimise kaudu
		$check = getimagesize($this->tempName);
		if($check == false) {
		  $this->errorsForUpload .= "Fail ei ole pilt.";
		  $this->uploadOk = 0;
		}
		return $this->uploadOk;
	}
	
	public function checkForFileSize($size){
		// faili suurus
		if ($this->imageSize > $size) {
		  $this->errorsForUpload .= " Kahjuks on fail liiga suur!";
		  $this->uploadOk = 0;
		}
		return $this->uploadOk;
	}
	
	public function checkForFileType(){
		// kindlad failitüübid
		if($this->imageFileType != "jpg" && $this->imageFileType != "png" && $this->imageFileType != "jpeg"
			&& $this->imageFileType != "gif" ) {
			$this->errorsForUpload ." Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid!";
			$uploadOk = 0;
		}
		return $this->uploadOk;		
	}
		
	public function checkIfExists($target){
		// kas on juba olemas
		if (file_exists($target)) {
		  $this->errorsForUpload .= "Kahjuks on selline pilt juba olemas!";
		  $this->uploadOk = 0;
		}
		return $this->uploadOk;
	}
	
	public function resizeImage($width, $height){
	  //vaatame pildi originaalsuuruse
	  $imageWidth = imagesx($this->myTempImage);
	  $imageHeight = imagesy($this->myTempImage);
	  //leian vajaliku suurendusfaktori
	  if($imageWidth > $imageHeight){
		$sizeRatio = $imageWidth / $width;
	  } else {
		$sizeRatio = $imageHeight / $height;
	  }
				
	  $newWidth = round($imageWidth / $sizeRatio);
	  $newHeight = round($imageHeight / $sizeRatio);
	  $this->myImage = $this->changePicSize($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
	}
	
	private function changePicSize($image, $ow, $oh, $w, $h){
	  $newImage = imagecreatetruecolor($w, $h);
	  //kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
	  imagesavealpha($newImage, true);
	  $transColor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
	  imagefill($newImage, 0, 0, $transColor);
      imagecopyresampled($newImage, $image, 0, 0 , 0, 0, $w, $h, $ow, $oh);
	  return $newImage;
    }
	
	public function addWatermark($pathToWatermark){
	  //lisame vesimärgi
	  $waterMark = imagecreatefrompng($pathToWatermark);
	  $waterMarkWidth = imagesx($waterMark);
	  $waterMarkHeight = imagesy($waterMark);
	  $waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth - 10;
	  $waterMarkPosY = imagesy($this->myImage) - $waterMarkHeight - 10;
      //kopeerin vesimärgi pikslid pildile
	  imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
	}
	
	public function addText($textToImage, $size, $y){
	  //lisame ka teksti
	  $textColor = imagecolorallocatealpha($this->myImage, 255,255,255, 60);
	  imagettftext($this->myImage, $size, 0, 10, $y, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
	}
	
	public function createThumbnail($directory, $size){
		$notice = NULL;
		$imageWidth = imagesx($this->myTempImage);
		$imageHeight = imagesy($this->myTempImage);
		if($imageWidth > $imageHeight){
			$cutSize = $imageHeight;
			$cutX = round(($imageWidth - $cutSize) / 2);
			$cutY = 0;
		} else {
			$cutSize = $imageWidth;
			$cutX = 0;
			$cutY = round(($imageHeight - $cutSize) / 2);
		}
		$myThumbnail = imagecreatetruecolor($size, $size);
		
		imagecopyresampled($myThumbnail, $this->myTempImage, 0, 0, $cutX, $cutY, $size, $size, $cutSize, $cutSize);
	    $targetFile = $directory .$this->fileName;
		$notice = $this->savePicFile($myThumbnail, $targetFile);
		//thumbnail kirjutatakse pildifailiks
		return $notice;
	}
	
	public function savePhoto($targetFile){
		$notice = $this->savePicFile($this->myImage, $targetFile);
		return $notice;
	}
	
	public function savePicFile($image, $targetFile){
	  $notice = "";
	  //muudetud suurusega pilt kirjutatakse pildifailiks
	  if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
		if(imagejpeg($image, $targetFile, 90)){
          $notice = 1;
	    } else {
		  $notice = 0;
	    }
	  }
	  if($this->imageFileType == "png"){
		if(imagepng($image, $targetFile, 6)){
          $notice = 1;
	    } else {
		  $notice = 0;
	    }
	  }
	  if($this->imageFileType == "gif"){
		if(imagegif($image, $targetFile)){
          $notice = 1;
		} else {
		  $notice = 0;
	    }
	  }
	
	  return $notice;
	}
	
  
  }//class lõppeb
?>
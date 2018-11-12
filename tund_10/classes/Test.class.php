<?php
  class Test {
    //muutujad ehk properties
	private $secretNumber;
	public $publicNumber;
	
	//funktsioonid on meetodid (methods)
	//constructor, mis käivitub klassi kasutuselevõtmisel
	function __construct($givenNumber){
	  $this->secretNumber = 7;
	  $this->publicNumber = $givenNumber * $this->secretNumber;
	}
	
	//destructor, mis käivitub klassi eemaldamisel
	function __destruct(){
	  echo "Klass lõpetab tegevuse!";
	}
	
	public function showValues(){
	  echo "Salajane number on: " .$this->secretNumber;
	  $this->tellInfo();
	}
	
	private function tellInfo(){
	  echo "See siin on väga salajane!";	
	}
  
  
  }//class lõppeb
?>
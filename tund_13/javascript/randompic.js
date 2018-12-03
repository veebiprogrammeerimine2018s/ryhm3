window.onload = function(){
	ajaxrandompic();
	document.getElementById("pic").addEventListener("click", ajaxrandompic);
}

function ajaxrandompic(){
	//AJAX, loome ühenduse, päringu serverile
	let xmlhttp = new XMLHttpRequest();
	//sõltuvalt päringu tulemusest tegutsen
	xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			//kui päring õnnestus ja tuli vastus, siis mida teha
			//paneme keskmise hinde nähtavale
			document.getElementById("pic").innerHTML = this.responseText;
		}
	}
	xmlhttp.open("GET", "addrandomphoto.php", true);
	xmlhttp.send();
	//AJAX lõppes
}
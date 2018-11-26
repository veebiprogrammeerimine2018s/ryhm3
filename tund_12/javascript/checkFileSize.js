//console.log("Javascript töötab!");
//window.alert("Appi, see töötab!");

window.onload = function(){
  document.getElementById("submitPic").disabled = true;
  document.getElementById("fileToUpload").addEventListener("change", checkSize);
}

function checkSize(){
  let fileToUpload = document.getElementById("fileToUpload").files[0];
  if(fileToUpload.size <= 2500000){
	document.getElementById("submitPic").disabled = false;
  }
}
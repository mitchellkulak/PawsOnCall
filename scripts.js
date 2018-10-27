function addNote() {
    var txt;
    var person = prompt("Please add a note", "");
    if (person == null || person == "") {
    } else {
        // insert into database statement
    }
    document.getElementById("demo").innerHTML = txt;
}

function addMed(x) {
	var txt;
	txt = x;
    document.getElementById("test").innerHTML = txt;
}

function addMedi(x) {
	var txt;
	txt = x;
    document.getElementById("test1").innerHTML = txt;
}

/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

function searchForDogs(){
    var dogName = document.getElementById("searchBar").value;
    fetch('http://server.246valley.com:1338/PawsOnCall/DogSearch.php?dog_name=' + dogName) //Add the file name
    .then(response => response.json())
    .then((data) => {
        var obj = JSON.parse(JSON.stringify(data));
        obj.forEach(function(element) {
            console.log(element.DogId);
        });
    });
    console.log("searched");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
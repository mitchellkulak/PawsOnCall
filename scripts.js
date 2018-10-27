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
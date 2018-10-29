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
 
function addMedi(x){         
	var txt;
	txt = x;
    document.getElementById("test1").innerHTML = txt;
} 

/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

function redirectToSearch(){
    var dogName = document.getElementById("searchBar").value;
    window.location.href = "searchresult.html?search=" + dogName;
}

function searchForDogs(){
    const urlParams = new URLSearchParams(window.location.search);
    const dogName = urlParams.get('search');
    var searchResultSection = document.getElementById("searchResults");
    console.log(searchResultSection.innerHTML);
    fetch('http://server.246valley.com:1338/PawsOnCall/DogSearch.php?dog_name=' + dogName) //Add the file name
    .then(response => response.json())
    .then((data) => {
        var obj = JSON.parse(JSON.stringify(data));
        obj.forEach(function(element) {

            var outerArticle = document.createElement("article");
            outerArticle.classList.add("tile");
            outerArticle.classList.add("is-child");
            outerArticle.classList.add("notification");
            outerArticle.classList.add("is-primary");
            outerArticle.classList.add("search");

            var dogNameDiv = document.createElement("div");
            dogNameDiv.classList.add("dogName");
            dogNameDiv.innerHTML = element.DogName;

            var breedDiv = document.createElement("div");
            breedDiv.classList.add("breed");
            breedDiv.innerHTML = element.Breed;

            var volunteerDiv = document.createElement("div");
            volunteerDiv.classList.add("volunteerName");
            volunteerDiv.innerHTML = element.VolunteerName;

            var idDiv = document.createElement("div");
            idDiv.classList.add("dogID");
            idDiv.innerHTML = element.DogId;

            var selectButton = document.createElement("button");
            selectButton.classList.add("selectButton");
            selectButton.innerHTML = "Select";
            //Need to add onclick with access to dogID

            outerArticle.appendChild(dogNameDiv);
            outerArticle.appendChild(breedDiv);
            outerArticle.appendChild(volunteerDiv);
            outerArticle.appendChild(idDiv);
            outerArticle.appendChild(selectButton);

            searchResultSection.appendChild(outerArticle);

            console.log("yeah");
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
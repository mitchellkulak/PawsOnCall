function redirectToSearch() {
    var dogName = document.getElementById("searchBar").value;
    window.location.href = "searchresult.html?search=" + dogName;
}

function searchForDogs() {
    const urlParams = new URLSearchParams(window.location.search);
    const dogName = urlParams.get('search');
    var searchResultSection = document.getElementById("searchResults");
    fetch('DogSearch.php?search=' + dogName + "&session=" + getCookie("session")) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            obj.forEach(function (element) {

                if (element.error == "auth error") {
                    logout();
                }

                var outerArticle = document.createElement("article");
                outerArticle.classList.add("tile");
                outerArticle.classList.add("notification");
                outerArticle.classList.add("is-primary");
                outerArticle.classList.add("search");

                var dogNameDiv = document.createElement("div");
                dogNameDiv.classList.add("dogName");
                dogNameDiv.classList.add("result");
                dogNameDiv.innerHTML = element.DogName;

                var breedDiv = document.createElement("div");
                breedDiv.classList.add("breed");
                breedDiv.classList.add("result");
                breedDiv.innerHTML = element.Breed;

                var volunteerDiv = document.createElement("div");
                volunteerDiv.classList.add("volunteerName");
                volunteerDiv.classList.add("result");
                volunteerDiv.innerHTML = element.VolunteerName;

                // var idDiv = document.createElement("div");
                // idDiv.classList.add("dogID");
                // idDiv.innerHTML = element.DogId;

                var selectButton = document.createElement("button");
                selectButton.classList.add("selectButton");
                selectButton.classList.add("is-link");
                selectButton.classList.add("button");
                selectButton.innerHTML = "Select";
                selectButton.onclick = function () { redirectToMother(element.DogId); };
                //Need to add onclick with access to dogID

                outerArticle.appendChild(dogNameDiv);
                outerArticle.appendChild(breedDiv);
                outerArticle.appendChild(volunteerDiv);
                // outerArticle.appendChild(idDiv);
                outerArticle.appendChild(selectButton);

                searchResultSection.appendChild(outerArticle);

            });
        });

    console.log("searched");

}
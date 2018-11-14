function redirectToSearch() {
    var dogName = document.getElementById("searchBar").value;
    window.location.href = "searchresult.html?search=" + dogName;
}

function searchForDogs() {
    const urlParams = new URLSearchParams(window.location.search);
    const volunteerName = urlParams.get('search');
    var searchResultSection = document.getElementById("searchResults");
    fetch('VolunteerSearch.php?search=' + VolunteerName + "&session=" + getCookie("session")) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            obj.forEach(function (element) {

              //  html junk

            });
        });

    console.log("searched");

}
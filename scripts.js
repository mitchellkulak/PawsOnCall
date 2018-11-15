function loadGoogle() {
    google.charts.load('current', { packages: ['corechart', 'line'] });
    google.charts.setOnLoadCallback(drawChart);
    window.addEventListener("resize", drawChart, false);
}

function addMomDogTemp() {
    var d = Date.now();
    var dogID = getCookie("dogID");
    if (dogID != "") {
        var temp = prompt("Please add a temp");
        var tempInt = parseInt(temp);
        if (tempInt != null && temp == tempInt && tempInt >= 90 && tempInt <= 110) {
            var url = "AddMomDogTemps.php?session=" + getCookie("session");
            var data = {};
            data.temp = temp;
            console.log(temp);
            data.dogID = getCookie("dogID");
            console.log(JSON.stringify(data));
            fetch(url, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, cors, *same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json; charset=utf-8",
                    // "Content-Type": "application/x-www-form-urlencoded",
                },
                redirect: "follow", // manual, *follow, error
                referrer: "no-referrer", // no-referrer, *client
                body: JSON.stringify(data), // body data type must match "Content-Type" header
            })
                //.then(response => response.json()) // parses response to JSON
                .then((responseContent) => {
                    console.log(responseContent);
                });
        }
        else {
            alert("please enter a number between 90 and 110");
        }
    }

}

function getWhelpDates() {
    var startWhelp;
    var endWhelp;
    var thisTableBody = document.getElementById("whelp");

    fetch('GetMomLitters.php?dogID=' + getCookie("dogID") + "&session=" + getCookie("session"))
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            console.log(obj);
            obj.forEach(function (element) {
                var newRow = document.createElement("tr");
                var startCell = document.createElement("td");
                var endCell = document.createElement("td");
                startWhelp = element.StartWhelp;
                endWhelp = element.EndWhelp
                console.log(startWhelp);
                console.log(endWhelp);
                startCell.innerHTML = startWhelp;
                endCell.innerHTML = endWhelp;
                newRow.appendChild(startCell);
                newRow.appendChild(endCell);
                thisTableBody.appendChild(newRow);
            });
        });
}

async function drawChart() {
    var data1 = new google.visualization.DataTable();
    data1.addColumn('date', 'Date');
    data1.addColumn('number', 'Temperature');
    var newData = new Array();
    newData = await prepareDataForChart();
    console.log(newData);
    console.log("Length of new Data = " + newData.length);
    var numRows = newData.length;
    for (var i = 0; i < numRows; i++) {
        console.log(newData[i]);
        data1.addRow(newData[i]);
    }

    var options = {
        hAxis: {
            format: 'M/d/YY',
            title: 'Time'
        },
        vAxis: {
            title: 'Temperature'
        },
        backgroundColor: '#f1f8e9',
        legend: { position: "none" }
    };

    var dataChart = new google.visualization.LineChart(document.getElementById('chart_div'));
    dataChart.draw(data1, options);
}

async function prepareDataForChart() {
    var bigArray = new Array();
    var i = 0;
    var data = await fetch('GetMomDogTemps.php?dogID=' + getCookie("dogID") + "&session=" + getCookie("session"))
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            obj.forEach(function (element) {
                var smallArray = new Array();
                var day = parseInt(element.date.day);
                var month = parseInt(element.date.month) - 1;
                var year = parseInt(element.date.year);
                var temp = element.Temp;
                smallArray[0] = new Date(year, month, day);
                smallArray[1] = parseInt(temp);
                bigArray[i] = smallArray;
                i++;
            });
        });
    return bigArray;
}

function resizeChart() {
    chart.draw(data, options);
    if (document.addEventListener) {
        window.addEventListener('resize', resizeChart);
    }
    else if (document.attachEvent) {
        window.attachEvent('onresize', resizeChart);
    }
    else {
        window.resize = resizeChart;
    }
}

function loadLitterInfo(){
    var session = getCookie("session");
    var dogID = getCookie("dogID");
    var litterNameDiv = document.getElementById("litterNameDiv");
    var whelpStartDateDiv = document.getElementById("whelpStartDateDiv");

    fetch('GetMomLitters.php?dogID=' + dogID + "&session=" + session) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            console.log(obj);
            litterNameDiv.innerHTML = "Litter of " + obj[0].MotherName;
            whelpStartDateDiv.innerHTML = "Whelp started " + obj[0].StartWhelp;
          // obj[0].MotherName 
          // obj[1][0][0].Name // [Gets first puppy name]

            
        });


}

function addMed(medication) {
    var d = Date.now();
    var dogID = getCookie("dogID");
    if (dogID != "") {
        var note = prompt("Please add medication information", "Date: " + timeConverter(d) + " Gave " + medication + ".");
        if (note != null) {
            var url = "AddMomDogNotes.php?session=" + getCookie("session");
            var data = {};
            data.Note = note;
            data.DogID = getCookie("dogID");
            console.log(JSON.stringify(data));
            fetch(url, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, cors, *same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json; charset=utf-8",
                    // "Content-Type": "application/x-www-form-urlencoded",
                },
                redirect: "follow", // manual, *follow, error
                referrer: "no-referrer", // no-referrer, *client
                body: JSON.stringify(data), // body data type must match "Content-Type" header
            })
                //.then(response => response.json()) // parses response to JSON
                .then((responseContent) => {
                    console.log(responseContent);
                });
        }
    }
}

function addMedi(x) {
    var txt;
    txt = x;
    document.getElementById("test1").innerHTML = txt;
}

function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

function logout() {
    document.cookie = "session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/PawsOnCall;";
    document.cookie = "dogID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/PawsOnCall;";
    document.cookie = "litterID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/PawsOnCall;";
    fetch("logoff.php");
    window.location.href = "login.html";
}

function verifySessionCookie() {
    var sessionKey = getCookie("session");
    if (sessionKey == "" || sessionKey == null) {
        window.location.href = "login.html";
    }
}

function handleSearchKeyPress(e) {
    if (e.keyCode === 13) {
        redirectToSearch();
    }

    return false;
}


// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
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

function loadMotherInfo() {
    loadGoogle();
    var searchMessage = document.getElementById("searchMessage");
    var motherContent = document.getElementById("wrapper");
    if (getCookie("dogID") == "" || getCookie("dogID") == null) {
        searchMessage.style.display = "block";
        motherContent.style.display = "none";
    }
    else {
        searchMessage.style.display = "none";
        motherContent.style.display = "block";
    }
    // verifySessionCookie(); //Removed to help chris debug
    var dogID = getCookie("dogID");
    var session = getCookie("session");
    var dogNameDiv = document.getElementById("dogNameDiv");
    var noteTable = document.getElementById("noteTable");
    noteTable.classList.add("table");
    noteTable.classList.add("table");
    var dogBreedDiv = document.getElementById("breedDiv");

    fetch('GetMomDogInfo.php?dogID=' + dogID + "&session=" + session) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            console.log(obj);
            dogNameDiv.textContent = obj.dogInfo[0].Name;
            dogBreedDiv.textContent = obj.dogInfo[0].Breed;

            obj.dogUpdates.forEach(function (element) {
                var newRow = document.createElement("tr");
                var newCell = document.createElement("td");
                newCell.innerHTML = element.Note;
                newRow.appendChild(newCell);
                noteTable.appendChild(newRow);
            });
        });
}

function loginUser() {
    // Call login.php with username and SHA-1 hashed password in the POST data.
    var emailInput = document.getElementById("emailInput");
    var passwordInput = document.getElementById("passwordInput");
    var url = "login.php";
    var username = "no@nomail.com";
    var password = "steve";
    var data = {};
    data.user_name = emailInput.value;
    data.hashed_password = SHA1(passwordInput.value);

    fetch(url, {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            // "Content-Type": "application/x-www-form-urlencoded",
        },
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
        body: JSON.stringify(data), // body data type must match "Content-Type" header
    })
        .then(response => response.json()) // parses response to JSON
        .then((data) => {
            console.log(data);
            document.cookie = "session=" + data.sessionKey;
            document.cookie = "admin=" + data.admin;
            console.log(document.cookie);
            window.location.href = "mother.html";
        });
}

function addDogNote() {
    var d = Date.now();
    var dogID = getCookie("dogID");
    if (dogID != "") {
        var note = prompt("Please add a note", "Date: " + timeConverter(d) + " Note: ");
        if (note != null) {
            var url = "AddMomDogNotes.php?session=" + getCookie("session");
            var data = {};
            data.Note = note;
            data.DogID = getCookie("dogID");
            console.log(JSON.stringify(data));
            fetch(url, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, cors, *same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json; charset=utf-8",
                    // "Content-Type": "application/x-www-form-urlencoded",
                },
                redirect: "follow", // manual, *follow, error
                referrer: "no-referrer", // no-referrer, *client
                body: JSON.stringify(data), // body data type must match "Content-Type" header
            })
                //.then(response => response.json()) // parses response to JSON
                .then((responseContent) => {
                    console.log(responseContent);


                });
        }
    }
}
function addLitterNote() {
    var d = Date.now();
    if (dogID != "") {
        var note = prompt("Please add a note", "Date: " + timeConverter(d) + " Note: ");
        if (note != null) {
            var url = "AddLitterNotes.php?session=" + getCookie("session");
            var data = {};
            data.Note = note;
            data.DogID = getCookie("litterID");
            console.log(JSON.stringify(data));
            fetch(url, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, cors, *same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json; charset=utf-8",
                    // "Content-Type": "application/x-www-form-urlencoded",
                },
                redirect: "follow", // manual, *follow, error
                referrer: "no-referrer", // no-referrer, *client
                body: JSON.stringify(data), // body data type must match "Content-Type" header
            })
                //.then(response => response.json()) // parses response to JSON
                .then((responseContent) => {
                    console.log(responseContent);


                });
        }
    }
}

function redirectToMother(dogId) {
    document.cookie = "dogID=" + dogId;
    window.location.href = "mother.html";
}

function redirectToAdmin(){
    window.location.href = "./admin/index.php?session=" + getCookie("session");
}

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

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function timeConverter(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    if (a.getMinutes() < 10) {
        min = "0" + a.getMinutes();
    }
    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min;
    return time;
}

/**
* Secure Hash Algorithm (SHA1)
* http://www.webtoolkit.info/
**/
function SHA1(msg) {
    function rotate_left(n, s) {
        var t4 = (n << s) | (n >>> (32 - s));
        return t4;
    };
    function lsb_hex(val) {
        var str = '';
        var i;
        var vh;
        var vl;
        for (i = 0; i <= 6; i += 2) {
            vh = (val >>> (i * 4 + 4)) & 0x0f;
            vl = (val >>> (i * 4)) & 0x0f;
            str += vh.toString(16) + vl.toString(16);
        }
        return str;
    };
    function cvt_hex(val) {
        var str = '';
        var i;
        var v;
        for (i = 7; i >= 0; i--) {
            v = (val >>> (i * 4)) & 0x0f;
            str += v.toString(16);
        }
        return str;
    };
    function Utf8Encode(string) {
        string = string.replace(/\r\n/g, '\n');
        var utftext = '';
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    };
    var blockstart;
    var i, j;
    var W = new Array(80);
    var H0 = 0x67452301;
    var H1 = 0xEFCDAB89;
    var H2 = 0x98BADCFE;
    var H3 = 0x10325476;
    var H4 = 0xC3D2E1F0;
    var A, B, C, D, E;
    var temp;
    msg = Utf8Encode(msg);
    var msg_len = msg.length;
    var word_array = new Array();
    for (i = 0; i < msg_len - 3; i += 4) {
        j = msg.charCodeAt(i) << 24 | msg.charCodeAt(i + 1) << 16 |
            msg.charCodeAt(i + 2) << 8 | msg.charCodeAt(i + 3);
        word_array.push(j);
    }
    switch (msg_len % 4) {
        case 0:
            i = 0x080000000;
            break;
        case 1:
            i = msg.charCodeAt(msg_len - 1) << 24 | 0x0800000;
            break;
        case 2:
            i = msg.charCodeAt(msg_len - 2) << 24 | msg.charCodeAt(msg_len - 1) << 16 | 0x08000;
            break;
        case 3:
            i = msg.charCodeAt(msg_len - 3) << 24 | msg.charCodeAt(msg_len - 2) << 16 | msg.charCodeAt(msg_len - 1) << 8 | 0x80;
            break;
    }
    word_array.push(i);
    while ((word_array.length % 16) != 14) word_array.push(0);
    word_array.push(msg_len >>> 29);
    word_array.push((msg_len << 3) & 0x0ffffffff);
    for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
        for (i = 0; i < 16; i++) W[i] = word_array[blockstart + i];
        for (i = 16; i <= 79; i++) W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
        A = H0;
        B = H1;
        C = H2;
        D = H3;
        E = H4;
        for (i = 0; i <= 19; i++) {
            temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        for (i = 20; i <= 39; i++) {
            temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        for (i = 40; i <= 59; i++) {
            temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        for (i = 60; i <= 79; i++) {
            temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        H0 = (H0 + A) & 0x0ffffffff;
        H1 = (H1 + B) & 0x0ffffffff;
        H2 = (H2 + C) & 0x0ffffffff;
        H3 = (H3 + D) & 0x0ffffffff;
        H4 = (H4 + E) & 0x0ffffffff;
    }
    var temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
    return temp.toLowerCase();
}

function adminShowHide() {
    if (getCookie("admin") == 1) {
        document.getElementById("adminLink").style.display = "flex";
    }
}
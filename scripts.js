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
        var tempFloat = parseFloat(temp);
        if (tempFloat != null && temp == tempFloat && tempFloat >= 90 && tempFloat <= 110) {
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
                endWhelp = element.EndWhelp;
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
                smallArray[1] = parseFloat(temp);
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

function loadLitterInfo() {
    var deadpuppies = 0;
    var stillborn = 0;
    var session = getCookie("session");
    var dogID = getCookie("dogID");
    var litterNameDiv = document.getElementById("litterNameDiv");
    var whelpStartDateDiv = document.getElementById("whelpStartDateDiv");
    var puppyNoteTable = document.getElementById("puppyNoteTable");
    var myDropdown = document.getElementById("myDropdown");
    var whelpStart = document.getElementById("whelpStart");
    var whelpEnd = document.getElementById("whelpEnd");
    var weanStart = document.getElementById("weanStart");
    var weanEnd = document.getElementById("weanEnd");
    var dewormStart = document.getElementById("dewormStart");
    var dewormEnd = document.getElementById("dewormEnd");
    var stillbornsDiv = document.getElementById("stillborns");
    var deathsDiv = document.getElementById("deaths");

    var txtFather = document.getElementById("father");

    var litterInfoTableBody = document.getElementById("litterInfoTableBody");

    fetch('GetMomLitters.php?dogID=' + dogID + "&session=" + session) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            document.cookie = "litter=" + obj[0].ID;
            litterNameDiv.innerHTML = "Litter of " + obj[0].MotherName;
            whelpStartDateDiv.innerHTML = "Whelp started " + obj[0].StartWhelp;
            txtFather.value = obj[0].FatherName;
            loadLitterWeightTable(obj[0].ID);

            // For each litter
            obj.forEach(function (element) {
                var newDdlLitter = document.createElement("a");
                newDdlLitter.onclick = function () { loadLitterInfoByID(element.ID); };
                newDdlLitter.innerHTML = "Whelp started " + element.StartWhelp;
                myDropdown.appendChild(newDdlLitter);
                weanStart.value = element.StartWean;
                weanEnd.value = element.EndWean;
                whelpStart.value = element.StartWhelp;
                whelpEnd.value = element.EndWhelp;
                dewormStart.value = element.StartDeworm;
                dewormEnd.value = element.StartDeworm;
            });

            // For each note in first litter
            obj[0][1].forEach(function (element) {
                var newRow = document.createElement("tr");
                var newCell = document.createElement("td");
                newCell.innerHTML = element.Note;
                newRow.appendChild(newCell);
                puppyNoteTable.appendChild(newRow);
            });
            litterInfoTableBody.innerHTML = "";

            // For each puppy
            obj[0][0].forEach(function (element) {
                if (element.Stillborn == 1) {
                    stillborn++;
                }
                var deathDate = new Date(element.Deathdate);
                if (deathDate < Date.now()) {
                    deadpuppies++;
                }
                var newRow = document.createElement("tr");
                var newIDCell = document.createElement("td");
                var newSexCell = document.createElement("td");
                newIDCell.innerHTML = element.Name;
                newSexCell.innerHTML = element.Sex;
                newRow.appendChild(newIDCell);
                newRow.appendChild(newSexCell);
                litterInfoTableBody.appendChild(newRow);
            });
            stillbornsDiv.value = stillborn;
            deathsDiv.value = deadpuppies;

        });


}

function addPuppy(){
        var data = {};
        data.name = prompt("Enter Puppy's Collar Color");
        if (data.name != null) {
            var url = "AddPuppies.php?session=" + getCookie("session"); 
            data.volunteerID;
            data.sex;
            data.birthdate;
            data.breed;
            data.litterID;
            data.stillborn;
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

function loadLitterWeightTable(id){
    var litterWeightTable = document.getElementById("litterWeightTable");
    var litterWeightHeaders1 = document.getElementById("litterWeightHeaders1");
    var litterWeightHeaders2 = document.getElementById("litterWeightHeaders2");
    
    fetch('GetLitterWeights.php?litterID=' + id + "&session=" + getCookie("session")) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            console.log(obj);
            obj.forEach(function (element) {
                var newRow1 = document.createElement("tr");
                var newRow2 = document.createElement("tr");
                newRow1.classList.add(element.DogID);
                newRow2.classList.add(element.DogID);

                var nameCell1 = document.createElement("td");
                nameCell1.classList.add("puppyName");
                nameCell1.value = element.Name;
                newRow1.appendChild(nameCell1);

                var nameCell2 = document.createElement("td");
                nameCell2.classList.add("puppyName");
                nameCell2.value = element.Name;
                newRow2.appendChild(nameCell2);

                var w1a = document.createElement("td");
                w1a.classList.add("w1a");
                w1a.setAttribute("contenteditable", true);
                w1a.value = element.d1a;
                newRow1.appendChild(w1a);

                var w1b = document.createElement("td");
                w1b.classList.add("w1b");
                w1b.setAttribute("contenteditable", true);
                w1b.value = element.d1p;
                newRow1.appendChild(w1b);

                var w2a = document.createElement("td");
                w2a.classList.add("w2a");
                w2a.setAttribute("contenteditable", true);
                w2a.value = element.d2a;
                newRow1.appendChild(w2a);

                var w2b = document.createElement("td");
                w2b.classList.add("w2b");
                w2b.setAttribute("contenteditable", true);
                w2b.value = element.d2p;
                newRow1.appendChild(w2b);


                var w3a = document.createElement("td");
                w3a.classList.add("w3a");
                w3a.setAttribute("contenteditable", true);
                w3a.value = element.d3a;
                newRow1.appendChild(w3a);

                var w3b = document.createElement("td");
                w3b.classList.add("w3b");
                w3b.setAttribute("contenteditable", true);
                w3b.value = element.d3p;
                newRow1.appendChild(w3b);

                var w4a = document.createElement("td");
                w4a.classList.add("w4a");
                w4a.setAttribute("contenteditable", true);
                w4a.value = element.d4a;
                newRow1.appendChild(w4a);

                var w4b = document.createElement("td");
                w4b.classList.add("w4b");
                w4b.setAttribute("contenteditable", true);
                w4b.value = element.d4p;
                newRow1.appendChild(w4b);

                var w5a = document.createElement("td");
                w5a.classList.add("w5a");
                w5a.setAttribute("contenteditable", true);
                w5a.value = element.d5a;
                newRow1.appendChild(w5a);

                var w5b = document.createElement("td");
                w5b.classList.add("w5b");
                w5b.setAttribute("contenteditable", true);
                w5b.value = element.d5p;
                newRow1.appendChild(w5b);

                var w6a = document.createElement("td");
                w6a.classList.add("w6a");
                w6a.setAttribute("contenteditable", true);
                w6a.value = element.d6a;
                newRow1.appendChild(w6a);

                var w6b = document.createElement("td");
                w6b.classList.add("w6b");
                w6b.setAttribute("contenteditable", true);
                w6b.value = element.d6p;
                newRow1.appendChild(w6b);

                var w7a = document.createElement("td");
                w7a.classList.add("w7a");
                w7a.setAttribute("contenteditable", true);
                w7a.value = element.d7a;
                newRow1.appendChild(w7a);

                var w7b = document.createElement("td");
                w7b.classList.add("w7b");
                w7b.setAttribute("contenteditable", true);
                w7b.value = element.d7b;
                newRow1.appendChild(w7b);

                var w8a = document.createElement("td");
                w8a.classList.add("w8a");
                w8a.setAttribute("contenteditable", true);
                w8a.value = element.d8a;
                newRow1.appendChild(w8a);

                var w8b = document.createElement("td");
                w8b.classList.add("w8b");
                w8b.setAttribute("contenteditable", true);
                w8b.value = element.d8p;
                newRow1.appendChild(w8b);

                var w9a = document.createElement("td");
                w9a.classList.add("w9a");
                w9a.setAttribute("contenteditable", true);
                w9a.value = element.d9a;
                newRow1.appendChild(w9a);

                var w9b = document.createElement("td");
                w9b.classList.add("w9b");
                w9b.setAttribute("contenteditable", true);
                w9b.value = element.d9p;
                newRow2.appendChild(w9b);

                var w10a = document.createElement("td");
                w10a.classList.add("w10a");
                w10a.setAttribute("contenteditable", true);
                w10a.value = element.d10a;
                newRow2.appendChild(w10a);

                var w10b = document.createElement("td");
                w10b.classList.add("w10b");
                w10b.setAttribute("contenteditable", true);
                w10b.value = element.d10p;
                newRow2.appendChild(w10b);

                var w11a = document.createElement("td");
                w11a.classList.add("w11a");
                w11a.setAttribute("contenteditable", true);
                w11a.value = element.d11a;
                newRow2.appendChild(w11a);

                var w11b = document.createElement("td");
                w11b.classList.add("w11b");
                w11b.setAttribute("contenteditable", true);
                w11b.value = element.d11p;
                newRow2.appendChild(w11b);

                var w12a = document.createElement("td");
                w12a.classList.add("w12a");
                w12a.setAttribute("contenteditable", true);
                w12a.value = element.d12a;
                newRow2.appendChild(w12a);
                
                var w12b = document.createElement("td");
                w12b.classList.add("w12b");
                w12b.setAttribute("contenteditable", true);
                w12b.value = element.d12p;
                newRow2.appendChild(w12b);

                var w13a = document.createElement("td");
                w13a.classList.add("w13a");
                w13a.setAttribute("contenteditable", true);
                w13a.value = element.d13a;
                newRow2.appendChild(w13a);

                var w13b = document.createElement("td");
                w13b.classList.add("w13b");
                w13b.setAttribute("contenteditable", true);
                w13b.value = element.d13p;
                newRow2.appendChild(w13b);

                var w14a = document.createElement("td");
                w14a.classList.add("w14a");
                w14a.setAttribute("contenteditable", true);
                w14a.value = element.d14a;
                newRow2.appendChild(w14a);

                var w14b = document.createElement("td");
                w14b.classList.add("w14b");
                w14b.setAttribute("contenteditable", true);
                w14b.value = element.d14p;
                newRow2.appendChild(w14b);
                
                var w3w = document.createElement("td");
                w3w.classList.add("w3w");
                w3w.setAttribute("contenteditable", true);
                w3w.value = element.w3;
                newRow2.appendChild(w3w);

                var w4w = document.createElement("td");
                w4w.classList.add("w4w");
                w4w.setAttribute("contenteditable", true);
                w4w.value = element.w4;
                newRow2.appendChild(w4w);               

                var w5w = document.createElement("td");
                w5w.classList.add("w5w");
                w5w.setAttribute("contenteditable", true);
                w5w.value = element.w5;
                newRow2.appendChild(w5w);

                var w6w = document.createElement("td");
                w6w.classList.add("w6w");
                w6w.setAttribute("contenteditable", true);
                w6w.value = element.w6;
                newRow2.appendChild(w6w);

                var w7w = document.createElement("td");
                w7w.classList.add("w7w");
                w7w.setAttribute("contenteditable", true);
                w7w.value = element.w7;
                newRow2.appendChild(w7w);

                var w8w = document.createElement("td");
                w8w.classList.add("w8w");
                w8w.setAttribute("contenteditable", true);
                w8w.value = element.w8;
                newRow2.appendChild(w8w);

                litterWeightHeaders1.insertAdjacentHTML('afterend', newRow1);
                litterWeightHeaders2.insertAdjacentHTML('afterend', newRow2);
                
            })
        });

}

function loadLitterInfoByID(id) {
    document.cookie = "litter=" + id;
    var session = getCookie("session");
    var dogID = getCookie("dogID");
    var txtFather = document.getElementById("father");
    var litterNameDiv = document.getElementById("litterNameDiv");
    var whelpStartDateDiv = document.getElementById("whelpStartDateDiv");
    var puppyNoteTable = document.getElementById("puppyNoteTable");
    var myDropdown = document.getElementById("myDropdown");
    var whelpStart = document.getElementById("whelpStart");
    var whelpEnd = document.getElementById("whelpEnd");
    var weanStart = document.getElementById("weanStart");
    var weanEnd = document.getElementById("weanEnd");
    var dewormStart = document.getElementById("dewormStart");
    var dewormEnd = document.getElementById("dewormEnd");
    var stillborn = 0;
    var deadpuppies = 0;
    var stillbornsDiv = document.getElementById("stillborns");
    var deathsDiv = document.getElementById("deaths");
    var litterInfoTableBody = document.getElementById("litterInfoTableBody");

    loadLitterWeightTable(id);

    fetch('GetMomLitters.php?dogID=' + dogID + "&session=" + session) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            // For each litter
            obj.forEach(function (element) {
                if (element.ID == id) {
                    console.log(element);
                    litterNameDiv.innerHTML = "Litter of " + element.MotherName;
                    whelpStartDateDiv.innerHTML = "Whelp started " + element.StartWhelp;
                    txtFather.value = element.FatherName;
                    weanStart.value = element.StartWean;
                    weanEnd.value = element.EndWean;
                    whelpStart.value = element.StartWhelp;
                    whelpEnd.value = element.EndWhelp;
                    dewormStart.value = element.StartDeworm;
                    dewormEnd.value = element.EndDeworm;
                    puppyNoteTable.innerHTML = "";
                    // Note population for the selected litter
                    element[1].forEach(function (element) {
                        var newRow = document.createElement("tr");
                        var newCell = document.createElement("td");
                        newCell.innerHTML = element.Note;
                        newRow.appendChild(newCell);
                        puppyNoteTable.appendChild(newRow);
                    });
                    litterInfoTableBody.innerHTML = "";
                    // For each puppy
                    element[0].forEach(function (element) {
                        if (element.Stillborn == 1) {
                            stillborn++;
                        }
                        var deathDate = new Date(element.Deathdate);
                        if (deathDate < Date.now()) {
                            deadpuppies++;
                        }
                        var newRow = document.createElement("tr");
                        var newIDCell = document.createElement("td");
                        var newSexCell = document.createElement("td");
                        newIDCell.innerHTML = element.Name;
                        newSexCell.innerHTML = element.Sex;
                        newRow.appendChild(newIDCell);
                        newRow.appendChild(newSexCell);
                        litterInfoTableBody.appendChild(newRow);
                    });
                    stillbornsDiv.value = stillborn;
                    deathsDiv.value = deadpuppies;

                }
            });

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
    document.cookie = "litter=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/PawsOnCall;";
    fetch("logoff.php");
    window.location.href = "/PawsOnCall/login.html";
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

function handleLoginKeyPress(e) {
    if (e.keyCode === 13) {
        loginUser();
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
            if (data.sessionKey != "" && data.sessionKey != null) {
                window.location.href = "mother.html";
            }
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
    var litterID = getCookie("litter");
    console.log(litterID);
    if (litterID != "") {
        var note = prompt("Please add a note", "Date: " + timeConverter(d) + " Note: ");
        if (note != null) {
            var url = "AddLitterNotes.php?session=" + getCookie("session");
            var data = {};
            data.Note = note;
            data.LitterID = getCookie("litter");
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

function getVolunteerInfo() {
    var dogID = getCookie("dogID");
    var VolunteerID;
    var txtName = document.getElementById("hostName");
    var txtStreet = document.getElementById("hostStreet");
    var txtCity = document.getElementById("hostCity");
    var txtState = document.getElementById("hostState");
    var txtZIP = document.getElementById("hostZIP");
    var txtPhone = document.getElementById("hostPhone");
    var dogName = document.getElementById("dogName");
    var dogBreed = document.getElementById("dogBreed");
    fetch("GetMomDogInfo.php?session=" + getCookie("session") + "&dogID=" + dogID) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            console.log(obj);
            dogName.innerHTML = obj.dogInfo[0].Name;
            dogBreed.innerHTML = obj.dogInfo[0].Breed;
            VolunteerID = obj.dogInfo[0].VolunteerID;
            console.log(VolunteerID);
        })

        .then((v) => {
            fetch("GetVolunteerInfo.php?session=" + getCookie("session") + "&volunteerID=" + VolunteerID) //Add the file name
                .then(response => response.json())
                .then((data) => {
                    var obj1 = JSON.parse(JSON.stringify(data));
                    console.log(obj1);

                    txtName.value = obj1[0].Name;
                    txtStreet.value = obj1[0].Address;
                    txtCity.value = obj1[0].City;
                    txtState.value = obj1[0].State;
                    txtZIP.value = obj1[0].ZIP;
                    txtPhone.value = obj1[0].Phone;

                    console.log(obj1[0].Name);
                });

        }
        );
}

function redirectToMother(dogId) {
    document.cookie = "dogID=" + dogId;
    window.location.href = "mother.html";
}

function redirectToAdmin() {
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
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
            data.dogID = getCookie("dogID");
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
                });
        }
        else {
            alert("please enter a number between 90 and 110");
        }
    }
    loadMotherInfo();

}

function getWhelpDates() {
    verifySessionCookie();
    var startWhelp;
    var endWhelp;
    var thisTableBody = document.getElementById("whelp");

    fetch('GetMomLitters.php?dogID=' + getCookie("dogID") + "&session=" + getCookie("session"), {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    })
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            obj.forEach(function (element) {
                var newRow = document.createElement("tr");
                var startCell = document.createElement("td");
                var endCell = document.createElement("td");
                startWhelp = element.StartWhelp;
                endWhelp = element.EndWhelp;
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
    var data = await fetch('GetMomDogTemps.php?dogID=' + getCookie("dogID") + "&session=" + getCookie("session"), {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    })
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
    verifySessionCookie();
    var searchMessage = document.getElementById("searchMessage");
    var litterContent = document.getElementById("wrapper");
    if (getCookie("dogID") == "" || getCookie("dogID") == null) {
        searchMessage.style.display = "block";
        litterContent.style.display = "none";
    }
    else {
        searchMessage.style.display = "none";
        litterContent.style.display = "block";
    }
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
    var volunteerIDHolder = document.getElementById("volunteerIDHolder");
    var breedHolder = document.getElementById("breedHolder");
    var litterIDHolder = document.getElementById("litterIDHolder");

    var txtFather = document.getElementById("father");

    var litterInfoTableBody = document.getElementById("litterInfoTableBody");

    fetch('GetMomLitters.php?dogID=' + dogID + "&session=" + session, {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    }) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            document.cookie = "litter=" + obj[0].ID;
            litterNameDiv.innerHTML = "Litter of " + obj[0].MotherName;
            whelpStartDateDiv.innerHTML = "Whelp started " + obj[0].StartWhelp;
            breedHolder.innerHTML = obj[0].MotherBreed;
            volunteerIDHolder.innerHTML = obj[0].VolunteerID;
            litterIDHolder.innerHTML = obj[0].ID;
            txtFather.value = obj[0].FatherName;
            loadLitterWeightTable(obj[0].ID);

            weanStart.value = validateDate(obj[0].StartWean);
            weanEnd.value = validateDate(obj[0].EndWean);
            whelpStart.value = validateDate(obj[0].StartWhelp);
            whelpEnd.value = validateDate(obj[0].EndWhelp);
            dewormStart.value = validateDate(obj[0].StartDeworm);
            dewormEnd.value = validateDate(obj[0].EndDeworm);

            // For each litter
            obj.forEach(function (element) {
                var newDdlLitter = document.createElement("a");
                newDdlLitter.onclick = function () { loadLitterInfoByID(element.ID); };
                newDdlLitter.innerHTML = "Whelp started " + element.StartWhelp;
                myDropdown.appendChild(newDdlLitter);

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
                newRow.id = element.ID;
                var newIDCell = document.createElement("td");
                var newSexCell = document.createElement("td");
                var newBirthdateCell = document.createElement("td");
                var newStillbornCell = document.createElement("td");
                var newDeadPuppyCell = document.createElement("td");
                var newStillbornInput = document.createElement("input");
                var newDeadPuppyInput = document.createElement("input");
                newDeadPuppyInput.type = "checkbox";
                newStillbornInput.type = "checkbox";
                newIDCell.innerHTML = element.Name;
                newIDCell.setAttribute("contenteditable", true);
                newSexCell.innerHTML = element.Sex;
                newSexCell.setAttribute("contenteditable", true);
                newBirthdateCell.innerHTML = element.Birthdate;
                newBirthdateCell.setAttribute("contenteditable", true);
                var date = new Date(element.Deathdate);
                if (date <= Date.now()) {
                    newDeadPuppyInput.checked = true;
                } else {
                    newDeadPuppyInput.checked = false;
                }
                newDeadPuppyCell.className = element.Deathdate.replace(" ","%20");
                newDeadPuppyCell.appendChild(newDeadPuppyInput);
                if (element.Stillborn == 1) {
                    newStillbornInput.checked = true;
                } else {
                    newStillbornInput.checked = false;
                }
                newStillbornCell.appendChild(newStillbornInput);
                newRow.appendChild(newIDCell);
                newRow.appendChild(newSexCell);
                newRow.appendChild(newBirthdateCell);
                newRow.appendChild(newStillbornCell);
                newRow.appendChild(newDeadPuppyCell);
                litterInfoTableBody.appendChild(newRow);

            });
            stillbornsDiv.value = stillborn;
            deathsDiv.value = deadpuppies;

        });


}

function addImportantDates() {
       var litterID = rewriteDate(document.getElementById("litterIDHolder").innerHTML);
    var whelpStart = rewriteDate(document.getElementById("whelpStart").value);
    if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(whelpStart)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }
    var whelpEnd = rewriteDate(document.getElementById("whelpEnd").value);
    if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(whelpEnd)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }
    var weanStart = rewriteDate(document.getElementById("weanStart").value);
    if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(weanStart)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }
    var weanEnd = rewriteDate(document.getElementById("weanEnd").value);
    if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(weanEnd)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }
    var dewormStart = rewriteDate(document.getElementById("dewormStart").value);
    if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(dewormStart)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }
    var dewormEnd = rewriteDate(document.getElementById("dewormEnd").value);
    if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(dewormEnd)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }

    var dateData = {};
    dateData.litterID = litterID;
    dateData.startWhelp = whelpStart;
    dateData.endWhelp = whelpEnd;
    dateData.startWean = weanStart;
    dateData.endWean = weanEnd;
    dateData.startDeworm = dewormStart;
    dateData.endDeworm = dewormEnd;

    var url = "AddImportantDates.php?session=" + getCookie("session");

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
        body: JSON.stringify(dateData), // body data type must match "Content-Type" header
    })
        .then(response => response.json()) // parses response to JSON
        .then((responseContent) => {
        });
    alert("Changes Saved");
}


function savePuppy() {
    var thisTbody = document.getElementById("litterInfoTableBody");
    var dogID;
    var collarColor;
    var sex;
    var DOB;
    var stillBorn;
    var deceased;
    var theMasterPupData = [];
    var url = "UpdatePuppies.php?session=" + getCookie("session");
    for (var i = 0; i < thisTbody.rows.length; i++) {
        var pupData = {};
        collarColor = thisTbody.rows[i].cells[0].textContent.replace('\n', "");
        sex = thisTbody.rows[i].cells[1].textContent.replace('\n', "");
        if (sex != "M" && sex != "F") { alert("Enter M or F for Sex"); return;  }
        DOB = thisTbody.rows[i].cells[2].textContent.replace('\n', "");
        if (!/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/.test(DOB)) { alert("Enter Date in YYYY-MM-DD HH:MM:SS Format"); return; }
        dogID = thisTbody.rows[i].id;
        if (thisTbody.rows[i].cells[3].getElementsByTagName("input")[0].checked) {
            stillBorn = 1;
        }
        else {
            stillBorn = 0;
        }
        if (thisTbody.rows[i].cells[4].getElementsByTagName("input")[0].checked && thisTbody.rows[i].cells[4].className.replace("%20"," ") == "2038-01-01 00:00:00") {
            deceased = timeConverter(Date.now(),2);
        }else if(thisTbody.rows[i].cells[4].getElementsByTagName("input")[0].checked){
            deceased = thisTbody.rows[i].cells[4].className.replace("%20"," ");
        }
        else {
            deceased = "2038-01-01 00:00:00";
        }
        pupData.dogID = dogID;
        pupData.name = collarColor;
        pupData.sex = sex;
        pupData.birthdate = DOB;
        pupData.stillborn = stillBorn;
        pupData.deathdate = deceased;

        theMasterPupData.push(pupData);
    }
    console.log(theMasterPupData);
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
        body: JSON.stringify(theMasterPupData), // body data type must match "Content-Type" header
    })
        .then((responseContent) => {
        });

    litterID = document.getElementById("litterIDHolder").innerHTML;
    loadLitterInfoByID(litterID);
    alert("Changes Saved");
}

function addPuppy() {
    var data = {};

    var collarColor = prompt("Enter Puppy's Collar Color");
    // collar color validation
    if (collarColor != null) {
        var url = "AddPuppies.php?session=" + getCookie("session");
        data.volunteerID = document.getElementById("volunteerIDHolder").innerHTML;
        data.name = collarColor;
    } else {
        alert("please enter either a valid collar color e.g. 'Blue'");
        return;
    }

    var sex = prompt("Enter Puppy's Sex ('M' or 'F'):");
    //sex validation
    sex = sex.toString().toUpperCase();
    if (sex != null && (sex == 'M' || sex == 'F')) {
        data.sex = sex;
    } else {
        alert("please enter either 'M' or 'F");
        return;
    }

    var birthDate = prompt("Enter Puppy's Date Of Birth (YYYY-MM-DD HH:MM):");
    //birth date validation
    birthDate = birthDate.toString();
    if (birthDate != null && /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}/.test(birthDate)) {
        birthDate = birthDate + ":00";
        data.birthdate = birthDate;
    } else {
        alert("please enter a valid birthdate: (YYYY-MM-DD HH-MM)");
        return;
    }

    var stillBorn = prompt("Is this puppy stillborn? (Enter '1' if Yes or '0' No):");
    //stillBorn validation
    stillBorn = parseInt(stillBorn);
    if (stillBorn != null && (stillBorn == '1' || stillBorn == '0')) {
        data.stillborn = stillBorn;
        data.breed = document.getElementById("breedHolder").innerHTML;
        data.litterID = document.getElementById("litterIDHolder").innerHTML;
    } else {
        alert("please enter either '1' for Yes or '0' for No");
        return;
    }

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
        });

    litterID = document.getElementById("litterIDHolder").innerHTML;
    loadLitterInfoByID(litterID);

}

function saveLitterWeightTable() {
    var data = [];
    var litterWeightTable = document.getElementById("litterWeightTable");
    var numPuppies = (litterWeightTable.rows.length - 2) / 2;
    for (var i = 1; i <= numPuppies; i++) {
        var innerData = {};
        innerData.DogID = String(litterWeightTable.rows[i].className);
        for (var ir = 1; ir < litterWeightTable.rows[i].cells.length; ir++) {
            innerData[String(litterWeightTable.rows[i].cells[ir].className)] = litterWeightTable.rows[i].cells[ir].textContent
                .replace('\n', "")
                .replace(" ", "");
            if (innerData[String(litterWeightTable.rows[i].cells[ir].className)] == "") {
                innerData[String(litterWeightTable.rows[i].cells[ir].className)] = "NULL";
            }
            innerData[String(litterWeightTable.rows[i + numPuppies + 1].cells[ir].className)] = litterWeightTable.rows[i + numPuppies + 1].cells[ir].textContent
                .replace('\n', "")
                .replace(" ", "");
            if (innerData[String(litterWeightTable.rows[i + numPuppies + 1].cells[ir].className)] == "") {
                innerData[String(litterWeightTable.rows[i + numPuppies + 1].cells[ir].className)] = "NULL";
            }
        }
        data.push(innerData);
    }


    var url = "AddLitterWeights.php?session=" + getCookie("session");
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
        });
    alert("Weights Saved");
}

function loadLitterWeightTable(id) {
    var litterWeightTable = document.getElementById("litterWeightTable");
    var litterWeightHeaders1 = document.getElementById("litterWeightHeaders1");
    var litterWeightHeaders2 = document.getElementById("litterWeightHeaders2");

    // Clear previous weight table loads
    for (var i = 0, row; row = litterWeightTable.rows[i]; i++) {
        if (litterWeightTable.rows[i].id != "litterWeightHeaders1" && litterWeightTable.rows[i].id != "litterWeightHeaders2") {
            litterWeightTable.rows[i].remove();
            i--;
        }
    }

    fetch('GetLitterWeights.php?litterID=' + id + "&session=" + getCookie("session")) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            obj.forEach(function (element) {
                var newRow1 = document.createElement("tr");
                var newRow2 = document.createElement("tr");
                newRow1.classList.add(element.DogID);
                newRow2.classList.add(element.DogID);
                var keys = Object.keys(element);

                var nameCell1 = document.createElement("td");
                nameCell1.classList.add("puppyName");
                nameCell1.innerHTML = element.Name;
                var nameCell2 = document.createElement("td");
                nameCell2.classList.add("puppyName");
                nameCell2.innerHTML = element.Name;
                newRow1.appendChild(nameCell1);
                newRow2.appendChild(nameCell2);
                for (var i = 2; i < 19; i++) {
                    var cell1 = document.createElement("td");
                    cell1.classList.add(String(keys[i]));
                    cell1.setAttribute("contenteditable", true);
                    cell1.innerHTML = element[String(keys[i])];
                    newRow1.appendChild(cell1);
                    var cell2 = document.createElement("td");
                    cell2.classList.add(String(keys[i + 17]));
                    cell2.setAttribute("contenteditable", true);
                    cell2.innerHTML = element[String(keys[i + 17])];
                    newRow2.appendChild(cell2);
                }
                litterWeightHeaders1.insertAdjacentElement('afterend', newRow1);
                litterWeightHeaders2.insertAdjacentElement('afterend', newRow2);
            }
            );
        });
}

function loadLitterInfoByID(id) {
    verifySessionCookie();
    var searchMessage = document.getElementById("searchMessage");
    var litterContent = document.getElementById("wrapper");
    if (getCookie("dogID") == "" || getCookie("dogID") == null) {
        searchMessage.style.display = "block";
        litterContent.style.display = "none";
    }
    else {
        searchMessage.style.display = "none";
        litterContent.style.display = "block";
    }
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
    var volunteerIDHolder = document.getElementById("volunteerIDHolder");
    var breedHolder = document.getElementById("breedHolder");
    var litterIDHolder = document.getElementById("litterIDHolder");

    loadLitterWeightTable(id);

    fetch('GetMomLitters.php?dogID=' + dogID + "&session=" + session, {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    }) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            // For each litter
            obj.forEach(function (element) {
                if (element.ID == id) {
                    litterNameDiv.innerHTML = "Litter of " + element.MotherName;
                    breedHolder.innerHTML = element.MotherBreed;
                    volunteerIDHolder.innerHTML = element.VolunteerID;
                    litterIDHolder.innerHTML = element.ID;
                    whelpStartDateDiv.innerHTML = "Whelp started " + element.StartWhelp;
                    txtFather.value = element.FatherName;
                    weanStart.value = validateDate(element.StartWean);
                    weanEnd.value = validateDate(element.EndWean);
                    whelpStart.value = validateDate(element.StartWhelp);
                    whelpEnd.value = validateDate(element.EndWhelp);
                    dewormStart.value = validateDate(element.StartDeworm);
                    dewormEnd.value = validateDate(element.EndDeworm);
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
                        var newDeadPuppyInput = document.createElement("input");
                        newDeadPuppyInput.type = "checkbox";
                        var deathDate = new Date(element.Deathdate);
                        if (deathDate < Date.now()) {
                            deadpuppies++;
                            newDeadPuppyInput.checked = true;
                        } else {
                            newDeadPuppyInput.checked = false;
                        }
                        var newRow = document.createElement("tr");
                        newRow.id = element.ID;
                        var newIDCell = document.createElement("td");
                        var newSexCell = document.createElement("td");
                        var newBirthdateCell = document.createElement("td");
                        var newStillbornCell = document.createElement("td");
                        var newDeadPuppyCell = document.createElement("td");
                        var newStillbornInput = document.createElement("input");
                        newStillbornInput.type = "checkbox";

                        newBirthdateCell.innerHTML = element.Birthdate;
                        newIDCell.setAttribute("contenteditable", true);
                        newSexCell.setAttribute("contenteditable", true);
                        newBirthdateCell.setAttribute("contenteditable", true);
                        newDeadPuppyCell.className = element.Deathdate.replace(" ","%20");
                        if (element.Stillborn == 1) {
                            newStillbornInput.checked = true;
                        } else {
                            newStillbornInput.checked = false;
                        }
                        newIDCell.innerHTML = element.Name;
                        newSexCell.innerHTML = element.Sex;
                        newStillbornCell.appendChild(newStillbornInput);
                        newDeadPuppyCell.appendChild(newDeadPuppyInput);
                        newRow.appendChild(newIDCell);
                        newRow.appendChild(newSexCell);
                        newRow.appendChild(newBirthdateCell);
                        newRow.appendChild(newStillbornCell);
                        newRow.appendChild(newDeadPuppyCell);
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
                    loadMotherInfo();
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
    fetch("logoff.php", {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    });
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
    verifySessionCookie();
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
    var dogID = getCookie("dogID");
    var session = getCookie("session");
    var dogNameDiv = document.getElementById("dogNameDiv");
    var noteTable = document.getElementById("noteTable");
    var noteTile = document.getElementById("noteTile");
    noteTile.style.maxHeight = "280px";
    noteTable.innerHTML = "";
    var dogBreedDiv = document.getElementById("breedDiv");

    fetch('GetMomDogInfo.php?dogID=' + dogID + "&session=" + session, {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    }) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
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
            document.cookie = "session=" + data.sessionKey;
            document.cookie = "admin=" + data.admin;
            if (data.sessionKey != "" && data.sessionKey != null) {
                window.location.href = "mother.html";
            } else {
                alert("Incorrect Username or Password");
                passwordInput.value = "";
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
                    loadMotherInfo();
                });
        }
    }
}

function addLitterNote() {
    var d = Date.now();
    var litterID = document.getElementById("litterIDHolder").innerHTML;
    if (litterID != "") {
        var note = prompt("Please add a note", "Date: " + timeConverter(d) + " Note: ");
        if (note != null) {
            var url = "AddLitterNotes.php?session=" + getCookie("session");
            var data = {};
            data.Note = note;
            data.LitterID = getCookie("litter");
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
                });
        }
    }
    loadLitterInfoByID(litterID);
}

function getVolunteerInfo() {
    verifySessionCookie();
    var searchMessage = document.getElementById("searchMessage");
    var volunteerContent = document.getElementById("wrapper");
    if (getCookie("dogID") == "" || getCookie("dogID") == null) {
        searchMessage.style.display = "block";
        volunteerContent.style.display = "none";
    }
    else {
        searchMessage.style.display = "none";
        volunteerContent.style.display = "block";
    }
    var dogID = getCookie("dogID");
    var VolunteerID;
    var txtName = document.getElementById("hostName");
    var txtStreet = document.getElementById("hostStreet");
    var txtCity = document.getElementById("hostCity");
    var txtState = document.getElementById("hostState");
    var txtZIP = document.getElementById("hostZIP");
    var txtPhone = document.getElementById("hostPhone");
    var dogName = document.getElementById("dogNameDiv");
    var dogBreed = document.getElementById("breedDiv");
    fetch("GetMomDogInfo.php?session=" + getCookie("session") + "&dogID=" + dogID) //Add the file name
        .then(response => response.json())
        .then((data) => {
            var obj = JSON.parse(JSON.stringify(data));
            dogName.innerHTML = obj.dogInfo[0].Name;
            dogBreed.innerHTML = obj.dogInfo[0].Breed;
            VolunteerID = obj.dogInfo[0].VolunteerID;
        })

        .then((v) => {
            fetch("GetVolunteerInfo.php?session=" + getCookie("session") + "&volunteerID=" + VolunteerID) //Add the file name
                .then(response => response.json())
                .then((data) => {
                    var obj1 = JSON.parse(JSON.stringify(data));
                    txtName.value = obj1[0].Name;
                    txtStreet.value = obj1[0].Address;
                    txtCity.value = obj1[0].City;
                    txtState.value = obj1[0].State;
                    txtZIP.value = obj1[0].ZIP;
                    txtPhone.value = obj1[0].Phone;
                });
        }
        );
}

function redirectToMother(dogId) {
    verifySessionCookie();
    document.cookie = "dogID=" + dogId;
    window.location.href = "mother.html";
}

function redirectToAdmin() {
    verifySessionCookie();
    window.location.href = "./admin/index.php?session=" + getCookie("session");
}

function redirectToSearch() {
    verifySessionCookie();
    var dogName = document.getElementById("searchBar").value;
    window.location.href = "searchresult.html?search=" + dogName;
}

function searchForDogs() {
    const urlParams = new URLSearchParams(window.location.search);
    const dogName = urlParams.get('search');
    var searchResultSection = document.getElementById("searchResults");
    fetch('DogSearch.php?search=' + dogName + "&session=" + getCookie("session"), {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    }) //Add the file name
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

function timeConverter(UNIX_timestamp,format) {
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
    var time;
    if(format == 2){
        var f2month = a.getMonth() + 1;
        if(f2month < 10){
            f2month = "0" + f2month;
        }
        if(date < 10){
            date = "0" + date;
        }
        if(hour < 10){
            hour = "0" + hour;
        }
        time = year + '-' + f2month + '-' + date + ' ' + hour + ':' + min + ':00';
    }else{
       time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min; 
    }
    return time;
}
function validateDate(date) {
    if (date == '2038-01-01 00:00:00') {
        return "";
    } else {
        return date;
    }
}
function rewriteDate(date) {
    if (date == '') {
        return "2038-01-01 00:00:00";
    } else {
        return date;
    }
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

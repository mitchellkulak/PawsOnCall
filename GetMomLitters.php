<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'authenticate.php';
include 'dbconnect.php';

$input = json_decode(authenticate(urldecode($_GET['session'])), true);
if ($input['error'] == 'auth error') {
    $error = array('error' => 'auth error');
    echo json_encode($error);
}
else{



if (mysqli_connect_error($db))
{
    die("Can't connect");
}
else {
    $i = 0;
    $littersArray = array();
    $dogID = mysqli_real_escape_string($db,urldecode($_GET['dogID']));
    $dogData = mysqli_query($db,
    "SELECT l.*, d.Name as MotherName, f.Name as FatherName, d.Breed as MotherBreed
    FROM Litter as l, Dogs as d, Dogs as f 
    WHERE $dogID = l.MotherID AND $dogID = d.ID AND l.MotherID = d.ID AND f.ID = l.FatherID ORDER BY l.ID DESC"
    );
    while ($result = mysqli_fetch_assoc($dogData)){
        $littersArray[$i] = $result;
		$in = 0;
    		$puppiesArray = array();
    		$litterID = $littersArray[$i]["ID"];
    		$litterData = mysqli_query($db,
    		"SELECT *
    		FROM Dogs 
    		WHERE LitterID = $litterID ORDER BY Birthdate ASC"
    		);
    		while ($litterResult = mysqli_fetch_assoc($litterData)){
        		$puppiesArray[$in] = $litterResult;
       	 		$in++;
    		}
		$inr = 0;
    		$updatesArray = array();
    		$updateData = mysqli_query($db,
    		"SELECT *
    		FROM LitterUpdates 
    		WHERE LitterID = $litterID ORDER BY Time DESC"
    		);
    		while ($updateResult = mysqli_fetch_assoc($updateData)){
        		$updatesArray[$inr] = $updateResult;
       	 		$inr++;
    		}

	array_push($littersArray[$i],$puppiesArray,$updatesArray);
        $i++;	
    }

    echo json_encode($littersArray);
        mysqli_close($db);
}
}
?>

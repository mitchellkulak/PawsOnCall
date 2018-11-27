<?php
//REQUIRES JSON POST of name, volunteerID, sex, birthdate, breed, litterID, stillborn GET of session
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'authenticate.php';

$input = json_decode(authenticate(urldecode($_GET['session'])), true);
if ($input['error'] == 'auth error') {
    $error = array('error' => 'auth error');
    echo json_encode($error);
}
else {
	
	include 'dbconnect.php';
	$ar = json_decode(file_get_contents('php://input'), true);

	if (mysqli_connect_error($db))
	{
		die("Can't connect");
	}
	else {
		$name = mysqli_real_escape_string($db,$ar["name"]);
		$volunteerID = mysqli_real_escape_string($db,$ar["volunteerID"]);
		$sex = mysqli_real_escape_string($db,$ar["sex"]);
		$birthdate = mysqli_real_escape_string($db,$ar["birthdate"]);
		$breed = mysqli_real_escape_string($db,$ar["breed"]);
		$litterID = mysqli_real_escape_string($db,$ar["litterID"]);
		$stillborn = mysqli_real_escape_string($db,$ar["stillborn"]);
		
		$SQL = "INSERT INTO Dogs VALUES (null, '$name',$volunteerID,'$sex','$birthdate',null,null,'$breed',$litterID,$stillborn)";
		if (mysqli_query($db,$SQL)) {
			echo json_encode(array("result" => "Record updated successfully"));
		} else {
			echo json_encode(array("error" => "Error updating record: " . mysqli_error($db)));
		}
	}
	mysqli_close($db);
}
?>
<?php
//REQUIRES JSON POST of an array with subarrays of dogID, name, sex, birthdate, stillborn and GET of session
//DELETES Dog if name is blank
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'authenticate.php';

$input = json_decode(authenticate(urldecode($_GET['session'])), true);
if (false){ //$input['error'] == 'auth error') {
    $error = array('error' => 'auth error');
    echo json_encode($error);
}
else {
	
	include 'dbconnect.php';
	$dogs = json_decode(file_get_contents('php://input'), true);

	if (mysqli_connect_error($db))
	{
		die("Can't connect");
	}
	else {
		foreach($dogs as $dog){
			$dogID = mysqli_real_escape_string($db,$dog["dogID"]);
			$name = mysqli_real_escape_string($db,$dog["name"]);
			$sex = mysqli_real_escape_string($db,$dog["sex"]);
			$birthdate = mysqli_real_escape_string($db,$dog["birthdate"]);
			$stillborn = mysqli_real_escape_string($db,$dog["stillborn"]);
            $deathdate = mysqli_real_escape_string($db,$dog["deathdate"]);
			if($name == ""){
				$SQL = "DELETE FROM Dogs WHERE ID = $dogID";
			}else{
				$SQL = "UPDATE Dogs SET Name = '$name', Sex = '$sex', Birthdate = '$birthdate', Stillborn = $stillborn, Deathdate = '$deathdate' WHERE ID = $dogID";
			}
			if (mysqli_query($db,$SQL)) {
				echo json_encode(array("result" => "Record updated successfully"));
			} else {
				echo json_encode(array("error" => "Error updating record: " . mysqli_error($db)));
			}
		}
	}
	mysqli_close($db);
}
?>

<?php
//REQUIRES JSON POST of an array with subarrays of dogID, name, sex, birthdate, stillborn and GET of session
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
	$dogs = json_decode(file_get_contents('php://input'), true);

	if ($db->connect_error)
	{
		die("Can't connect");
	}
	else {
		foreach($dogs as $dog){
			$dogID = mysqli_real_escape_string($db,$ar["dogID"]);
			$name = mysqli_real_escape_string($db,$ar["name"]);
			$sex = mysqli_real_escape_string($db,$ar["sex"]);
			$birthdate = mysqli_real_escape_string($db,$ar["birthdate"]);
			$stillborn = mysqli_real_escape_string($db,$ar["stillborn"]);
			if($name == ""){
				$SQL = "DELETE FROM Dogs WHERE ID = $dogID";
			}else{
				$SQL = "Update Dogs SET Name = '$name', Sex = '$sex', Birthdate = '$birthdate', Stillborn = $stillborn WHERE ID = $dogID)";
			}
			if ($db->query($SQL)) {
				echo json_encode(array("result" => "Record updated successfully"));
			} else {
				die(json_encode(array("error" => "Error updating record: " . $db->error)));
			}
		}
	}
	$db->close();
}
?>
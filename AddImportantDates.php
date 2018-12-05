<?php
//REQUIRES JSON POST of litterID, start/end whelp, wean, and deworm GET of session

header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
        $litterID = mysqli_real_escape_string($db,$ar["litterID"]);
		$startWhelp = mysqli_real_escape_string($db,$ar["startWhelp"]);
		$endWhelp = mysqli_real_escape_string($db,$ar["endWhelp"]);
		$startWean = mysqli_real_escape_string($db,$ar["startWean"]);
		$endWean = mysqli_real_escape_string($db,$ar["endWean"]);
		$startDeworm = mysqli_real_escape_string($db,$ar["startDeworm"]);
		$endDeworm = mysqli_real_escape_string($db,$ar["endDeworm"]);
		$SQL = "UPDATE Litter SET StartWhelp = '$startWhelp', EndWhelp = '$endWhelp',
		StartWean = '$startWean', EndWean = '$endWean',
		StartDeworm = '$startDeworm', EndDeworm = '$endDeworm' WHERE ID = $litterID";
		if (mysqli_query($db,$SQL)) {
			echo json_encode(array("result" => "Record updated successfully"));
		} else {
			echo json_encode(array("result" => "Error updating record: " . mysqli_error($db)));
		}
	}
	mysqli_close($db);
}
?>

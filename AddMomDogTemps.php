<?php
header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
else {

$ar = json_decode(file_get_contents('php://input'), true);
$momDogTemp = mysqli_real_escape_string($db,$ar['temp']);
$dogID = mysqli_real_escape_string($db,$ar['dogID']);
if (mysqli_connect_error($db))
{
    die("Can't connect");
}
else {
    if (mysqli_query($db,
    "INSERT 
    INTO Temperature
    VALUES ($dogID, null, $momDogTemp)") === TRUE) {
        $success = array('result' => 'Record updated successfully');
        echo json_encode($success);
    } else {
        $error = array('result' => 'Record NOT updated successfully');
        echo json_encode($error);
    }
    mysqli_close($db);
}
}
?>

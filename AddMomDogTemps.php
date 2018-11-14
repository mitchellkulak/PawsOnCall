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
else {

$ar = json_decode(file_get_contents('php://input'), true);
$momDogNote = mysqli_real_escape_string($db,$ar['temp']);
$dogID = mysqli_real_escape_string($db,$ar['DogID']);
var_dump($momDogNote);
var_dump($dogID);
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    if ($db->query(
    "INSERT 
    INTO Temperature
    VALUES ($dogID, null, '$momDogNote')") === TRUE) {
        $success = array('result' => 'Record updated successfully');
        echo json_encode($success);
    } else {
        $error = array('result' => 'Record NOT updated successfully');
        echo json_encode($error);
    }
    $db->close();
}
}
?>

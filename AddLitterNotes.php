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
$litterNote = mysqli_real_escape_string($db,$ar['Note']);
$litterID = mysqli_real_escape_string($db,$ar['LitterID']);
echo $litterNote;
echo $litterID;
echo $time;
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    if ($db->query(
    "INSERT 
    INTO LitterUpdates
    VALUES ($litterID, current_timestamp, '$litterNote')") === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $db->error;
    }
    $db->close();
}
}
?>

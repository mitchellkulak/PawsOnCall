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
$name;
$street;
$city;
$state;
$zip;
$phone;
$volInfo = array();
$ar = json_decode(file_get_contents('php://input'), true);
$litterNote = mysqli_real_escape_string($db,$ar['Note']);
$volunteerID = mysqli_real_escape_string($db,$ar['ID']);
//$time = 'current_timestamp';//mysqli_real_escape_string($db,$ar['Time']);
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    if ($db->query(
    "SELECT * 
    FROM Volunteer
    where ID = $volunteerID") === TRUE) {
        echo "Record grabbed successfully";
    } else {
        echo "Error grabbing record: " . $db->error;
    }
    $i = 0;
    while ($result = $db->fetch_assoc()){
        $volInfo[$i] = $result;
        $i++;
    }
    $db->close();
}
}
?>

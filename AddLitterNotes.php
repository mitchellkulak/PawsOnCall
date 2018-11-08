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
$litter_note = "this is another note";//mysqli_real_escape_string($db,$ar['Note']);
$litter_id =  1;//mysqli_real_escape_string($db,$ar['LitterID']);
$time = 'current_timestamp';//mysqli_real_escape_string($db,$ar['Time']);
echo $litter_note;
echo $litter_id;
echo $time;
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    if ($db->query(
    "INSERT 
    INTO LitterUpdates
    VALUES ($litter_id, $time, '$litter_note')") === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $db->error;
    }
    $db->close();
}
}
?>

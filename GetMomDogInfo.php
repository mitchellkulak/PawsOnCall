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

$theMasterArray = array();

if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $dogInfo = array();
    $dogUpdates = array();
    $dog_id = mysqli_real_escape_string($db,urldecode($_GET['dogID']));
    $userID = $input['userID'];
    $dogRequest = $db->query(
    "SELECT *
    FROM Dogs 
    WHERE id = $dog_id and VolunteerID = $userID"
    );
    $i = 0;
    while ($result = $dogRequest->fetch_assoc()){
        $dogInfo[$i] = $result;
        $i++;
    }
    $updateRequest = $db->query(
    "SELECT *
    FROM DogUpdates
    WHERE dogID = $dog_id"
    );
    $i = 0;
    while ($result = $updateRequest->fetch_assoc()){
        $dogUpdates[$i] = $result;
        $i++;
    }
    $theMasterArray = array('dogInfo' => $dogInfo, 'dogUpdates' => $dogUpdates);
    echo json_encode($theMasterArray);
    $db->close();
}
}
?>

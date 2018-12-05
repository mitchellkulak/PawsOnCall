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

$theMasterArray = array();

if (mysqli_connect_error($db))
{
    die("Can't connect");
}
else {
    $dogInfo = array();
    $dogUpdates = array();
    $dog_id = mysqli_real_escape_string($db,urldecode($_GET['dogID']));
    $userID = $input['userID'];
    $dogRequest = mysqli_query($db,
    "SELECT *
    FROM Dogs 
    WHERE id = $dog_id"
    );
    $i = 0;
    while ($result = mysqli_fetch_assoc($dogRequest)){
        $dogInfo[$i] = $result;
        $i++;
    }
    $updateRequest = mysqli_query($db,
    "SELECT *
    FROM DogUpdates
    WHERE dogID = $dog_id ORDER BY Time DESC"
    );
    $i = 0;
    while ($result = mysqli_fetch_assoc($updateRequest)){
        $dogUpdates[$i] = $result;
        $i++;
    }
    $theMasterArray = array('dogInfo' => $dogInfo, 'dogUpdates' => $dogUpdates);
    echo json_encode($theMasterArray);
    mysqli_close($db);
}
}
?>

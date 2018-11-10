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
else{


$theMasterArray = array();

if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $i = 0;
    $theMasterArray = array();
    $dog_id = mysqli_real_escape_string($db,urldecode($_GET['dog_id']));
    $dog_data = $db->query(
    "SELECT *
    FROM Litter 
    WHERE $dog_id = motherID"
    );
    while ($result = $dog_data->fetch_assoc()){
        $theMasterArray[$i] = $result;
        $i++;
    }
    echo json_encode($theMasterArray);
        $db->close();
}
}
?>

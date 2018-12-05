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
else{
    
$MasterArray = array();

if (mysqli_connect_error($db))
{
    die("Can't connect");
}
else {
    $i = 0;
    $dog_name = mysqli_real_escape_string($db,urldecode($_GET['search']));
    $userID = $input['userID'];
    if($input['admin']){
	$SQL = "SELECT d.id AS DogId, d.Name AS DogName, v.Name AS VolunteerName, d.Breed 
    	FROM Volunteer AS v, Dogs AS d 
    	WHERE d.name LIKE '%$dog_name%' AND d.Sex = 'F' AND v.ID = d.VolunteerID";
    }else{
	$SQL = "SELECT d.id AS DogId, d.Name AS DogName, v.Name AS VolunteerName, d.Breed 
    	FROM Volunteer AS v, Dogs AS d 
    	WHERE d.name LIKE '%$dog_name%' AND d.Sex = 'F' AND d.VolunteerID = $userID AND v.ID = d.VolunteerID";
    }
    $dog_data = mysqli_query($db,$SQL);
    while ($result = mysqli_fetch_assoc($dog_data)){
        $MasterArray[$i] = $result;
        $i++;
    }
    echo json_encode($MasterArray);
}
}
mysqli_close($db);
?>

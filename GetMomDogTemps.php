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


$theMasterArray = array();


if (mysqli_connect_error($db))
{
    die("Can't connect");
}
else {
    $year = 0;
    $month = 0;
    $day = 0;
    $i = 0;
    $theMasterArray = array();
    $dogID = mysqli_real_escape_string($db,urldecode($_GET['dogID']));
    $dogData = mysqli_query($db,
    "SELECT *
    FROM Temperature 
    WHERE $dogID = DogID"
    );
    while ($result = mysqli_fetch_assoc($dogData)){
        if(strtotime($result["Time"]) >= time()-5184000){
        $theMasterArray[$i] = $result;
        $ar1 = explode (' ', $result["Time"]);
        $ar2 = explode ('-', $ar1[0]);
        $year = $ar2[0];
        $month = $ar2[1];
        $day = $ar2[2];
        $theDateArray = array('year' => $year,'month' => $month, 'day' => $day);
        $theMasterArray[$i]['date'] = $theDateArray;
		$i++;
	}
    }
    echo json_encode($theMasterArray);
        mysqli_close($db);
}
}
?>

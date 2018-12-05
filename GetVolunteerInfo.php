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

$input = json_decode(authenticate(urldecode($_GET['session'])), true);
if ($input['error'] == 'auth error') {
    $error = array('error' => 'auth error');
    echo json_encode($error);
}
else {
    $volunteerID = $_GET["volunteerID"];
    include 'dbconnect.php';
    if (mysqli_connect_error($db))
    {
        die("Can't connect");
    }
    else{
        $volInfo = array();
        $i=0;
        $SQL = "SELECT Name, Email, Phone, Address, City, State, ZIP FROM Volunteer Where ID = $volunteerID";
        if ($volunteers = mysqli_query($db,$SQL)) {
            while ($result = mysqli_fetch_assoc($volunteers)){
                $volInfo[$i] = $result;
                $i++;
            }
        } else {
            $volInfo = array('error' => 'failed to fetch');
        }
        echo json_encode($volInfo);
    }
    mysqli_close($db);
}
?>

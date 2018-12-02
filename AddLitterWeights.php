<?php
	header("Content-Type: application/json");
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
		include 'dbconnect.php';
		$theMasterArray = array();
		if (mysqli_connect_error($db))
		{
			die("Can't connect");
		}else{
			$weights = json_decode(file_get_contents('php://input'), true);
				foreach($weights as $weight){
					$d1a  = mysqli_real_escape_string($db,$weight['d1a']);
					$d1p  = mysqli_real_escape_string($db,$weight['d1p']);
					$d2a  = mysqli_real_escape_string($db,$weight['d2a']);
					$d2p  = mysqli_real_escape_string($db,$weight['d2p']);
					$d3a  = mysqli_real_escape_string($db,$weight['d3a']);
					$d3p  = mysqli_real_escape_string($db,$weight['d3p']);
					$d4a  = mysqli_real_escape_string($db,$weight['d4a']);
					$d4p  = mysqli_real_escape_string($db,$weight['d4p']);
					$d5a  = mysqli_real_escape_string($db,$weight['d5a']);
					$d5p  = mysqli_real_escape_string($db,$weight['d5p']);
					$d6a  = mysqli_real_escape_string($db,$weight['d6a']);
					$d6p  = mysqli_real_escape_string($db,$weight['d6p']);
					$d7a  = mysqli_real_escape_string($db,$weight['d7a']);
					$d7p  = mysqli_real_escape_string($db,$weight['d7p']);
					$d8a  = mysqli_real_escape_string($db,$weight['d8a']);
					$d8p  = mysqli_real_escape_string($db,$weight['d8p']);
					$d9a  = mysqli_real_escape_string($db,$weight['d9a']);
					$d9p  = mysqli_real_escape_string($db,$weight['d9p']);
					$d10a  = mysqli_real_escape_string($db,$weight['d10a']);
					$d10p  = mysqli_real_escape_string($db,$weight['d10p']);
					$d11a  = mysqli_real_escape_string($db,$weight['d11a']);
					$d11p  = mysqli_real_escape_string($db,$weight['d11p']);
					$d12a  = mysqli_real_escape_string($db,$weight['d12a']);
					$d12p  = mysqli_real_escape_string($db,$weight['d12p']);
					$d13a  = mysqli_real_escape_string($db,$weight['d13a']);
					$d13p  = mysqli_real_escape_string($db,$weight['d13p']);
					$d14a  = mysqli_real_escape_string($db,$weight['d14a']);
					$d14p  = mysqli_real_escape_string($db,$weight['d14p']);
					$w3  = mysqli_real_escape_string($db,$weight['w3']);
					$w4  = mysqli_real_escape_string($db,$weight['w4']);
					$w5  = mysqli_real_escape_string($db,$weight['w5']);
					$w6  = mysqli_real_escape_string($db,$weight['w6']);
					$w7  = mysqli_real_escape_string($db,$weight['w7']);
					$w8  = mysqli_real_escape_string($db,$weight['w8']);
					$dogID  = mysqli_real_escape_string($db,$weight['DogID']);


					$SQL = "UPDATE Weight SET d1a = $d1a, d1p = $d1p, d2a = $d2a, d2p = $d2p, d3a = $d3a, d3p = $d3p, d4a = $d4a, d4p = $d4p, d5a = $d5a, d5p = $d5p, d6a = $d6a, d6p = $d6p, d7a = $d7a, d7p = $d7p, d8a = $d8a, d8p = $d8p, d9a = $d9a, d9p = $d9p, d10a = $d10a,	d10p = $d10p, d11a = $d11a,	d11p = $d11p, d12a = $d12a,	d12p = $d12p, d13a = $d13a,	d13p = $d13p, d14a = $d14a, d14p = $d14p, w3 = $w3, w4 = $w4, w5 = $w5, w6 = $w6, w7 = $w7, w8 = $w8 WHERE DogID = $dogID";
					mysqli_query($db,$SQL);
					echo json_encode(array('error' => mysqli_error($db)."hello"));
				}
			}
		}
		mysqli_close($db);
?>


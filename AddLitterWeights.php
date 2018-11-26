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
			$weights = mysqli_real_escape_string($db,json_decode(file_get_contents('php://input'), true));
				foreach($weights as $weight){
					$SQL = "UPDATE Weight SET
						d1a = $weight['d1a'], 
						d1p = $weight['d1p'], 
						d2a = $weight['d2a'], 
						d2p = $weight['d2p'], 
						d3a = $weight['d3a'], 
						d3p = $weight['d3p'], 
						d4a = $weight['d4a'], 
						d4p = $weight['d4p'], 
						d5a = $weight['d5a'], 
						d5p = $weight['d5p'], 
						d6a = $weight['d6a'], 
						d6p = $weight['d6p'], 
						d7a = $weight['d7a'], 
						d7p = $weight['d7p'], 
						d8a = $weight['d8a'], 
						d8p = $weight['d8p'], 
						d9a = $weight['d9a'], 
						d9p = $weight['d9p'], 
						d10a = $weight['d10a'], 
						d10p = $weight['d10p'], 
						d11a = $weight['d11a'], 
						d11p = $weight['d11p'], 
						d12a = $weight['d12a'], 
						d12p = $weight['d12p'], 
						d13a = $weight['d13a'], 
						d13p = $weight['d13p'], 
						d14a = $weight['d14a'], 
						d14p = $weight['d14p'], 
						w3 = $weight['w3'], 
						w4 = $weight['w4'], 
						w5 = $weight['w5'], 
						w6 = $weight['w6'], 
						w7 = $weight['w7'], 
						w8 = $weight['w8']
					WHERE DogID = $weight['DogID']";
					if(!mysqli_query($db,$SQL)){die("Failed");}		
				}
			}
		}
		mysqli_close($db);
	}
?>

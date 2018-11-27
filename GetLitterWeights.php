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
			$dogInfo = array();
			$litterID = mysqli_real_escape_string($db,urldecode($_GET['litterID']));
			$SQL = "SELECT d.Name, w.* FROM Weight AS w, Dogs AS d WHERE d.LitterID = $litterID AND w.DogID = d.ID ORDER BY w.DogID";
			$dogRequest = mysqli_query($db,$SQL);
			$i = 0;
			while ($result = mysqli_fetch_assoc($dogRequest)){
				$dogInfo[$i] = $result;
				$i++;
			}
			echo json_encode($dogInfo);
		}
		mysqli_close($db);
	}
?>
<?php
include 'authenticate.php';
$session = "XamUpyJniQzJntrCLbFB";//$_GET['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('login.php');</script>";
}else{
  include 'dbconnect.php';
  if ($db->connect_error){
      die("Can't connect");
  }
  else {

    $litterID = mysqli_real_escape_string($db,$_POST["loadID"]);
    $volunteerID = mysqli_real_escape_string($db,$_POST["volunteerID"]);
    $motherID = mysqli_real_escape_string($db,$_POST["motherID"]);
    $fatherID = mysqli_real_escape_string($db,$_POST["fatherID"]);
    $startWhelp = mysqli_real_escape_string($db,$_POST["startWhelp"]);
    $endWhelp = mysqli_real_escape_string($db,$_POST["endWhelp"]);
    $startWean = mysqli_real_escape_string($db,$_POST["startWean"]);
    $endWean = mysqli_real_escape_string($db,$_POST["endWean"]);

    if($litterID != 0){
      $SQL = "UPDATE Litter SET
        VolunteerID = '$volunteerID',
        MotherID = '$motherID',
        FatherID = '$fatherID',
        StartWhelp = '$startWhelp',
        EndWhelp = '$endWhelp',
        StartWean = '$startWean',
        EndWean = '$endWean'
      WHERE ID = $litterID";
    }else{
      $SQL = "INSERT INTO Litter Values(null,'$volunteerID','$motherID','$fatherID','$startWhelp','$endWhelp','$startWean','$endWean')";
    }
    $db->query($SQL);
    $error = mysqli_error($db);
    if($error = ""){
      echo "Record Updated/Added Successfully";
    }else{
      echo $error;
    }

  }
}
$db->close();
?>
<a href="admin.html">Return to admin page</a>

<?php
include '../authenticate.php';
$session = "XamUpyJniQzJntrCLbFB";//$_GET['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('../login.php');</script>";
}else{
  include '../dbconnect.php';
  if ($db->connect_error){
      die("Can't connect");
  }
  else {

    $userID = mysqli_real_escape_string($db,$_POST["loadID"]);
    $name = mysqli_real_escape_string($db,$_POST["name"]);
    $email = mysqli_real_escape_string($db,$_POST["email"]);
    $phone = mysqli_real_escape_string($db,$_POST["phone"]);
    $address = mysqli_real_escape_string($db,$_POST["address"]);
    $city = mysqli_real_escape_string($db,$_POST["city"]);
    $state = mysqli_real_escape_string($db,$_POST["state"]);
    $zip = mysqli_real_escape_string($db,$_POST["zip"]);
    $admin = mysqli_real_escape_string($db,$_POST["admin"]);

    if($userID != 0){
      $SQL = "UPDATE Volunteer SET
        Name = '$name',
        Email = '$email',
        Phone = '$phone',
        Address = '$address',
        City = '$city',
        State = '$state',
        ZIP = '$zip',
        Admin = '$admin'
      WHERE id = $userID";
    }else{
      $SQL = "INSERT INTO Volunteer Values(null,'$name','$email','$phone','$address','$city','$state','$zip',null,$admin)";
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

<?php
  include 'authenticate.php';
  $session = "XamUpyJniQzJntrCLbFB";//$_GET['session'];
  $auth = json_decode(authenticate(urldecode($session)), true);
  if ($auth['error'] == 'auth error') {
      $error = array('error' => 'auth error');
      echo json_encode($error);
      echo "<script>window.location.replace('login.php');</script>";
  }else{
      
  }
?>

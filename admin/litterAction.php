<?php
session_start();
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include '../authenticate.php';

$session = $_SESSION['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('../login.html');</script>";
}else{
  include '../dbconnect.php';
  $litterID = mysqli_real_escape_string($db,$_POST["loadID"]);
  if (mysqli_connect_error($db)){
      die("Can't connect");
  }elseif(isset($_POST["Save"])) {

    $volunteerID = mysqli_real_escape_string($db,$_POST["volunteerID"]);
    $motherID = mysqli_real_escape_string($db,$_POST["motherID"]);
    $fatherID = mysqli_real_escape_string($db,$_POST["fatherID"]);
    $startWhelp = mysqli_real_escape_string($db,$_POST["startWhelp"]);
    if($startWhelp == ""){
      $startWhelp = "2038-01-01 00:00:00";
    }
    $endWhelp = mysqli_real_escape_string($db,$_POST["endWhelp"]);
    if($endWhelp == ""){
      $endWhelp = "2038-01-01 00:00:00";
    }
    $startWean = mysqli_real_escape_string($db,$_POST["startWean"]);
    if($startWean == ""){
      $startWean = "2038-01-01 00:00:00";
    }
    $endWean = mysqli_real_escape_string($db,$_POST["endWean"]);
    if($endWean == ""){
      $endWean = "2038-01-01 00:00:00";
    }
    $startDeworm = mysqli_real_escape_string($db,$_POST["startDeworm"]);
    if($startDeworm == ""){
      $startDeworm = "2038-01-01 00:00:00";
    }
    $endDeworm = mysqli_real_escape_string($db,$_POST["endDeworm"]);
    if($endDeworm == ""){
      $endDeworm = "2038-01-01 00:00:00";
    }

    if($litterID != 0){
      $SQL = "UPDATE Litter SET
        VolunteerID = '$volunteerID',
        MotherID = '$motherID',
        FatherID = '$fatherID',
        StartWhelp = '$startWhelp',
        EndWhelp = '$endWhelp',
        StartWean = '$startWean',
        EndWean = '$endWean',
        StartDeworm = '$startDeworm',
        EndDeworm = '$endDeworm'
      WHERE ID = $litterID";
    }else{
      $SQL = "INSERT INTO Litter Values(null,'$volunteerID','$motherID','$fatherID','$startWhelp','$endWhelp','$startWean','$endWean','$startDeworm','$endDeworm')";
    }
    $error = mysqli_error($db);
    if(mysqli_query($db,$SQL)){
      $message = "Record Added/Updated";
    }else{
      $message = mysqli_error($db);
    } 

  }elseif(isset($_POST["Delete"])){
    if($dogID != 0){
      die("Cannot Delete");
    }else{
      $SQL = "DELETE FROM Litter WHERE ID = $litterID";
    }
    if(mysqli_query($db,$SQL)){
      $message = "Record Deleted";
    }else{
      $message = mysqli_error($db);
    }    
  }
}
mysqli_close($db);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PAWS Motherhood Database</title>
	
  <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
	
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="../scripts.js"></script>
	
	
	<!-- favicon stuff-->
	<link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<!-- favicon stuff-->

	<script>
document.addEventListener('DOMContentLoaded', function () {

  // Get all "navbar-burger" elements
  var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Check if there are any nav burgers
  if ($navbarBurgers.length > 0) {

    // Add a click event on each of them
    $navbarBurgers.forEach(function ($el) {
      $el.addEventListener('click', function () {

        // Get the target from the "data-target" attribute
        var target = $el.dataset.target;
        var $target = document.getElementById(target);

        // Toggle the class on both the "navbar-burger" and the "navbar-menu"
        $el.classList.toggle('is-active');
        $target.classList.toggle('is-active');

      });
    });
  }

});
</script>
</head>
<body onload=" adminShowHide()">



<!-- Navbar, logo, logout button -->
<nav class="navbar ">
  <div class="navbar-brand">
    <a href="../mother.html">
			<img src="../images/pawslogo.png" alt="PAWS Logo" >
		</a>

    <div class="navbar-burger burger" data-target="navMenubd-example">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div id="navMenubd-example" class="navbar-menu">
    <div class="navbar-start">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link  is-active">
          Menu
        </a>
        <div class="navbar-dropdown ">
          <a class="navbar-item " href="../mother.html">
            Mom
          </a>
          <a class="navbar-item " href="../puppies.html">
            Puppies
          </a>
          <a class="navbar-item " href="../misc.html">
            Misc
          </a>
          <a class="navbar-item " id="adminLink" onclick="redirectToAdmin()">
            Admin
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <a class="button is-primary" onclick="logout()">
              <span>Logout</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</nav>
<!-- Navbar, logo, logout button -->
<article class="tile notification is-primary is-vertical admin">
  <?php echo $message;?>
  <a class="button is-link admin" href="index.php">Return to Admin page</a><br>
    <a class="button is-link admin" href="../mother.html">Return to Mother page</a><br>

</article>


</body>
</html>

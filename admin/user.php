<?php
session_start();
include '../authenticate.php';
$session = $_SESSION['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('../login.html');</script>";
}else{
  include '../dbconnect.php';
  if ($db->connect_error){
      die("Can't connect");
  }
  else {
    $userrow = array('Name' => "", 'Email' => "", 'Phone' => "", 'Address' => "", 'City' => "", 'State' => "", 'ZIP' => "");
    if($_GET['loadID'] != ""){
      $userID = mysqli_real_escape_string($db,$_GET['loadID']);
      $user = $db->query("SELECT * FROM Volunteer WHERE id = $userID");
      $userrow = $user->fetch_assoc();
    }
    $users = $db->query("SELECT ID, Name FROM Volunteer");
  }
}
$db->close();
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PAWS Motherhood Database</title>
  <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
	
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="../scripts.js"></script>
	
	<!-- favicon stuff-->
	<link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
	<link rel="manifest" href="../site.webmanifest">
	<link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<!-- favicon stuff-->
</head>

<!-- Navbar, logo, logout button -->
<nav class="navbar" role="navigation" aria-label="main navigation">
	<div id="navbarDesktop " class="navbar-brand">
		<a href="searchresult.html">
			<img src="../images/pawslogo.png" alt="PAWS Logo" >
		</a>
		<a class="navbar-item" href="../mother.html">Mom</a>
		<a class="navbar-item" href="../puppies.html">Puppies</a>
		<a class="navbar-item" href="../misc.html">Misc</a>
		<a class="navbar-item" href="../admin" style="display:flex">Admin</a>
	</div>
	<div class="buttons">
		<a class="button is-primary logout" onclick="logout()">
		Log out
		</a>
	</div>
</nav>
<!-- Navbar, logo, logout button -->

<article class="tile notification is-primary is-vertical admin">

  <!--select user-->
  <form action="user.php">
    <select class="dropbtn admin" name='loadID'>
      <option value="0">New User</option>
      <!--results go here-->
    </select>

    <input class="button is-link admin" type="submit" value="Load">
  </form>

  <form action="userAction.php" method="post">
    
    <!--Name section-->
    <input type="text" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $userID?>">
    <label class="label admin">Name:</label>
    <input class="input admin"type="text" name="name" value="<?php echo $userrow['Name']?>"><br>

    <!--email-->
    <label class="label admin">Email: </label>
    <input class="input admin"type="email" name="email" value="<?php echo $userrow['Email']?>"><br>

    <!--phone-->
    <label class="label admin">Phone: </label>
    <input class="input admin"type="tel" name="phone" value="<?php echo $userrow['Phone']?>"><br>


    <!--address-->
    <label class="label admin">Address: </label>
    <input class="input admin"type="text" name="address" value="<?php echo $userrow['Address']?>"><br>

    <!--city-->
    <label class="label admin">City:</label>
    <input class="input admin"type="text" name="city" value="<?php echo $userrow['City']?>"><br>

    <!--state-->
    <label class="label admin">State: </label>
    <input class="input admin"type="text" name="state" maxlength="2" value="<?php echo $userrow['State']?>"><br>

    <!--zip-->
    <label class="label admin">ZIP: </label>
    <input class="input admin"type="text" name="zip" value="<?php echo $userrow['ZIP']?>"><br>
    
    <!--admin buttons-->
    <label class="label admin">Admin:</label>
    <input type="radio" name="admin" value="1" <?php if($userrow["Admin"] == 1){echo "checked";}?>>Yes<br>
    <input type="radio" name="admin" value="0" <?php if($userrow["Admin"] == 0 || $userID == 0){echo "checked";}?>> No<br>
    <input class="button is-link admin" type="submit" value="Save">
  </form>

  <a href="index.php">Return to admin page</a>
</article>
</body>
<html>

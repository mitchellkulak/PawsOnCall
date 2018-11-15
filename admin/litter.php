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
    $dogrow = array('VolunteerID' => "", 'MotherID' => "", 'FatherID' => "", 'StartWhelp' => "", 'StartWean' => "", 'EndWean' => "");
    if($_GET['loadID'] != ""){
      $litterID = mysqli_real_escape_string($db,$_GET['loadID']);
      $litter = $db->query("SELECT * FROM Litter WHERE id = $litterID");
      $litterrow = $litter->fetch_assoc();
      $fatherID = $litterrow["FatherID"];
      $motherID = $litterrow["MotherID"];
      $volunteerID = $litterrow["VolunteerID"];
    }
    $litters = $db->query("SELECT Dogs.Name, Litter.ID, Litter.StartWhelp FROM Litter, Dogs WHERE Litter.MotherID = Dogs.ID ORDER BY Dogs.Name");
    $motherdogs = $db->query("SELECT ID, Name FROM Dogs WHERE Sex = 'F'");
    $fatherdogs = $db->query("SELECT ID, Name FROM Dogs WHERE Sex = 'M'");
    $users = $db->query("SELECT ID, Name FROM Volunteer");
  }
}
$db->close();
?>
<html>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PAWS Motherhood Database</title>
  <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
	
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="scripts.js"></script>
	
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
		<a class="navbar-item" id="adminLink" href="../admin" style="display:flex">Admin</a>
	</div>
	<div class="buttons">
		<a class="button is-primary logout" onclick="logout();">
		Log out
		</a>
	</div>
</nav>
<!-- Navbar, logo, logout button -->

  <!--central tile-->
  <article class="tile notification is-primary is-vertical admin">
    <form action="litter.php">
      <select class="dropbtn admin" name='loadID'>
        <option  value="0">New Litter</option>
        <?php while($sublitter = $litters->fetch_assoc()){echo "<option value=".$sublitter["ID"];if($sublitter["ID"]==$litterID){echo " selected";} echo ">".$sublitter["StartWhelp"]." ".$sublitter["Name"]."</option>";}?>
      </select>
      <input type="submit" class="button is-link admin" value="Load">
    </form>

    <form action="litterAction.php" method="post">
      <input type="text" class="dropbtn" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $litterID?>">
      <label class="label admin">Volunteer:</label>
      <select name="volunteerID" class="dropbtn admin">
        <option value="0">select</option>
        <?php while($subuser = $users->fetch_assoc()){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $volunteerID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
      </select>

      <label class="label admin"> Mother:</label>
      <select name="motherID" class="dropbtn admin">
        <option class="dropbtn admin" value="0">select</option>
        <?php while($subuser = $motherdogs->fetch_assoc()){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $motherID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
      </select><br>

      <label class="label admin">Father:</label>
      <select name="fatherID" class="dropbtn admin">
        <option class="dropbtn admin" value="0">select</option>
        <?php while($subuser = $fatherdogs->fetch_assoc()){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $fatherID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
      </select><br>
      
      <label class="label admin">Start Whelp: </label>
      <input class="input admin" type="datetime-local" name="startWhelp" value="<?php echo $litterrow['StartWhelp']?>"><br>

      <label class="label admin">End Whelp:</label>
      <input class="input admin" type="datetime-local" name="endWhelp" value="<?php echo $litterrow['EndWhelp']?>"><br>

      <label class="label admin">Start Wean: </label>
      <input class="input admin" type="datetime-local" name="startWean" value="<?php echo $litterrow['StartWean']?>"><br>

      <label class="label admin">End Wean: </label>
      <input class="input admin" type="datetime-local" name="endWean" value="<?php echo $litterrow['EndWean']?>"><br>
      
      <input type="submit" value="Save">
    </form>

    <a href="index.php">Return to admin page</a>
  </article>
</body>
<html>

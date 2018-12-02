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
  if (mysqli_connect_error($db)){
      die("Can't connect");
  }
  else {
    $dogrow = array('Name' => "", 'Volunteer' => "", 'Sex' => "", 'Birthdate' => "", 'Deathdate' => "", 'Adoptiondate' => "", 'Breed' => "",'Litter' => "");
    if($_GET['loadID'] != ""){
      $dogID = mysqli_real_escape_string($db,$_GET['loadID']);
      $dog = mysqli_query($db,"SELECT * FROM Dogs WHERE id = $dogID");
      $dogrow = mysqli_fetch_assoc($dog);
    }
    $dogs = mysqli_query($db,"SELECT ID, Name, Breed FROM Dogs WHERE LitterID IS NULL ORDER BY NAME ASC");
    $users = mysqli_query($db,"SELECT ID, Name FROM Volunteer ORDER BY NAME ASC");
    $litters = mysqli_query($db,"SELECT Dogs.Name, Litter.ID, Litter.StartWhelp FROM Dogs,Litter WHERE Litter.MotherID = Dogs.ID ORDER BY Dogs.NAME ASC");
  }
}
mysqli_close($db);
?>

<html>
<head>
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

	<!-- central tile-->
	<article class="tile notification is-primary is-vertical admin">
    <form action="dog.php">
      <select name="loadID" class="dropbtn" >
        <option value="0">New Dog</option>
        <?php while($subdog = mysqli_fetch_assoc($dogs)){echo "<option value=".$subdog["ID"];if($subdog["ID"]==$dogID){echo " selected";} echo ">".$subdog["Name"]." ".$subdog["Breed"]."</option>";}?>
      </select>
      <input type="submit" class="button is-link admin " value="Load">
    </form>


    <form action="dogAction.php" method="post">
      <!--pick dog from listed dogs-->
      <input type="text" class="dropdown-content" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $dogID?>">
      
      <!--enter dog name-->
      <label class="label admin">Name: </label>
      <input type="text" required class="input admin" name="name" value="<?php echo $dogrow['Name']?>"><br>

      <!--volunteer dropdown-->
      <label class="label admin">Volunteer:</label>
      <select class="dropbtn" name="volunteerID">
        <option value="0">select</option>
        <?php while($subuser = mysqli_fetch_assoc($users)){
          echo "<option value=".$subuser["ID"];
          if($subuser["ID"]==$dogrow["VolunteerID"]){
            echo " selected";}
          echo ">".$subuser["Name"]."</option>";
        }?>
      </select><br>
      
      <!--sex input-->
      <label class="label admin">Sex:</label> 
      <input type="radio" name="sex" value="F" <?php if($dogrow["Sex"] == "F"){echo "checked";}?>>Female<br>
      <input type="radio" name="sex" value="M" <?php if($dogrow["Sex"] == "M"){echo "checked";}?>>Male<br>
      
      <!--birthday-->
      <label class="label admin"> Birthdate: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input type="text" class="input admin" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}" name="birthdate" value="<?php echo $dogrow['Birthdate']?>"><br>
      
      <!--adoption date-->
      <label class="label admin">Adoption Date: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input type="text" class="input admin" name="adoptiondate" value="<?php echo $dogrow['Adoptiondate']?>"><br>
      
      <!--death date-->
      <label class="label admin">Deathdate: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input type="text" class="input admin" name="deathdate" value="<?php echo $dogrow['Deathdate']?>"><br>
      
      <!--breed-->
      <label class="label admin">Breed:</label>
      <input type="text" required class="input admin" name="breed" value="<?php echo $dogrow['Breed']?>"><br>
      
      <!--litter-->
      <label class="label admin">Litter:</label>
      <select class="dropbtn" name="litterID">
        <option value=null>None</option>
        <?php while($sublitter = mysqli_fetch_assoc($litters)){echo "<option value=".$sublitter["ID"];if($sublitter["ID"]==$dogrow["LitterID"]){echo " selected";} echo ">".$sublitter["Name"]." ".$sublitter["StartWhelp"]."</option>";}?>
      </select>
      <label class="label stillborn">Stillborn:</label>
      <input type="radio" name="stillborn" value="1" <?php if($dogrow["Stillborn"] == 1){echo "checked";}?>>Yes<br>
      <input type="radio" name="stillborn" value="0" <?php if($dogrow["Stillborn"] == 0 || $dogID == 0){echo "checked";}?>> No<br>
      <input class="button is-link admin " type="submit" value="Save" name="Save">
      <input class="button is-link admin " type="submit" name="Delete" value="Delete" onclick="return confirm('Are you sure you want to delete this dog?');">
    </form>
    <a href="index.php">Return to admin page</a>

  </article>
</body>
<html>

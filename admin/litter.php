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
  if (mysqli_connect_error($db)){
      die("Can't connect");
  }
  else {
    $dogrow = array('VolunteerID' => "", 'MotherID' => "", 'FatherID' => "", 'StartWhelp' => "", 'StartWean' => "", 'EndWean' => "");
    if($_GET['loadID'] != ""){
      $litterID = mysqli_real_escape_string($db,$_GET['loadID']);
      $litter = mysqli_query($db,"SELECT * FROM Litter WHERE id = $litterID");
      $litterrow = mysqli_fetch_assoc($litter);
      $fatherID = $litterrow["FatherID"];
      $motherID = $litterrow["MotherID"];
      $volunteerID = $litterrow["VolunteerID"];
    }
    $litters = mysqli_query($db,"SELECT Dogs.Name, Litter.ID, Litter.StartWhelp FROM Litter, Dogs WHERE Litter.MotherID = Dogs.ID ORDER BY Dogs.Name");
    $motherdogs = mysqli_query($db,"SELECT ID, Name, Breed FROM Dogs WHERE Sex = 'F' AND LitterID IS NULL ORDER BY NAME ASC");
    $fatherdogs = mysqli_query($db,"SELECT ID, Name, Breed FROM Dogs WHERE Sex = 'M' AND LitterID IS NULL ORDER BY NAME ASC");
    $users = mysqli_query($db,"SELECT ID, Name FROM Volunteer ORDER BY NAME ASC");
  }
}
mysqli_close($db);
?>
<html>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PAWS Motherhood Database</title>
  <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
    <style>
      .asterisk_input:after {
content:" *"; 
color: #e32;
}
      select {
  width: 300px;
  max-width: 100%;
  /* So it doesn't overflow from it's parent */
}
    option {
  /* wrap text in compatible browsers */
  -moz-white-space: pre-wrap;
  -o-white-space: pre-wrap;
  white-space: pre-wrap;
  /* hide text that can't wrap with an ellipsis */
  overflow: hidden;
  text-overflow: ellipsis;
  /* add border after every option */
  border-bottom: 1px solid #DDD;
}
  </style>
	
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

  <!--central tile-->
  <article class="tile notification is-primary is-vertical admin">
    <form action="litter.php">
      <select class="dropbtn admin" name='loadID'>
        <option  value="0">New Litter</option>
        <?php while($sublitter = mysqli_fetch_assoc($litters)){echo "<option value=".$sublitter["ID"];if($sublitter["ID"]==$litterID){echo " selected";} echo ">".$sublitter["Name"]." ".$sublitter["StartWhelp"]."</option>";}?>
      </select>
      <input type="submit" class="button is-link admin" value="Load">
    </form>

    <form action="litterAction.php" method="post">
      <input type="text" class="dropbtn" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $litterID?>">
      <label class="label admin asterisk_input">Volunteer:</label>
      <select name="volunteerID" id="volunteerID" class="dropbtn admin">
        <option value="0">select</option>
        <?php while($subuser = mysqli_fetch_assoc($users)){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $volunteerID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
      </select>

      <label class="label admin asterisk_input"> Mother:</label>
      <select name="motherID" id="motherID" class="dropbtn admin">
        <option class="dropbtn admin" value="0">select</option>
        <?php while($subuser = mysqli_fetch_assoc($motherdogs)){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $motherID){echo " selected";}echo ">".$subuser["Name"]." ".$subuser["Breed"]."</option>";}?>
      </select><br>

      <label class="label admin asterisk_input">Father:</label>
      <select name="fatherID" id="fatherID" class="dropbtn admin">
        <option class="dropbtn admin" value="0">select</option>
        <?php while($subuser = mysqli_fetch_assoc($fatherdogs)){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $fatherID){echo " selected";}echo ">".$subuser["Name"]." ".$subuser["Breed"]."</option>";}?>
      </select><br>
      
      <label class="label admin asterisk_input">Start Whelp: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input class="input admin" id="startWhelp" type="text" name="startWhelp" value="<?php echo $litterrow['StartWhelp']?>"><br>

      <label class="label admin">End Whelp: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input class="input admin" id="endWhelp" type="text" name="endWhelp" value="<?php echo $litterrow['EndWhelp']?>"><br>

      <label class="label admin">Start Wean: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input class="input admin" id="startWean" type="text" name="startWean" value="<?php echo $litterrow['StartWean']?>"><br>

      <label class="label admin">End Wean: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input class="input admin" id="endWean" type="text" name="endWean" value="<?php echo $litterrow['EndWean']?>"><br>
      
      <label class="label admin">Start Deworm: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input class="input admin" id="startDeworm" type="text" name="startDeworm" value="<?php echo $litterrow['StartDeworm']?>"><br>
      
      <label class="label admin">End Deworm: <i>Enter in YYYY-MM-DD HH:MM:SS Format</i></label>
      <input class="input admin" id="endDeworm" type="text" name="endDeworm" value="<?php echo $litterrow['StartDeworm']?>"><br>
      
      <input class="button is-link admin" type="submit" name="Save" onclick="return validateLitter();" value="Save">
      <input class="button is-link admin" name="Delete" type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this litter?');">
    </form>
    <a href="index.php">Return to admin page</a>
  </article>
</body>
<html>
  <script>
  function validateLitter(){
    var errors = [];
    var re = new RegExp("[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}");
    var volunteerID=document.getElementById('volunteerID').value;
    var motherID=document.getElementById('motherID').value;
    var fatherID=document.getElementById('fatherID').value;
    var startWhelp=document.getElementById('startWhelp').value;
    var endWhelp=document.getElementById('endWhelp').value;
    var startWean=document.getElementById('startWean').value;
    var endWean=document.getElementById('endWean').value;
    var startDeworm=document.getElementById('startDeworm').value;
    var endDeworm=document.getElementById('endDeworm').value;
    if(volunteerID == 0){
      errors.push("Volunteer cannot be blank");
    }
    if(motherID == 0){
      errors.push("Mother cannot be blank");
    }
    if(fatherID == 0){
      errors.push("Father cannot be blank");
    }
    if(!re.test(startWhelp) || startWhelp == ""){
      errors.push("Start whelp must be in valid format, and not blank");
    }
    if(!re.test(endWhelp) && endWhelp != ""){
      errors.push("End whelp must be in valid format, or blank");
    }
    if(!re.test(startWean) && startWean != ""){
      errors.push("Start wean must be in valid format, or blank");
    }
    if(!re.test(endWean) && endWean != ""){
      errors.push("End wean must be in valid format, or blank");
    }
    if(!re.test(startDeworm) && startDeworm != ""){
      errors.push("Start deworm must be in valid format, or blank");
    }
    if(!re.test(endDeworm) && endDeworm != ""){
      errors.push("End deworm must be in valid format, or blank");
    }
    if(errors.length > 0){
      var errorsString = errors.join('\n');
      alert("Please correct the following errors:\n\n" + errorsString);
      return false;
    }else{
      return true;
    }
    
  }
</script>

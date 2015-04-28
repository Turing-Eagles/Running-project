<!DOCTYPE html> 
<html>
<?php
require 'DbConnect.php';
// For processing the login:
require ('login_functions.inc.php');
  if(isset($_COOKIE['username'])){
	setcookie("username", "", time()-3600);//unset cookie
	setcookie("adminUsername", "", time()-3600);//unset cookie
  }
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // create short variable names
  $fName=$mysqli->real_escape_string(trim($_POST['fName']));
  $lName=$mysqli->real_escape_string(trim($_POST['lName']));
  $uName=$mysqli->real_escape_string(trim($_POST['uName']));
  $password=sha1($mysqli->real_escape_string(trim($_POST['password'])));
  $password2=sha1($mysqli->real_escape_string(trim($_POST['password2'])));
  $sex=$mysqli->real_escape_string(trim($_POST['sex']));
  $year=$mysqli->real_escape_string(trim($_POST['year']));
  $day=$mysqli->real_escape_string(trim($_POST['day']));
  $month=$mysqli->real_escape_string(trim($_POST['month']));

  if (!isset($fName) || !isset($lName) || !isset($uName) || !isset($sex) || !isset($_POST['password'])) {
	echo '<script type="text/javascript">';
	echo 'window.alert("You have not entered in all of the required details. Please try again.")';
	echo '</script>';
    exit;
  }
  if($password != $password2){
	echo '<script type="text/javascript">';
	echo 'window.alert("Passwords do not match. Please try again.")';
	echo '</script>';
    exit;
  }
  
  if(strlen($_POST['password']) < 7){
	echo '<script type="text/javascript">';
	echo 'window.alert("Password must be at least 7 characters.")';
	echo '</script>';
    exit;
  }
  
  $birthString = $month . '/' . $day . '/' . $year;
  $q = "insert into racer_account (first_name, last_name, username, password, birthdate, sex) values ('$fName', '$lName', '$uName', '$password', STR_TO_DATE('$birthString', '%m/%d/%Y'), '$sex')";
  $mysqli->query($q);	
  if ($mysqli->affected_rows == 1) {
	  setcookie('username', $uName);
	  redirect_user('races.php');
  }
  $mysqli->close(); // Close the database connection.
}
else if($_SERVER['REQUEST_METHOD'] == 'GET'){
  if(isset($_GET['logInUsername']) && isset($_GET['logInPassword'])){
  $uName=$mysqli->real_escape_string(trim($_GET['logInUsername']));
  $pass=sha1($mysqli->real_escape_string(trim($_GET['logInPassword'])));
  // Retrieve the user_id and first_name for that email/password combination:
  $q = "SELECT (username) FROM racer_account WHERE username='$uName' AND password='$pass'";		
  $r = $mysqli->query ($q); // Run the query.
  // Check the result:
  if (mysqli_num_rows($r) == 1) {
    $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	// Set the cookies:
	setcookie('username', $row['username']);
	// Redirect:
	redirect_user('races.php');
	}
  }
}
?>

<head>
  <title>The Running Project</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <!-- modernizr enables HTML5 elements and feature detects -->
  <script type="text/javascript" src="js/modernizr-1.5.min.js"></script>  
</head>

<body>
  <div id="main">

    <header>
	  <div id="strapline">
	    <div id="welcome_slogan">
	      <h3>The Running Project <span></span></h3>
	    </div><!--close welcome_slogan-->
      </div><!--close strapline-->	  
	  <nav>
	    <div id="menubar">
          <ul id="nav">
            <li><a href="home.php">Home</a></li>
            <li><a href="adminLogin.php">Race Admin</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="races.php">Races</a></li>
            <li class="current"><a href="register.php">Register</a></li>
          </ul>
        </div><!--close menubar-->	
      </nav>
    </header>
	
    <div id="slideshow_container">  
	  <div class="slideshow">
	    <ul class="slideshow">
          <li class="show"><img width="940" height="250" src="images/home_1.jpg" alt="&quot;&quot;" /></li>
          <li><img width="940" height="250" src="images/home_2.jpg" alt="&quot;&quot;" /></li>
        </ul> 
	  </div><!--close slideshow-->  	
	</div><!--close slideshow_container-->  	
    
	<div id="site_content">

	  <div class="sidebar_container">       
		<div class="sidebar">
          <div class="sidebar_item">
            <h2>The Running Project</h2>
            <p>The internet's finest (not to mention most secure) source for local road racing. </p>
          </div><!--close sidebar_item--> 
        </div><!--close sidebar-->     		
		<div class="sidebar">
          <div class="sidebar_item">
            <h3>Account</h3>
            <p>This is the page where you can either create an account or log in to your existing account.</p>         
		  </div><!--close sidebar_item--> 
        </div><!--close sidebar-->
       </div><!--close sidebar_container-->
	
	  <div id="content">
        <div class="content_item">
		  <div class="form_settings">
            <h2>Registration</h2>
			  <form action="register.php" method="post">
				<table border="0">
				  <tr>
					<td>Username</td>
					 <td><input type="text" name="uName" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td>Password</td>
					 <td><input type="password" name="password" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td>Re-enter Password</td>
					 <td><input type="password" name="password2" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td>First Name</td>
					 <td><input type="text" name="fName" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td>Last Name</td>
					<td> <input type="text" name="lName" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td>Birthday</td>
					<td> Month: <select name="month" size="1">
							<?php 
							for ($x = 1; $x <= 12; $x++) {
								echo '<option value="' . $x .'">' . $x . '</option>';
							} 
							?>
						</select>
						Day: <select name="day" size="1">
							<?php 
							for ($x = 1; $x <= 30; $x++) {
								echo '<option value="' . $x .'">' . $x . '</option>';
							} 
							?>
						</select>
						Year: <select name="year" size="1">
							<?php 
							for ($x = 1900; $x <= 2015; $x++) {
								echo '<option value="' . $x .'">' . $x . '</option>';
							} 
							?>
						</select></td>
				  </tr>
				  <tr>
					<td>Sex</td>
					<td><select name="sex" size="1">
						<option value="M">Male</option>
						<option value="F">Female</option>
						</select></td>
				  </tr>
				  <tr>
					<td colspan="2"><input type="submit" value="Register"></td>
				  </tr>
				</table>
			  </form>
			  <h2>Already have an account?</h2>
			  <form action="register.php" method="get">
				<table border="0">
				  <tr>
					<td>Username</td>
					 <td><input type="text" name="logInUsername" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td>Password</td>
					 <td><input type="password" name="logInPassword" maxlength="15" size="30"></td>
				  </tr>
				  <tr>
					<td colspan="2"><input type="submit" value="Log In"></td>
				  </tr>
				</table>
			  </form>
			</div><!--close form_settings-->
		</div><!--close content_item-->
      </div><!--close content-->   
	</div><!--close site_content-->    
  </div><!--close main-->

  <!-- javascript at the bottom for fast page loading -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/image_slide.js"></script>	
  
</body>
</html>

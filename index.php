<?php
session_start();
$servername = "localhost";
$username = "php";
$password = "kavip2004";
$dbname = "picproj";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
}

$sql = "SELECT id, name, path, passcode FROM main";
$result = $conn->query($sql);
$allIDS = [];
$allNames = [];
$allPaths = [];
$allPasscodes = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $allIDS[] = $row["id"];
	    $allNames[] = $row["name"];
        $allPaths[] = $row["path"];
	    $allPasscodes[] = $row["passcode"];
    }
} else {
    echo "0 results, possible SQL Connection fail";
}
$_SESSION['ALLNAMES'] = $allNames;
$_SESSION['ALLPASSCODES'] = $allPasscodes;
$conn->close();
?>




<?php

if(isset($_POST['submit_pass']) && $_POST['pass'])
{
 $pass=$_POST['pass'];
 if(in_array($pass, $allPasscodes))
 {
    //Client Now authorized
   $key = array_search($pass, $allPasscodes); //id for all of the clients info
   $_SESSION['password']=$pass;
   $_SESSION['name'] = $allNames[$key];
   $_SESSION['path'] = $allPaths[$key];
 }
 else
 {
  $error="Incorrect Pssword";
 }
}

if(isset($_POST['page_logout']))
{
 unset($_SESSION['password']);
}
?>




<html>
<head>
<link rel="stylesheet" type="text/css" href="password_style.css">
</head>
<body>
<div id="wrapper">
<link rel="stylesheet" type="text/css" href="password_style.css">
<?php
if(in_array($_SESSION['password'], $allPasscodes))
{
 ?>
 <?php
 echo "<h1>Hi " .$_SESSION['name'] ."</h1>";
 
 $dirname = $_SESSION['path'] . '/';
 $images = glob($dirname."*.jpg");
 
 foreach($images as $image) {
     echo '<img src="'.$image.'" width="300" style="padding-left: 10px; padding-bottom: 10px; padding-top: 10px; padding-right: 10px;" />';
 }

$images = glob($dirname."*.png");
 
 foreach($images as $image) {
     echo '<img src="'.$image.'" width="300" style="padding-left: 10px; padding-bottom: 10px; padding-top: 10px; padding-right: 10px;"/>';
 }

 ?>
<link rel="stylesheet" type="text/css" href="password_style.css">
 <form method="post" action="" id="logout_form">
  <input type="submit" name="page_logout" value="LOGOUT">
  <input type="submit" name="button1" value="Download as Zip" />
  <input type="submit" name="button2" value="Refresh Zip File" />
 </form>
<link rel="stylesheet" type="text/css" href="password_style.css">
<form action="upload-manager.php" method="post" enctype="multipart/form-data">
        <label for="fileSelect">Filename:</label>
        <input type="file" name="photo" id="fileSelect">
        <input type="submit" name="submit" value="Upload">
  </form>

<?php

if(array_key_exists('button1', $_POST)) { 
   $zip_file = $_SESSION['path'] . '/photos.zip';
   header('Content-type: application/zip');
	header('Content-Disposition: attachment; filename="'.basename($zip_file).'"');
	header("Content-length: " . filesize($zip_file));
	header("Pragma: no-cache");
   header("Expires: 0");
   ob_clean();
   flush();
   readfile($zip_file);
   unlink($zip_file);
   exit;

} 
if(array_key_exists('button2', $_POST)) { 
   $output = shell_exec('sudo bash zipScript.sh ' . $_SESSION['path']);
   echo $output;
   echo "Package has been zipped up successfully";
}

?>

 <?php
}
else
{
 ?>
 <link rel="stylesheet" type="text/css" href="password_style.css">
 <form method="post" action="" id="login_form">
  <h1>Login Below</h1>
  <input type="password" name="pass" placeholder="*******">
  <input type="submit" name="submit_pass" value="Login">
  <p>Enter Access Code</p>
  <p><font style="color:red;"><?php echo $error;?></font></p>
  <p><a href = 'register.php'>To Register an Account Click Here</a></p>
 </form>
 <?php	
}
?>

</div>
</body>
</html>




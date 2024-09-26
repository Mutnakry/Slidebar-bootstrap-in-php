

<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "pos_sana";
$conn= mysqli_connect("$server","$username","$password");
$select_db = mysqli_select_db($conn, $database);
if(!$select_db)
{
	echo("connection terminated");
}
?>

<?php
	$host="localhost";
	$user="root";
	$pass="PE5mjREW8BNfZbH7";
	$db="rocad_admin";

	$con = mysqli_connect($host, $user, $pass);

	if (!mysqli_select_db($con, $db)){
		echo 'Error connnecting to the database ';
	}
?>

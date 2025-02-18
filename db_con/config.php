<?php
if(session_status()===PHP_SESSION_NONE){
//session_start();
} 
date_default_timezone_set('Africa/Lagos');
$hostname_config = "localhost";
$database_config = "rocad_app";
$username_config = "rocad_app";
$password_config = "v?A]olT3OGiQ";   
$config = mysqli_connect($hostname_config, $username_config, $password_config);
if (!mysqli_select_db($config, $database_config)){
   echo 'Error connnecting to the database ';
}
?>
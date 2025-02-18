<?php 
session_start();
unset($_SESSION['admin_rocad']);
session_destroy();
header("location:/rocad_admin/pages/sections/login.php");
?>
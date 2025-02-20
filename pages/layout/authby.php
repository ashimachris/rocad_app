<?php

$qry_authby="select * from admin where id=$authbyID";

$authby=mysqli_query($config,$qry_authby) or die(mysqli_error($config));
$authby2=mysqli_query($config,$qry_authby) or die(mysqli_error($config));

$row_authby=mysqli_fetch_assoc($authby);

?>



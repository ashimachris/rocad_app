<?php

//mysql_select_db($database_config, $config);

$qry_preby="select * from admin where id=$prebyID";

$preby=mysqli_query($config,$qry_preby) or die(mysqli_error($config));
$preby2=mysqli_query($config,$qry_preby) or die(mysqli_error($config));

$row_preby=mysqli_fetch_assoc($preby);

?>
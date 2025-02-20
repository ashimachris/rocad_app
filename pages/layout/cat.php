<?php

//mysql_select_db($database_config, $config);

$qry_cats="select cat_name from staff_cat where id=$catID";

$cats=mysqli_query($config,$qry_cats) or die(mysqli_error($config));

$row_cats=mysqli_fetch_assoc($cats);

?>
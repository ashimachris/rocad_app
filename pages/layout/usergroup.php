<?php

//mysql_select_db($database_config, $config);

$qry_group="select * from `usergroup` where permission=$usergroupID";

$group=mysqli_query($config,$qry_group) or die(mysqli_error($config));

$row_group=mysqli_fetch_assoc($group);

?>
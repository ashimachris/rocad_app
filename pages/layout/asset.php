<?php

//mysql_select_db($database_config, $config);

$qry_asset="select * from assets where id=$assetID and status=1";

$asset=mysqli_query($config,$qry_asset) or die(mysqli_error($config));

$row_asset=mysqli_fetch_assoc($asset);

?>
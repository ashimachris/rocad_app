<?php

//mysql_select_db($database_config, $config);

$qry_site="select sitename,id,site_lga,site_state,site_loc from rocad_site where id=$siteID and status=1";

$qry_sites="select sitename,id,site_lga,site_state,site_loc from rocad_site where id not in($siteID) and status=1";

$site=mysqli_query($config,$qry_site) or die(mysqli_error($config));

$sites=mysqli_query($config,$qry_sites) or die(mysqli_error($config));

$row_site=mysqli_fetch_assoc($site);
/////
$dep="select * from department where status=1 and id=$siteID";
$qry_dept=mysqli_query($config,$dep);
$row_dept=mysqli_fetch_assoc($qry_dept);
?>
<?php
//mysql_select_db($database_config, $config);
$qry_status="select status from admin where `user_mail`='$mail'";
$status=mysqli_query($config,$qry_status) or die(mysqli_error($config));
$row_status=mysqli_fetch_assoc($status);
switch($row_status['status']){
case 0:
$msg_status="danger";
$msgout="Denied";
break;
case 1:
$msg_status="warning";
$msgout="Pending";

break;
case 2:
$msg_status="success";
$msgout="Active";
break;
default;
$msg_status="danger";
$msgout="Unknown";

}
?>
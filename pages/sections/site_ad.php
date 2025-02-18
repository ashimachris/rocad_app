<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$preby =$_SESSION['admin_rocad'];
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
  if( (isset($_GET['v'])and (!empty($_GET['r'])))){
  $ids=$_GET['r'];
  $action=$_GET['v'];
}
  else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
  if($action==1){////Activate
    $sql="UPDATE `rocad_site` SET status=1,upd_by='$preby' where id='$ids'";
  }
  elseif($action==2){//Deny
    $sql="UPDATE `rocad_site` SET status=2,upd_by='$preby' where id='$ids'";
  }
    elseif($action==3){//Completed
    $sql="UPDATE `rocad_site` SET status=3,upd_by='$preby' where id='$ids'";
  }
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
     
echo    "<script>window.location='sites.php';</script>";
    
}

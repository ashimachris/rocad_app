<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
  if(isset($_GET['v'])and (!empty($_GET['r']))and (!empty($_GET['s']))){
  $ids=$_GET['v'];
  $reference=$_GET['r'];
  $s=$_GET['s'];
  }
else{
  //echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$preby =$_SESSION['admin_rocad'];

$sql="UPDATE `storeloadingdetails` SET isReject=1,modify_by='$preby' where id='$ids'";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
    if($s==1){
echo    "<script>alert('Data successfully Updated.');
   setTimeout(function(){window.location='notification.php?id=$reference';},1200);
      </script>";
    } else{
   
   echo    "<script>alert('Data successfully Updated.');
   setTimeout(function(){window.location='requi-notif.php?v=$reference';},1200);
      </script>";
  }
}

<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
 $timeDate=date('Y-m-d H:i:s');

  if(isset($_GET['v'])and (!empty($_GET['rsn']))){
  $ids=$_GET['v'];
  $resn=$_GET['rsn'];
  $url=$_GET['urls'];
   }
else{
  echo "<script>location.href='morerepair.php?v=$ids';</script>";
}
$preby =$_SESSION['admin_rocad'];

$sql="UPDATE `history` SET isreturn=1,returnby='$preby',returnR='$resn',returnTime='$timeDate' where id='$ids'";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
   echo    "<script>alert('Return Success.');
   setTimeout(function(){window.location='$url';},1200);
      </script>";
    }


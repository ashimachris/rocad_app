<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

  $active_menu = "data_tables";

  include_once "../layout/header.php";

 require_once('../../db_con/config.php');

  if( (isset($_GET['v'])and (!empty($_GET['r'])))){

  $ids=$_GET['r'];

  $action=$_GET['v'];

  $url=$_GET['lk'];

}

  else{

  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';

}

  if($action=='2stf'){////Activate

  	$sql="UPDATE `admin` SET status=0 where user_mail='$ids'";

  }

  elseif($action=='1stf'){

  	$sql="UPDATE `admin` SET status=2 where user_mail='$ids'";

  }elseif($action=='3stf'){

    $sql="DELETE FROM `admin` where user_mail='$ids'";

    $sql2="DELETE FROM `staff` where email='$ids'";

    mysqli_query($config,$sql2) or die(mysqli_error($config));

  

  }elseif($action=='4assets'){

    $sql="UPDATE `structural_asset` SET status=2 where id=$ids";

  

  }elseif($action=='5assets'){

    $sql="UPDATE `structural_asset` SET status=1 where id=$ids";



  }elseif($action=='6assets'){

    $sql="DELETE FROM `structural_asset` where id=$ids";

  }

   elseif($action=='7battery'){

    $sql="DELETE FROM `history` where itemSerial in($ids)";

  } 



$preby =$_SESSION['admin_rocad'];

       

      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));

    

    if($insert==1){

     

echo "<script>window.location='$url';</script>";

    

}

else{

  echo "<script>window.location='$url';</script>";

}


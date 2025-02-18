<?php
require_once('../../../db_con/config.php');
  if (isset($_POST['forgot'])) {
  $passwd = mysqli_real_escape_string($config, $_POST['newpass']);
  $idsv = mysqli_real_escape_string($config, $_POST['ids']);
  $password=password_hash($passwd, PASSWORD_BCRYPT);
  ////////////////////////////////////////
  if (!empty($passwd) && !empty($idsv)) {
      $query_ch = mysqli_query($config, "SELECT * FROM `admin` WHERE id='$idsv'")or die (mysqli_error($config));;
      $result = mysqli_num_rows($query_ch);
    if($result > 0){    
      $sql="UPDATE `admin` SET `passwd`='$password' WHERE id='$idsv'";
$query_upd=mysqli_query($config,$sql)or die (mysqli_error($config));
     if ($query_upd) {
       //require 'mail.php';
          echo    "<div class='alert alert-success text-center' role='alert' style='margin-bottom: 0px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <strong>Success Please re-login!</strong>
                </div>";
        echo    "<script>setTimeout(function(){window.location='/rocad_admin/logout.php';},4200);</script>";
        } else {
           
      echo    "<div class='alert alert-danger text-center' role='alert' style='margin-bottom: 0px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <strong>Error reseting password!</strong>
                </div>";
    }
       
  }
  else{
    echo    "<div class='alert alert-danger text-center' role='alert' style='margin-bottom: 0px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <strong>Error please try again!.</strong>
                </div>";
  }
  }   
  }
  
?>
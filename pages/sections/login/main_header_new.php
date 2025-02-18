<?php
require "../../db_con/config.php";
//echo $_SESSION['admin_rocad'];
if(!empty($_SESSION['admin_rocad'])){
	//header("Location:/rocad_admin/pages/dashboard/");
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
  //echo $_SESSION['admin_rocad'];
}
//////////////////////////Vars
$error="";
	///////////////////////
 if (isset($_POST['login'])) {
    $usermail=mysqli_real_escape_string($config,$_POST['usermail']);
    $passwd =mysqli_real_escape_string($config,$_POST['passwd']);
    //mysql_select_db($database_config, $config);
	//////////////////////////
	 
	///////////////////////
$admin = "SELECT * FROM `admin` WHERE `user_mail`='$usermail'";
$as_admin=mysqli_query($config,$admin) or die(mysqli_error($config));
$row_admin=mysqli_fetch_assoc($as_admin);
$checkadmin = mysqli_num_rows($as_admin);
$status = $row_admin['status'];

if($checkadmin == 0){
$error="The Email Address is not valid!";
}
else{
	switch($status){
		case 0:
		$caseR="Sorry, your account has been deactivated!";
		break;
		case 1:
		$caseR="Sorry, your account has not activated!";
		break;
	}
  if (password_verify($passwd, $row_admin['passwd'])){
    if($status==0 or $status==1){
$error= $caseR;
}
else{
$_SESSION['admin_rocad']=$row_admin['id'];
$_SESSION['usergroup']=$row_admin['usergroup'];

  if(isset($_SESSION['admin_rocad']) and (!empty($_SESSION['admin_rocad']))){
  //header("Location:https://rocad.com/rocad_admin/pages/dashboard/");
  echo "<script>location.href='https://app.rocad.com/rocad_admin/pages/dashboard/index.php'</script>";
   //echo $_SESSION['admin_rocad'];
  }

}
    }
  else{
    echo "<script>alert('Incorrect Password');location.href='/rocad_admin/pages/sections/login.php';</script>";

  }
	
}
}
 
?>
<div class="login-box">
  
  <!-- /.login-logo --> 
  <div class="login-box-body">
    <p class="login-box-msg"><img src="../../images/rocad-logo.png" width="152.5px" height="52.2px"></p>
<p class="login-box-msg"><?php echo "<font color='red'>$error</font>";?></p>
    <form action="" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" required name="usermail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" required name="passwd">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-md-6">
        <button type="submit" class="btn btn-primary btn-block btn-flat w-100" name="login">Log In </button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <center>
    </br>
      <a href="forgot-password.php">forgot password</a>
    </center>

    <style type="text/css">
        a{
          color: black;
        }
        .row{
          display: flex;
          justify-content: center;
        }
    </style>
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php require_once('../../db_con/config.php');?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
 
if(!empty($_SESSION['admin_rocad'])){
	header("Location:/rocad_admin/pages/dashboard/");
}

  
  //////////////////////////Vars
	$error="";
	///////////////////////
 if (isset($_POST['login'])) {
    $usermail=mysql_real_escape_string($_POST['usermail']);
    $passwd =mysql_real_escape_string($_POST['passwd']);
    mysql_select_db($database_config, $config);
	//////////////////////////
	 
	///////////////////////
$admin = "SELECT * FROM `admin` WHERE `user_mail`='$usermail' AND `passwd`='$passwd'";
$as_admin=mysql_query($admin, $config) or die(mysql_error());
$row_admin=mysql_fetch_assoc($as_admin);
$checkadmin = mysql_num_rows($as_admin);
$status = $row_admin['status'];
if($checkadmin == 0){
$error="Invalid login details!";
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
	if($status==0 or $status==1){
$error= $caseR;
}
else{
$_SESSION['admin_rocad']=$row_admin['id'];
$_SESSION['usergroup']=$row_admin['usergroup'];

  if(isset($_SESSION['admin_rocad']) and (!empty($_SESSION['admin_rocad']))){
  header("Location:/rocad_admin/pages/dashboard/");
  }
}
}

}
?>
<div class="login-box">
  
  <!-- /.login-logo --> 
  <div class="login-box-body">
    <p class="login-box-msg"><img src="../../images/rocad-logo.png" width="152.5px" height="52.2px" style="cursor:pointer" onlick="https://rocad.com"></p>
<p class="login-box-msg"><?php echo "<font color='red'>$error</font>" ?></p>
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
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    <!-- /.social-auth-links -->

    <a href="#">forgot password</a><br>
    <a href="register.php" class="text-center">Register</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
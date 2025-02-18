<?php require_once('../../db_con/config.php');
$insert=0;
 $error=0;
 $insetme=0;
if (isset($_POST['reg'])) {
  $fname=mysqli_real_escape_string($config,$_POST['fname']);
    $usermail=mysqli_real_escape_string($config,$_POST['email']);
    $passwd =mysqli_real_escape_string($config,$_POST['passwd']);
     

  $date=date('Y-m-d H:m:s');
  $images=mt_rand();
$admin = "SELECT * FROM `admin` WHERE `user_mail`='$usermail'";
$as_admin=mysqli_query($config,$admin) or die(mysqli_error($config));
$row_admin=mysqli_fetch_assoc($as_admin);
$checkadmin = mysqli_num_rows($as_admin);
if($checkadmin==1){///Avoid Duplicate Users
$error="<font color='red'>Sorry user already Exist! please try another</font>";
}
else{
$insert="INSERT INTO admin(`user_mail`,`passwd`,`fullname`,`images`,`time_date`)VALUES('$usermail','$passwd','$fname','$images','$date')";
$insetme=mysqli_query($config,$insert)or die(mysqli_error($config));
if($insetme==1){
  $error="<font color='green'>Success</font>";
  }
}
}
 
 
 define ("MAX_SIZE","22.6"); 
 
 function getExtension($str) {

         $i = strrpos($str,".");

         if (!$i) { return ""; }

         $l = strlen($str) - $i;

         $ext = substr($str,$i+1,$l);

         return $ext;

 }

 

 $errors=0;

//checks if the form has been submitted

 if(isset($_POST['reg'])) 

 {

  

  //reads the name of the file the user submitted for uploading

  $image=$_FILES['filename']['name'];

  //if it is not empty

  if ($image) 

  {

  //get the original name of the file from the clients machine

    $filename = stripslashes($_FILES['filename']['name']);

  //get the extension of the file in a lower case format

      $extension = getExtension($filename);

    $extension = strtolower($extension);

  

 $size=filesize($_FILES['filename']['tmp_name']);
 
 

$image_name=$images.'.jpg';// convert to jpg

$newname="../../user_img/".$image_name;

}}

 if(isset($_POST['reg']) && !$errors) 

 {

 $copied=copy($_FILES['filename']['tmp_name'], $newname);

  

   }
 
 ?>
 <?php if($insetme==1){?>
  
 <meta http-equiv="refresh" content="3;URL=/rocad_admin/pages/sections/login.php">
 <?php }?>
<div class="register-box">
  <b> </b>

  <div class="register-box-body">
   <p class="login-box-msg"><img src="../../images/rocad-logo.png" width="152.5px" height="52.2px"></p>
    <p class="login-box-msg">Register a new membership</p>
<p class="login-box-msg" id="error"><?php echo "$error";?></p>

    <form action="" method="post" enctype="multipart/form-data" onSubmit="return matchPassword()">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Full name"required name="fname">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" required name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="file" class="form-control" required name="filename" title="Upload your Passport">
       <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" required name="passwd" id="passwd">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Retype password" required name="repasswd" id="repasswd">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" required> 
              I agree to the <a href="#">Terms</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="reg">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using
        Google+</a>
    </div>

    <a href="../../pages/sections/login.php" class="text-center"> Already have an account</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->
<script>  
function matchPassword(){  
  var pw1 = document.getElementById("passwd").value;  
  var pw2 = document.getElementById("repasswd").value;  
  if(pw1 != pw2)  
  {   
  document.getElementById("error").innerHTML="<font color='red'>Password did not match!</font>"; 
    //alert("Passwords did not match");
  return false; 
  } else {  
    return true;
  }  
}  
</script>  

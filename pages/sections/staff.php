<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "dashboard";
  include_once "../layout/header.php";
  require_once('../../db_con/config.php'); 
 $error=0;
$insetme=0;
$msg="";
if (isset($_POST['staff'])) {
  $rank=mysqli_real_escape_string($config,$_POST['usrgrp']);
  switch($rank){
    case 1:
    $getrank="Storekeeper";
    break;
    case 2:
    $getrank="Staff";
    break;
    case 3:
    $getrank="Admin";
    break;
    case 4:
    $getrank="Super Admin";
    break;
    case 5:
    $getrank="Account";
    break;
    case 6:
    $getrank="Visitor";
    break;
  }
  $preby =$_SESSION['admin_rocad'];
	$staffname=mysqli_real_escape_string($config,$_POST['staffname']);
    $email=mysqli_real_escape_string($config,$_POST['email']);
    $phone =mysqli_real_escape_string($config,$_POST['phone']);
	$addr =mysqli_real_escape_string($config,$_POST['addr']);
	$site =mysqli_real_escape_string($config,$_POST['site']);
	$cat =mysqli_real_escape_string($config,$_POST['cat']);
	$usrgrp =mysqli_real_escape_string($config,$_POST['usrgrp']);
	$password =mysqli_real_escape_string($config,$_POST['passwd']);
 $pass=password_hash($password, PASSWORD_DEFAULT);
	//mysql_select_db($database_config, $config);
	$error=""; 
	$date=date('Y-m-d H:m:s');
$staff = "SELECT * FROM `staff` WHERE `email`='$email'";
$as_staff=mysqli_query($config,$staff) or die(mysqli_error($config));
$row_staff=mysqli_fetch_assoc($as_staff);
$checkstaff = mysqli_num_rows($as_staff);
if($checkstaff==1){///Avoid Duplicate Staff
//$error="<font color='red'>($email) Already Exist! please try another!</font>";
$msg="<font color='red'>($email) Already Exist! please try another!</font>";
   echo    "<script>setTimeout(function(){window.location='#';},3200);</script>";
}
else{	 
$insert="INSERT INTO staff(`fname`,`email`,`phone`,`addr`,`site`,`cat`,`usergroup`,`time_date`,`pre_by`)VALUES('$staffname','$email',$phone,'$addr','$site','$cat','$usrgrp','$date',$preby)";
$insetme=mysqli_query($config,$insert)or die(mysqli_error($config));
///////////////////
$insert2="INSERT INTO admin(`user_mail`,`passwd`,`fullname`,`time_date`,`phone`,`status`,`ranks`,`usergroup`,`images`)VALUES('$email','$pass','$staffname','$date','$phone',1,'$getrank','$usrgrp','839448361')";
mysqli_query($config,$insert2)or die(mysqli_error($config));
////////////////////
 if($insetme==1){
  $msg="<font color='green'>Data successfully Saved.</font>";
   echo    "<script>setTimeout(function(){window.location='staffs.php';},3200);</script>";
  }
}
}
 
//mysql_select_db($database_config, $config);
$qry_grp="select usergroups,permission from usergroup";
$grp=mysqli_query($config,$qry_grp) or die(mysqli_error($config));

 
?>
 
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->
  
  <!-- ChartJS -->
  <script src="../../plugins/chartjs/Chart.min.js"></script>

  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="../../dist/js/pages/dashboard2.js"></script> -->
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="../../dist/js/demo.js"></script> -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php";allow_access_all(1,1,0,0,0,0,$usergroup); ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
         
        <ol class="breadcrumb">
          <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
           <li><a href="staffs.php">Staff</a></li>
          <li class="active"> New Staff</li>
        </ol>
      </section>
<br>
      <!-- Main content -->
      <section class="content">
      <div class="row">
      <div class="col-md-2"></div>
 <div class="col-md-8">
          <div class="box box-primary">
             
            <div class="box-header with-border">
              <h3 class="box-title"><u>New Staff</u></h3>
              <div id="error"><?php echo $msg; ?></div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         
            <form action="" method="post" onSubmit="return matchPassword()">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Staff Name: </label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Staff Name" name="staffname">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Staff Email</label>
                  <input type="email" class="form-control" id="exampleInputPassword1" placeholder="Staff Email: @rocad.com" name="email">
                </div>
                 <div class="form-group">
                  <label for="exampleInputPassword1">Phone Number</label>
                  <input type="number" class="form-control" id="exampleInputPassword1" placeholder="Phone Number" name="phone">
                </div>
                 <div class="form-group">
                  <label for="exampleInputPassword1">Addresss</label>
                  <textarea name="addr" style="width: 100%;" placeholder="Staff Address"></textarea>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Assign Staff to Site</label>
                  <select name="site" class="form-control" require>
                  <option value="" selected>::Select Site::</option>
                   <?php while($row_site = mysqli_fetch_assoc($site)){?>
                  <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?></option>
                  <?php }?>
                  </select>
                </div>
                 <div class="form-group">
                  <label for="exampleInputPassword1">Staff Category</label>
                  <select name="cat" class="form-control"require>
                  <option value="" selected>::Select Category::</option>
                  <?php while($row_cat = mysqli_fetch_assoc($cat)){?>
                  <option value="<?php echo $row_cat['id']; ?>"><?php echo $row_cat['cat_name']; ?></option>
                  <?php }?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">User Group</label>
                  <select name="usrgrp" class="form-control" required> 
                  <option value="" selected>::Select Usergroup::</option>
                  <?php while($row_grp=mysqli_fetch_assoc($grp)){?>
                  <option value="<?php echo $row_grp['permission']; ?>"><?php echo $row_grp['usergroups']; ?></option>
                  <?php }?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password </label>
                  <input type="password" class="form-control" placeholder="Password" required name="passwd" id="passwd">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Re-type password </label>
                 <input type="password" class="form-control" placeholder="Retype password" required name="repasswd" id="repasswd">
                </div>
       
                <div class="form-group">
                  

                  <p class="help-block">Prepared by: <?php echo $_SESSION['admin_name']; ?></p><?php  if($error>0){echo $error;} ?>
                </div>
                 
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="staff">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        </div>
        
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../dist/js/pages/dashboard2.js"></script>
<script src="script.js"></script>
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
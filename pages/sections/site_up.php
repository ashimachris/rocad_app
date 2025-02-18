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
if(isset($_GET['v'])and (!empty($_GET['v']))){
  $id=$_GET['v'];
}
else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$staff = "SELECT * FROM `staff` WHERE `id`='$id'";

$as_staff=mysqli_query($config,$staff) or die(mysqli_error($config));

$row_staff=mysqli_fetch_assoc($as_staff);

 $ids=$row_staff['id'];
 $email=$row_staff['email'];

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
 
 
$upd="UPDATE `staff` SET fname='$staffname',phone='$phone',addr='$addr',site='$site',cat='$cat',usergroup='$usrgrp',upd_by='$preby' where id=$ids";
$qry=mysqli_query($config,$upd) or die(mysqli_error($config));

 
$upd2="UPDATE `admin` SET fullname='$staffname',phone='$phone',usergroup='$usrgrp',`passwd`='$pass',`ranks`='$getrank' where user_mail='$email'";
mysqli_query($config,$upd2) or die(mysqli_error($config));
 
if($qry==1){

  $msg="<font color='green'>Success.</font>";

   echo    "<script>setTimeout(function(){window.location='staff_up.php?v=$id';},3200);</script>";

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

          <li class="active">Update Staff</li>

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

                  <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row_staff['fname']; ?>" name="staffname">

                </div>                 

                 <div class="form-group">

                  <label for="exampleInputPassword1">Phone Number</label>

                  <input type="number" class="form-control" id="exampleInputPassword1" placeholder="Phone Number" name="phone" value="<?php echo $row_staff['phone']; ?>">

                </div>

                 <div class="form-group">

                  <label for="exampleInputPassword1">Addresss</label>

                  <textarea name="addr" style="width: 100%;" placeholder="Staff Address"><?php echo $row_staff['addr']; ?></textarea>

                </div>

                <div class="form-group">

                  <label for="exampleInputPassword1">Assign Staff to Site (Current: <?php $siteID=$row_staff['site'];require '../layout/site.php';echo $row_site['sitename']; ?>)</label>

                  <select name="site" class="form-control" require>

                  <option value="" selected>::Select Site::</option>

                   <?php while($row_sites = mysqli_fetch_assoc($sites)){?>

                  <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>

                  <?php }?>

                  </select>

                </div>

                 <div class="form-group">

                  <label for="exampleInputPassword1">Staff Category (Current: <?php $catID=$row_staff['cat'];require '../layout/cat.php'; echo $row_cats['cat_name']; ?>)</label>

                  <select name="cat" class="form-control"require>

                  <option value="" selected>::Select Category::</option>

                  <?php while($row_cat = mysqli_fetch_assoc($cat)){?>

                  <option value="<?php echo $row_cat['id']; ?>"><?php echo $row_cat['cat_name']; ?></option>

                  <?php }?>

                  </select>

                </div>

                <div class="form-group">

                  <label for="exampleInputPassword1">User Group (Current: <?php $usergroupID=$row_staff['usergroup'];require '../layout/usergroup.php';echo $row_group['usergroups']; ?>)</label>

                  <select name="usrgrp" class="form-control" required> 

                  <option value="" selected>::Select Usergroup::</option>

                  <?php while($row_grp=mysqli_fetch_assoc($grp)){?>

                  <option value="<?php echo $row_grp['permission']; ?>"><?php echo $row_grp['usergroups']; ?></option>

                  <?php }?>

                  </select>

                </div>

                <div class="form-group">

                  <label for="exampleInputPassword1">Password </label>

                  <input type="password" class="form-control" placeholder="Password" required name="passwd" id="passwd" value="<?php echo $row_staff['addr']; ?>">

                </div>

                <div class="form-group">

                  <label for="exampleInputPassword1">Re-type password </label>

                 <input type="password" class="form-control" placeholder="Retype password" required name="repasswd" id="repasswd" value="<?php echo $row_staff['addr']; ?>">

                </div>

       

                <div class="form-group">

                  



                  <p class="help-block">Prepared by: <?php echo $_SESSION['admin_name']; ?></p><?php  if($error>0){echo $error;} ?>

                </div>

                 

              </div>

              <!-- /.box-body -->



              <div class="box-footer">

                <button type="submit" class="btn btn-primary" name="staff">Update</button>

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
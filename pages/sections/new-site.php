<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "dashboard";
  include_once "../layout/header.php";
  require_once('../../db_con/config.php'); 
$insetme=0;
$error='';
//session_start();
$preby =$_SESSION['admin_name'];
if (isset($_POST['site'])) {
	$sitename=mysqli_real_escape_string($config,$_POST['sitename']);
    $loc=mysqli_real_escape_string($config,$_POST['siteloc']);
    $state =mysqli_real_escape_string($config,$_POST['state']);
	$lga =mysqli_real_escape_string($config,$_POST['lga']);
	//mysql_select_db($database_config, $config);
	$error=""; 
	$date=date('Y-m-d H:m:s');
	 
$admin = "SELECT * FROM `rocad_site` WHERE `sitename`='$sitename'";
$as_admin=mysqli_query($config,$admin) or die(mysql_error($config));
$row_admin=mysqli_fetch_assoc($as_admin);
$checkadmin = mysqli_num_rows($as_admin);

if($checkadmin==1){///Avoid Duplicate site
$error="<font color='red'>($sitename) Already Exist! please try another!</font>";
}
else{

$insert="INSERT INTO rocad_site(`sitename`,`site_loc`,`site_state`,`site_lga`,`pre_by`,`time_date`)VALUES('$sitename','$loc','$state','$lga','$preby','$date')";
$insetme=mysqli_query($config,$insert)or die(mysql_error($config));
$last_id = mysqli_insert_id($config);

$insertOIL="INSERT INTO `oil_stock`(`cat`,`ltr`,`site`,`time_date`,`preby`)VALUES
('Diesel',0,$last_id,'$date','$preby'),
('Petrol',0,$last_id,'$date','$preby'),
('Engine Oil',0,$last_id,'$date','$preby'),
('Gear Oil',0,$last_id,'$date','$preby'),
('Hydraulic Oil',0,$last_id,'$date','$preby')";

mysqli_query($config,$insertOIL)or die(mysql_error($config));
if($insetme==1){
	$error="<font color='green'>Success</font>";
  echo "<script>setTimeout(function(){window.location='sites.php';},4200);</script>";
	}
}
}
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
    <?php include_once "../layout/left-sidebar.php"; ?>
    
	<?php allow_access_all(1, 0, 0, 0, 0, 0, $usergroup); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
         
        <ol class="breadcrumb">
          <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
           <li><a href="sites.php">Sites</a></li>
          <li class="active">Create New Site</li>
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
              <h3 class="box-title">Create New Site</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="post">
              <div class="box-body">
                <h3 class="box-title"><?php  echo $error; ?></h3>
                <div class="form-group">
                  <label for="exampleInputEmail1">Site Name: </label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Site Name" name="sitename">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Site Location</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Site Location" name="siteloc">
                </div>
                 <div class="form-group">
                  <label for="exampleInputPassword1">State</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="State of the site" name="state">
                </div>
                 <div class="form-group">
                  <label for="exampleInputPassword1">LGA</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Local Governement" name="lga">
                </div>
                <div class="form-group">
                  

                  <p class="help-block">Prepared by: <?php echo $_SESSION['admin_name']; ?></p><?php  if($error>0){echo $error;} ?>
                </div>
                 
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="site">Submit</button>
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
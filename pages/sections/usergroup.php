<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

$active_menu = "dashboard";

include_once "../layout/header.php";

require_once('../../db_con/config.php');

 

//session_start();



if (isset($_POST['group'])) {

  $preby =$_SESSION['admin_rocad'];

	$name=mysqli_real_escape_string($config,$_POST['gname']);

	$grp=$_POST['gpm'];

     	//mysql_select_db($database_config);

  

	$date=date('Y-m-d H:m:s');

$staff = "SELECT * FROM `usergroup` WHERE `usergroups`='$name'";

$as_staff=mysqli_query($config,$staff) or die(mysqli_error($config));

$row_staff=mysqli_fetch_assoc($as_staff);

$checkstaff = mysqli_num_rows($as_staff);

if($checkstaff==1){///Avoid Duplicate Staff

$error="<font color='red'>($email) Already Exist! please try another!</font>";

}

else{	 

$insert="INSERT INTO usergroup(`usergroups`,`permission`)VALUES('$name','$grp')";

$insetme=mysqli_query($config,$insert)or die(mysqli_error($config));

///////////////////

 

if($insetme){

	$error="<font color='green'>Success</font>";

  echo "<script>setTimeout(function(){window.location='usergroups.php';},4200);</script>";

	}

}

}

?>

 

<body class="hold-transition skin-blue sidebar-mini">

  <!-- Put Page-level css and javascript libraries here -->

  

  <!-- ChartJS -->

  

   <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">

  <!-- bootstrap datepicker -->

  <link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">

  <!-- iCheck for checkboxes and radio inputs -->

  <link rel="stylesheet" href="../../plugins/iCheck/all.css">



  <!-- Bootstrap Color Picker -->

  <link rel="stylesheet" href="../../plugins/colorpicker/bootstrap-colorpicker.min.css">

  <!-- Bootstrap time Picker -->

  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">

  <!-- Select2 -->

  <link rel="stylesheet" href="../../plugins/select2/select2.min.css">



  <!-- Select2 -->

  <script src="../../plugins/select2/select2.full.min.js"></script>

  <!-- InputMask -->

  <script src="../../plugins/input-mask/jquery.inputmask.js"></script>

  <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>

  <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>



  <!-- date-range-picker -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>



  <script src="../../plugins/daterangepicker/daterangepicker.js"></script>

  <!-- bootstrap datepicker -->

  <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>

  <!-- bootstrap color picker -->

  <script src="../../plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

  <!-- bootstrap time picker -->

  <script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>



  <!-- iCheck 1.0.1 -->

  <script src="../../plugins/iCheck/icheck.min.js"></script>



  <div class="wrapper">



    <?php include_once "../layout/topmenu.php"; ?>

    <?php include_once "../layout/left-sidebar.php";allow_access_all(1,0,0,0,0,0,$usergroup); ?>

    



    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">

      <!-- Content Header (Page header) -->

      <section class="content-header">

         

        <ol class="breadcrumb">

          <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>

           <li><a href="#">Administration</a></li>

          <li class="active"><a href="usergroups.php">Usergroups</a></li>

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

              <h3 class="box-title"> User Groups</h3>

              <div id="error"><?php echo $error; ?></div>

            </div>

            <!-- /.box-header -->

            <!-- form start -->

            <form action="" method="post" onSubmit="return matchPassword()">

              <div class="box-body">

                <div class="form-group">                  

                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Group Name" name="gname" required><br>

                </div>

                              <div class="form-group">

                <label>

                  <input type="radio" name="gpm" class="flat-red" checked value="4">

                </label>

                

             Super Admin.</div>

  <div class="form-group">

                <label>

                  <input type="radio" name="gpm" class="flat-red" checked value="3">

                </label>

                Admin.

     

  </div>

  <div class="form-group">

                <label>

                  <input type="radio" name="gpm" class="flat-red" checked value="2">

                </label>

    Staff</div>

  

               <div class="form-group">

                <label>

                  <input type="radio" name="gpm" class="flat-red" checked value="1">

                </label>

    Storekeeper</div>

     <div class="form-group">

                <label>

                  <input type="radio" name="gpm" class="flat-red" checked value="5">

                </label>

    Account</div>

    <div class="form-group">

                <label>

                  <input type="radio" name="gpm" class="flat-red" checked value="6">

                </label>

    Visitor</div>





  

             

              

              <!-- /.box-body -->



              <div class="box-footer"><br>

                <button type="submit" class="btn btn-primary" name="group">Submit</button>

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

 

   

<script src="../forms/advanced_elements/script.js"></script>
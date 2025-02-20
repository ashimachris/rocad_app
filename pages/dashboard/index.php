<?php
require_once('../../db_con/config.php');
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "dashboard";
include_once "../layout/header.php";
//include "../layout/session.php";
$assets_oil = "SELECT SUM(CASE WHEN cat='Diesel' THEN ltr END) as diesel,
                  SUM(CASE WHEN cat='Petrol' THEN ltr END) as petrol,
                  SUM(CASE WHEN cat='Engine Oil' THEN ltr END) as engineoil,
                  SUM(CASE WHEN cat='Gear Oil' THEN ltr END) as gearoil,
                  SUM(CASE WHEN cat='Hydraulic Oil' THEN ltr END) as hydraulicoil
  FROM `oil_stock` where ltr>0";
$oil_assets=mysqli_query($config,$assets_oil) or die(mysqli_error($config));
$row_oil_count=mysqli_fetch_assoc($oil_assets);

$assets = "SELECT count(CASE WHEN cat='Diesel' THEN ltr END) as diesel,
                  count(CASE WHEN cat='Petrol' THEN ltr END) as petrol,
                  count(CASE WHEN cat='Engine Oil' THEN ltr END) as engineoil,
                  count(CASE WHEN cat='Gear Oil' THEN ltr END) as gearoil,
                  count(CASE WHEN cat='Hydraulic Oil' THEN ltr END) as hydraulicoil
                  FROM `oil_stock_history` where ltr>0";

$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
$row_oil=mysqli_fetch_assoc($as_assets);
////Assets
$assetsC = "SELECT * FROM `assets` where asset_type=2";
$as_assetsC=mysqli_query($config,$assetsC) or die(mysqli_error($config));
$Cassets = mysqli_num_rows($as_assetsC);
////Consumables
$assetsC1 = "SELECT * FROM `assets` where asset_type=1";
$as_assetsC1=mysqli_query($config,$assetsC1) or die(mysqli_error($config));
$C1assets = mysqli_num_rows($as_assetsC1);
////Staff
$assetsC2 = "SELECT * FROM `staff`";
$as_assetsC2=mysqli_query($config,$assetsC2) or die(mysqli_error($config));
$C2assets = mysqli_num_rows($as_assetsC2);
////Site
$assetsC3 = "SELECT * FROM `rocad_site` where status not in(0)";
$as_assetsC3=mysqli_query($config,$assetsC3) or die(mysqli_error($config));
$C3assets = mysqli_num_rows($as_assetsC3);

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



    <?php include "../layout/topmenu.php"; ?>

    <?php include "../layout/left-sidebar.php"; ?>

    



    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">

      <!-- Content Header (Page header) -->

      <section class="content-header">

        <h1>

          Dashboard 

        </h1>

        <ol class="breadcrumb">

          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

          <li class="active">Dashboard</li>

        </ol>

      </section>



      <!-- Main content -->

      <section class="content">



        <?php include_once "../dashboard/main_header.php"; ?>

        

      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->

    

    <?php include_once "../layout/copyright.php"; ?> 



    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

    <div class="control-sidebar-bg"></div>

  </div><!-- ./wrapper -->



<?php include_once "../layout/footer.php" ?>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<script src="../../dist/js/pages/dashboard2.js"></script>

<script src="script.js"></script>

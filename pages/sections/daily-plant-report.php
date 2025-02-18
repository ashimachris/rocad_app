<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  //$active_menu = "blank";
  include_once "../layout/header.php";
  $active_menu = "blank";
  include_once "../layout/header.php";
  ?>
<style type="text/css">
  .treeview-menu li {
    padding-left: 15px;
  }
  .box-body {
    min-height: 500px;
  }
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daily Plant Report Details
        <small>Report Date: </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="daily-plant-reports.php"><i class="fa fa-dashboard"></i> Daily Plant Reports</a></li>
        <li class="active">Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header">
        	<center><h2></h2></center>
        </div>
            <!-- /.box-header -->
            <div class="box-body">
            
          </div>
        </div>
        <div class="box-body">
          
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="blank/script.js"></script>
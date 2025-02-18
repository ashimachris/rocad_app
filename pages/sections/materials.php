<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
?>
<?php require_once('../../db_con/config.php');?>
<?php
//mysql_select_db($database_config, $config);
$assets = "SELECT * FROM `assets` where asset_type=1";//Materials
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
//$row_admin=mysql_fetch_assoc($as_admin);
$checkassets = mysqli_num_rows($as_assets);
?>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Assets</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="asset.php">New Asset</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title" ><a href="asset.php">Add new Asset</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                <th>S/N</th>
                  <th>Asset Name</th>
                  <th>Part Number</th>
                  <th>Quantity</th>
                   <th>Asset Category</th>
                    <th>Status</th>
                     <th>Site</th>
                   
                   
                </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>
                <tr>
                <td><?php echo $j; ?></td>
                  <td><?php echo $row_assets['assetname']; ?></td>
                  <td><?php echo $row_assets['partno']; ?></td>
                  <td><?php echo $row_assets['qty']; ?></td>
                   <td><?php echo "Material"; ?></td>
                   <td><?php switch($row_assets['status']){case 0:echo "Not Working";break;case 1:echo "Active";break;} ?></td>
                   <td><?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['sitename']; ?></td>
                   
                  
                 </tr>
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                <th>S/N</th>
                  <th>Asset Name</th>
                  <th>Part Number</th>
                  <th>Quantity</th>
                   <th>Asset Category</th>
                    <th>Status</th>
                     <th>Site</th>
                  
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

           
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>
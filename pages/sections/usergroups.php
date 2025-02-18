<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
//mysql_select_db($database_config, $config);
$group = "SELECT * FROM `usergroup` order by id desc";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
//$row_group=mysql_fetch_assoc($as_group);
$checkgroup = mysqli_num_rows($as_group);
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

    <?php include_once "../layout/topmenu.php";
	allow_access_all(1, 0, 0, 0,0,0, $usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Usergroup</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="usergroup.php">New Usergroup</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><a href="usergroup.php">Create Usergroup</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                <th>S/N</th>
                  <th>User Groups</th>
                  <th>Permission</th>
                  <th>Time & Date</th>
                  
                </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_group=mysqli_fetch_assoc($as_group)){$j++; ?>
                <tr>
                <td><?php echo $j; ?></td>
                  <td><?php echo $row_group['usergroups']; ?></td>
                  <td><?php switch($row_group['permission']){case 4:echo "<span class='dropbtn label label-success'>Create,Read,Update and Delete</span>";break;case 3:echo "<span class='dropbtn label label-success'>Create,Read and Update</span>";break;case 2:echo "<span class='dropbtn label label-success'>Read and Create</span>";break;case 1:echo "<span class='dropbtn label label-success'>Create and Read</span>";break;case 5:echo "<span class='dropbtn label label-success'>Read and Update</span>";break;case 6:echo "<span class='dropbtn label label-success'>Read Only</span>";break;default;echo "<span class='dropbtn label label-danger'>Unknown</span>";} ?></td>
                  <td><?php echo $row_group['time_date']; ?></td>
                  
                </tr>
                <?php }?>
              
                </tbody>
                <tfoot>
               <tr>
                <th>S/N</th>
                  <th>User Groups</th>
                  <th>Permission</th>
                  <th>Time & Date</th>
                  
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
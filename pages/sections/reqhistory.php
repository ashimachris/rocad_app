<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
//mysql_select_db($database_config, $config);
if(isset($_GET['v'])and (!empty($_GET['pt']))){
  $id=$_GET['v'];
  $plant=$_GET['pt'];
}
else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$group = "SELECT * FROM `assets` where id=$id";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
$row_group=mysqli_fetch_assoc($as_group);
$checkgroup = mysqli_num_rows($as_group);
 
// $assets = "SELECT * FROM `storeloadingdetails` where PlantNo='$plant' and dept is not null group by reference,ifnull(reference,id) order by id desc";
$assets = "SELECT * FROM `storeloadingdetails` where PlantNo='$plant' order by id desc";

$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
?>

<style>
.dropbtn {
  background-color: #fff;
  color: #337ab7;
  padding: 3px;
  font-size: 13.5px;
  border: none;
  cursor: pointer;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #fff;
}
</style>

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
  include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Plant Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="equipments.php">plant</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
             <!-- /.box-header -->
             <center>
               
              <h3>Requisition history for  - <?php echo $plant ?> </h3>
            </center>

              <hr>
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">

                <thead>

                <tr>

                <th>S/N</th>
                <th>From:</th>
                <th>Reference:</th>
                <th>Description:</th>
                <th>Autorised By:</th>
                <th>Supplier</th>
                <th>Time_Date:</th>
                <th>Status:</th>      

                  </tr>

                </thead>

                <tbody>

                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>

                  <tr onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" >

                <td><?php echo $j; ?></td>

                <td><?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>

                <td><?php echo $row_assets['reference']; ?></td>

                  <!-- <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td> -->
                  <td><?php echo $row_assets['reqfor']; ?></td>
                  <td><?php if(!empty($row_assets['authby'])){$prebyID=$row_assets['authby'];}else{$prebyID=0;}require '../layout/preby.php';echo $row_preby['fullname']; ?></td>

                   <td><?php echo $row_assets['supl']; ?></td>
                                
                 <td><?php echo $row_assets['time_date']; ?></td>
                  

                   <td><?php switch($row_assets['status']){case 0:echo "<span class='label label-warning'>Pending</span>";break;case 1:echo "<span class='label label-danger'>Denied</span>";break;case 2:echo "<span class='label label-success'>Approved</span>";break;case 4:echo "<span class='label label-success'>Received</span>";break;case 5:echo "<span class='label label-success'>Received</span>";break;default:echo "<span class='label label-danger'>Unknown</span>";} ?>
                     
                   </td>
                 

                <?php }?>
              
                </tbody>

                <tfoot>

                  <tr>

                <th>S/N</th>
                <th>From:</th>
                <th>Reference:</th>
                <th>Description:</th>
                <th>Autorised By:</th>
                <th>Supplier</th>
                <th>Time_Date:</th>
\                <th>Status:</th>      

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
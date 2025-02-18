<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
//mysql_select_db($database_config, $config);
if(isset($_GET['v'])and (!empty($_GET['v']))){
  $ids=$_GET['v'];
}
else{
  echo '<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
 $asset = "SELECT * FROM `storeloadingdetails` where reference=$ids";
 $as_asset=mysqli_query($config,$asset) or die(mysqli_error($config));
$row_assets=mysqli_fetch_assoc($as_asset);

////////////////////////
$assetI = "SELECT * FROM `invoices` where lpo=$ids or reference=$ids";
 $as_assetI=mysqli_query($config,$assetI) or die(mysqli_error($config));
$row_assetsI=mysqli_fetch_assoc($as_assetI);
$checkassets = mysqli_num_rows($as_assetI);
if($checkassets==0){
  echo "<script>location.href='receiving_report.php?v=$ids';</script>";
}
$img=$row_assetsI['reference'];
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
  min-width: 20px;
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
 allow_access_all(1,1,0,0,1,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Invoice Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="invoices.php">Invoices</a></li>
        <li class="active"  ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
             
            <!-- /.box-header -->
            <div class="box-body"> 
              <table id="example" class="table table-bordered table-hover" style=" text-transform: uppercase;">
                
                <tbody>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th align="right">L.P.O. No.:</th>
          <td align="left"><?php echo $row_assetsI["lpo"]; ?></td>
                  <!--<th>Payed With:</th>
                 <td><?php switch($row_assetsI["cct"]){case '':echo "N/A";break;case 1: echo "Cash";break;case 2:echo "Check";break;case 3:echo "Transfer";break;}?></td>
                 <th></th>
                <th><?php switch($row_assetsI['ispaid']){ case 1: echo "<div class='dropdown'>
  <span class='dropbtn label label-success'>PAID</span></div>";
  break; case 0:
   echo "<div class='dropdown'>
  <span class='dropbtn label label-warning'>UNPAID &#11167;</span>
  <div class='dropdown-content'>
 <a href='oil_invoices.php?v=$img'>PAY</a>
  </div> 
</div>";break;}?></th> -->
                
                 </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Plant No.:</th>
                  <td align="left"><?php if($row_assets["PlantNo"]){echo $row_assets["PlantNo"];}else{echo "N/A";} ?></td>
                 <!-- <th>Check Reference No:</th>
                 <td><?php if($row_assetsI["check_ref"]){echo $row_assetsI["check_ref"];}else{echo "N/A";} ?></td>-->
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Title:</th>
                  <td align="left"><?php echo $row_assetsI["title"]; ?></td>
                  <th>Account Name:</th>
                 <td><?php if($row_assets["supl"]){echo $row_assets["supl"];}else{echo "N/A";}?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Upload On:</th>
                  <td align="left"><?php echo $row_assetsI["uploaded_on"]; ?></td>
                  <th>Account Number.:</th>
                 <td><?php if($row_assets["pay_to"]){echo $row_assets["pay_to"];}else{echo "N/A";}?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Upload By:</th>
                  <td align="left"><?php $prebyID=$row_assetsI["uploadby"];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                
                <th> Bank Name :</th>
                 <td><?php if($row_assets["bank_name"]){echo $row_assets["bank_name"];}else{echo "N/A";}?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Supplier Name:</th>
                <td align="left"><?php echo $row_assetsI["supl"]; ?></td>
                <th>Quantity:</th>
                <td><?php echo $row_assets["qty"]; ?></td>
              
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Required For:</th>
                  <td align="left"><?php echo $row_assets["reqfor"]; ?><?php echo $row_assetsI["infoD"]; ?></td>
                <!-- <th>Quantity:</th>
                 <td><?php echo $row_assets["qty"]; ?></td> -->
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                 </tbody>
                 
              </table>
              <hr>
              <center>
                <div id="inv">
                
                  <!-- <img src="<?php echo  $row_assetsI["file_name"]; ?>" style="padding-top:50px;padding-left:20px; max-height: 10%;" class="hidden-mobile" height="650px">  -->
                  <img src="<?php echo  $row_assets["invoice"]; ?>" style="padding-top:50px;padding-left:20px; max-height: 10%;" class="hidden-mobile" height="650px">
                </div>
              </center>
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
<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');


if(isset($_GET['v'])and (!empty($_GET['v']))){
  $id=$_GET['v'];
}
else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$group = "SELECT * FROM `structural_asset` where id=$id";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
$row_group=mysqli_fetch_assoc($as_group);
$plantNo=$row_group['sortno'];
$asset_id=$row_group['id'];

$checkgroup = mysqli_num_rows($as_group);
//$assets = "SELECT * FROM `history` where assetID=$id or PlantNo='$plantNo' order by id desc";
//$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config)); 
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
   ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Asset Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="structural_asset.php">Asset</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
             
            <!-- /.box-header 
            <div class="box-body"><center><div <?php allow_access(1,0,1,1,0,0,$usergroup); ?>><?php if($row_group["EngineType_dies_petr"]!=="ELECTRICAL"){?><a href="diesel.php?v=<?php echo $id;?>"><?php echo $row_group["EngineType_dies_petr"]; ?></a> || <?php }?><a href="engine_oil.php?v=<?php echo $id;?>">ENGINE OIL</a> || <a href="hydraulic_oil.php?v=<?php echo $id;?>">HYDRAULIC OIL</a> || <a href="gear_oil.php?v=<?php echo $id;?>">GEAR OIL</a> ||<a href="repair.php?v=<?php echo $id;?>">REPAIR</a> || <a href="tyre.php?v=<?php echo $id;?>">TYRE</a> || <a href="battery.php?v=<?php echo $id;?>">BATTERY</a> </div></center> -->
              
            <table id="example" class="table table-bordered table-hover" style=" text-transform: uppercase;"> 
                
                <tbody>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <td align="left">
                  
                     <center>
                      <a href="<?php echo $row_group["image"]; ?>" target="_blank">
                      <img src="<?php echo $row_group["image"]; ?>" width="500" height="300" />
                    </a>
                    </center>
                  </td>
                  <th></th>
                 <td></td>
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
                <th align="right">Description:</th>
                  <td align="left"><?php echo $row_group["asset_type"]; ?></td>
                  <th>Asset Name:</th>
                 <td><?php echo $row_group["asset_name"]; ?></td>
                 <th></th>
                <th></th>
                
               <!-- <th <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a href="updatePlant.php?v=<?php echo $id;?>">Update</a></th>
                <th><div class="dropdown">
  <button class="dropbtn">HISTORY</button>
  <div class="dropdown-content">
  <?php if($row_group["EngineType_dies_petr"]!="ELECTRICAL"){?><a href="dieselhistory.php?v=<?php echo $id;?>"><?php echo $row_group["EngineType_dies_petr"]; ?></a><?php }?>
  <a href="repairhistory.php?v=<?php echo $id;?>">Repair</a>
  <a href="tyrehistory.php?v=<?php echo $id;?>">Tyre</a>
  <a href="batteryhistory.php?v=<?php echo $id;?>">Battery</a>
<a href="ldhistory.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">loading-note</a>
<a href="reqhistory.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">Requisition</a>
<a href="aggregate_history.php?v=<?php echo $row_group["sortno"];?>">Aggregate</a>
  </div>
</div>
</th>
<th <?php allow_access(1,0,0,0,1,0,$usergroup); ?>><a href="asset_invoices.php?v=<?php echo $id;?>">Invoices</a></th>
<th></th> -->
                </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Asset Size:</th>
                  <td align="left"><?php echo $row_group["size"]; ?></td>
                  <th>Asset No:</th>
                 <td><?php echo $row_group["asset_number"]; ?></td>
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
                <th>Purchase Year:</th>
                  <td align="left"><?php echo $row_group["purchase_year"]; ?></td>
                  <th>Quantity in Store.:</th>
                 <td><?php echo $row_group["qty_store"]; ?></td>
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
                <th>Total Quantity:</th>
                  <td align="left"><?php echo $row_group["total_qty"]; ?></td>
                  <th>Cost of Equipment:</th>
                 <td><?php echo $row_group["cost"]; ?></td>
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
                <th>Store Keeper:</th>
                  <td align="left"><?php echo $row_group["store_keeper"]; ?></td>
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
                  <th>Site:</th>
                 <td><?php $siteID=$row_group['site'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']; ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                                
                </tbody>
              </table>

              <button onclick="window.location='/rocad_admin/pages/sections/asset_activity.php?id=<?php echo $asset_id ?>'" style="width: 120px; height: 30px; font-size: 12px; border-radius: 10px; margin: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none; float:right; display: flex; justify-content: center; align-items: center;"
                              class="move">Move Location</button>
              <button onclick="window.location='/rocad_admin/pages/sections/receive_structural_asset.php?id=<?php echo $asset_id ?>'" style="width: 100px; height: 30px; font-size: 14px; border-radius: 10px; margin: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none; float:right; display: flex; justify-content: center; align-items: center;"
                              class="move">Receive</button>

              <hr>
              <center><div><h2><span style="color:green;text-decoration: overline;">Moved out Activities</span></h2></div></center>
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Authorised By</th>
                    <!--<th>Available Qty</th>-->
                    <th>Quantity Moved</th>
                    <th>Store Keeper</th>
                    <th>Site</th>
                    <th>Prepare by</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php 
                $assets = "SELECT * FROM `asset_activity` where asset_id=$id and type='move' order by id desc";
                $as_assets=mysqli_query($config,$assets) or die(mysqli_error($config)); 
                $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>
                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');">
                    <td><?php echo $j; ?></td>
                    <td><?php echo $row_assets['date']; ?></td>
                    <!--<td><?php echo $row_assets['total_qty']; ?></td>-->
                    <td><?php if(!empty($row_assets['approved_by'])){$authbyID=$row_assets['approved_by'];}else{$authbyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/authby.php';echo $row_preby['fullname']; ?></td>
                    <!--<td><?php echo $row_assets['avl_qty']; ?></td>-->
                    <td><?php echo $row_assets['move_qty']; ?></td>
                    <td><?php echo $row_assets['store_keeper']; ?></td>             
                    <td><?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['sitename']; ?></td>                
                    <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td> 

                    <td>
                    <?php
                    switch ($row_assets['status']){
                       case 1:
                       echo '<span class="rounded-pill badge text-primary bg-gradient-primary px-3"  style="background:blue">Approved</span>';
                       break;
                      case 2:
                        echo '<span class="rounded-pill badge badge-dark bg-gradient-error px-3 text-light">Not Approve</span>';
                      break;
                      default:
                        echo '<span class="rounded-pill badge badge-dark bg-gradient-default px-3 text-light">Pending</span>'; 
                      }
                      ?>
                   </td>       
                 
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Authorised By</th>
                    <th>Quantity Moved</th>
                    <th>Store Keeper</th>
                    <th>Site</th>
                    <th>Prepare by</th>
                    <th>Status</th>
                </tr>
                </tfoot>
              </table>

              <hr>
              <center><div><h2><span style="color:green;text-decoration: overline;">Received in Activities</span></h2></div></center>
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Quantity Received</th>
                    <th>Store Keeper</th>
                    <th>Site</th>
                    <th>Prepare by</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php 
                $assets = "SELECT * FROM `asset_activity` where asset_id=$id and type='receive' order by id desc";
                $as_assets=mysqli_query($config,$assets) or die(mysqli_error($config)); 
                $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>
                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');">
                    <td><?php echo $j; ?></td>
                    <td><?php echo $row_assets['date']; ?></td>
                    <td><?php echo $row_assets['move_qty']; ?></td>
                    <td><?php echo $row_assets['store_keeper']; ?></td>             
                    <td><?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['sitename']; ?></td>                
                    <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td> <td>
                    <?php
                    // switch ($row_assets['status']){
                    //    case 1:
                       echo '<span class="rounded-pill badge text-primary bg-gradient-primary px-3"  style="background:blue">Approved</span>';
                    //    break;
                    //   case 2:
                        // echo '<span class="rounded-pill badge badge-dark bg-gradient-error px-3 text-light">Not Approve</span>';
                      // break;
                      // default:
                      //   echo '<span class="rounded-pill badge badge-dark bg-gradient-default px-3 text-light">Pending</span>'; 
                      // }
                      ?>
                   </td>                
                      
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Quantity Received</th>
                    <th>Store Keeper</th>
                    <th>Site</th>
                    <th>Prepare by</th>
                    <th>Status</th>
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
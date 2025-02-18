<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
//mysql_select_db($database_config, $config);
if(isset($_GET['v'])and (!empty($_GET['v']))){
  $id=$_GET['v'];
}
else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$group = "SELECT * FROM `assets` where id=$id";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
$row_group=mysqli_fetch_assoc($as_group);
$plantNo=$row_group['sortno'];
$checkgroup = mysqli_num_rows($as_group);
$assets = "SELECT * FROM `history` where assetID=$id or PlantNo='$plantNo' order by id desc";
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
   ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

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
          <div class="box table-responsive">
             
            <!-- /.box-header -->
            <div class="box-body"><center><div <?php allow_access(1,0,1,1,0,0,$usergroup); ?>><?php if($row_group["EngineType_dies_petr"]!=="ELECTRICAL"){?><a href="diesel.php?v=<?php echo $id;?>"><?php echo $row_group["EngineType_dies_petr"]; ?></a> || <?php }?><a href="engine_oil.php?v=<?php echo $id;?>">ENGINE OIL</a> || <a href="hydraulic_oil.php?v=<?php echo $id;?>">HYDRAULIC OIL</a> || <a href="gear_oil.php?v=<?php echo $id;?>">GEAR OIL</a> ||<a href="repair.php?v=<?php echo $id;?>">REPAIR</a> || <a href="tyre.php?v=<?php echo $id;?>">TYRE</a> || <a href="battery.php?v=<?php echo $id;?>">BATTERY</a> </div></center>
              <table id="example" class="table table-bordered table-hover table-responsive" style=" text-transform: uppercase;">
                
                <tbody>
                <tr> 
                  <th> </th>
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <td <?php allow_access(1,1,0,0,0,1,$usergroup); ?>><a href="updatePlant.php?v=<?php echo $id;?>">Update</a></td>
                  <td><div class="dropdown">
                    <button class="dropbtn">HISTORY</button>
                    <div class="dropdown-content">
                      <a href="reqhistory.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">Requisition</a>
                      <a href="ad_voucher_history.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">Advance Voucher</a> 
                      <a href="repairhistory.php?v=<?php echo $id;?>">Repair</a>
                      <?php if($row_group["EngineType_dies_petr"]!="ELECTRICAL"){?><a href="dieselhistory.php?v=<?php echo $id;?>"><?php echo $row_group["EngineType_dies_petr"]; ?></a><?php }?>
                      <a href="tyrehistory.php?v=<?php echo $id;?>">Tyre</a>
                      <a href="batteryhistory.php?v=<?php echo $id;?>">Battery</a>
                      <!-- <a href="ldhistory.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">loading-note</a> -->
                       <a href="aggregate_history.php?v=<?php echo $row_group["sortno"];?>">Aggregate</a>
                    </div>
                  </div>
                </td>
                <th <?php allow_access(1,0,0,0,1,0,$usergroup); ?>><a href="asset_invoices.php?v=<?php echo $id;?>">Invoices</a></th>
                <th></th>
                <th></th> 
                <th></th> 
                <th></th> 
                <th></th> 
                <th></th> 
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
                <th align="right">Description:</th>
                  <td align="left"><?php echo $row_group["assetname"]; ?></td>
                  <td></td>
                  <th>Make:</th>
                 <td><?php echo $row_group["make"]; ?></td>
                <th></th>
                <th></th>
                <th>Model No.:</th>
                  <td align="left"><?php echo $row_group["model"]; ?></td>
                  <td></td>
                  <th>Plant No:</th>
                 <td><?php echo $row_group["sortno"]; ?></td>
                 <td></td>
                <th></th>
                <th></th>
                </tr>

                <tr>
                <th></th>
                <th></th>
                <th>Model Type:</th>
                  <td align="left"><?php echo $row_group["modelType"]; ?></td>
                  <td></td>
                  <th>Chasis No.:</th>
                <td><?php echo $row_group["chasis"]; ?></td>
                <th></th>
                <th></th>
                <th>Eng. Type:</th>
                <td align="left"><?php echo $row_group["engineType"]; ?></td>
                <td></td>
                <th>Eng Serial No.:</th>
                <td><?php echo $row_group["engSerialNo"]; ?></td>
                <td></td>
                <th></th>
                <th></th>
                <th style="color:red">{<?php echo $row_group["platen"]; ?>}</th>
                <th></th>
                <th></th>
                </tr>
              

                <tr>
                <th></th>
                <th></th>
                <th>Axle configuration:</th>
                  <td align="left"><?php echo $row_group["Aconfiguration"]; ?></td>
                  <td></td>
                <th>Number of axles:</th>
                <td><?php echo $row_group["Nofaxles"]; ?></td>
                <th></th>
                <th></th>
                <th>Operating Voltage:</th>
                <td align="left"><?php echo $row_group["operV"]; ?></td>
                <td></td>
                <th>Operating Amps:</th>
                <td><?php echo $row_group["operA"]; ?></td>
                <th></th>
                <th></th>
                </tr>
                

                <tr>
                <th></th>
                <th></th>
                <th>Number of Tyre:</th>
                  <td align="left"><?php echo $row_group["NofTyre"]; ?></td>
                  <td></td>
                  <th>Size of Tyre:</th>
                  <td><?php echo $row_group["SofTyre"]; ?></td>
                  <th></th>
                  <th></th>
                <th>Gross weight:</th>
                  <td align="left"><?php echo $row_group["Gweight"]; ?></td>
                  <td></td>
                  <th>Site:</th>
                  <td><?php $siteID=$row_group['site'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']; ?></td>
                  <td></td>
                <th></th>
                <th></th>
              
               
                <tr>
                <th></th>
                <th></th>
                <th>Tank Capacity:</th>
                  <td align="left"><?php echo $row_group["fuel_tank"]; ?></td>
                  <td></td>
                <th>Radiator:</th>
                <td><?php echo $row_group["radi"]; ?></td>
                <th></th>
                <th></th>
                <th>Crankcase:</th>
                  <td align="left"><?php echo $row_group["crank"]; ?></td>
                  <td></td>
                <th>Hydraulic System:</th>
                <td><?php echo $row_group["hyd_sys"]; ?>
                <td></td>
                <th></th>
                <th></th>
                </tr>
                
              
                <tr>
                <th></th>
                <th></th>
                <th>Driver's Name:</th>
                  <td align="left"><?php echo $row_group["driver"]; ?></td>
                  <td></td>
                <th>Location:</th>
                <td><?php echo $row_site['site_loc']; ?></td>
                <th></th>
                <th></th>
                <th>Mobile Device:</th>
                  <td align="left"><?php echo $row_group["mobile_device"]; ?></td>
                  <td></td>
                <th>IMEI Device::</th>
                <td><?php echo $row_group["imei_device"]; ?></td>
                <td></td>
                <th></th>
                <th></th>
                </tr>
                
                <tr>
                <th></th>
                <th></th>
                <th>Braking System:</th>
                  <td align="left"><?php echo $row_group["brake"]; ?></td>
                  <td></td>
                <th>Speed(km/h):</th>
                <td><?php echo $row_group['speed']; ?></td>
                <th></th>
                <th></th>
                <th>Operating Capacity:</th>
                  <td align="left"><?php echo $row_group["opcap"]; ?></td>
                  <td></td>
                <th>Payload Capacity::</th>
                <td><?php echo $row_group["load_cap"]; ?></td>
                <td></td>
                <th></th>
                <th></th>
                </tr>

              </tbody>
                 
              </table>
              <hr>
              <center><div><h2><span style="color:green;text-decoration: overline;">Activities</span></h2></div></center>
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">
                <thead>
                <tr>
                <th>S/N</th>
                  <th>Plant No:</th>
                  <th>LPO:</th>
                  <th>Title:</th>
                  <th>Description.</th>
                  <th>Loads:</th>
                  <th>Unit(&#13221;):</th>
                  <th>Time & Date:</th>
                  <th>Autorize By:</th>
                  
                  
                  </tr>
                </thead>
                <tbody>

                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; 


                    $ref=$row_assets['requi']; 

                    $assetL = "SELECT * FROM `history` where requi='$ref'";
                     $as_assetL=mysqli_query($config,$assetL) or die(mysqli_error($config));
                    $row_assetsL=mysqli_fetch_assoc($as_assetL);
                    $invoiceL=$row_assetsL['invoice'];
                    
                  
                  ?>

                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" <?php if($row_assets['title']=="Repair"){?>onClick="window.location='repairdtls.php?v=<?php $id=$row_assets['itemSerial'];echo $id;?>'" <?php }?><?php if($row_assets['title']=="Aggregate"||$row_assets['title']=="Sand"||$row_assets['title']=="Laterite"||$row_assets['title']=="Boulder"||$row_assets['title']=="MC1"||$row_assets['title']=="Asphalt"||$row_assets['title']=="Blocks"||$row_assets['title']=="S125"||$row_assets['title']=="Reinforcement"){?>onClick="window.location='aggregate_history.php?v=<?php echo $row_assets['PlantNo']; ?>&tlt=<?php echo $row_assets['title'];?>&f=2010-01-04&t=2090-01-01'" <?php }?><?php if($row_assets['title']=="Advance Voucher"){?>onClick="window.location='ad_voucher_history.php?v=<?php echo $row_assets['PlantNo']; ?>&tlt=<?php echo $row_assets['title'];?>&f=2010-01-04&t=2090-01-01'" <?php }?><?php if($row_assets['title']=="DIESEL"){?>onClick="window.location='dieselhistory.php?v=<?php $id=$row_assets['assetID'];echo $id;?>'" <?php }?><?php if($row_assets['title']=="Tyre"){?>onClick="window.location='tyredtls.php?v=<?php $id=$row_assets['itemSerial'];echo $id;?>'" <?php }?><?php if($row_assets['title']=="Battery"){?>onClick="window.location='batterydtls.php?v=<?php $id=$row_assets['itemSerial'];echo $id;?>'" <?php }?>>
                <td><?php echo $j; ?></td>
                  <td><?php echo $row_assets['PlantNo']; ?></td>
                  <td><?php echo $row_assets['requi']; ?></td>
                  <td><?php echo "<font color='blue'>".$row_assets['title']."</font>"; ?></td>
                  <td><?php echo $row_assets['info']; ?></td>
                   <td><?php if($row_assets['loadcarry']==null){echo "N/A";}else{echo $row_assets['loadcarry'];} ?></td>
                    <td><?php if($row_assets['unit']==null){echo "N/A";}else{echo $row_assets['unit'];} ?></td>
                    <td><?php echo $row_assets['time_date']; ?></td>
                    <td><?php $prebyID=$row_assets['pre_by'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                    
                    
                    
                 
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                <th>S/N</th>
                  <th>Plant No:</th>
                  <th>LPO:</th>
                  <th>Title:</th>
                  <th>Description.</th>
                  <th>Loads:</th>
                  <th>Unit(&#13221;):</th>
                  <th>Time & Date:</th>
                  <th>Autorize By:</th>
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
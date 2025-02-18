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
if(isset($_GET['f'])and (!empty($_GET['t']))){
  $f=$_GET['f'];
  $t=$_GET['t'];
  if(isset($_GET['tlt'])){
  $tlt=$_GET['tlt'];
  $tlts=" and ispaid=$tlt";
}
  $mysql=" and (date(uploaded_on) BETWEEN '$f' and '$t')$tlts";

}
else{
$mysql="";
}
 $group = "SELECT * FROM `assets` where id=$id";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
$row_group=mysqli_fetch_assoc($as_group);
$plantNo=$row_group['sortno'];
//mysql_select_db($database_config, $config);

$assets = "SELECT * FROM `invoices` where PlantNo in('$plantNo')$mysql order by id desc";
$as_asset=mysqli_query($config,$assets) or die(mysqli_error($config));

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
<style type="text/css">
  .center {
  text-align: center;
  border: 2px solid green;
  margin-bottom: 20px;
}
.right{

  text-align: right;
   
  margin:0;
}
.modal {
  display: none; /* Hidden by default */
   
   
  padding-top: 10px; /* Location of the box */
  left: 30%;
  top: 25%;
  width: 50%; /* Full width */
   height: 50%;
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
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
  allow_access_all(1,0,0,0,1,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Store</small>
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
            <div class="box-body"><center><div <?php allow_access(1,0,1,1,0,0,$usergroup); ?>><?php if($row_group["EngineType_dies_petr"]!=="ELECTRICAL"){?><a href="diesel.php?v=<?php echo $id;?>"><?php echo $row_group["EngineType_dies_petr"]; ?></a> || <?php }?><a href="engine_oil.php?v=<?php echo $id;?>">ENGINE OIL</a> || <a href="hydraulic_oil.php?v=<?php echo $id;?>">HYDRAULIC OIL</a> ||<a href="repair.php?v=<?php echo $id;?>">REPAIR</a> || <a href="tyre.php?v=<?php echo $id;?>">TYRE</a> || <a href="battery.php?v=<?php echo $id;?>">BATTERY</a> </div></center>
              <table id="example" class="table table-bordered table-hover table-responsive" style=" text-transform: uppercase;">
                
                <tbody>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th align="right">Description:</th>
                  <td align="left"><?php echo $row_group["assetname"]; ?></td>
                  <th>Make:</th>
                 <td><?php echo $row_group["make"]; ?></td>
                 <th></th>
                <th></th>
                
                <th <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a href="updatePlant.php?v=<?php echo $id;?>">Update</a></th>
                <th><div class="dropdown">
  <button class="dropbtn">HISTORY</button>
  <div class="dropdown-content">
  <a href="reqhistory.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">Requisition</a>
  <a href="ad_voucher_history.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">Advance Voucher</a>
  <a href="repairhistory.php?v=<?php echo $id;?>">Repair</a>
  <?php if($row_group["EngineType_dies_petr"]!="ELECTRICAL"){?><a href="dieselhistory.php?v=<?php echo $id;?>"><?php echo $row_group["EngineType_dies_petr"]; ?></a><?php }?>
  <a href="tyrehistory.php?v=<?php echo $id;?>">Tyre</a>
  <a href="batteryhistory.php?v=<?php echo $id;?>">Battery</a>
  <a href="ldhistory.php?v=<?php echo $id;?>&pt=<?php echo $row_group["sortno"];?>">loading-note</a>
  <a href="aggregate_history.php?v=<?php echo $row_group["sortno"];?>">Aggregate</a>
  </div>
</div>
</th>
<th <?php allow_access(1,0,0,0,1,0,$usergroup); ?>><a href="asset_invoices.php?v=<?php echo $id;?>">Invoices</a></th>
<th></th>
                </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Model No.:</th>
                  <td align="left"><?php echo $row_group["model"]; ?></td>
                  <th>Plant No:</th>
                 <td><?php echo $row_group["sortno"]; ?></td>
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
                <th>Model Type:</th>
                  <td align="left"><?php echo $row_group["modelType"]; ?></td>
                  <th>Chasis No.:</th>
                 <td><?php echo $row_group["chasis"]; ?></td>
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
                <th>Eng. Type:</th>
                  <td align="left"><?php echo $row_group["engineType"]; ?></td>
                  <th>Eng Serial No.:</th>
                 <td><?php echo $row_group["engSerialNo"]; ?></td>
                 <th></th>
                <th></th>
                <th style="color:red;">{<?php echo $row_group["platen"]; ?>}</th>
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
                <th>Axle configuration:</th>
                  <td align="left"><?php echo $row_group["Aconfiguration"]; ?></td>
                  <th>Number of axles:</th>
                 <td><?php echo $row_group["Nofaxles"]; ?></td>
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
                <th>Operating Voltage:</th>
                  <td align="left"><?php echo $row_group["operV"]; ?></td>
                  <th>Operating Amps:</th>
                 <td><?php echo $row_group["operA"]; ?></td>
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
                <th>Number of Tyre:</th>
                  <td align="left"><?php echo $row_group["NofTyre"]; ?></td>
                  <th>Size of Tyre:</th>
                 <td><?php echo $row_group["SofTyre"]; ?></td>
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
                <th>Gross weight:</th>
                  <td align="left"><?php echo $row_group["Gweight"]; ?></td>
                  <th>Site:</th>
                 <td><?php $siteID=$row_group['site'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']; ?></td>
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
                <th>Driver's Name:</th>
                  <td align="left"><?php echo $row_group["driver"]; ?></td>
                  <th>Location:</th>
                 <td><?php echo $row_site['site_loc']; ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Mobile Device:</th>
                  <td align="left"><?php echo $row_group["mobile_device"]; ?></td>
                  <th>IMEI Device::</th>
                 <td><?php echo $row_group["imei_device"]; ?></td>
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
                <center><div><h2><span style="color:green;text-decoration: overline;">Invoices</span></h2></div></center>
              <div>

              <p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</p>
                 
                  </div>
                                
               <hr>
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>

                <th>S/N</th>
                <!--<th>File Name:</th>-->
                <th>LPO:</th>                                 
                 <th>Plant No:</th>
                 <th>Title:</th>
                 <th>Status:</th>
                 <th>Uploaded On:</th>
                <th>Uploaded By:</th>
                <th>Preview:</th>       
                  </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_asset=mysqli_fetch_assoc($as_asset)){$j++;$imageURL = 'uploads/'.$row_asset["file_name"];$lpo=$row_asset['lpo'];$ref=$row_asset['reference']; ?>
                  <tr style="cursor:pointer;" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');">
                <td><?php echo "Item ".$j; ?></td>
                <!--<td><?php if($row_asset['file_name']){echo $row_asset['file_name'];}else{echo "N/A";} ?></td>-->
               <td><?php echo $row_asset['lpo']; ?></td>
                  <td><?php if($row_asset['PlantNo']){echo $row_asset['PlantNo'];}else{echo "N/A";} ?></td>
                  <td><?php if($row_asset['infoD']){echo $row_asset['infoD']."--";}echo $row_asset['title']; ?></td>
                  <td align="center" onClick="window.location='invoices_more.php?v=<?php echo $ref; ?>'"><?php switch($row_asset['ispaid']){ case 1: echo "<div class='dropdown'>
  <span class='dropbtn label label-success'>PAID</span></div>";
  break; case 0:
   echo "<div class='dropdown'>
  <span class='dropbtn label label-warning'>UNPAID &#11167;</span>
  <div class='dropdown-content'>
 <a href='oil_invoices.php?v=$ref'>PAY</a>
  </div>
</div>";break;}?></td>               
                  <td><?php echo $row_asset['uploaded_on']; ?></td>                   
                 <td><?php $prebyID=$row_asset['uploadby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                   <td align="center"><i class="fa fa-eye" aria-hidden="true" style="cursor:pointer" onClick="window.open('<?php echo $imageURL; ?>')">
                  <?php }?></i></td>
                </tbody>
                <tfoot>
                 <tr>

                <th>S/N</th>
                <!--<th>File Name:</th>-->
                <th>LPO:</th>                                 
                 <th>Plant No:</th>
                 <th>Title:</th>
                 <th>Status:</th>
                <th>Uploaded On:</th>
                <th>Uploaded By:</th>
               <th>Preview:</th> 
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
    </div><!-- /.content-wrapper -->
    <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center><form><table>
                                  <tr>  
                <th>From</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="f" required class="form-control"></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>
                <th>To</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="t" required class="form-control"></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                <th>Status</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="tlt" class="form-control"><option value="0">Select</option><option value="1">Paid</option><option value="0">Unpaid</option></select></td>                   
                  </tr>
                  
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                <th><input type="hidden" name="v" value="<?php echo $id;?>"></th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td align="center"><button>Filter Record</button></td>
                                   
                  </tr>                
              </table>
              </form>
              </center>
  </div>

</div>
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
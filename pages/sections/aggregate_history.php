<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
if(isset($_GET['v'])and (!empty($_GET['v']))){
  $ids=$_GET['v'];
  }
  else{
  echo'<script>location.href="aggregate_report.php";</script>';
}
 if(isset($_GET['f'])and (!empty($_GET['t']))){
  $f=$_GET['f'];
  $t=$_GET['t'];
  if(isset($_GET['p'])and(!empty($_GET['p']))){
  $plant=$_GET['p'];
  $plants="and PlantNo='$plant'";
}
if(isset($_GET['tlt'])and(!empty($_GET['tlt']))){
  $tlt=$_GET['tlt'];
  $tlts="and title='$tlt'";
}
if(isset($_GET['st'])and(!empty($_GET['st']))){
  $sts=$_GET['st'];
  $st="and site='$sts'";
}
  $mysql="and (date(time_date) BETWEEN '$f' and '$t') $plants $st $tlts";
}
else{
$mysql="";
}
$qryasset="SELECT * FROM assets where sortno='$ids' and status=1";
$assetQry=mysqli_query($config,$qryasset) or die(mysqli_error($config));
$row_admin=mysqli_fetch_assoc($assetQry);
$plantID=$row_admin["id"];
//$asset = "SELECT * FROM `history` WHERE `title` in('Aggregate','Sand','Laterite','Boulder','MC1','Aspalt','Blocks','S125','Reinforcement') and PlantNo='$ids' order by id desc";
$assets = "SELECT * FROM `history` WHERE `title` in('Aggregate','Sand','Laterite','Boulder','MC1','Asphalt','Blocks','S125','Reinforcement') and PlantNo='$ids' $mysql order by id desc";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
//$as_asset=mysqli_query($config,$asset) or die(mysqli_error($config));
//$row_admin=mysqli_fetch_assoc($as_asset);
$checkassets = mysqli_num_rows($as_assets);
//$plantNo=$row_admin["PlantNo"];
//////////////////
$site = "SELECT * FROM `rocad_site` where sitename!='' order by sitename Asc";
$as_site=mysqli_query($config,$site) or die(mysqli_error($config));

$sum = "SELECT sum(CASE WHEN liter IS NULL THEN lprice ELSE lprice * liter end) as allsum FROM `history` WHERE `title` in('Aggregate','Sand','Laterite','Boulder','MC1','Asphalt','Blocks','S125','Reinforcement') $mysql";
$sql_sum=mysqli_query($config,$sum)or die(mysqli_error($config));
$row_sum=mysqli_fetch_assoc($sql_sum);
//echo $row_sum['allsum'];
?>
<style type="text/css">
  .center {
  text-align: center;
  border: 2px solid green;
  margin-bottom: 20px;
}
.left {
  text-align: left;
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
    allow_access_all(1,0,0,1,0,0,$usergroup); ?>
        <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>AGGREGATE</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="aggregate.php">Aggregate</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title" ><a href="aggregate_report.php">AGGREGATE  HISTORY- <?php echo $ids; ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                            
              <div>
              <p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</p>
                <p class="center"><span style="color:#3c8dbc;"><b>Quantity In:</b></span><span style="color:darkred;">#<?php echo number_format($row_sum['allsum']);?><span style="color:#3c8dbc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Quantity Out:</b></span><span style="color:darkred;">#<?php echo number_format(0,2);?> </p>
                  </div>
               
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">
                <thead>
                <tr>

                <th>S/N</th>
                <th>Date:</th>
                <th>Plant No.:</th>
                <th>Supplier:</th>
                <th>WayBill:</th>
                <th>QTY/CUM.</th>
                <th>Category:</th>
                <th>Description:</th>
                <th>Site:</th>
                <th>Prepared By:</th>
                </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>
                  <tr style="cursor:pointer;background-color: #d4edda" onmouseover="$(this).css('background', '#FFF');" onMouseOut="$(this).css('background', '#d4edda');" onClick="window.location='more.php?v=<?php echo $plantID?>'">
                <td><?php echo $j; ?></td>
                  <td><?php echo $row_assets['time_date']; ?></td>
                   <td><?php echo "<font color='blue'>".$row_assets['PlantNo']."</font>"; ?></td>
                  <td><?php echo $row_assets['supl']; ?></td>                   
                 <td><?php echo $row_assets['itemSerial']; ?></td>                 
                 <td><?php echo "<span class='dropbtn label label-success'>".$row_assets['lprice']."-".$row_assets['unit']."</span>"; ?></td>
                 <td><?php echo $row_assets['title']; ?></td>
                 <td><?php echo $row_assets['info']; ?></td>
                 <td><?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['sitename']; ?></td>
                 <td><?php $prebyID=$row_assets['pre_by'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>           
                    
                
                    
                    
                    
                 
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>

                <th>S/N</th>
                <th>Date:</th>
                <th>Plant No.:</th>
                <th>Supplier:</th>
                <th>WayBill:</th>
                <th>QTY/CUM.</th>
                <th>Category:</th>
                <th>Description:</th>
                <th>Site:</th>
                <th>Prepared By:</th>
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
                <th>Site</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="st" class="form-control"><option value="" selected>::Select Site::</option><option value="">N/A</option><?php while($row_sites=mysqli_fetch_assoc($as_site)){?><option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."-".$row_sites['site_lga']."-".$row_sites['site_loc']; ?></option><?php }?></select></td>                   
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                <th><input type="hidden" name="v" value="<?php echo $ids;?>">
                    <input type="hidden" name="tlt" value="<?php echo $tlt;?>"></th>
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
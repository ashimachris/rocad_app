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
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
if(isset($_GET['f'])and (!empty($_GET['t']))){
  $f=$_GET['f'];
  $t=$_GET['t'];
  $mysql=" and (date(time_date) BETWEEN '$f' and '$t')";
}
else{
$mysql="";
}
//mysql_select_db($database_config, $config);
$asset = "SELECT * FROM `storeloadingdetails` where reference=$ids order by id desc";
$assets = "SELECT * FROM `storeloadingdetails` where reference=$ids$mysql order by id desc";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
$as_asset=mysqli_query($config,$asset) or die(mysqli_error($config));
$row_admin=mysqli_fetch_assoc($as_asset);
$checkassets = mysqli_num_rows($as_assets);
//$plantNo=$row_admin["PlantNo"];
//////////////////

$sum = "SELECT sum(totalvalue) as allsum FROM `storeloadingdetails` where reference=$ids$mysql";
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
   include_once "../layout/left-sidebar.php"; ?>
    

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
        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
             <h3 class="box-title" ><a href="print_ld.php?v=<?php echo $ids;?>">PREVIEW</a></h3>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if($row_sum['allsum']){?>
              <div>
              <p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</p>
                <p class="center"><span style="color:#3c8dbc;"><b>Total Amount:</b></span> <span style="color:darkred;">&#8358;<?php echo number_format($row_sum['allsum']);?></p>
                  </div>
                <?php }?>
               <hr>
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                <th>Items</th>
                <th>Description:</th>
                  <th>Plant No.:</th>
                  <th>Part No.:</th>                  
                  <th>Quantity:</th>
                  <th>Unit Price:</th>   
                  <th>Condition.</th>
                  <th>Note</th>
                   
                  </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>
                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" onClick="#">
                <td><?php echo "Item ".$j; ?></td>
                <td><?php echo $row_assets['descrip']; ?></td>
                  <td><?php echo $row_assets['PlantNo']; ?></td>
                  <td><?php echo $row_assets['partno']; ?></td>
                  <td><?php echo $row_assets['qty']; ?></td>
                  <td><?php echo $row_assets['unitprice']; ?></td>
                  <td><?php echo $row_assets['conditions']; ?></td>
                  <td><?php echo $row_assets['note']; ?></td>
                   
                    
                              
                 
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                <th>Items</th>
                <th>Description:</th>
                  <th>Plant No.:</th>
                  <th>Part No.:</th>                  
                  <th>Quantity:</th>
                  <th>Unit Price:</th>   
                  <th>Condition.</th>
                  <th>Note</th>
                   

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
                <td><input type="date" name="f" required></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>
                <th>To</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="t" required></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                <th><input type="hidden" name="v" value="<?php echo $ids;?>"></th>
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
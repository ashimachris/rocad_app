<?php
require_once('../../db_con/config.php');
  if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "blank";
  include_once "../layout/header.php";
  $qry_all_asset="SELECT * FROM assets where status=1";
  $asset_all=mysqli_query($config,$qry_all_asset) or die(mysqli_error($config));
   
  if(isset($_GET['f'],$_GET['t'],$_GET['p'])){
  $f=$_GET['f'];
  $t=$_GET['t'];
  $p=$_GET['p'];
  if($p=='0'){
    $psql="and id not in(0)";
  }
  else{
$psql="and PlantNo='$p'";
  }
  $mysql=" and (date(time_date) BETWEEN '$f' and '$t') $psql";
  //$title=$psql;
$title="From: (".$f.") To: (".$t.")";
}
else{
$mysql=" and (date(time_date)=curdate())";
$title="REAL TIME REPORTS";
}
$qryasset="SELECT * FROM `daily_plant_reports_details` where id is not null$mysql";
$assets=mysqli_query($config,$qryasset) or die(mysqli_error($config));
?>
<style type="text/css">
  .treeview-menu li {
    padding-left: 15px;
  }
  .box-body {
    min-height: 500px;
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


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Payroll
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payroll</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header">
              <h3 class="box-title"><a href="new-plant-report.php">Generate Report</a></h3>
                 <h3 class="box-title" style="float: right;cursor: pointer;"><font color="darkred" id="myBtn">Filter By Date</font></h3>
                <center><h3 class="box-title"><font color="darkgreen"><?php echo $title; ?></font></h3></center>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
           <table id="example1" class="table table-bordered table-hover w-100">
                <thead>
                  
                <tr>
                  <th>STAFF NAME:</th>
                  <th>TRADE:</th>
                  <th>BASIC:</th>
                  <th>ALLOWANCE</th>
                  <th>OVERTIME:</th>
                  <th>DEDUCTION:</th>
                  <th>LOAN:</th>
                  <th>SALARY ADVS:</th>
                  <th>ABST DED:</th>
                  <th>ANNUAL BONUS:</th>
                  <th>LEAVE BONUS:</th>
                  <th>NET VALUE:</th>
                </tr>
                
                </thead>
                <tbody>
                   <?php $j=0;while($row_assets=mysqli_fetch_assoc($assets)){$j++; ?>
                <tr style="text-transform: uppercase;">

                <td> <?php echo $j; ?></td>
                  <td><?php echo $row_assets['PlantNo']; ?> </td>
                  <td><?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>
                   
                  <td><?php echo $row_assets['wstatus']; ?></td>
                  <td><?php echo $row_assets['remark']; ?></td>
                  <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                  <td><?php echo $row_assets['time_date']; ?></td>
                  <td></td>
                </tr>
                  <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                  <th>STAFF NAME:</th>
                  <th>TRADE:</th>
                  <th>BASIC:</th>
                  <th>ALLOWANCE</th>
                  <th>OVERTIME:</th>
                  <th>DEDUCTION:</th>
                  <th>LOAN:</th>
                  <th>SALARY ADVS:</th>
                  <th>ABST DED:</th>
                  <th>ANNUAL BONUS:</th>
                  <th>LEAVE BONUS:</th>
                  <th>NET VALUE:</th>
                </tr>
                </tfoot>
              </table>
          </div>
        </div>
         
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
    </div><!-- /.content-wrapper -->
    <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center><form><table>
                                  <tr>  
                <th>From:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="f" required class="form-control"></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>
                <th>To:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="t" required class="form-control"></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                  <tr>
                <th>Plant No.</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="p" class="form-control" required><option value="" selected>::Select Plant No::</option><option value="0">ALL</option><?php while($row_asset=mysqli_fetch_assoc($asset_all)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr>  <tr>
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
    
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="blank/script.js"></script>
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
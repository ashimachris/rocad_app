<?php
require_once('../../db_con/config.php');
  if(session_status()===PHP_SESSION_NONE){
session_start();
} 
$msg="";
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
$psql="and plant_no='$p'";
  }
  $mysql=" and (date(time_date) BETWEEN '$f' and '$t') $psql";
  //$title=$psql;
$title="From: (".$f.") To: (".$t.")";
}
else{

$mysql=" and (date(time_date)>= date_sub(curdate(), interval 1 month))";
// $mysql=" and (date(uploaded_on)=curdate())";
$title="FOR LAST 1 MONTH";
}

$qryasset="SELECT * FROM `storeloadingdetails` where id is not null AND status=1 $mysql";
$assets=mysqli_query($config,$qryasset) or die(mysqli_error($config));

$qry_record = mysqli_query($config, "SELECT COUNT(*) AS total_record FROM `storeloadingdetails` where id is not null AND status=1 $mysql");
$total_record =  mysqli_fetch_assoc($qry_record)['total_record'];

// $qry_pending = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` where id is not null AND status='1' $mysql");
// $total_pending =  mysqli_fetch_assoc($qry_pending)['total_amount'];

?>


<style type="text/css">
  .treeview-menu li {
    padding-left: 15px;
  }
  .box-body {
    min-height: 500px;
  }
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
  z-index: 10;
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
  background-color: #d4edda;
}

</style>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <link rel="stylesheet" href="js/datatable/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="js/datatable/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="js/datatable/datatables-buttons/css/buttons.bootstrap4.min.css">


  <!-- ================================================ -->
	

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3>
        Denied invoices

      </h3>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Denied invoices</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
      <!-- Default box -->
      <div class="box">
	<center><h3 class="box-title"><?php  echo $msg; ?></h3></center>
        <div class="box-header">
                 <!-- <h3 class="box-title" style="float: right;cursor: pointer;"><font color="darkred" id="myBtn">Filter By Date</font></h3>  -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
            
           <table id="dataTable1" class="table table-bordered table-hover w-100">
                <caption>
                  <h2 style="text-align: center" class="box-title"><font color="darkgreen"> DENIED REPORT <?php echo $title ?></font></h2> 
                     <h3 style="text-align: center" class="box-title"><font color="darkgreen"> TOTAL DENIED : <?php echo number_format($total_record,2); ?></font></h3> 
                      <!-- <h4 style="text-align: center" class="box-title">  <font color="darkgreen"> Total Pending : <?php echo number_format($total_pending,2); ?></h4> -->
                </caption>

                <thead>
                <tr>

                <th>S/N</th>
                <th>From:</th>
                <th>Department:</th>
                <th>Request By:</th>
                <th>Auth Purchase:</th>
                <th>Signed By.</th>
                <th>Time_Date:</th>
                <th>Required For:</th>
                <th>L.P.O No.:</th>
                <th>Status:</th>      

                  </tr>

                </thead>

                <tbody>
                  
                tbody>

                <?php $j=0;while($row_assets=mysqli_fetch_assoc($assets)){$j++; ?>

                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" <?php if($lk){$link="receiving_report.php?v=";}else{$link="more_rr.php?v=";}?>onClick="var sts=<?php echo $row_assets['status']; ?>;if(sts==0){alert('L.P.O No.: '+<?php echo $row_assets['reference']; ?>+'\nStill Pending\nPlease wait for approval!');}else{window.location='<?php $id=$row_assets['reference'];echo $link.$id;?>'}">

                <td>
                  <?php echo $j; ?>
                   <?php 
                   //echo"<br />Type :";
                  if($row_assets['dept'] == 2023){
                    echo "<span class='label label-info'>Ad voucher</span>";
                  }else{ 
                    echo "<span class='label label-info'>Invoice</span>";
                  }?>
                  
                </td>


                <td><?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>

                <td><?php $siteID=$row_assets['dept'];require '../layout/site.php';if($row_dept['dname']){echo $row_dept['dname'];}else{echo "N/A";} ?></td>
                  <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                  <td><?php if(!empty($row_assets['authby'])){$prebyID=$row_assets['authby'];}else{$prebyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                  <td><?php echo $row_assets['sign_by']; ?></td>
                 <td><?php echo $row_assets['time_date']; ?></td>
                 <td><?php echo $row_assets['reqfor']; ?></td>
                   <td><?php echo $row_assets['reference']; ?></td>
                   <td><?php switch($row_assets['status']){case 0:echo "<span class='label label-warning'>Pending</span>";break;case 1:echo "<span class='label label-danger'>Denied</span>";break;case 2:echo "<span class='label label-success'>Approved</span>";break;case 4:echo "<span class='label label-success'>Received</span>";break;case 5:echo "<span class='label label-success'>Received</span>";break;default:echo "<span class='label label-danger'>Unknown</span>";} ?></td>

                    </tr>
             

                <?php }?>

              

                </tbody>

                <tfoot>

                <tr>

                  <th>S/N</th>
                  <th>From:</th>
                  <th>Department:</th>
                  <th>Request By:</th>
                  <th>Auth Purchase:</th>
                  <th>Signed By.</th>
                  <th>Time_Date:</th>
                  <th>Required For:</th>
                  <th>L.P.O No.:</th>
                  <th>Status:</th>      

                </tr>

                </tfoot>
                

              </table>
              <div>
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
<?php include_once "js/datatable_footer.php" ?>


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

<script src="js/table.js"></script>
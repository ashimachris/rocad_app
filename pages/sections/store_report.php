<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

  $active_menu = "data_tables";

  include_once "../layout/header.php";
  require_once('../../db_con/config.php'); 

if(isset($_GET["w"])and (!empty($_GET["w"]))){
  $w=$_GET["w"];
  $wq="";
  $ttl="Store Report";
  $lk=true;
}
  else{
  $ttl="Store Report";
  $lk=false;
  $wq="";
}

$ref="";
if(isset($_GET["v"])and (!empty($_GET["v"]))){
  $reference=$_GET["v"];
  $ref=" and reference=$reference";
}

// filter record
  if(isset($_GET['f'],$_GET['t'])){
  $f=$_GET['f'];
  $t=$_GET['t'];
  $mysql=" and (date(time_date) BETWEEN '$f' and '$t') $psql";
  //$title=$psql;
$title="FROM: (".$f.") To: (".$t.")";
}
else{
  $date_started = '2024-12-18 14:00:00';
// $mysql=" and (date(time_date)>= date_sub(curdate(), interval 1 month))";
$mysql=" and date(time_date)>= '2024-12-18 14:00:00'";
$title="1 MONTH ";
}

// $assets = "SELECT * FROM `general_store` where reference is not null $wq $ref $mysql AND NOT status=1 order by id desc";
$assets = "SELECT * FROM `storeloadingdetails` where (dept='4' or dept='40') $mysql order by id desc";

$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));

//$row_admin=mysql_fetch_assoc($as_admin);

$checkassets = mysqli_num_rows($as_assets);


?>

<?php   

//Deleting record
if (isset($_GET['delete_id'])) {
  $sql_query = "DELETE FROM storeloadingdetails WHERE id=" . $_GET['delete_id'];
  $deleted = mysqli_query($config, $sql_query);
  if($deleted){
    $msg="<font color='green'>Record deleted successfully</font>";
      echo "<script>setTimeout(function(){window.location='requisition_report.php';},4200);</script>";
  }else{
    echo"Failed to delete the record";
  }
}

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



  <!-- DataTables -->

  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">



  <!-- DataTables -->

  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>

  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>



  <!-- ================================================ -->



  <div class="wrapper">



    <?php include_once "../layout/topmenu.php"; ?>

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

        <li class="active" ><a href="#"><?php echo $ttl; ?></a></li>

        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

             <div class="box-header">
                 <h3 <?php allow_access(1,1,0,0,0,0,$usergroup); ?> class="box-title" style="float: right;cursor: pointer;"><font color="darkred" id="myBtn">Filter By Date</font></h3> 
            </div>

            <!-- /.box-header -->
             <h2 style="text-align: center" class="box-title"><font color="darkgreen"> <?php echo $title ?> STORE REPORT</h2> 

            <div class="box-body">
              <div class="table-responsive">

              <table id="example1" class="table table-bordered table-hover table-responsive" style=" text-transform: uppercase;">

                <thead>

                <tr>

                  <th>S/N</th>
                  <th>Description:</th>
                  <th>Store_Location:</th>
                  <th>Plant No: </th>
                  <th>L.P.O No.:</th>
                  <th>Qty_in_Store</th>
                  <th>Time_Date:</th>
                  <th>Store_Keeper:</th>
                  <th>Authorised By.</th>
                  <th>Status:</th>      
                  <th>Action:</th>   

                </tr>

                </thead>

               <tbody>

                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>

                  <tr style="cursor:pointer" >

                <td><?php echo $j; ?></td>
                <td><?php echo $row_assets['reqfor']; ?></td>
                <td><?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>

                <td>
                   <?php if($row_assets['PlantNo']=="0" || $row_assets['PlantNo']==""){
                     echo 'N/A';
                    }else{
                     echo $row_assets['PlantNo'];
                   }
                 
                  ?>
                    
                </td>

                <td onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" <?php if($lk){$link="receiving_report.php?v=";}else{$link="more_rr.php?v=";}?> onClick="var sts=<?php echo $row_assets['status']; ?>;if(sts==0){alert('L.P.O No.: '+<?php echo $row_assets['reference']; ?>+'\nStill Pending\nPlease wait for approval!');}else{window.location='<?php $id=$row_assets['reference'];echo $link.$id;?>'}"><?php echo $row_assets['reference']; ?></td>
                <td><?php echo $row_assets['qty']; ?></td>
                <td><?php echo $row_assets['time_date']; ?></td>
                <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                <td><?php if(!empty($row_assets['authby'])){$prebyID=$row_assets['authby'];}else{$prebyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/preby.php';echo $row_preby['fullname']; ?>
                <td>
                    <?php switch($row_assets['dept']){
                      case 4:echo "<span class='label label-warning'>Store-In</span>";break;
                      case 40:echo "<span class='label label-danger'>Store-Out</span>";break;
                      // case 2:echo "<span class='label label-success'>Approved</span>";break;
                      // case 4:echo "<span class='label label-success'>Received</span>";break;
                      // case 5:echo "<span class='label label-success'>Completed</span>";break;
                      default:echo "<span class='label label-danger'>Store-In</span>";} ?>
                    
                </td>
                <td>
                      <div class="dropdown">
                        <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true" <?php allow_access(1,1,1,1,1,0,$usergroup); ?>></i></button>
                        <div class="dropdown-content">
                           
                        <?php 
                          $id = $row_assets['id'];
                          if($row_assets['status']==0 || $row_assets['status']==1 || $row_assets['status']==2) { 
                            echo "
                             <a href='requisition_report_edit.php?edit_id=$id'>Edit</a>
                             <a href='javascript:delete_id($id)'>Delete</a>
                            ";
                          }else {
                            echo "<h5>Completed</h5>";
                          }
                        ?>
                      </div>
                    </div>
                  </td>

                    </tr>
             

                <?php }?>

                </tbody>

                <tfoot>

                <tr>

<th>S/N</th>
<th>Description:</th>
<th>Store_Location:</th>
<th>Plant No: </th>
<th>L.P.O No.:</th>
<th>Qty_in_Store</th>
<th>Time_Date:</th>
<th>Store_Keeper:</th>
<th>Authorised By.</th>
<th>Status:</th>      
<th>Action:</th>   

</tr>

                </tfoot>

              </table>
            </div>

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
      <center>
        <form>
          <table>
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

<script>
  function delete_id(id) {
    if (confirm(`Are You Sure You Want To Remove This Record?`)) {
      window.location.href = 'requisition_report.php?delete_id=' + id;
    }
  }
  function approve_id(id) {
    if (confirm(`Are You Sure You Want To Approve This Expense?`)) {
      window.location.href = 'requisition_report.php?approve_id=' + id;
    }
  }

</script>

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
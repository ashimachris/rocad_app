<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

$active_menu = "data_tables";

include_once "../layout/header.php";

?>

<?php require_once('../../db_con/config.php');?>

<style>

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

  color: #000;

  float: right;

  font-size: 50px;

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

img.attachementPreview{
    height: 80vh;
    width: 80vh;
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


    <?php include_once "../layout/topmenu.php";allow_access_all(1,1,1,1,1,1,$usergroup);
     if(isset($_GET['asset_id'])and (!empty($_GET['asset_id']))){
  $asset_id=$_GET['asset_id'];
}else{
  echo "Invalid URL";
  die;
}

$notiqry1="SELECT * FROM `asset_activity` WHERE id=$asset_id";

$noti1=mysqli_query($config,$notiqry1);

$noti11=mysqli_query($config,$notiqry1);

$row_notii=mysqli_fetch_array($noti11);

$total1=mysqli_num_rows($noti1);

$ast_id = $row_notii['asset_id'];

$qry_asset="SELECT * FROM `structural_asset` WHERE id=$ast_id";

$asset=mysqli_query($config,$qry_asset);
$aset_det = mysqli_fetch_array($asset);$asset_name=$aset_det['asset_name'];

$preby =$_SESSION['admin_rocad'];

$title="Asset notif";

// $site=mysqli_real_escape_string($config,$row_notii['fromsite']);

// $reference=mysqli_real_escape_string($config,$row_notii['reference']);

//////////////////

if($total1==0){

  header("location:/rocad_admin/pages/dashboard/");

}

 ?>

    <?php include_once "../layout/left-sidebar.php"; ?>


    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">

        <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        ROCAD

        <small>Asset Activity Notification</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active"><a href="#">Notification</a></li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">

              <table width="402" class="table table-bordered table-hover">

              <tr>

              <td width="110"><div align="right">Site:</div></td>

               <td colspan="8"><?php $siteID=$row_notii['site'];require '../layout/site.php'; echo $row_site['sitename'];  ?></td>

              <td width="161" rowspan="6" valign="top" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>
                <font color="blue">Note:</font> <br>
                <textarea placeholder="Ok" required name="note"></textarea>
                <br><br>
                <center>Action<br>

                <span class="label label-danger" style="cursor:pointer" id="denied"> Denied
                </span>  | | 
                <span class="label label-success" <?php allow_access(1,1,0,0,0,0,$usergroup); ?> style="cursor:pointer" id="approve">Approve
                </span>
                
              </center><br><br>
               <input type="hidden" name="activity_id" value="<?= $asset_id ?>">
               <input type="hidden" name="asset_id" value="<?= $ast_id ?>">
               <input type="hidden" name="quantity_left" value="<?= $row_notii['avl_qty'] ?>">

              <div align="center"> 
                <strong> Quantity Requested  <span class="badge bg-yellow"><?php echo $row_notii['move_qty']; ?></span></strong> 
              </div>
            </td>  
              <td width="161" rowspan="6" valign="top" <?php allow_access(0,0,1,1,1,1,$usergroup); ?>>
                <font color="blue">Note:</font> <br>
               
                <br><br>
                <center><br>

              </center><br><br>

               <div align="center"> <strong>Quantity Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> 
              </div>
            </td>

            </tr>

            <tr>
              <td>
                <div align="right">Prepared By:</div>
              </td>

               <td  colspan="8"><?php $prebyID=$row_notii['preby'];require '../layout/preby.php'; echo $row_preby['fullname'];  ?>
                 
               </td>

            </tr>

              <tr>

              <td><div align="right">Asset Name:</div></td>

               <td  colspan="8"><?php echo $aset_det['asset_name']; ?></td>

              </tr>

              <tr>

               

              <td><div align="right">Time &amp; Date:</div></td>

               <td  colspan="8"><?php echo $row_notii['time_date']; ?></td>

              </tr>

              <tr>

              <td><div align="right">Requested by:</div></td>

               <td  colspan="8"><?php echo $row_notii['store_keeper']; ?></td>

              </tr>

               <tr>

              <td><div align="right">Status:</div></td>

               <td  colspan="8"><?php switch($row_notii['status']){case 0:echo "<font color='blue'>Pending</font>";break;case 1:echo "<font color='red'>Denied</font>";break;case 2:echo "<font color='darkgreen'>Approved</font>";break;case 4:echo "<font color='pink'>Received</font>";break;case 5:echo "<font color='pink'>Received</font>";break;default:echo "<font color='red'>Unknown</font>";} ?></td>

              </tr>

            <tr>
              <td  colspan="9"><div align="right"></div></td>
            </tr>

              </table>

            </div>

            <!-- /.box-header -->


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


    <!-- Modal content -->
 <div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <center>
      <table>
        <tr>  <td>&nbsp;&nbsp;&nbsp;</td></tr>
        <tr>
            <?php
            
            ?>
        
       </tr>
       <tr>  <td>&nbsp;&nbsp;&nbsp;</td></tr>
       </table>
    </center>

  </div>
</div>

    

    <?php include_once "../layout/copyright.php"; ?>

    <?php include_once "../layout/right-sidebar.php"; ?>

 <script>
  var activity_id = $('input[name="activity_id"]').val();
  var asset_id = $('input[name="asset_id"]').val();
  var quantity_left = $('input[name="quantity_left"]').val();

  $(document).ready(function(){

  $('#approve').click(function(){
      if (confirm(`Are You Sure You Want To Approve?`)) {
     
        $.post(`ajax/asset_notification.php?action=approve`, { activity_id: activity_id, asset_id:asset_id, quantity_left:quantity_left }).done(function (data) {
            console.log('data', data);
            alert("Record updated successfully");
            setTimeout(function() {
              window.location.replace(`history.php?v=${asset_id}`);
            }, 1500);
          });
    }
  })

$('#denied').click(function(){
      if (confirm(`Are You Sure You Want To Reject?`)) {
     
        $.post(`ajax/asset_notification.php?action=denied`, { activity_id: activity_id, asset_id:asset_id }).done(function (data) {
            console.log('data', data);
            alert("Record updated successfully");
            setTimeout(function() {
              // window.location = 'daily_report.php';
              window.location.replace(`history.php?v=${asset_id}`);
            }, 1500);
          });
    }
  })

  })
</script>



    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

    <div class="control-sidebar-bg"></div>

  </div><!-- ./wrapper -->

  <script>
// Get the modal

var modal = document.getElementById("myModal");


// Get the button that opens the modal

var btn = document.getElementById("showInvoiceBtn");


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



<?php include_once "../layout/footer.php" ?>


<script src="js/table.js"></script>
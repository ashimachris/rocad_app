<?php
// Start session if not already started
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set the active menu for the page
$active_menu = "data_tables";

// Include the header layout
include_once "../layout/header.php";

// Require the database configuration file
require_once('../../db_con/config.php');

// Set the title for the report
$ttl = "Advance Voucher Report";

// Check if filter dates are set (GET request)
if (isset($_GET['f'], $_GET['t'])) {
    // Get the 'from' and 'to' dates from the GET request
    $f = $_GET['f'];
    $t = $_GET['t'];

    // Set the MySQL query condition to filter based on date range
    $mysql = " and (date(time_date) BETWEEN '$f' and '$t')";
    // Set the title for the filtered report
    $title = "FROM: ($f) TO: ($t)";
} else {
    // If no filter is provided, get records from the last month
    $mysql = " and (date(time_date) >= date_sub(curdate(), interval 1 month))";
    // Set the default title for the report (Last month)
    $title = "1 MONTH";
}

// MySQL query to fetch the records based on the department and status
$assets = "SELECT * FROM `storeloadingdetails` WHERE dept = 2023 AND NOT status = 1 $mysql ORDER BY id DESC";

// Execute the query and store the result
$as_assets = mysqli_query($config, $assets) or die(mysqli_error($config));

// Check if there are any assets matching the query
$checkassets = mysqli_num_rows($as_assets);

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

  <!-- Page-level CSS and JavaScript libraries -->
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- DataTables JS -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

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
          <li class="active"><a href="#"><?php echo $ttl; ?></a></li>
          <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <!-- Filter Button -->
              <div class="box-header">
                <h3 class="box-title" style="float: right; cursor: pointer;">
                  <font color="darkred" id="myBtn">Filter By Date</font>
                </h3>
              </div>

              <!-- Title and All Denied Button -->
              <div class="box-header">
                <h3 class="box-title"><a href="#"><?php echo $ttl; ?></a></h3>
                <button onclick="window.location='denied_invoices.php'" 
                        style="width: 100px; height: 30px; font-size: 12px; border-radius: 10px; padding: 10px 10px; 
                               background-color: #3498db; color: #fff; border: none; 
                               float:right; display: flex; justify-content: center; align-items: center;" 
                        class="pending"> All Denied
                </button>
              </div>

              <!-- Report Title -->
              <h2 style="text-align: center" class="box-title"><font color="darkgreen"> <?php echo $title ?> ADVANCE VOUCHER REPORT</h2> 

              <div class="box-body">
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-hover" style="text-transform: uppercase;">
                    <thead>
                      <tr>
                        <th>S/N</th>
                        <th>From:</th>
                        <th>Plant No.:</th>
                        <th>Request By:</th>
                        <!--<th>Auth Purchase:</th>-->
                        <th>Signed By.</th>
                        <th>Time_Date:</th>
                        <th>Required For:</th>
                        <th>L.P.O No.:</th>
                        <th>Status:</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php $j = 0; while ($row_assets = mysqli_fetch_assoc($as_assets)) { $j++; ?>
                        <tr style="cursor:pointer" 
                            onmouseover="$(this).css('background', '#d4edda');" 
                            onMouseOut="$(this).css('background', '#FFF');" 
                            <?php if ($lk) { $link = "receiving_report.php?v="; } else { $link = "more_rr.php?v="; }?>>

                          <td><?php echo $j; ?></td>

                          <!-- Site Name -->
                          <td><?php 
                            $siteID = $row_assets['fromsite']; 
                            require '../layout/site.php'; 
                            echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']; 
                          ?></td>

                          <!-- Plant Number -->
                          <td onClick="window.location='ad_voucher_history.php?v=<?php echo $row_assets['PlantNo']?>'">
                            <?php if ($row_assets['PlantNo']) {
                              echo "<font color='blue'>".$row_assets['PlantNo']."</font>";
                            } else {
                              echo "<font color='blue'>N/A</font>";
                            } ?>
                          </td>

                          <!-- Request By -->
                          <td><?php 
                            $prebyID = $row_assets['preby']; 
                            require '../layout/preby.php';
                            echo $row_preby['fullname']; 
                          ?></td>

                          <!-- Auth Purchase 
                          <td><?php 
                            if (!empty($row_assets['authby'])) {
                              $prebyID = $row_assets['authby'];
                            } else {
                              $prebyID = 0;
                              echo "<span class='label label-danger'>Waiting...</span>";
                            }
                            require '../layout/preby.php';
                            echo $row_preby['fullname']; 
                          ?></td>-->

                          <!-- Signed By -->
                          <td><?php echo $row_assets['sign_by']; ?></td>

                          <!-- Time and Date -->
                          <td><?php echo $row_assets['time_date']; ?></td>

                          <!-- Required For -->
                          <td><?php echo $row_assets['reqfor']; ?></td>

                          <!-- L.P.O No. -->
                          <td onClick="window.location='<?php $id = $row_assets['reference']; echo $link . $id . "&t=d"; ?>'">
                            <?php echo "<font color='blue'>".$row_assets['reference']."</font>"; ?>
                          </td>

                          <!-- Status -->
                          <td>
                            <?php 
                            switch ($row_assets['status']) {
                              case 0: echo "<span class='label label-warning'>Pending</span>"; break;
                              case 1: echo "<span class='label label-danger'>Denied</span>"; break;
                              case 2: echo "<span class='label label-success'>Approved</span>"; break;
                              case 4: echo "<span class='label label-success'>Received</span>"; break;
                              case 5: echo "<span class='label label-success'>Received</span>"; break;
                              default: echo "<span class='label label-danger'>Unknown</span>";
                            }
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>

                    <tfoot>
                      <tr>
                        <th>S/N</th>
                        <th>From:</th>
                        <th>Plant No.:</th>
                        <th>Request By:</th>
                        <!--<th>Auth Purchase:</th>-->
                        <th>Signed By.</th>
                        <th>Time_Date:</th>
                        <th>Required For:</th>
                        <th>L.P.O No.:</th>
                        <th>Status:</th>
                      </tr>
                    </tfoot>

                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div><!-- /.content-wrapper -->
  </div><!-- /.wrapper -->
</body>

<<!-- Modal Structure -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <!-- Close Button -->
    <span class="close">&times;</span>

    <!-- Modal Form -->
    <center>
      <form>
        <table>
          <!-- From Date Input -->
          <tr>
            <th>From:</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="f" required class="form-control"></td>  
          </tr>
          
          <!-- Empty Row for Spacing -->
          <tr>
            <th>&nbsp;&nbsp;&nbsp;</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;</td>  
          </tr>

          <!-- To Date Input -->
          <tr>
            <th>To:</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="t" required class="form-control"></td>  
          </tr>

          <!-- Empty Row for Spacing -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr>

          <!-- Filter Button -->
          <tr>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td align="center">
              <button type="submit">Filter Record</button>
            </td>  
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
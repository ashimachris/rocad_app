<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set active menu for highlighting
$active_menu = "data_tables";

// Include the header layout
include_once "../layout/header.php";

// Database connection
require_once('../../db_con/config.php');

// Check if 'f' (from date) and 't' (to date) are set in GET request
if (isset($_GET['f']) and !empty($_GET['t'])) {
    // Retrieve 'from' and 'to' dates
    $f = $_GET['f'];
    $t = $_GET['t'];
    
    // Check if 'tlt' (ispaid status) is set in GET request
    if (isset($_GET['tlt'])) {
        $tlt = $_GET['tlt'];
        $tlts = " and ispaid=$tlt"; // Add condition for 'ispaid' status if available
    }
    
    // Formulate the SQL condition for date range and 'ispaid' status
    $mysql = " and (date(uploaded_on) BETWEEN '$f' and '$t')$tlts";
} else {
    // No date range provided, set $mysql to an empty string
    $mysql = "";
}

// Define SQL query to retrieve asset records with specific titles and reference > 0
$assets = "SELECT * FROM `invoices` 
           WHERE `title` IN ('Aggregate', 'Sand', 'Laterite', 'Boulder', 'MC1', 'Asphalt', 'Blocks', 'S125', 'Reinforcement') 
           AND reference > 0 $mysql 
           ORDER BY id DESC";

// Execute the query and handle potential errors
$as_asset = mysqli_query($config, $assets) or die(mysqli_error($config));

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
  color: red;
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
  <!-- Include DataTables CSS -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- Include DataTables JS -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php 
    // Include top menu layout and check user access permissions
    include_once "../layout/topmenu.php"; 
    allow_access_all(1, 1, 0, 0, 1, 0, $usergroup); 
    ?>
    
    <!-- Include left sidebar layout -->
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <!-- Content Header -->
        <section class="content-header">
          <h1>
            ROCAD
            <small>Finance</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Administration</a></li>
            <li class="active"><a href="equipments.php">plant</a></li>
            <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
          </ol>
        </section>

        <!-- Main content section -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <!-- /.box-header -->

                <div class="box-body">
                   
                  <!-- Filter Button -->
                  <div>
                    <p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</p>
                  </div>
                  
                  <hr>
                  
                  <!-- Title of the page -->
                  <center><div><h2><span style="color:green;text-decoration: overline;">Delivery Note</span></h2></div></center>
                  
                  <!-- DataTable -->
                  <table id="example1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>S/N</th>
                        <th>File Name:</th>
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
                      <?php 
                      // Loop through fetched assets and display them in the table
                      $j = 0;
                      while ($row_asset = mysqli_fetch_assoc($as_asset)) {
                          $j++;
                          $imageURL = 'uploads/' . $row_asset["file_name"];
                          $lpo = $row_asset['lpo'];
                          $ref = $row_asset['reference'];
                      ?>
                      <tr style="cursor:pointer;" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" onClick="window.location='invoices_more.php?v=<?php echo $ref; ?>'">
                        <td><?php echo "Item " . $j; ?></td>
                        <td><?php echo $row_asset['file_name'] ? $row_asset['file_name'] : "N/A"; ?></td>
                        <td><?php echo $row_asset['lpo']; ?></td>
                        <td><?php echo $row_asset['PlantNo'] ? $row_asset['PlantNo'] : "N/A"; ?></td>
                        <td><?php echo $row_asset['infoD'] ? $row_asset['infoD'] . "--" : ""; ?><?php echo $row_asset['title']; ?></td>
                        <td align="center">
                          <!-- Display payment status -->
                          <?php 
                          switch ($row_asset['ispaid']) {
                              case 1:
                                  echo "<div class='dropdown'>
                                          <span class='dropbtn label label-success'>PAID</span>
                                        </div>";
                                  break;
                              case 0:
                                  echo "<div class='dropdown'>
                                          <span class='dropbtn label label-warning'>UNPAID &#11167;</span>
                                          <div class='dropdown-content'>
                                            <a href='oil_invoices.php?v=$ref'>PAY</a>
                                          </div>
                                        </div>";
                                  break;
                          }
                          ?>
                        </td>
                        <td><?php echo $row_asset['uploaded_on']; ?></td>
                        <td>
                          <?php 
                          // Fetch the uploaded by user details
                          $prebyID = $row_asset['uploadby'];
                          require '../layout/preby.php';
                          echo $row_preby['fullname']; 
                          ?>
                        </td>
                        <td>
                          <!-- Preview link -->
                          <i class="fa fa-eye" aria-hidden="true" style="cursor:pointer" onClick="window.open('<?php echo $imageURL; ?>')">
                          </i>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>S/N</th>
                        <th>File Name:</th>
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
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
    </div><!-- /.content-wrapper -->

    <div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <!-- Close button -->
    <span class="close">&times;</span>
    
    <!-- Center the form within the modal -->
    <center>
      <form>
        <table>
          <!-- From Date Field -->
          <tr>
            <th>From</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="f" required class="form-control"></td>                  
          </tr>

          <!-- Empty Row for Spacing -->
          <tr>
            <th>&nbsp;&nbsp;&nbsp;</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;</td>                   
          </tr> 

          <!-- To Date Field -->
          <tr>
            <th>To</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="t" required class="form-control"></td>                   
          </tr>

          <!-- Empty Row for Spacing -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr>

          <!-- Status Dropdown -->
          <tr>
            <th>Status</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>
              <select name="tlt" class="form-control">
                <option value="0">Select</option>
                <option value="1">Paid</option>
                <option value="0">Unpaid</option>
              </select>
            </td>                   
          </tr>

          <!-- Empty Row for Spacing -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr> 

          <!-- Hidden Input for ID -->
          <tr>
            <th><input type="hidden" name="v" value="<?php echo $ids; ?>"></th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td align="center">
              <!-- Submit Button -->
              <button>Filter Record</button>
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
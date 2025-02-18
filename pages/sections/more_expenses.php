<?php
// Start session if it hasn't already been started
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set the active menu for the layout
$active_menu = "data_tables";
include_once "../layout/header.php";

// Include the database configuration file
require_once('../../db_con/config.php');

// Initialize the query string
$mysql = "";

// Check if the 'f' and 't' parameters are set and not empty
if(isset($_GET['f']) && !empty($_GET['t'])) {
    $f = $_GET['f'];
    $t = $_GET['t'];
    
    // Initialize filter variables
    $plants = "";
    $tlts = "";
    $st = "";

    // Check for optional parameters and build the query accordingly
    if(isset($_GET['p']) && !empty($_GET['p'])) {
        $plant = $_GET['p'];
        $plants = "AND assetID='$plant'";
    }
    
    if(isset($_GET['tlt']) && !empty($_GET['tlt'])) {
        $tlt = $_GET['tlt'];
        $tlts = "AND title='$tlt'";
    }
    
    if(isset($_GET['st']) && !empty($_GET['st'])) {
        $sts = $_GET['st'];
        $st = "AND site='$sts'";
    }

    // Build the WHERE clause for the main query
    $mysql = "WHERE (date(time_date) BETWEEN '$f' AND '$t') $plants $tlts $st";
}

// Query to select assets that are active (status=1)
$qryasset = "SELECT * FROM assets WHERE status=1";
$assetQry = mysqli_query($config, $qryasset) or die(mysqli_error($config));

// Query to get all history records ordered by ID descending
$asset = "SELECT * FROM `history` ORDER BY id DESC";
$assets = "SELECT * FROM `history` $mysql ORDER BY id DESC";

// Execute the asset queries
$as_assets = mysqli_query($config, $assets) or die(mysqli_error($config));
$as_asset = mysqli_query($config, $asset) or die(mysqli_error($config));

// Fetch the first row from the asset query
$row_admin = mysqli_fetch_assoc($as_asset);
$checkassets = mysqli_num_rows($as_assets);
$plantNo = $row_admin["PlantNo"];

// Query to select all sites where the sitename is not empty, ordered alphabetically
$site = "SELECT * FROM `rocad_site` WHERE sitename != '' ORDER BY sitename ASC";
$as_site = mysqli_query($config, $site) or die(mysqli_error($config));

// Query to sum total price based on liter and price, considering null values
$sum = "SELECT SUM(CASE WHEN liter IS NULL THEN lprice ELSE lprice * liter END) AS allsum FROM `history` $mysql";
$sql_sum = mysqli_query($config, $sum) or die(mysqli_error($config));
$row_sum = mysqli_fetch_assoc($sql_sum);

// Query to sum total liters or price, considering null values
$sum2 = "SELECT SUM(CASE WHEN liter IS NULL THEN lprice ELSE liter END) AS allCs FROM `history` $mysql";
$sql_sum2 = mysqli_query($config, $sum2) or die(mysqli_error($config));
$row_sum2 = mysqli_fetch_assoc($sql_sum2);

// Determine if the title is one of the specified types to decide whether to show additional information
if(isset($tlt) && in_array($tlt, ["PETROL", "Engine Oil", "DIESEL", "Hydraulic Oil", "Gear oil"])) {
    $showup = true;
} else {
    $showup = false;
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
</style>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Include DataTables CSS -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- Include DataTables JS -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <div class="wrapper">

    <!-- Top Menu and Left Sidebar -->
    <?php include_once "../layout/topmenu.php"; allow_access_all(1, 0, 0, 0, 1, 0, $usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

      <!-- Content Header -->
      <section class="content-header">
        <h1>
          ROCAD
          <small>Diesel History</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
          <li><a href="#">Administration</a></li>
          <li class="active"><a href="equipments.php">Plant</a></li>
          <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
        </ol>
      </section>

      <!-- Main Content -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">
                  <?php if(empty($_GET['R'])) { ?>
                    <a href="expenses.php">Expenses </a>
                    <?php if($tlt) { ?>
                      <span class='dropbtn label label-success'><?php echo $_GET['tlt']; ?></span>
                    <?php } ?>
                  <?php } else { ?>
                    <a href='#'>STOCK - CARD (Quantity Out) - <span class='dropbtn label label-success'><?php echo $_GET['tlt']; ?></span></a>
                  <?php } ?>
                </h3>
              </div>
              <!-- /.box-header -->

              <div class="box-body">
                <!-- Filter and Total Consumption Display -->
                <div>
                  <p class="right">
                    <span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</span>
                  </p>
                  <p class="center">
                    <?php if($row_sum2['allCs']) { ?>
                      <?php if($showup) { ?>
                        <span style="color:#3c8dbc;"><b>Total Liters Consumed:</b></span> 
                        <span style="color:darkred;"><?php echo number_format($row_sum2['allCs'])."--LTR"; ?></span>
                      <?php } ?>
                    <?php } ?>
                    <b><span style="color:#3c8dbc;">Total:</b></span> 
                    <span style="color:darkred;">&#8358;<?php echo number_format($row_sum['allsum'], 2); ?></span>
                  </p>
                </div>

                <!-- DataTable -->
                 <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover" style="text-transform: uppercase;">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Plant No:</th>
                      <th>Title:</th>
                      <th>Amps:</th>
                      <th>Liters Consumed:</th>
                      <th>Amount Spent:</th>
                      <th>Time & Date:</th>
                      <th>Prepared By:</th>
                      <th>Loads:</th>
                      <th>Unit(&#13221;):</th>
                      <th>Site</th>
                      <th>Description.</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $j=0; while($row_assets = mysqli_fetch_assoc($as_assets)) { $j++; $pid = $row_assets['assetID']; ?>
                      <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" 
                          <?php 
                            // Determine link based on the title
                            switch($row_assets['title']) {
                              case 'DIESEL': $lk = 'dieselhistory.php?v='.$pid; break;
                              case 'Repair': $lk = 'repairhistory.php?v='.$pid; break;
                              case 'Tyre': $lk = 'tyrehistory.php?v='.$pid; break;
                              case 'Battery': $lk = 'batteryhistory.php?v='.$pid; break;
                              default: $lk = "#"; 
                            } 
                          ?>
                          onClick="window.location='<?php echo $lk; ?>'">
                        <td><?php echo $j; ?></td>
                        <td><?php echo $row_assets['PlantNo'] ?: "N/A"; ?></td>
                        <td><?php echo $row_assets['title']; ?></td>
                        <td><?php echo $row_assets['bamps'] ?: "N/A"; ?></td>
                        <td><?php echo $row_assets['liter'] ? $row_assets['liter']."L" : "N/A"; ?></td>
                        <td><?php echo $row_assets['liter'] ? "&#8358;".number_format(($row_assets['lprice']*$row_assets['liter']), 2) : "&#8358;".number_format(($row_assets['lprice']), 2); ?></td>
                        <td><?php echo $row_assets['time_date']; ?></td>
                        <td>
                          <?php 
                          $prebyID = $row_assets['pre_by']; 
                          if($prebyID==""){
                            $prebyID=0;
                           }
                          require '../layout/preby.php'; echo $row_preby['fullname'];
                           ?>    
                        </td>
                        <td><?php echo $row_assets['loadcarry'] ?: "N/A"; ?></td>
                        <td><?php echo $row_assets['unit'] ?: "N/A"; ?></td>
                        <td><?php $siteID = $row_assets['site']; require '../layout/site.php'; echo $row_site['sitename']; ?></td>
                        <td><?php echo $row_assets['info']; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>S/N</th>
                      <th>Plant No:</th>
                      <th>Title:</th>
                      <th>Amps:</th>
                      <th>Liters:</th>
                      <th>Price:</th>
                      <th>Time & Date:</th>
                      <th>Authorize:</th>
                      <th>Loads:</th>
                      <th>Unit(&#13221;):</th>
                      <th>Site</th>
                      <th>Description.</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              </div>
              <!-- /.box-body -->
            </div>
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
                <!-- Date selection for the 'From' date -->
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
                <!-- Date selection for the 'To' date -->
                <tr>
                    <th>To</th>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><input type="date" name="t" required class="form-control"></td>                   
                </tr>
                <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                </tr> 
                <!-- Plant selection dropdown -->
                <tr>
                    <th>Plant No</th>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <select name="p" class="form-control">
                            <option value="" selected>::Select Plant No::</option>
                            <option value="">N/A</option>
                            <?php while($row_asset = mysqli_fetch_assoc($assetQry)) { ?>
                                <option value="<?php echo $row_asset['id']; ?>">
                                    <?php echo $row_asset['sortno']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="tlt" value="<?php echo $tlt; ?>">
                    </td>                   
                </tr>
                <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <!-- Site selection dropdown -->
                <tr>
                    <th>Site</th>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <select name="st" class="form-control">
                            <option value="" selected>::Select Site::</option>
                            <option value="">N/A</option>
                            <?php while($row_sites = mysqli_fetch_assoc($as_site)) { ?>
                                <option value="<?php echo $row_sites['id']; ?>">
                                    <?php echo $row_sites['site_state']."-".$row_sites['site_lga']."-".$row_sites['site_loc']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>                   
                </tr>
                <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                </tr> 
                <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                </tr> 
                <!-- Hidden input and filter button -->
                <tr>
                    <th><input type="hidden" name="v" value="<?php echo $ids; ?>"></th>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td align="center">
                        <button type="submit">Filter Record</button>
                    </td>                                   
                </tr>                
            </table>
        </form>
    </center>
</div>

<!-- Include copyright and right sidebar -->
<?php include_once "../layout/copyright.php"; ?>
<?php include_once "../layout/right-sidebar.php"; ?>

  <!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<?php include_once "../layout/footer.php"; ?>

<!-- Include the JavaScript for table functionality -->
<script src="js/table.js"></script>

<script>
// Get the modal element
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Function to open the modal when the button is clicked
btn.onclick = function() {
  modal.style.display = "block";
}

// Function to close the modal when the <span> (x) is clicked
span.onclick = function() {
  modal.style.display = "none";
}

// Function to close the modal when clicking outside of it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

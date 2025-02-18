<?php
// Include the database configuration
require_once('../../db_con/config.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

// Initialize message variable
$msg = "";

// Set active menu for highlighting
$active_menu = "blank";

// Include the header layout
include_once "../layout/header.php";

// SQL query to retrieve all assets with status 1
$qry_all_asset = "SELECT * FROM assets WHERE status = 1";
$asset_all = mysqli_query($config, $qry_all_asset) or die(mysqli_error($config));

// Check if 'f', 't', and 'p' parameters are set in the GET request
if (isset($_GET['f'], $_GET['t'], $_GET['p'])) {
    $f = $_GET['f'];
    $t = $_GET['t'];
    $p = $_GET['p'];

    // Set filter condition based on 'p' parameter
    if ($p == '0') {
        $psql = "AND id NOT IN (0)";
    } else {
        $psql = "AND plant_no = '$p'";
    }

    // Set the date range filter condition for 'f' and 't' (from and to)
    $mysql = " AND (DATE(time_date) BETWEEN '$f' AND '$t') $psql";

    // Set the title based on the date range
    $title = "From: ($f) To: ($t)";
} else {
    // If no date range is provided, default to the last 1 month
    $date = "FROM_UNIXTIME(CreatedDate) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    $mysql = " AND (DATE(time_date) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";

    // Set the title to reflect the default filter (last 1 month)
    $title = "FOR LAST 1 MONTH";
}

// SQL query to retrieve daily expense reports based on the filter conditions
$qryasset = "SELECT * FROM `daily_expenses_reports` WHERE id IS NOT NULL $mysql ORDER BY id DESC";
$assets = mysqli_query($config, $qryasset) or die(mysqli_error($config));

// Query to sum the total amount of expenses with status '2' (approved)
$qry_amount = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` WHERE id IS NOT NULL AND status = '2' $mysql");
$total_amount = mysqli_fetch_assoc($qry_amount)['total_amount'];

// Query to sum the total amount of pending expenses with status '1' (pending)
$qry_pending = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` WHERE id IS NOT NULL AND status = '1' $mysql");
$total_pending = mysqli_fetch_assoc($qry_pending)['total_amount'];

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

  <!-- Page-level CSS and JavaScript libraries -->
  <link rel="stylesheet" href="js/datatable/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="js/datatable/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="js/datatable/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- ================================================ -->

  <div class="wrapper">

    <!-- Include the top menu -->
    <?php include_once "../layout/topmenu.php"; ?>

    <!-- Include the left sidebar -->
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3>
        General Expense Report
        <!-- Optional buttons for download and email -->
        <!-- 
        <button style="width: 100px; height: 30px; font-size: 12px; border-radius: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none;" class="download">Download</button>
        <button style="width: 70px; height: 30px; font-size: 12px; border-radius: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none;" class="email">Email</button>
        -->
      </h3>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Daily Expense Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    
      <!-- Default box -->
      <div class="box">

        <!-- Display message at the top -->
        <center><h3 class="box-title"><?php echo $msg; ?></h3></center>

        <div class="box-header">
          <!-- Button to trigger date filter modal -->
          <h3 class="box-title" style="float: right; cursor: pointer;">
            <font color="darkred" id="myBtn">Filter By Date</font>
          </h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
          <!-- Table displaying the expense report -->
          <table id="dataTable1" class="table table-bordered table-hover w-100">
            <caption>
              <!-- Table caption displaying the report title and total amounts -->
              <h2 style="text-align: center" class="box-title">
                <font color="darkgreen">EXPENSE REPORT <?php echo $title; ?></font>
              </h2>
              <h3 style="text-align: center" class="box-title">
                <font color="darkgreen">Total Approved: <?php echo number_format($total_amount, 2); ?></font>
              </h3>
              <h4 style="text-align: center" class="box-title">
                <font color="darkgreen">Total Pending: <?php echo number_format($total_pending, 2); ?></font>
              </h4>
            </caption>

            <thead>
              <tr>
                <!-- Table column headers -->
                <th>S/N:</th>
                <th>PLANT NO:</th>
                <th>SITE:</th>
                <th>DESCRIPTION:</th>
                <th>AMOUNT:</th>
                <th>DATE:</th>
                <th>PREPARED BY:</th>
                <th>STATUS:</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              // Loop through each asset and display its details
              $j = 0;
              while ($row_assets = mysqli_fetch_assoc($assets)) {
                  $j++; 
              ?>
                <tr style="text-transform: uppercase;">
                  <td><?php echo $j; ?></td>
                  <td>
                    <!-- Display plant number or 'Nil' if plant number is 0 -->
                    <?php echo ($row_assets['plant_no'] == '0') ? "Nil" : $row_assets['plant_no']; ?>
                  </td>
                  <td>
                    <?php 
                      // Fetch site information using site ID
                      $siteID = $row_assets['fromsite'];
                      require '../layout/site.php';
                      echo $row_site['site_state'] . "-" . $row_site['site_lga'] . "-" . $row_site['site_loc'];
                    ?>
                  </td>
                  <td><?php echo $row_assets['description']; ?></td>
                  <td><?php echo number_format($row_assets['amount'], 2); ?></td>
                  <td><?php echo $row_assets['time_date']; ?></td>
                  <td>
                    <?php 
                    // Fetch the name of the person who prepared the expense
                    $prebyID = $row_assets['preby'];
                    require '../layout/preby.php';
                    echo $row_preby['fullname']; 
                    ?>
                  </td>
                  <td>
                    <?php
                    // Display status based on the value of 'status'
                    switch ($row_assets['status']) {
                      case 1:
                        echo '<span class="rounded-pill badge badge-dark bg-gradient-dark px-3 text-light">Not Approved</span>';
                        break;
                      case 2:
                        echo '<span class="rounded-pill badge text-primary bg-gradient-primary px-3" style="background: blue">Approved</span>';
                        break;
                    }
                    ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <!-- Table footer with column headers -->
              <tr>
                <th>S/N:</th>
                <th>PLANT NO:</th>
                <th>SITE:</th>
                <th>DESCRIPTION:</th>
                <th>AMOUNT:</th>
                <th>DATE:</th>
                <th>PREPARED BY:</th>
                <th>STATUS</th>
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
    <center>
      <!-- Filter form -->
      <form method="GET" action="general_expense_report.php">
        <table>
          <tr>
            <th>From:</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="f" required class="form-control"></td>
          </tr>

          <!-- Spacer Row -->
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

          <!-- Spacer Row -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr>

          <tr>
            <th>Plant No.</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>
              <!-- Plant No. dropdown -->
              <select name="p" class="form-control" required>
                <option value="" selected>::Select Plant No::</option>
                <option value="0">ALL</option>
                <!-- Loop through assets to create plant options -->
                <?php while($row_asset = mysqli_fetch_assoc($asset_all)) { ?>
                  <option value="<?php echo $row_asset['sortno']; ?>">
                    <?php echo $row_asset['sortno']; ?>
                  </option>
                <?php } ?>
              </select>
            </td>
          </tr>

          <!-- Spacer Row -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr>

          <tr>
            <!-- Hidden value for 'v' -->
            <th><input type="hidden" name="v" value="<?php echo $ids; ?>"></th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td align="center"><button type="submit">Filter Record</button></td>
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

<!-- <script src="js/datatable/datatables/jquery.dataTables.min.js"></script>
<script src="js/datatable/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="js/datatable/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="js/datatable/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="js/datatable/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="js/datatable/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="js/datatable/jszip/jszip.min.js"></script>
<script src="js/datatable/pdfmake/pdfmake.min.js"></script>
<script src="js/datatable/pdfmake/vfs_fonts.js"></script>
<script src="js/datatable/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="js/datatable/datatables-buttons/js/buttons.print.min.js"></script>
<script src="js/datatable/datatables-buttons/js/buttons.colVis.min.js"></script> -->

<script src="js/table.js"></script>
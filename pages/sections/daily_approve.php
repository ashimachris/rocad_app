<?php
// Include database configuration file
require_once('../../db_con/config.php');

// Start session if not already started
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize message variable
$msg = "";

// Set active menu
$active_menu = "blank";

// Include header layout
include_once "../layout/header.php";

// Query to fetch all assets with an active status
$qry_all_asset = "SELECT * FROM assets WHERE status=1";
$asset_all = mysqli_query($config, $qry_all_asset) or die(mysqli_error($config));

// Check if filter parameters (f, t, p) are set in the URL
if (isset($_GET['f'], $_GET['t'], $_GET['p'])) {
    $f = $_GET['f'];  // Filter start date
    $t = $_GET['t'];  // Filter end date
    $p = $_GET['p'];  // Plant number

    // Define additional SQL condition based on plant number
    if ($p == '0') {
        $psql = "AND id NOT IN (0)";
    } else {
        $psql = "AND plant_no = '$p'";
    }

    // Define SQL condition for date range and plant number
    $mysql = "AND (DATE(time_date) BETWEEN '$f' AND '$t') $psql";

    // Set report title based on date range
    $title = "From: ($f) To: ($t)";
} else {
    // Default to real-time reports if no filters are applied
    $mysql = "AND (DATE(time_date) = CURDATE())";
    $title = "REAL TIME REPORTS";
}

// Query to fetch daily expense reports based on filters/status
$qryasset = "SELECT * FROM `daily_expenses_reports` WHERE id IS NOT NULL AND status='2' $mysql";
$assets = mysqli_query($config, $qryasset) or die(mysqli_error($config));

// Query to calculate the total amount based on the applied filters
$qry_amount = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` WHERE id IS NOT NULL AND status='2' $mysql");
$total_amount = mysqli_fetch_assoc($qry_amount)['total_amount'];
?>
<?php

//Approving expenses
if (isset($_GET['approve_id'])) {
	$sql_query = "UPDATE daily_expenses_reports SET status=2 WHERE id=" . $_GET['approve_id'];
	$approved = mysqli_query($config, $sql_query);
	if($approved){
		$msg="<font color='green'>Expense approved successfully</font>";
   		echo "<script>setTimeout(function(){window.location='daily_approve.php';},4200);</script>";
	}else{
		$msg="<font color='red'>Failed to approve the expense</font>";
		echo"Failed to approve the expenses";
	}
}

//Deleting record
if (isset($_GET['delete_id'])) {
	$sql_query = "DELETE FROM daily_expenses_reports WHERE id=" . $_GET['delete_id'];
	$deleted = mysqli_query($config, $sql_query);
	if($deleted){
		$msg="<font color='green'>Record deleted successfully</font>";
   		echo "<script>setTimeout(function(){window.location='daily_approve.php';},4200);</script>";
	}else{
		echo"Failed to delete the record";
	}
}

//All - Approving expenses
if (isset($_GET['approve_all'])) {
	$ids = isset($_POST['ids']) ? $_POST['ids'] : [];

    if(count($ids) > 0){
	
        $sql_query = "UPDATE `daily_expenses_reports` SET status=2 where `id` IN (".(implode(",", $ids)).")";
        $updated = mysqli_query($config, $sql_query);
        if($updated){
            $resp['status'] = 'success';
	    
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = mysqli_error($config);
        }
    }else{
        $resp['status'] = 'failed';
        $resp['error'] = 'No Expense ID(s) Data sent to delete.';
    }
	
echo json_encode($resp);
exit;
}

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
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <div class="wrapper">
    <!-- Include Top Menu and Sidebar -->
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper containing page content -->
    <div class="content-wrapper">
      
      <!-- Page Header -->
      <section class="content-header">
        <h3>Approved Expense Report</h3>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Daily Expense Reports</li>
        </ol>
      </section>

      <!-- Main content section -->
      <section class="content">

        <!-- Expense Report Box -->
        <div class="box">
          <center><h3 class="box-title"><?php echo $msg; ?></h3></center>

          <!-- Header with links for creating a new report and filtering by date -->
          <div class="box-header">
            <h3 <?php allow_access(0,0,0,0,1,0,$usergroup); ?> class="box-title">
              <a href="daily_expense.php">Create New Report</a>
            </h3>
            <h3 class="box-title" style="float: right; cursor: pointer;">
              <font color="darkred" id="myBtn">Filter By Date</font>
            </h3>
          </div>

          <!-- Display Total Approved Amount -->
          <div class="box-body">
            <h2 class="box-title" style="text-align: center; color: darkgreen;">
              Total Approved : <?php echo number_format($total_amount, 2); ?>
            </h2>

            <!-- Approve Selected Expenses Button (hidden by default) -->
            <div style="float: right; display: none;" id="approve_div">
              <button <?php allow_access(1,1,0,0,0,0,$usergroup); ?> 
                class="btn btn-primary btn-sm btn-flat d-none" id="approve_btn">
                Approve Selected Expenses(s)
              </button>
            </div>

            <!-- Spacer for layout -->
            <br><br>

            <!-- Table displaying expense reports -->
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-hover w-100">
                
                <!-- Table Header -->
                <thead>
                  <tr>
                    <th></th>
                    <th>S/N:</th>
                    <th>PLANT NO:</th>
                    <th>SITE:</th>
                    <th>DESCRIPTION:</th>
                    <th>AMOUNT:</th>
                    <th>DATE:</th>
                    <th>PREPARED BY:</th>
                    <th>INVOICE:</th>
                    <th>STATUS:</th>
                    <th>ACTION</th>
                  </tr>
                </thead>

                <!-- Table Body (Populated with PHP) -->
                <tbody>
                  <?php $j=0; while($row_assets=mysqli_fetch_assoc($assets)) { $j++; ?>
                    <tr style="text-transform: uppercase; color: darkred;">
                      
                      <!-- Checkbox for selecting expenses -->
                      <td class="text-center">
                        <input class="form-check-input" type="checkbox" name="expense_id[]" value="<?= $row_assets['id'] ?>" 
                               <?php if($row_assets['status'] != 1) { ?> checked disabled <?php } ?>>
                      </td>
                      
                      <!-- Serial Number -->
                      <td><?php echo $j; ?></td>
                      
                      <!-- Plant Number, displays "N/A" if value is '0' -->
                      <td><?php echo $row_assets['plant_no'] == '0' ? 'N/A' : $row_assets['plant_no']; ?></td>
                      
                      <!-- Site information (site state, LGA, location) -->
                      <td><?php $siteID = $row_assets['fromsite']; require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']; ?></td>
                      
                      <!-- Description of expense -->
                      <td><?php echo $row_assets['description']; ?></td>
                      
                      <!-- Amount with formatted currency -->
                      <td><?php echo number_format($row_assets['amount'], 2); ?></td>
                      
                      <!-- Date of expense -->
                      <td><?php echo $row_assets['time_date']; ?></td>
                      
                      <!-- Name of person who prepared the expense report -->
                      <td><?php $prebyID = $row_assets['preby']; require '../layout/preby.php'; echo $row_preby['fullname']; ?></td>
                      
                      <!-- Downloadable invoice link -->
                      <td>
                        <center>
                          <?php 
                            $invoice_link = "uploads/" . $row_assets['reference'] . ".jpg";
                            $jpeg_link = "uploads/" . $row_assets['reference'] . ".jpeg";
                            if (file_exists($jpeg_link)) { $invoice_link = $jpeg_link; }
                            if (!empty($row_assets['invoice'])) { $invoice_link = $row_assets['invoice']; }
                          ?>
                          <a class="text-primary" href="<?php echo $invoice_link; ?>" target="_blank">
                            <i class="fa fa-download" aria-hidden="true"></i>
                          </a>
                        </center>
                      </td>
                      
                      <!-- Status of the report (Approved or Not Approved) -->
                      <td>
                        <?php
                          switch ($row_assets['status']) {
                            case 1:
                              echo '<span class="badge badge-dark bg-gradient-dark text-light px-3">Not Approve</span>';
                              break;
                            case 2:
                              echo '<span class="badge text-primary bg-gradient-primary px-3">Approved</span>';
                              break;
                          }
                        ?>
                      </td>
                      
                      <!-- Action dropdown menu with options to approve, edit, or delete report -->
                      <td>
                        <div class="dropdown">
                          <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>></i></button>
                          <div class="dropdown-content">
                            <?php if($row_assets['status'] != 2) { ?>
                              <a <?php allow_access(1,0,0,0,0,0,$usergroup); ?> href="javascript:approve_id('<?php echo $row_assets['id']; ?>')">Approve</a>
                            <?php } ?>
                            <?php 
                              if ($row_assets['status'] != 2) {
                                echo "
                                  <a href='daily_expense_edit.php?edit_id={$row_assets['id']}'>Edit</a>
                                  <a href='javascript:delete_id({$row_assets['id']})'>Delete</a>
                                ";
                              } else {
                                echo "<h5>Approved</h5>";
                              }
                            ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
                
                <!-- Table Footer (same as header) -->
                <tfoot>
                  <tr>
                    <th></th>
                    <th>S/N:</th>
                    <th>PLANT NO:</th>
                    <th>SITE:</th>
                    <th>DESCRIPTION:</th>
                    <th>AMOUNT:</th>
                    <th>DATE:</th>
                    <th>PREPARED BY:</th>
                    <th>INVOICE:</th>
                    <th>STATUS:</th>
                    <th>ACTION</th>
                  </tr>
                </tfoot>

              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</body>

    <!-- Modal for Filtering Records -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center>
      <form>
        <table>
          <!-- From Date Field -->
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

          <!-- To Date Field -->
          <tr>
            <th>To:</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="t" required class="form-control"></td>
          </tr>

          <!-- Empty Row for Spacing -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr>

          <!-- Plant Number Dropdown -->
          <tr>
            <th>Plant No.</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>
              <select name="p" class="form-control" required>
                <option value="" selected>::Select Plant No::</option>
                <option value="0">ALL</option>
                <?php while($row_asset=mysqli_fetch_assoc($asset_all)) { ?>
                  <option value="<?php echo $row_asset['sortno']; ?>">
                    <?php echo $row_asset['sortno']; ?>
                  </option>
                <?php } ?>
              </select>
            </td>
          </tr>

          <!-- Empty Row for Spacing -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
          </tr>

          <!-- Filter Button -->
          <tr>
            <th><input type="hidden" name="v" value="<?php echo $ids; ?>"></th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td align="center"><button>Filter Record</button></td>
          </tr>
        </table>
      </form>
    </center>
  </div>
</div>

<!-- External Includes for Layout -->
<?php include_once "../layout/copyright.php"; ?>
<div class="control-sidebar-bg"></div>
<?php include_once "../layout/footer.php"; ?>

<!-- JavaScript for Delete and Approve Functions -->
<script>
  // Delete Record Confirmation
  function delete_id(id) {
    if (confirm(`Are You Sure You Want To Remove This Record?`)) {
      window.location.href = 'daily_approve.php?delete_id=' + id;
    }
  }

  // Approve Expense Confirmation
  function approve_id(id) {
    if (confirm(`Are You Sure You Want To Approve This Expense?`)) {
      window.location.href = 'daily_approve.php?approve_id=' + id;
    }
  }
</script>

<!-- JavaScript for Managing Checkboxes and Approvals -->
<script>
  var checked_ids = []; // Stores selected expense IDs
  var total_checks = $('input[name="expense_id[]"]').length; // Total checkboxes for expenses
  var total_checks_checked = checked_ids.length; // Number of checked boxes

  $(document).ready(function() {
    // Handle Individual Checkbox Change
    $('input[name="expense_id[]"]').change(function() {
      $('#approve_div').show(); // Show approve button if any box is checked

      var id = $(this).val();
      if ($(this).is(':checked') === true) {
        if ($.inArray(id, checked_ids) < 0) checked_ids.push(id);
      } else {
        checked_ids = checked_ids.filter(e => e != id);
      }

      // Update counts and select/deselect "Select All" checkbox accordingly
      total_checks_checked = checked_ids.length;
      $('#selectAll').prop('checked', total_checks_checked === total_checks);

      // Show or hide approve button based on selection
      if (total_checks_checked > 0) {
        $('#approve_div').show();
      } else {
        $('#approve_div').hide();
      }
    });

    // Handle Select All Functionality
    $('#selectAll').change(function(e) {
      e.preventDefault();
      var _this = $(this);
      $('input[name="expense_id[]"]').prop('checked', _this.is(':checked')).trigger('change');
    });

    // Approve Selected Expenses with Confirmation
    $('#approve_btn').click(function() {
      if (confirm(`Are You Sure You Want To Approve The Selected Expenses?`)) {
        $.post("daily_approve.php?approve_all", { ids: checked_ids }).done(function(data) {
          alert("Record updated successfully");
          setTimeout(function() {
            window.location = 'daily_approve.php';
          }, 4200);
        });
      }
    });
  });
</script>

<!-- JavaScript for Modal Display -->
<script>
  // Get modal elements
  var modal = document.getElementById("myModal");
  var btn = document.getElementById("myBtn");
  var span = document.getElementsByClassName("close")[0];

  // Open modal when button is clicked
  btn.onclick = function() {
    modal.style.display = "block";
  }

  // Close modal when "x" is clicked
  span.onclick = function() {
    modal.style.display = "none";
  }

  // Close modal when clicking outside of it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>

<!-- Additional Script Imports -->
<script src="blank/script.js"></script>
<script src="js/table.js"></script>

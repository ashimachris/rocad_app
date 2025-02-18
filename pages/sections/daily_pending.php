<?php
// Include database configuration file
require_once('../../db_con/config.php');

// Start session if it hasn't already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$msg = "";
$today = date('Y-m-d H:i:s');
$active_menu = "blank";

// Include header layout
include_once "../layout/header.php";

// Query to retrieve all assets with status 1 (active)
$qry_all_asset = "SELECT * FROM assets WHERE status = 1";
$asset_all = mysqli_query($config, $qry_all_asset) or die(mysqli_error($config));

// Check if date filter parameters (f, t) and plant parameter (p) are set in GET request
if (isset($_GET['f'], $_GET['t'], $_GET['p'])) {
    $f = $_GET['f']; // Start date
    $t = $_GET['t']; // End date
    $p = $_GET['p']; // Plant number

    // Define SQL condition for plant number
    if ($p == '0') {
        $psql = "AND id NOT IN(0)"; // Exclude records with ID 0
    } else {
        $psql = "AND plant_no = '$p'"; // Filter by specific plant number
    }

    // Date range condition combined with plant filter
    $mysql = " AND (date(time_date) BETWEEN '$f' AND '$t') $psql";
    $title = "From: ($f) To: ($t)";
} else {
    // Default filter to show reports only for the current date
    $mysql = " AND (date(time_date) = CURDATE())";
    $title = "REAL TIME REPORTS";
}

// Query to retrieve daily expense reports based on filters
$qryasset = "SELECT * FROM `daily_expenses_reports` WHERE id IS NOT NULL AND status = '1' $mysql";
$assets = mysqli_query($config, $qryasset) or die(mysqli_error($config));

// Query to calculate the total amount based on the filtered records
$qry_amount = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` WHERE id IS NOT NULL AND status = '1' $mysql");
$total_amount = mysqli_fetch_assoc($qry_amount)['total_amount'];

?>

<?php
$today = date('Y-m-d H:i:s'); // Get the current date and time

// Approving a single expense
if (isset($_GET['approve_id'])) {
    // Update the expense record to set status to 'approved' (status=2)
    $sql_query = "UPDATE daily_expenses_reports SET status=2, time_date='$today' WHERE id=" . $_GET['approve_id'];
    $approved = mysqli_query($config, $sql_query);
    
    // Check if the update was successful
    if ($approved) { 
        $msg = "<font color='green'>Expense approved successfully</font>";
        // Redirect to the report page after 4.2 seconds
        echo "<script>setTimeout(function(){window.location='daily_report.php';}, 4200);</script>";
    } else {
        $msg = "<font color='red'>Failed to approve the expense</font>";
        echo "Failed to approve the expense";
    }
}

// Deleting a record
if (isset($_GET['delete_id'])) {
    // Delete the specified expense record
    $sql_query = "DELETE FROM daily_expenses_reports WHERE id=" . $_GET['delete_id'];
    $deleted = mysqli_query($config, $sql_query);
    
    // Check if the deletion was successful
    if ($deleted) {
        $msg = "<font color='green'>Record deleted successfully</font>";
        // Redirect to the report page after 4.2 seconds
        echo "<script>setTimeout(function(){window.location='daily_report.php';}, 4200);</script>";
    } else {
        echo "Failed to delete the record";
    }
}

// Approving multiple expenses
if (isset($_GET['approve_all'])) {
    $ids = isset($_POST['ids']) ? $_POST['ids'] : []; // Array of expense IDs to approve
    $banks = $_POST['banks']; // Bank details
    $comment = $_POST['comment']; // Approval comment

    if (count($ids) > 0) {
        // Update multiple expense records to set status to 'approved' (status=2)
        $sql_query = "UPDATE daily_expenses_reports SET status=2, bank_mail='$banks', comment_mail='$comment', time_date='$today' WHERE id IN (" . implode(",", $ids) . ")";
        $updated = mysqli_query($config, $sql_query);
        
        // Prepare the response
        if ($updated) { 
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = mysqli_error($config);
        }

        // Send approval email
        $mail = true;
        $sender = $row_preby['fullname'];
        require 'mail_sender.php';
    } else {
        $resp['status'] = 'failed';
        $resp['error'] = 'No Expense ID(s) provided to approve.';
    }
    
    // Output the response in JSON format
    echo json_encode($resp);
    exit;
}

// Moving multiple expenses to daily expenses
if (isset($_GET['move_to_daily_expenses'])) {
    $ids = isset($_POST['ids']) ? $_POST['ids'] : []; // Array of expense IDs to move

    if (count($ids) > 0) {
        // Update multiple expense records with the current date and time
        $sql_query = "UPDATE daily_expenses_reports SET time_date='$today' WHERE id IN (" . implode(",", $ids) . ")";
        $updated = mysqli_query($config, $sql_query);
        
        // Prepare the response
        if ($updated) { 
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = mysqli_error($config);
        }
    } else {
        $resp['status'] = 'failed';
        $resp['error'] = 'No Expense ID(s) provided to move.';
    }
    
    // Output the response in JSON format
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

  <!-- Wrapper to contain page content and layout elements -->
  <div class="wrapper">

    <!-- Include top menu and left sidebar -->
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper to hold main content of the page -->
    <div class="content-wrapper">
      
      <!-- Content Header with page title and breadcrumbs -->
      <section class="content-header">
        <h3>Pending Expense Report</h3>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Daily Expense Reports</li>
        </ol>
      </section>

      <!-- Main content section -->
      <section class="content">
        
        <!-- Default box for the expense report content -->
        <div class="box">
          
          <!-- Centered message for displaying status updates -->
          <center><h3 class="box-title"><?php echo $msg; ?></h3></center>
          
          <!-- Header section of the box with action buttons -->
          <div class="box-header">
            <h3 <?php allow_access(0,0,0,0,1,0,$usergroup); ?> class="box-title">
              <a href="daily_expense.php">Create New Report</a>
            </h3>
            <h3 class="box-title" style="float: right; cursor: pointer;">
              <font color="darkred" id="myBtn">Filter By Date</font>
            </h3>
          </div>

          <!-- Box body with table and controls for expense management -->
          <div class="box-body">
            <h2 style="text-align: center" class="box-title">
              <font color="darkgreen">Total Pending : <?php echo number_format($total_amount, 2); ?></font>
            </h2>

            <!-- Status message area -->
            <center>
              <h2 class="box-title"><div id="status"></div></h2>
            </center>

            <!-- Approve All checkbox for bulk selection -->
            <h4 <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>
              <label>Approve All:</label> 
              <input class="form-check-input" type="checkbox" id="selectAll">
            </h4>

            <!-- Approve buttons section (displayed conditionally) -->
            <div style="float: right; display:none" id="approve_div">
              <button <?php allow_access(1,0,0,0,0,0,$usergroup); ?> class="btn btn-primary btn-sm btn-flat" id="move_btn">
                Move To Daily Expense(s)
              </button> ||
              <button <?php allow_access(1,0,0,0,0,0,$usergroup); ?> class="btn btn-primary btn-sm btn-flat" id="approve_btn">
                Approve Selected Expenses(s)
              </button>
            </div>
            <br /><br />

            <!-- Expense report table -->
            <table id="example1" class="table table-bordered table-hover w-100">
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
              
              <!-- Table body populated dynamically with PHP -->
              <tbody>
                <?php $j = 0; while ($row_assets = mysqli_fetch_assoc($assets)) { $j++; ?>
                <tr style="text-transform: uppercase; <?php if ($row_assets['status'] != 1) { ?>color:darkred <?php } ?>">
                  <td class="text-center">
                    <input class="form-check-input" type="checkbox" name="expense_id[]" value="<?= $row_assets['id'] ?>" id="List1" <?php if ($row_assets['status'] != 1) { ?>checked disabled <?php } ?>>
                  </td>
                  <td><?php echo $j; ?></td>
                  <td><?php echo ($row_assets['plant_no'] == '0') ? "N/A" : $row_assets['plant_no']; ?></td>
                  <td><?php $siteID = $row_assets['fromsite']; require '../layout/site.php'; echo $row_site['site_state'] . "-" . $row_site['site_lga'] . "-" . $row_site['site_loc']; ?></td>
                  <td><?php echo $row_assets['description']; ?></td>
                  <td><?php echo number_format($row_assets['amount'], 2); ?></td>
                  <td><?php echo $row_assets['time_date']; ?></td>
                  <td><?php $prebyID = $row_assets['preby']; require '../layout/preby.php'; echo $row_preby['fullname']; ?></td>

                  <!-- Invoice download link logic -->
                  <td>
                    <center>
                      <?php 
                        $invoice_link = "uploads/" . $row_assets['reference'] . ".jpg";
                        $jpeg_link = "uploads/" . $row_assets['reference'] . ".jpeg";
                        if (file_exists($jpeg_link)) {
                          $invoice_link = $jpeg_link;
                        }
                        if (!empty($row_assets['invoice'])) { 
                          $invoice_link = $row_assets['invoice'];
                        }
                      ?>
                      <a class="text-primary" href="<?php echo $invoice_link; ?>" target="_blank">
                        <i class="fa fa-download" aria-hidden="true"></i>
                      </a>
                    </center>
                  </td>

                  <!-- Status display with different labels -->
                  <td>
                    <?php
                      switch ($row_assets['status']) {
                        case 1:
                          echo '<span class="rounded-pill badge badge-dark bg-gradient-dark px-3 text-light">Not Approve</span>';
                          break;
                        case 2:
                          echo '<span class="rounded-pill badge text-primary bg-gradient-primary px-3" style="background:blue">Approved</span>';
                          break;
                      }
                    ?>
                  </td>

                  <!-- Action dropdown with conditional options -->
                  <td>
                    <div class="dropdown">
                      <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>></i></button>
                      <div class="dropdown-content">
                        <?php if ($row_assets['status'] != 2) { ?>
                          <a <?php allow_access(1,0,0,0,0,0,$usergroup); ?> href="javascript:approve_id('<?php echo $row_assets['id']; ?>')">Approve</a>
                        <?php } ?>
                        <?php 
                          $id = $row_assets['id'];
                          if ($row_assets['status'] != 2) {
                            echo "<a href='daily_expense_edit.php?edit_id=$id'>Edit</a>";
                            echo "<a href='javascript:delete_id($id)'>Delete</a>";
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

              <!-- Table footer with column headings -->
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
      </section>
    </div>
  </div>
</body>

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

<!--///////////////////////////////////////////// -->
<!-- Modal -->

<div id="modalInvoice" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" id="close2">&times;</button>
        <h4 class="modal-title"><center>Daily Expense Reports</center></h4>
      </div>
      <div class="modal-body">
        <center class="row">
          <div class="navbar-form navbar-center" method="post">
            <div class="form-group full-center">
              <!-- Dropdown for selecting banks -->
              <select name="banks" id="banks" required>
                <option>Access Bank Plc</option>
                <option>Fidelity Bank Plc</option>
                <option>First Bank of Nigeria Limited</option>
                <option>First City Monument Bank Limited</option>
                <option>Guaranty Trust Holding Company Plc</option>
                <option>Keystone Bank Limited</option>
                <option>Polaris Bank Limited</option>
                <option>Sterling Bank Plc</option>
                <option>Union/Titan Trust Bank Limited</option>
                <option>United Bank for Africa Plc</option>
                <option>Zenith Bank Plc</option>
              </select>
              <br><br>
              <!-- Textarea for comments -->
              <textarea placeholder="Enter Comment" name="comment" id="comment"></textarea>
              <br><br>
              <!-- Button to approve expenses -->
              <button type="submit" onclick="submitForm2()" class="btn btn-default" id="approve_btn2">Approve</button>
            </div>
          </div>
        </center>
      </div>
    </div>
  </div>
</div>

<!-- Include copyright and footer -->
<?php include_once "../layout/copyright.php"; ?>
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<?php include_once "../layout/footer.php"; ?>

<script>
  // Function to delete a record
  function delete_id(id) {
    if (confirm(`Are You Sure You Want To Remove This Record?`)) {
      window.location.href = 'daily_report.php?delete_id=' + id;
    }
  }

  // Function to approve an expense
  function approve_id(id) {
    if (confirm(`Are You Sure You Want To Approve This Expense?`)) {
      window.location.href = 'daily_report.php?approve_id=' + id;
    }
  }
</script>

<script>
  var checked_ids = [];
  var total_checks = $('input[name="expense_id[]"]').length;

  $(document).ready(function(){
    // Store Checked Expense ID
    $('input[name="expense_id[]"]').change(function(){
      $('#approve_div').show();
      var id = $(this).val();
      if ($(this).is(':checked') === true) {
        if ($.inArray(id, checked_ids) < 0)
          checked_ids.push(id);
      } else {
        checked_ids = checked_ids.filter(e => e != id);
      }
      
      // Update selection state
      if (checked_ids.length == total_checks) {
        $('#selectAll').prop('checked', true);
      } else {
        $('#selectAll').prop('checked', false);
      }
      
      // Show or hide approve div based on selection
      if (checked_ids.length > 0) {
        $('#approve_div').show();
      } else {
        $('#approve_div').hide();
      }
    });

    // Select All Functionality
    $('#selectAll').change(function(e){
      e.preventDefault();
      var _this = $(this);
      if (_this.is(':checked') === true) {
        $('input[name="expense_id[]"]').prop('checked', true).trigger('change');
      } else {
        $('input[name="expense_id[]"]').prop('checked', false).trigger('change');
      }
    });

    // Approve selected expenses
    $('#approve_btn2').click(function(){
      if (confirm(`Are You Sure You Want To Approve The Selected Expenses?`)) {
        var banks = $('#banks').val();
        var comment = $('#comment').val();
        
        // AJAX call to approve expenses
        $.post("daily_report.php?approve_all", {ids: checked_ids, banks: banks, comment: comment}).done(function (data) {
          alert("Record updated successfully");
          setTimeout(function() { window.location = 'daily_report.php'; }, 4200);
        });
      }
    });

    // Move selected expenses to daily expenses
    $('#move_btn').click(function(){
      if (confirm(`Are You Sure You Want To Move The Selected Expenses To Daily Expenses?`)) {
        $.post("daily_pending.php?move_to_daily_expenses", {ids: checked_ids}).done(function (data) {
          alert("Record updated successfully");
          setTimeout(function() { window.location = 'daily_report.php'; }, 4200);
        });
      }
    });
  });
</script>

<script src="blank/script.js"></script>

<script>
  // Modal functionality for the first modal
  var modal = document.getElementById("myModal");
  var btn = document.getElementById("myBtn");
  var span = document.getElementsByClassName("close")[0];

  // Open the modal when button is clicked
  btn.onclick = function() {
    modal.style.display = "block";
  }

  // Close the modal when the close button is clicked
  span.onclick = function() {
    modal.style.display = "none";
  }

  // Close the modal when clicking outside of it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>

<script>
  // Modal functionality for the second modal
  var modal2 = document.getElementById("modalInvoice");
  var btn2 = document.getElementById("approve_btn");
  var span2 = document.getElementById("close2");

  // Open the second modal when the approve button is clicked
  btn2.onclick = function() {
    modal2.style.display = "block";
  }

  // Close the second modal when the close button is clicked
  span2.onclick = function() {
    modal2.style.display = "none";
  }

  // Close the second modal when clicking outside of it
  window.onclick = function(event) {
    if (event.target == modal2) {
      modal2.style.display = "none";
    }
  }
</script>

<script src="js/table.js"></script>

<script type="text/javascript">
  // Handle changes in selected expenses for calculation
  $("input[name='expense_id[]']").change(function(){
    var favorite = [];
    $.each($("input[name='expense_id[]']:checked"), function(){            
      favorite.push($(this).val());
    });
    
    var value1 = favorite.join(",");
    // AJAX call to calculate daily expenses
    $.ajax({
      url: "actions/cal_daily_exp.php",
      method: "post",
      data: {displayAmount: 1, value1: value1},
      beforeSend: function(){
        $('#status').html('Please wait...');
      },
      success: function(data){
        console.log('Data ', data);
        $("#status").html(data);
      }
    });
  }); 
</script>

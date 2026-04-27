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

// SQL query to fetch all bulk transfer
$qry_all_transfer = "SELECT * FROM bulk_transfer ";
$all_transfer = mysqli_query($config, $qry_all_transfer) or die(mysqli_error($config));
$sender="ACCESS BANK PLC";
// $reference = "";

 if(isset($_GET['reference'])){
  $reference = $_GET['reference'];
 }


// Check if 'f', 't', and 'p' parameters are set in the GET request
if (isset($_GET['f'], $_GET['t'])) {
    $f = $_GET['f'];
    $t = $_GET['t'];

    // Set the date range filter condition for 'f' and 't' (from and to)
    $mysql = " AND (DATE(date) BETWEEN '$f' AND '$t') $psql";

    // Set the title based on the date range
    $title = "From: ($f) To: ($t)";
} else {
    // If no date range is provided, default to the last 1 month
    $date = "FROM_UNIXTIME(date) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    //$mysql=" AND (DATE(date)=curdate())"; 
    $mysql="";

    // Set the title to reflect the default filter (last 1 month)
    $title = "TODAY";
}

// SQL query to retrieve bulk transfer reports based on the filter conditions
$qry_transfer = "SELECT * FROM `bulk_transfer` WHERE id IS NOT NULL AND reference='$reference' AND sender='$sender' $mysql ORDER BY id DESC";
$bulk_transfer = mysqli_query($config, $qry_transfer) or die(mysqli_error($config));

// Query to sum the total amount of expenses with status '2' (approved)
$qry_amount = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `bulk_transfer` WHERE id IS NOT NULL AND reference='$reference'  AND sender='$sender' $mysql");
$total_amount = mysqli_fetch_assoc($qry_amount)['total_amount'];

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
      Bulk Transfer Report
      
      </h3>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Daily Bulk Transfer Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    
      <!-- Default box -->
      <div class="box">

        <!-- Display message at the top -->
        <center><h3 class="box-title"><?php echo $msg; ?></h3></center>

      <?php if(!isset($_GET['reference'])){ ?>
        <div class="box-header">
          <!-- Button to trigger date filter modal -->
          <h3 class="box-title" style="float: right; cursor: pointer;">
            <font color="darkred" id="myBtn">Filter By Date</font>
          </h3>
        </div>
      <?php } ?>
        <!-- /.box-header -->

        <div class="box-body">
          <?php if(isset($_GET['reference'])){ ?>
  
          <caption>
            <!-- Table caption displaying the report title and total amounts -->
            <h2 style="text-align: center" class="box-title">
                <font color="darkgreen"><?php echo $sender ?> BULK TRANSFER REPORT <?php echo "$title - $reference" ; ?></font>
            </h2>
            <h3 style="text-align: center" class="box-title">
                <font color="darkgreen">Total AMOUNT: <?php echo number_format($total_amount, 2); ?></font>
            </h3>
            
            </caption>
          <div class="table-responsive">
                <table id="bulkTransfer" class="table table-bordered table-hover w-100">
                    <thead>
                    <tr>
                        <!-- Table column headers -->
                        <th>BENEFICIARY NAME:</th>
                        <th>BANK CODE:</th>
                        <th>ACCOUNT NUMBER:</th>
                        <th>AMOUNT:</th>
                        <th>NARRATION:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    // Loop through each asset and display its details
                    $j = 0;
                    while ($row_transfer = mysqli_fetch_assoc($bulk_transfer)) {
                        $j++; 
                    ?>
                        <tr style="text-transform: uppercase;">
                        <td><?php echo $row_transfer['account_name']; ?></td>
                        <td><?php echo $row_transfer['bank_code']; ?></td>
                        <td><?php echo $row_transfer['account_number']; ?></td>
                        <td><?php echo number_format($row_transfer['amount'], 2); ?></td>
                        <td><?php echo $row_transfer['description']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <!-- Table footer with column headers -->
                    <tr>
                        <th>BENEFICIARY NAME:</th>
                        <th>BANK CODE:</th>
                        <th>ACCOUNT NUMBER:</th>
                        <th>AMOUNT:</th>
                        <th>NARRATION:</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
          <?php }else{

            if (isset($_GET['start_date'], $_GET['end_date'])) {
                  $start_date = $_GET['start_date'];
                  $end_date = $_GET['end_date'];
                  $mysql = " AND (DATE(date) BETWEEN '$start_date' AND '$end_date')";
                  $title = "From: ($start_date) To: ($end_date)";
              } else {
                  //$date = "FROM_UNIXTIME(date) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                  $mysql=" AND (DATE(date)=curdate())"; 
                  $title = "TODAY";
              }
           ?>
            <h1>Bulk Transfer Batches - <?php echo $title; ?></h1>
            <div class="table-responsive00">
            <table id="example1" class="table table-bordered table-hover w-100">
                    
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>REFERNCE</th>
                        <th>DATE</th>
                        <th>ACTION</th>
                    </tr>
                    </thead> 
                    <tbody>
                    <?php       
                    $result = mysqli_query($config, "SELECT DISTINCT reference, date FROM bulk_transfer WHERE reference IS NOT NULL $mysql AND sender='$sender' ");
                    $i = 0;
                    while ($row_transfer = mysqli_fetch_assoc($result)) {
                        $i++; 
                    ?>
                        <tr style="text-transform: uppercase;">
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row_transfer['reference']; ?></td>
                        <td><?php echo $row_transfer['date']; ?></td>
                        <td>
                              <a  href="access_bank.php?reference=<?php echo $row_transfer['reference']; ?>">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                              
                            </a>
                        </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                         <th>SN</th>
                        <th>REFERNCE</th>
                        <th>DATE</th>
                        <th>ACTION</th>
                    </tr>
                    </tfoot>
                </table>
              </div>
           <?php } ?>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->

  </div><!-- /.content-wrapper -->

  <div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <center>
      <!-- Filter form -->
      <form method="GET" action="access_bank.php">
        <table>
          <tr>
            <th>From:</th>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" name="start_date" required class="form-control"></td>
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
            <td><input type="date" name="end_date" required class="form-control"></td>
          </tr>

          <!-- Spacer Row -->
          <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
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

<script src="js/table.js"></script> 
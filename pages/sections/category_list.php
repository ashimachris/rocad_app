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

$qry_categories = "SELECT * FROM `expenses_category` ";
$categories = mysqli_query($config, $qry_categories) or die(mysqli_error($config));

$qry_sub_categories = "SELECT * FROM `expenses_sub_category` ";
$sub_categories = mysqli_query($config, $qry_sub_categories) or die(mysqli_error($config));

?>
<?php

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
        <h3>Approved Expense Categorization</h3>
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

          <button onclick="window.location='/rocad_admin/pages/sections/create_expenses_category.php'" style="width: 120px; height: 30px; font-size: 12px; border-radius: 10px; margin: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none; float:right; display: flex; justify-content: center; align-items: center;"
                class="pending"> Create Category
          </button> 

          <!-- Display Total Approved Amount -->
          <div class="box-body">
            <h2 class="box-title" style="text-align: center; color: darkgreen;">
              Expenses categories
            </h2>

            <!-- Spacer for layout -->
            <br><br>

            <!-- Table displaying expense reports -->
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-hover w-100">
                
                <!-- Table Header -->
                <thead>
                <tr>
                    <th>S/N:</th>
                    <th>MAIN CATEGORY:</th>
                    <th>DESCRIPTION</th>
                    <th>ACTION</th>                    
                  </tr>
                </thead>

                <tbody>
                  <?php $j=0; while($row_categories=mysqli_fetch_assoc($categories)) { $j++; ?>
                    <tr style="text-transform: uppercase; color: darkred;">
                       
                      <!-- Serial Number -->
                      <td><?php echo $j; ?></td>

                      <!-- Date of expense -->
                      <td><?php echo $row_categories['name']; ?></td>
                      
                      <!-- Description of expense -->
                      <td><?php echo $row_categories['description']; ?></td>
                      
                      
                      <td>
                        <div class="dropdown">
                          <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true" <?php allow_access(1,1,0,0,1,0,$usergroup); ?>></i></button>
                          <div class="dropdown-content">
                            <?php 
                                echo "<a href='edit_expenses_category.php?edit_id={$row_categories['id']}'>Edit</a>";
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
                    <th>S/N:</th>
                    <th>MAIN CATEGORY:</th>
                    <th>DESCRIPTION</th>
                    <th>ACTION</th>                    
                  </tr>
                </tfoot>

              </table>
            </div>
<br><br>


<h2 class="box-title" style="text-align: center; color: darkgreen;">Expenses Sub Categories</h2>
<div class="table-responsive">
  <table id="example" class="table table-bordered table-hover w-100">
    
    <thead>
      <tr>
        <th>S/N:</th>
        <th>MAIN CATEGORY:</th>
        <th>SUB CATEGORY:</th>
        <th>DESCRIPTION:</th>
        <th>ACTION</th>                    
      </tr>
    </thead>

    <!-- Table Body (Populated with PHP) -->
    <tbody>
      <?php $j=0; while($row_sub_categories=mysqli_fetch_assoc($sub_categories)) { $j++; 
            $category_id = $row_sub_categories['category_id'];
            $qry_category = "SELECT * FROM `expenses_category` WHERE id=$category_id";

            $category=mysqli_query($config,$qry_category) or die(mysqli_error($config));

            $row_category = mysqli_fetch_assoc($category);
        ?>
        <tr style="text-transform: uppercase; color: darkred;">
           
          <!-- Serial Number -->
          <td><?php echo $j; ?></td>

          <td><?php echo $row_category['name']; ?></td>
          

          <td><?php echo $row_sub_categories['name']; ?></td>
          <td><?php echo $row_sub_categories['description']; ?></td>
          
          <td>
            <div class="dropdown">
              <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true" <?php allow_access(1,1,0,0,1,0,$usergroup); ?>></i></button>
              <div class="dropdown-content">
                <?php 
                    echo "<a href='edit_expenses_category.php?edit_id={$row_sub_categories['id']}'>Edit</a>";
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
        <th>S/N:</th>
        <th>MAIN CATEGORY:</th>
        <th>SUB CATEGORY:</th>
        <th>DESCRIPTION:</th>
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

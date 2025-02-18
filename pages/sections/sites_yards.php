<?php
// Start a session if it hasn't been started already
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define the active menu and include the header layout
$active_menu = "data_tables";
include_once "../layout/header.php";
?>

<?php 
// Include database configuration
require_once('../../db_con/config.php');
?>

<?php
// SQL query to fetch records from `rocad_site` table where location or site name includes 'yard'
// Results are ordered by the 'id' field in descending order
$admin = "SELECT * FROM `rocad_site` WHERE site_loc LIKE '%yard%' OR sitename LIKE '%yard%' ORDER BY id DESC";
$as_admin = mysqli_query($config, $admin) or die(mysqli_error($config));

// Check the number of records returned from the query
$checkadmin = mysqli_num_rows($as_admin);
?>

<!-- CSS Styling for Dropdown Button -->
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
  background-color: #fff;
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Page-level CSS and JavaScript libraries for DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- Wrapper for the page layout -->
  <div class="wrapper">
    <!-- Include top menu and left sidebar layouts -->
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Main Content Wrapper -->
    <div class="content-wrapper">
      
      <!-- Page Header Section -->
      <section class="content-header">
        <h1>ROCAD <small>Sites</small></h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Administration</a></li>
          <li class="active" <?php allow_access(1, 0, 0, 0, 0, 0, $usergroup); ?>><a href="#">Yards</a></li>
        </ol>
      </section>

      <!-- Main Content Section -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title" <?php allow_access(1, 0, 0, 0, 0, 0, $usergroup); ?>><a href="#">Yards</a></h3>
              </div>

              <!-- DataTable for displaying sites -->
              <div class="box-body">
                <table id="example1" class="table table-bordered table-hover" style="text-transform: uppercase;">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Site Name</th>
                      <th>Location</th>
                      <th>State</th>
                      <th>LGA</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    // Initialize a counter variable
                    $j = 0;
                    
                    // Loop through each record and display in table row
                    while($row_admin = mysqli_fetch_assoc($as_admin)) { 
                        $j++; 
                    ?>
                    <tr>
                      <!-- Serial number and site details -->
                      <td><?php echo $j; ?></td>
                      <td><?php echo $row_admin['sitename']; ?></td>
                      <td><?php echo $row_admin['site_loc']; ?></td>
                      <td><?php echo $row_admin['site_state']; ?></td>
                      <td><?php echo $row_admin['site_lga']; ?></td>
                      
                      <!-- Display status based on status value -->
                      <td>
                        <?php 
                          switch($row_admin['status']) {
                            case 0:
                              echo "<span class='dropbtn label label-danger'>Inactive</span>";
                              break;
                            case 1:
                              echo "<span class='dropbtn label label-warning'>Active</span>";
                              break;
                            case 2:
                              echo "<span class='dropbtn label label-danger'>Deny</span>";
                              break;
                            case 3:
                              echo "<span class='dropbtn label label-success'>Completed</span>";
                              break;
                          }
                        ?>
                      </td>

                      <!-- Action dropdown menu for each site -->
                      <td>
                        <div class="dropdown">
                          <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
                          <div class="dropdown-content">
                            <?php if($row_admin['status'] == 2) { ?>
                              <a href="site_ad.php?v=1&r=<?php echo $row_admin['id']; ?>">Activate</a>
                            <?php } ?>
                            <?php if($row_admin['status'] == 1) { ?>
                              <a href="site_ad.php?v=2&r=<?php echo $row_admin['id']; ?>">Deny</a>
                              <a href="site_ad.php?v=3&r=<?php echo $row_admin['id']; ?>">Completed</a>
                            <?php } ?>
                            <a href="site_up.php?v=<?php echo $row_admin['id']; ?>">Update</a>
                            <a href="filter-equipments.php?v=<?php echo $row_admin['id']; ?>">Check Plant</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>S/N</th>
                      <th>Site Name</th>
                      <th>Location</th>
                      <th>State</th>
                      <th>LGA</th>
                      <th>Status</th>
                      <th>Action</th>
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
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- Background for control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

<?php include_once "../layout/footer.php"; ?>
<script src="js/table.js"></script>

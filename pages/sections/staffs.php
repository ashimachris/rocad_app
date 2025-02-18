<?php
// Start session if not already started
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');

// Query to fetch staff data excluding Super Admin
$staff = "SELECT * FROM `staff` WHERE id NOT IN (2) ORDER BY id DESC";
$as_staff = mysqli_query($config, $staff) or die(mysqli_error($config));
$checkstaff = mysqli_num_rows($as_staff);
?>

<style>
/* Styling for dropdown button */
.dropbtn {
  background-color: #fff;
  color: #337ab7;
  padding: 3px;
  font-size: 13.5px;
  border: none;
  cursor: pointer;
}

/* Dropdown container */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown content styling */
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

.dropdown-content a:hover {background-color: #f1f1f1;}

/* Display dropdown content on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #fff;
}
</style>

<script>
// Confirmation dialog for delete action
function myFunction() {
  let text = "Are you sure to delete?";
  return confirm(text);
}
</script>

<body class="hold-transition skin-blue sidebar-mini">

<!-- DataTables CSS and JS includes -->
<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php allow_access_all(1, 1, 0, 0, 0, 0, $usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <!-- Page Header -->
        <section class="content-header">
            <h1>ROCAD <small>Staffs</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Administration</a></li>
                <li class="active" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a href="staff.php">New Staffs</a></li>
            </ol>
        </section>

        <!-- Main content section -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Site Assign</th>
                                        <th>Usergroup</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th <?php allow_access(1, 1, 0, 0, 0, 0, $usergroup); ?>>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $j = 0; while($row_staff = mysqli_fetch_assoc($as_staff)) { $j++; ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo $row_staff['fname']; ?></td>
                                        <td><?php echo $row_staff['email']; ?></td>
                                        <td><?php echo $row_staff['phone']; ?></td>
                                        <td>
                                            <?php 
                                            $siteID = $row_staff['site'];
                                            require '../layout/site.php';
                                            echo $row_site['sitename'];
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $usergroupID = $row_staff['usergroup'];
                                            require '../layout/usergroup.php';
                                            echo $row_group['usergroups']; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $catID = $row_staff['cat'];
                                            require '../layout/cat.php';
                                            echo $row_cats['cat_name']; 
                                            ?>
                                        </td>
                                        <td>
                                            <span class="label label-<?php 
                                            $mail = $row_staff['email'];
                                            require '../layout/status.php'; 
                                            echo $msg_status; ?>">
                                            <?php echo $msgout; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                <div class="dropdown-content">
                                                    <?php if($row_status['status'] != 2) { ?>
                                                        <a href="staff_ad.php?v=1stf&r=<?php echo $row_staff['email']; ?>&lk=staffs.php">Activate</a>
                                                    <?php } ?>
                                                    <?php if($row_status['status'] == 2) { ?>
                                                        <a href="staff_ad.php?v=2stf&r=<?php echo $row_staff['email']; ?>&lk=staffs.php">Deny</a>
                                                    <?php } ?>
                                                    <a href="staff_up.php?v=<?php echo $row_staff['id']; ?>">Update</a>
                                                    <a <?php allow_access(1, 1, 0, 0, 0, 0, $usergroup); ?> href="staff_ad.php?v=3stf&r=<?php echo $row_staff['email']; ?>&lk=staffs.php" onclick="return myFunction()">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Site Assign</th>
                                        <th>Usergroup</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th <?php allow_access(1, 1, 0, 0, 0, 0, $usergroup); ?>>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>
    <div class="control-sidebar-bg"></div>
</div>

<?php include_once "../layout/footer.php"; ?>
<script src="js/table.js"></script>

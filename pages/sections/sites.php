<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php"; 
?>
<?php require_once('../../db_con/config.php');?>

<?php
// Fetch all sites except for those with 'House', 'Office' in site_loc and 'yard' in sitename
$admin = "SELECT * FROM `rocad_site` WHERE site_loc NOT LIKE '%House%' AND site_loc NOT LIKE '%Office%' AND sitename NOT LIKE '%yard%' ORDER BY id DESC";
$as_admin = mysqli_query($config, $admin) or die(mysqli_error($config));
$checkadmin = mysqli_num_rows($as_admin);
?>

<style>
/* Styles for dropdown button */
.dropbtn {
  background-color: #fff;
  color: #337ab7;
  padding: 3px;
  font-size: 13.5px;
  border: none;
  cursor: pointer;
}

/* Dropdown container and content styling */
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
<!-- Page-level CSS and JavaScript libraries here -->
<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Page header -->
        <section class="content-header">
            <h1>ROCAD<small>Sites</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Administration</a></li>
                <li class="active"<?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?>><a href="new-site.php">New Sites</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"<?php allow_access(1, 0, 0, 0, 0, 0, $usergroup); ?>><a href="#">Ongoing Projects</a></h3>
                        </div>

                        <!-- Table content -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">
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
                                    $j = 0;
                                    while($row_admin = mysqli_fetch_assoc($as_admin)){
                                        // Skip rendering for completed sites
                                        if ($row_admin['status'] == 3) {
                                            continue;
                                        }
                                        $j++; 
                                    ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo $row_admin['sitename']; ?></td>
                                        <td><?php echo $row_admin['site_loc']; ?></td>
                                        <td><?php echo $row_admin['site_state']; ?></td>
                                        <td><?php echo $row_admin['site_lga']; ?></td>
                                        <td>
                                            <?php 
                                            // Display status with labels
                                            switch($row_admin['status']){
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
                                        <td <?php allow_access(1,1,0,0,0,1,$usergroup); ?>>
                                            <!-- Dropdown for actions -->
                                            <div class="dropdown">
                                                <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                <div class="dropdown-content">
                                                    <!-- Dropdown action links -->
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
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>
    <div class="control-sidebar-bg"></div>
</div>

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>

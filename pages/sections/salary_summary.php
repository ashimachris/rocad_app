<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
?>
<?php require_once('../../db_con/config.php');?>
<?php
//mysql_select_db($database_config, $config);
$staff = "SELECT * FROM `staff` where id not in(2) order by id desc";//Exclude Super Admin
$as_staff=mysqli_query($config,$staff) or die(mysqli_error($config));
//$row_admin=mysql_fetch_assoc($as_admin);
$checkstaff= mysqli_num_rows($as_staff);
//$preby =$_SESSION['admin_rocad'];
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
<script>
function myFunction() {
  let text = "Are you sure to delete?";
  if (confirm(text) == true) {
    return true;
  } else {
    return false;
  }
  }
</script>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php allow_access_all(1, 1, 0, 0,0,0, $usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Staffs</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box"><!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                <th>S/N</th>
                  <th>Name</th>
                  <th>B/Salary</th>
                  <th>Allowance</th>
                  <th>Bank Name</th>
                   <th>Acct/Number</th>
                  <th>Staff category</th>
                   <th <?php allow_access(1, 1, 0, 0,0,0, $usergroup); ?>>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_staff=mysqli_fetch_assoc($as_staff)){$j++; ?>
                <tr>
                <td><?php echo $j; ?></td>
                  <td><?php echo $row_staff['fname']; ?></td>
                  <td><?php echo $row_staff['email']; ?></td>
                  <td><?php echo $row_staff['phone']; ?></td>
                   <td><?php $siteID=$row_staff['site'];require '../layout/site.php';echo $row_site['sitename'] ?></td>
                  <td> <?php $usergroupID=$row_staff['usergroup'];require '../layout/usergroup.php';echo $row_group['usergroups']; ?></td>
                  <td><?php $catID=$row_staff['cat'];require '../layout/cat.php';echo $row_cats['cat_name'] ?>
                     
                  </td>
                  
                  <td><span class="label label-<?php $mail=$row_staff['email'];require '../layout/status.php'; echo $msg_status; ?>"><?php echo $msgout; ?></span></td>
                <td><div class="dropdown">
  <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
  <div class="dropdown-content">
  <?php if($row_status['status']!=2){?><a href="staff_ad.php?v=<?php echo '1stf';?>&r=<?php echo $row_staff['email'];?>&lk=<?php echo 'staffs.php';?>">Activate</a><?php }?>
  <?php if($row_status['status']==2){?><a href="staff_ad.php?v=<?php echo '2stf';?>&r=<?php echo $row_staff['email'];?>&lk=<?php echo 'staffs.php';?>">Deny</a><?php }?>
  <a href="staff_up.php?v=<?php echo $row_staff['id'];?>">Update</a>
  <a <?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?> href="staff_ad.php?v=<?php echo '3stf';?>&r=<?php echo $row_staff['email'];?>&lk=<?php echo 'staffs.php';?>" onclick="return myFunction()">Delete</a>
   
 
  </div>
</div></td>
              </tr>
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                <th>S/N</th>
                  <th>Name</th>
                  <th>B/Salary</th>
                  <th>Allowance</th>
                  <th>Bank Name</th>
                  <th>Acct/Number</th>
                  <th>Staff category</th>
                   <th <?php allow_access(1, 1, 0, 0,0,0, $usergroup); ?>>Status</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

           
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>
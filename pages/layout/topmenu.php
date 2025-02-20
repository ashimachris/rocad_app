<?php
include "session.php";
//session_start();
switch($usergroup){
	case 4://Admin Mode
	case 3://sub-Admin Mode
  
	// $sts=0;
  $sts = 0; // new approach
	break;
	case 2://Staffs
	$sts=1;
	break;
	case 1://Storekeeper
	$sts=2;
	break;
	default;
	$sts=10;//out of series
}
$preby =$_SESSION['admin_rocad'];
switch($row_admin['usergroup']){
  case 4://Admin Mode
  case 3://sub-Admin Mode
  $notiqry="SELECT status,dept,reference,note,time_date FROM `storeloadingdetails` WHERE
  status in($sts) GROUP BY dept,reference,note,time_date
UNION
SELECT status,dept,reference,note,time_date FROM `plant_release_sheet` WHERE status in($sts)";
$noti=mysqli_query($config,$notiqry) or die(mysqli_error($config));
//$row_noti=mysql_fetch_assoc($noti);
$total=mysqli_num_rows($noti);

//asset activities starts here
$qry_struct_act = "SELECT * FROM `asset_activity` WHERE `status`=0";
$asset_notif=mysqli_query($config,$qry_struct_act) or die(mysqli_error($config));
$total_asset_notif=mysqli_num_rows($asset_notif);
  break;
  default:
$notiqry="SELECT status,dept,reference,note,time_date FROM `storeloadingdetails` WHERE status in($sts) and preby in($preby) GROUP BY dept,reference,note,time_date
UNION
SELECT status,dept,reference,note,time_date FROM `plant_release_sheet` WHERE status in($sts) and pre_by=$preby";
$noti=mysqli_query($config,$notiqry) or die(mysqli_error($config));
//$row_noti=mysql_fetch_assoc($noti);
$total=mysqli_num_rows($noti);

//asset activities starts here
$qry_struct_act = "SELECT * FROM `asset_activity` WHERE `status`=0";
$asset_notif=mysqli_query($config,$qry_struct_act) or die(mysqli_error($config));
$total_asset_notif=mysqli_num_rows($asset_notif);

}

?>
<header class="main-header">
    <!-- Logo -->
    <a href="../dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><strong>RCD</strong></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" style="float:left"><img src="../../images/rocad-logo.png" width="152.5px" height="52.2px" align="left"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less --> 
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu" style="display:none">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        AdminLTE Design Team
                        <small><i class="fa fa-clock-o"></i> 2 hours</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Developers
                        <small><i class="fa fa-clock-o"></i> Today</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Sales Department
                        <small><i class="fa fa-clock-o"></i> Yesterday</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Reviewers
                        <small><i class="fa fa-clock-o"></i> 2 days</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-<?php if($total + $total_asset_notif==0){echo 'success';}elseif($total + $total_asset_notif==2){echo 'info';}else{echo 'danger';} ?>"><?php echo $total + $total_asset_notif; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $total + $total_asset_notif; ?> notifications</li>

              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                <?php while($row_noti=mysqli_fetch_assoc($noti)){?>
                  <li>
                    <?php if($row_noti["dept"]==2023){?>
                    <a href="/rocad_admin/pages/sections/advance-notif.php?v=<?php echo $row_noti['reference']; ?>">
                    <?php }?>
                    <?php if(($row_noti["dept"]>0 and $row_noti["dept"]!=2023)){?>
                    <a href="/rocad_admin/pages/sections/requi-notif.php?v=<?php echo $row_noti['reference']; ?>">
                    <?php }?>
                    
                    <?php if($row_noti["dept"]==null){?>
                      <a href="/rocad_admin/pages/sections/notification.php?id=<?php echo $row_noti['reference']; ?>">
                      <?php }?>
                      <?php if($row_noti["dept"]=="ex"){?>
                        <a href="/rocad_admin/pages/sections/plant-notif.php?v=<?php echo $row_noti['reference']; ?>">
                      <?php }?>
                      <i class="fa fa-users text-aqua"></i><?php if($row_noti["dept"]>0 and $row_noti['dept']!=2023){$tbl="Requisition - ";}elseif($row_noti["dept"]==null){$tbl="Store Loading - ";}elseif($row_noti["dept"]=="ex"){$tbl="Plant Release - ";}elseif($row_noti["dept"]==2023){$tbl="Advance Voucher - ";}
                      $date=$row_noti["time_date"];echo date('Y:m:d', strtotime($date))." ".$tbl." ".$row_noti['note'];?>
                    </a>
                  </li>
                  <?php }?>
                  <!-- structural asset activity notification  -->
                  <?php while($row_asset_notif=mysqli_fetch_assoc($asset_notif)){?>
                  <li>
                    <a href="/rocad_admin/pages/sections/asset_notif.php?asset_id=<?php echo $row_asset_notif['id']; ?>">
                      <i class="fa fa-list text-aqua"></i>
                      <?php $tbl="Asset qty to move ";
                      $date=$row_asset_notif["time_date"];echo date('Y:m:d', strtotime($date))." ".$tbl." ".$row_asset_notif['move_qty'];?>
                    </a>
                  </li>
                  <?php }?>

                  
                </ul>
              </li>
              
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu" style="display:none">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Create a nice theme
                        <small class="pull-right">40%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">40% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Some task I need to do
                        <small class="pull-right">60%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">

                      <h3>
                        Make beautiful transitions
                        <small class="pull-right">80%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="/rocad_admin/user_img/<?php echo $row_admin['images'];?>.jpg" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $row_admin['fullname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="/rocad_admin/user_img/<?php echo $row_admin['images'];?>.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $row_admin['fullname']; ?>
                  <small><?php echo $row_admin['ranks']; ?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/rocad_admin/pages/sections/profile.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="/rocad_admin/logout.php" class="btn btn-default btn-flat">Log Out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li style="display:none">
            <a href="../../#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
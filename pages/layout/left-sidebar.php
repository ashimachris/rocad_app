<?php

  function isActive($menu, $mode="full"){

    global $active_menu;

    if ($mode == "partial")

      echo ($active_menu == $menu? "active": "");

    else

      echo ($active_menu == $menu? "class='active'": "");

  }

?>

<!-- Left side column. contains the logo and sidebar -->

<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->

    <section class="sidebar">

      <!-- Sidebar user panel -->

      <div class="user-panel" style="cursor: pointer;" onclick="window.location='/rocad_admin/pages/sections/profile.php'">

        <div class="pull-left image">

          <img src="/rocad_admin/user_img/<?php echo $row_admin['images'];?>.jpg" class="img-circle" alt="User Image"  title="User Image">

        </div>

        <div class="pull-left info">

          <p><?php echo $row_admin['fullname']; ?></p>

          <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $row_admin['ranks']; ?></a> 

        </div>

      </div>

      

      <!-- sidebar menu: : style can be found in sidebar.less -->

      <!-- Sidebar Menu -->

      <ul class="sidebar-menu">

        <li class="header">MENU</li>

        <!-- Optionally, you can add icons to the links -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <li ><a href="/rocad_admin/pages/dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span></a></li>

        <!-- Admin -->

        <li  class="treeview" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>

            <a href="#"><i class="fa fa-user-md"></i> <span>Administration</span>

              <span class="pull-right-container">

                  <i class="fa fa-angle-left pull-right"></i>

              </span>

            </a>

            <ul class="treeview-menu">

              
             
<li <?php allow_access(1,0,0,0,0,0,$usergroup); ?> <?php isActive("flot") ?>>

                <a href="/rocad_admin/pages/sections/offices_residence.php"><i class="fa fa-users"></i>Offices and Residence</a>

              </li>
               <li <?php isActive("flot") ?>>

                <a href="/rocad_admin/pages/sections/staffs.php"><i class="fa fa-users"></i> Staff </a>

              </li>
              <!--<li <?php isActive("flot") ?>>

                <a href="#"><i class="fa fa-users"></i> Attendance </a>

              </li>-->
              <li <?php allow_access(1,0,0,0,0,0,$usergroup); ?>>

                <a href="/rocad_admin/pages/sections/usergroups.php"><i class="fa fa-user"></i>Usergroups</a>

              </li>

            </ul>

        </li>
<li  <?php allow_access(1,1,0,0,0,1,$usergroup); ?>class="treeview">

            <a href="#"><i class="fa fa-globe"></i> <span>Construction Site</span>

              <span class="pull-right-container">

                  <i class="fa fa-angle-left pull-right"></i>

              </span>

            </a>

            <ul class="treeview-menu">

              <li <?php allow_access(1,1,0,0,0,0,$usergroup); ?> <?php isActive("chartjs") ?>>

                <a href="/rocad_admin/pages/sections/new-site.php"><i class="fa fa-globe"></i>Create Site</a>

              </li>

 <li <?php isActive("chartjs") ?>>

                <a href="/rocad_admin/pages/sections/sites.php"><i class="fa fa-diamond"></i>Ongoing Projects</a>

              </li>

              <li <?php isActive("chartjs") ?>>

                <a href="/rocad_admin/pages/sections/sites_completed.php"><i class="fa fa-road"></i>Completed Projects</a>

              </li>
               <li <?php isActive("chartjs") ?>>

                <a href="/rocad_admin/pages/sections/sites_yards.php"><i class="fa fa-database"></i>Yards</a>

              </li>
              

            </ul>

        </li>
        <li  class="treeview" <?php allow_access(1,1,0,1,0,1,$usergroup); ?>>

            <a href="#"><i class="fa fa-folder"></i> <span>Assets</span>

              <span class="pull-right-container">

                  <i class="fa fa-angle-left pull-right"></i>

              </span>

            </a>

            <ul class="treeview-menu">

             <!-- <li <?php allow_access(1,1,0,0,0,1,$usergroup); ?> <?php isActive("chartjs") ?>> 

                <a href="/rocad_admin/pages/sections/asset.php"><i class="fa fa-cogs"></i>New Plant</a>

              </li> -->

              
              <li <?php isActive("morris") ?>>

                <a href="/rocad_admin/pages/sections/equipments.php"><i class="fa fa-truck"></i>Plants</a>

              </li>

              <li <?php allow_access(1,1,0,1,0,1,$usergroup); ?> <?php isActive("chartjs") ?>>

                <a href="/rocad_admin/pages/sections/tools.php"><i class="fa fa-wrench"></i>Structural Asset</a>

              </li>


              <li <?php allow_access(1,1,0,1,0,1,$usergroup); ?>><a href="/rocad_admin/pages/sections/daily-plant-reports.php"><i class="fa fa-truck"></i> <span>Daily Plant Reports</span></a></li>

            </ul>

        </li>

         <li  class="treeview" <?php isActive("chartjs") ?>>

          <a href="#"><i class="fa fa-bell"></i> <span>Site Activities</span>

            <span class="pull-right-container">

              <i class="fa fa-angle-left pull-right"></i>

            </span>

          </a>

          <ul class="treeview-menu">

            <li ><a href="/rocad_admin/pages/sections/requisition.php"><i class="fa fa-book"></i> <span>Requisition</span></a></li>
            <li <?php isActive("chartjs") ?>><a href="/rocad_admin/pages/sections/advance_voucher.php"><i class="fa fa-print">         
            </i> <span>Advance Voucher</span></a></li>
            <li <?php allow_access(1,0,0,1,0,0,$usergroup); ?> <?php isActive("chartjs") ?>><a href="/rocad_admin/pages/sections/aggregate.php"><i class="fa fa-road"></i> <span>Aggregate Update</span></a></li>
            <li <?php allow_access(1,0,0,1,0,1,$usergroup); ?>><a href="/rocad_admin/pages/sections/plant_release_sheet.php"><i class="fa fa-anchor"></i> <span>Plant Release Sheet</span></a></li>
            <li <?php allow_access(1,0,0,0,0,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/daily_progress.php"><i class="fa fa-bar-chart"></i> <span>Work Progress</span></a></li>
            <li <?php allow_access(1,1,0,1,0,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/loading-note.php"><i class="fa fa-exchange"></i> <span>Store Loading Request</span></a></li>
        </ul>
        </li>

        

        <li  class="treeview" <?php allow_access(1,0,0,1,0,0,$usergroup); ?>>

          <a href="#"><i class="fa fa-balance-scale"></i> <span>Stock</span>

          <span class="pull-right-container">

              <i class="fa fa-angle-left pull-right"></i>

            </span>

          </a>

          <ul class="treeview-menu">
            <li  ><?php allow_access(1,0,0,1,0,0,$usergroup); ?><a href="/rocad_admin/pages/sections/diesel_consumption.php"><i class="fa fa-tint"></i> <span>Stock Card</span></a></li>
            <li  ><?php allow_access(1,0,0,1,0,0,$usergroup); ?><a href="/rocad_admin/pages/sections/store_report.php"><i class="fa fa-shopping-basket"></i> <span>General Store</span></a></li>
            <li  ><a href="/rocad_admin/pages/sections/battery_track.php"><i class="fa fa-battery-full"></i> <span>Battery Track</span></a></li>
            <!--<li <?php allow_access(1,0,0,1,0,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/fixed_stock.php"><i class="fa fa-eyedropper"></i> <span>Add Fixed Stock</span></a></li> -->
            <li  ><a href="/rocad_admin/pages/sections/fixed_stocks.php"><i class="fa fa-eyedropper"></i> <span>Fixed stock</span></a></li>
            <li  ><a href="/rocad_admin/pages/sections/oil_store.php"><i class="fa fa-folder"></i> <span>Consumables stock</span></a></li>
          </ul>
        </li>

        
        <li  class="treeview" <?php allow_access(1,1,1,1,1,0,$usergroup); ?> <?php isActive("chartjs") ?>>

          <a href="#"><i class="fa fa-money"></i> <span>Finance</span>

            <span class="pull-right-container">

              <i class="fa fa-angle-left pull-right"></i>

            </span>

          </a>

          <ul class="treeview-menu">
           <li <?php allow_access(1,1,1,1,1,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/daily_report.php"><i class="fa fa-money"></i> <span>Daily Expenses</span></a></li>
           <li <?php allow_access(1,1,1,1,1,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/expense_category.php"><i class="fa fa-money"></i> <span>Accounting</span></a></li>  
           <li <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/expenses.php"><i class="fa fa-money"></i> <span>Site Expenses</span></a></li>  
           <li <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/invoices.php"><i class="fa fa-dollar"></i> <span>Invoices</span></a></li>
           <li <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/delivery_note.php"><i class="fa fa-dollar"></i> <span>Delivery Note</span></a></li>
           <!--<li <?php allow_access(1,0,0,0,1,0,$usergroup); ?>><a href="#"><i class="fa fa-truck"></i> <span>Payroll</span></a></li>-->
            
          </ul>
        </li>
                
        <!-- Engineer -->
        <li  class="treeview">

<a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>

  <span class="pull-right-container">

    <i class="fa fa-angle-left pull-right"></i>

  </span>

</a>

<ul class="treeview-menu">

  <li <?php allow_access(1,1,1,0,1,0,$usergroup); ?> ><a href="/rocad_admin/pages/sections/general_expense_report.php"><i class="fa fa-money"></i>General Expense Report</a></li> 
  <li <?php allow_access(1,1,1,0,1,0,$usergroup); ?> ><a href="/rocad_admin/pages/sections/profit_loss_report.php"><i class="fa fa-money"></i>Profit & Loss Report</a></li>     
  <li ><a href="/rocad_admin/pages/sections/requisition_report.php"><i class="fa fa-book"></i>Requisition Report</a></li>
  <li ><a href="/rocad_admin/pages/sections/ad_voucher_report.php"><i class="fa fa-star"></i>Advance Voucher Report</a></li>
  <li <?php allow_access(1,1,0,1,0,1,$usergroup); ?> ><a href="/rocad_admin/pages/sections/plant_release.php"><i class="fa fa-phone"></i>Plant Release</a></li>
  <li <?php allow_access(1,1,0,0,1,1,$usergroup); ?>><a href="/rocad_admin/pages/sections/plant-reports.php"><i class="fa fa-road"></i>Plant Report</a></li>
  <li <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/aggregate_report.php"><i class="fa fa-road"></i>Aggregate Report</a></li>
  <li <?php allow_access(1,0,0,0,0,0,$usergroup); ?> ><a href="/rocad_admin/pages/sections/progress_report.php"><i class="fa fa-archive"></i>Progress Report</a></li>   
  <li <?php allow_access(1,1,0,1,0,0,$usergroup); ?> ><a href="/rocad_admin/pages/sections/loading_report.php"><i class="fa fa-table"></i>Store Loading Note</a></li>
  <li <?php allow_access(1,1,0,0,1,0,$usergroup); ?> ><a href="/rocad_admin/pages/sections/requisition_report.php?w=true"><i class="fa fa-handshake-o"></i>Receiving Report</a></li>
  <li <?php allow_access(1,1,0,1,0,0,$usergroup); ?>><a href="/rocad_admin/pages/sections/returned_items.php"><i class="fa fa-comment"></i>Returned Items</a></li>
  
</ul>

</li>

        <!-- General -->             
          <li><a href="/rocad_admin/pages/sections/profile.php"><i class="fa fa-user"></i> <span>My Profile</span></a></li>
        <li><a href="/rocad_admin/logout.php"><i class="fa fa-power-off"></i> <span>Log Out</span></a></li>
      </ul>

      <!-- /.sidebar-menu -->

    </section>

    <!-- /.sidebar -->

  </aside>

<script>

  var parent = $("ul.sidebar-menu li.active").closest("ul").closest("li");

  if (parent[0] != undefined)

    $(parent[0]).addClass("active");

</script>
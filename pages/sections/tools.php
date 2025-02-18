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
$assets = "SELECT * FROM `structural_asset` order by sortno";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
//$row_admin=mysql_fetch_assoc($as_admin);
$checkassets = mysqli_num_rows($as_assets);
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
  background-color: #d4edda;
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
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Plant</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a href="structural_asset.php">Structural Asset</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a href="structural_asset.php">Add Structural Asset</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">
                <thead>
                
                <tr>
                    <th>S/N</th>
                    <th>Asset Name:</th>
                    <th>Asset No.:</th>
                    <th>Available Qty:</th>
                    <th>Initial Qty:</th>
                    <th>Description:</th>                           
                    <th>Size:</th>
                    <th>Store Keeper:</th>
                    <th>Site:</th>
                    <th <?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?>>Action</th>
                </tr>

                </thead>
                <tbody>
                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>
                  <tr style="cursor:pointer;<?php if($row_assets['status']==2){?>color:red;text-decoration:line-through;<?php }?>" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" <?php if($row_assets['status']!=2){?>onClick="window.location='history.php?v=<?php $id=$row_assets['id'];echo $id;?>'"<?php }?> <?php if($row_assets['status']==2){?>title="De-activated"<?php }?>>
                <td><?php echo $j; ?></td>
                <td><?php echo $row_assets['asset_name']; ?></td>
                <td><?php echo $row_assets['asset_number']; ?></td>
                <td><?php echo $row_assets['qty_store']; ?></td>
                <td><?php echo $row_assets['total_qty']; ?></td>
                <td><?php if($row_assets['asset_type']==null){echo "N/A";}else{echo $row_assets['asset_type'];} ?></td>
                <td><?php if($row_assets['size']==NULL){echo"<a href='updateAsset.php?v=$id'>Update</a>";}else{echo $row_assets['size'];} ?></td>
                <td><?php echo $row_assets['store_keeper']; ?></td>             
                <td><?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['sitename']; ?></td>                
                <td <?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?>><div class="dropdown">
  <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
  <div class="dropdown-content">
  <?php if($row_assets['status']==1){?><a href="staff_ad.php?v=<?php echo '4assets';?>&r=<?php echo $row_assets['id'];?>&lk=<?php echo 'structural_asset.php';?>">De-activate</a><?php }?>
  <?php if($row_assets['status']==2){?><a href="staff_ad.php?v=<?php echo '5assets';?>&r=<?php echo $row_assets['id'];?>&lk=<?php echo 'structural_asset.php';?>">Activate</a><?php }?>
  <a href="updateAsset.php?v=<?php echo $id; ?>">Update</a>
  <a  href="deleteAsset.php?v=<?php echo '6assets';?>&r=<?php echo $row_assets['id'];?>&lk=<?php echo 'tools.php';?>" onclick="return myFunction()">Delete</a>
   
 
  </div>
</div></td> 
                    
                 
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                    <th>S/N</th>
                    <th>Asset Name:</th>
                    <th>Asset No.:</th>
                    <th>Available Qty:</th>
                    <th>Initial Qty:</th>
                    <th>Description:</th>                           
                    <th>Size:</th>
                    <th>Store Keeper:</th>
                    <th>Site:</th>
                    <th <?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?>>Action</th>
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
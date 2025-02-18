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

$assets = "SELECT * 
FROM `history`
WHERE title = 'Battery' 
AND id IN (
    SELECT MAX(id) 
    FROM `history`
    WHERE title = 'Battery'
    GROUP BY itemSerial
)
ORDER BY id DESC;
";
// $assets = "SELECT * FROM `history` where title='Battery' group by itemSerial,ifnull(itemSerial,id) order by id desc";

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

    <?php include_once "../layout/topmenu.php";
    allow_access_all(1,0,0,1,0,0,$usergroup); ?>

    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">

        <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        ROCAD

        <small>Store</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">Administration</a></li>

        <li class="active" ><a href="#">Battery Track Stock</a></li>

        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">
<h3 class="box-title"><a href="#">Battery Track Stock</a></h3>
               
            </div>

            <!-- /.box-header -->

            <div class="box-body">

              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">

                <thead>

                <tr>

                <th>S/N</th>
                <th>PLANT NO:</th>
                <th>DATE OF PURCHASE:</th>
                <th>SUPPLIER NAME:</th>
                <th>SUPPLIER PHONE:</th>
                <th>PRODUCT NAME:</th>
                <th>AMPS:</th>
                 <th>BATTERY S.NO.:</th>                
                <th>REQUISITION/RECEIPT</th>
                   <th <?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?>>Action</th>

                  </tr>

                </thead>

                <tbody>

                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>

                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" onClick="window.location='battery_report.php?v=<?php $id=$row_assets['itemSerial'];echo $id;?>'">

                <td><?php echo $j; ?></td>

                <td><?php echo $row_assets['PlantNo'];?></td>

                <td><?php echo $row_assets['time_date'];?></td>
                  <td><?php echo $row_assets['supl'];?></td>
                  <td><?php echo $row_assets['suplPhone'];?></td>
                 <td><?php echo $row_assets['itemName'];?></td>                   
                 <td><?php echo $row_assets['bamps']; ?></td>
                   <td><?php echo $row_assets['tsno']; ?></td>
                   <td><?php echo $row_assets['requi']; ?></td>
<td <?php allow_access(1, 0, 0, 0,0,0, $usergroup); ?>><div class="dropdown">
  <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
  <div class="dropdown-content">
  <a  href="staff_ad.php?v=<?php echo '7battery';?>&r=<?php echo $row_assets['itemSerial'];?>&lk=<?php echo 'battery_track.php';?>" onclick="return myFunction()">Delete</a>
                <?php }?>

              

                </tbody>

                <tfoot>

                  <tr>

                <th>S/N</th>
                <th>PLANT NO:</th>
                <th>DATE OF PURCHASE:</th>
                <th>SUPPLIER NAME:</th>
                <th>SUPPLIER PHONE:</th>
                <th>PRODUCT NAME:</th>
                <th>AMPS.</th>
                 <th>BATTERY S.NO.:</th>                
                <th>REQUISITION/RECEIPT</th>
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
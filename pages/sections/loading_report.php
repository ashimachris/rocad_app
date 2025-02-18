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

$assets = "SELECT * FROM `storeloadingdetails` where dept is null order by id desc";

$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));

//$row_admin=mysql_fetch_assoc($as_admin);

$checkassets = mysqli_num_rows($as_assets);

?>

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

        <small>Store</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">Administration</a></li>

        <li class="active" ><a href="#">Loading Note</a></li>

        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">

               

            </div>

            <!-- /.box-header -->

            <div class="box-body">

              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">

                <thead>

                <tr>

                <th>S/N</th>
                <th>From:</th>
                <th>To:</th>
                <th>Request By:</th>
                <th>Autorised By:</th>
                <th>Received By.</th>
                <th>Time_Date:</th>
                <th>L.P.O No.:</th>
                <th>Status:</th>      

                  </tr>

                </thead>

                <tbody>

                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>

                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" onClick="window.location='more_lr.php?v=<?php $id=$row_assets['reference'];echo $id;?>'">

                <td><?php echo $j; ?></td>

                <td><?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>

                <td><?php $siteID=$row_assets['tosite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc'];  ?></td>

                 <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>

                  <td><?php if(!empty($row_assets['authby'])){$prebyID=$row_assets['authby'];}else{$prebyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/preby.php';echo $row_preby['fullname']; ?></td>

                 <td><?php if(!empty($row_assets['recby'])){$prebyID=$row_assets['recby'];}else{$prebyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/preby.php';echo $row_preby['fullname']; ?></td>                   

                   <td><?php echo $row_assets['time_date']; ?></td>
                   <td><?php echo $row_assets['reference']; ?></td>
                   <td><?php switch($row_assets['status']){case 0:echo "<span class='label label-warning'>Pending</span>";break;case 1:echo "<span class='label label-danger'>Denied</span>";break;case 2:echo "<span class='label label-success'>Approved</span>";break;case 4:echo "<span class='label label-success'>Received</span>";break;case 5:echo "<span class='label label-success'>Received</span>";break;default:echo "<span class='label label-danger'>Unknown</span>";} ?></td>

                    

                    

                 

                <?php }?>

              

                </tbody>

                <tfoot>

                  <tr>

                <th>S/N</th>
                <th>From:</th>
                <th>To:</th>
                <th>Request By:</th>
                <th>Autorised By:</th>
                <th>Received By.</th>
                <th>Time_Date:</th>
                <th>L.P.O No.:</th>
                <th>Status:</th>      

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
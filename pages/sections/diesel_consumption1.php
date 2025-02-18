<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
if(isset($_GET['f'])and (!empty($_GET['t']))){
  $f=$_GET['f'];
  $t=$_GET['t'];   
if(isset($_GET['tlt'])and(!empty($_GET['tlt']))){
  $tlt=$_GET['tlt'];
  $tlts="and title='$tlt'";
  $cat="and cat='$tlt'";
}
if(isset($_GET['st'])and(!empty($_GET['st']))){
  $sts=$_GET['st'];
  $st="and site='$sts'";
}
  $mysql1="WHERE (date(time_date) BETWEEN '$f' and '$t') $cat $st";
  $sql=" and(date(time_date) BETWEEN '$f' and '$t')";
}
else{
$mysql1="";
$sql="";
}
$assets = "SELECT * FROM `oil_stock`$mysql1 order by id desc";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
//$row_admin=mysql_fetch_assoc($as_admin);
$checkassets = mysqli_num_rows($as_assets);
$siteQ = "SELECT * FROM `rocad_site` where sitename!='' order by sitename Asc";
$as_siteQ=mysqli_query($config,$siteQ) or die(mysqli_error($config));

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
  z-index: 1;
}

.dropdown-content a {
  color: red;
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
</style>
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
    <?php allow_access_all(1,1,0,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    



    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">



        <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        ROCAD

        <small>Stock Card</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">Administration</a></li>

        <li class="active"><a href="diesel_stock.php">Store New</a></li>

        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">
<h3 class="box-title"><a href="oil_store.php"></a></h3>
               

            </div>

            <!-- /.box-header -->

            <div class="box-body">
<div>
              <p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn">Filter Record</p>
                <p class="center"><span style="color:#3c8dbc;"><b style="cursor:pointer;" onclick="window.location='diesel_consumption.php'">STOCK - CARD</b></span></p>
                  </div>
              <table id="example1" class="table table-bordered table-hover" style=" text-transform: uppercase;">

                <thead>

                <tr>

                <th>S/N</th>
                <th>SITE</th>
                <th>TYPE:</th>
                <th>QUANTITY IN:</th>
                <th>QUANTITY OUT:</th>
               <th>QUANTITY IN STOCK:</th>
                <th>DATE:</th>             
                                 
                  </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; $ltr=$row_assets['ltr']; $stID=$row_assets['site'];$ttl=$row_assets['cat']; ?>
                  <tr style="cursor: pointer; background-color: #d4edda" onmouseover="$(this).css('background', '#FFF');" onMouseOut="$(this).css('background', '#d4edda');">
                <td><?php echo $j; ?></td>
                <td><?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>

                <td><?php echo "<span class='dropbtn label label-warning'>".$row_assets['cat']."</span>";?></td>
                <td <?php if(empty($_GET['f'])){ ?> onclick="window.location='oil_store.php?f=2013-01-01&t=2090-01-01&p=&tlt=<?php echo $ttl; ?>&st=<?php echo $stID; ?>&R=1'" <?php }else{ ?>onclick="window.location='oil_store.php?f=<?php echo $f; ?>&t=<?php echo $t; ?>&p=&tlt=<?php echo $ttl; ?>&st=<?php echo $_GET['st']; ?>&R=1'" <?php }?>><?php if(empty($_GET['ttl'])){$cat=$row_assets['cat'];}else{$cat=$_GET['ttl'];}if(empty($_GET['st'])){$site=$row_assets['site'];}else{$site=$_GET['st'];} $assetsL = "SELECT sum(ltr) as cltr FROM `oil_stock_history` WHERE `site`=$site and `cat`='$cat'$sql";
                  $L_assets=mysqli_query($config,$assetsL) or die(mysqli_error($config));
                  $row_L=mysqli_fetch_assoc($L_assets);echo  "<span class='dropbtn label label-success'>#".number_format($row_L['cltr'],2)."--LTR</span>";?></td>

                <td  <?php if(empty($_GET['f'])){ ?> onclick="window.location='more_expenses.php?f=2013-01-01&t=2090-01-01&p=&tlt=<?php echo $ttl; ?>&st=<?php echo $stID; ?>&R=1'" <?php }else{ ?>onclick="window.location='more_expenses.php?f=<?php echo $f; ?>&t=<?php echo $t; ?>&p=&tlt=<?php echo $ttl; ?>&st=<?php echo $_GET['st']; ?>&R=1'" <?php }?>><?php $assetsL2 = "SELECT sum(liter) as cltr2 FROM `history` WHERE `site`=$site and `title`='$cat'$sql";
                  $L2_assets=mysqli_query($config,$assetsL2) or die(mysqli_error($config));
                  $row_L2=mysqli_fetch_assoc($L2_assets);echo  "<span class='dropbtn label label-success'>#".number_format($row_L2['cltr2'],2)."--LTR</span>";?></td>
                 
                <td align="center"><?php $ltr=$row_assets['ltr']; switch ($ltr){ case 0: echo "<span class='dropbtn label label-danger'>#".number_format($ltr,2)."</span>";break;default: echo "<span class='dropbtn label label-success'>#".number_format($ltr,2)."--LTR</span>";} ?></td>
                   
                 <td><?php echo $row_assets['time_date'];?></td>                   
                 
                <?php }?>

              

                </tbody>

                <tfoot>

                <tr>

                <th>S/N</th>
                <th>SITE</th>
                <th>TYPE:</th>
                <th>QUANTITY IN:</th>
                <th>QUANTITY OUT:</th>
                <th>QUANTITY IN STOCK:</th>
                <th>DATE:</th>             
                                 
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

        <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center><form><table>
                                  <tr>  
                <th>From</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="f" required class="form-control"></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>
                <th>To</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="t" required class="form-control"></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   
                  <tr>
                <th>Title</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="tlt" class="form-control"><option value="" selected>::Select Title::</option><option>Diesel</option><option>Petrol</option><option>Hydraulic Oil</option><option>Engine Oil</option></select></td>                   
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                <th>Site</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="st" class="form-control"><option value="" selected>::Select Site::</option><?php while($row_sites=mysqli_fetch_assoc($as_siteQ)){?><option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."-".$row_sites['site_lga']."-".$row_sites['site_loc']; ?></option><?php }?></select></td>                   
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                <th><input type="hidden" name="v" value="<?php echo $ids;?>"></th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td align="center"><button>Filter Record</button></td>
                                   
                  </tr>                
              </table>
              </form>
              </center>
  </div>

</div>

    <?php include_once "../layout/copyright.php"; ?>

    <?php include_once "../layout/right-sidebar.php"; ?>



    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

    <div class="control-sidebar-bg"></div>

  </div><!-- ./wrapper -->



<?php include_once "../layout/footer.php" ?>

<script src="js/table.js"></script>
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
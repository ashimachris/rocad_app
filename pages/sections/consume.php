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
  $mysql="WHERE liter!='' and (date(time_date) BETWEEN '$f' and '$t') $tlts $st" ;
 
}
else{
$mysql1="";
$mysql="WHERE liter!=''";

 
}

$assets = "SELECT * FROM `oil_stock_history`$mysql1 order by id desc";

$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));

//$row_admin=mysql_fetch_assoc($as_admin);

$checkassets = mysqli_num_rows($as_assets);
//$sum = "SELECT sum(CASE WHEN liter IS NULL THEN lprice ELSE liter end) as allsum FROM `history` $mysql";
$sum = "SELECT sum(ltr) as allsum,sum(ltrp*ltr) as lprice FROM `oil_stock_history` $mysql1";
$sql_sum=mysqli_query($config,$sum)or die(mysqli_error($config));
$row_sum2=mysqli_fetch_assoc($sql_sum);
///////////////////
$sum2 = "SELECT sum(CASE WHEN liter IS NULL THEN lprice ELSE lprice * liter end) as ltrsum,sum(liter) as ltr2 FROM `history` $mysql";
$sql_sum2=mysqli_query($config,$sum2)or die(mysqli_error($config));
$row_sum22=mysqli_fetch_assoc($sql_sum2);
/////////////////////////////////
$sitet = "SELECT * FROM `history` $mysql";
$as_site=mysqli_query($config,$sitet) or die(mysqli_error($config));
?>
<style type="text/css">
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

    <?php include_once "../layout/left-sidebar.php"; ?>

    



    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">



        <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        ROCAD

        <small>Stock History</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">Administration</a></li>

        <li class="active"><?php if(empty($_GET['R'])){?><a href="diesel_stock.php">Store New</a><?php }?></li>

        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">
               

 <h3 class="box-title">|| <a href="oil_store.php?f=2013-01-01&t=2090-01-01&p=&tlt=Diesel&st=15&lk=1">STOCKING CONSUMABLE</h3> <!--</a> || <a href="oil_store.php?f=2013-01-01&t=2090-01-01&p=&tlt=Petrol&st=15&&lk=1">PETROL</a> || <a href="oil_store.php?f=2013-01-01&t=2090-01-01&p=&tlt=Engine%20Oil&st=15&R=1$lk=1">ENGINE OIL</a> || <a href="oil_store.php?f=2013-01-01&t=2090-01-01&p=&tlt=Hydraulic%20Oil&st=15&R=1$lk=1">HYDRAULIC OIL</a> || <a href="aggregate_report.php">AGGREGATE</a> || <a href="more_expenses.php?f=2020-01-01&t=2080-01-01&p=&tlt=Asphalt&st=&v=">ASPHALT</a> ||// -->
               

            </div>

            <!-- /.box-header -->

            <div class="box-body">
               
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
                <th>Category</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="tlt" class="form-control"><option value="" selected>::Select Category::</option><?php while($row_sites=mysqli_fetch_assoc($as_site)){?><option value="<?php echo $row_sites['title']; ?>"><?php echo $row_sites['title']; ?></option><?php }?></select></td>                   
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                <th></th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td align="center"><button>Filter Record</button></td>
                                   
                  </tr>                
              </table>
              </form>
              </center>
  </div>

</div>

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
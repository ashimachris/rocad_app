<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
//mysql_select_db($database_config, $config);
if(isset($_GET['v'])and (!empty($_GET['v']))){
  $id=$_GET['v'];
}
else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$group = "SELECT * FROM `history` where id=$id";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
$row_group=mysqli_fetch_assoc($as_group);
$checkgroup = mysqli_num_rows($as_group);
$assets = "SELECT * FROM `history` where assetID=$id group by itemSerial,ifnull(itemSerial,id) order by id desc";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
?>
<style type="text/css">
  .center {
  text-align: center;
  border: 2px solid green;
  margin-bottom: 20px;
}
.left {
  text-align: left;
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

    <?php include_once "../layout/topmenu.php";
?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Tyre Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="equipments.php">plant</a></li>
        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
             
            <!-- /.box-header -->
            <div class="box-body">
               <?php if($row_group['isreturn']){ ?>
              <hr>
               <center><div><h2><span style="color:green;text-decoration: overline;">This Item is retured by: <?php $prebyID=$row_group['returnby'];require '../layout/preby.php';echo $row_preby['fullname']; ?> </span><hr>{<?php echo $row_group['returnTime']; ?>}</h2></div></center><?php }?>
              <table id="example" class="table table-bordered table-hover">
                
                <tbody>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th align="right">Plant No.:</th>
                  <td align="left"><span class='dropbtn label label-success'><?php echo $row_group["PlantNo"]; ?></span></td>
                  <th>Product Name:</th>
                 <td><?php echo $row_group["itemName"]; ?></td>
                 <th></th>
                <th></th>
                
                <th><?php if(!$row_group['returnby']){?><p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn"><span class='dropbtn label label-warning'>Return this Item</span></p><?php }?></th>
                <th> 
</th>
<th></th>
<th></th>
                </tr>
                 <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>L.P.O No.:</th>
                  <td align="left"><?php echo $row_group["itemSerial"]; ?></td>
                  <th>Amount:</th>
                 <td>&#8358;<?php echo number_format($row_group["lprice"]); ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Autorize By:</th>
                  <td align="left"><?php $prebyID=$row_group['pre_by'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                  <th>Tyre Serial No.:</th>
                 <td><?php echo $row_group["tsno"]; ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Date of Purchase:</th>
                  <td align="left"><?php echo $row_group['time_date'];?></td>
                  <th>Manufacture Date:</th>
                 <td><?php echo $row_group["manuf_date"]; ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Tyre Size:</th>
                  <td align="left"><?php echo $row_group["tsize"]; ?></td>
                  <th>Site:</th>
                 <td><?php $siteID=$row_group['site'];require '../layout/site.php'; echo $row_site['sitename']; ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Supplier Name:</th>
                  <td align="left"><?php echo $row_group["supl"]; ?></td>
                  <th>Supplier Phone:</th>
                 <td><?php echo $row_group["suplPhone"]; ?></td>
                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Requisition/Receipt:</th>
                  <td align="left" colspan="9"><?php echo strtoupper($row_group["requi"]); ?></td>
                   
                </tr>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Description</th>
                  <td align="left" colspan="9">(<?php echo strtoupper($row_group["info"]); ?>)</td>
                   
                </tr>
                </tbody>
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
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <span class='dropbtn label label-danger'>Are you sure?</span><center><form action="returnitem.php" method="get"><table>
                                  <tr>  
                <th>Name of Item:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><?php echo $row_group["itemName"]; ?></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>  
                <th>Plant No:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><?php echo $row_group["PlantNo"]; ?></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>
                <th>Requisition/Receipt:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><?php echo strtoupper($row_group["requi"]); ?></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                  <tr>
                <th>Part's Required:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><?php echo strtoupper($row_group["info"]); ?></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                <th>Site:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><?php $siteID=$row_group['site'];require '../layout/site.php'; echo $row_site['sitename']; ?> </td>                   
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                <th valign="top">Please state Reasons:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><textarea style="width: 146px; height: 91px;" name="rsn" required></textarea></td>                   
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                   <tr>
                <th><input type="hidden" name="v" required value="<?php echo $row_group["id"];?>"><input type="hidden" name="urls" required value="<?php echo $_SERVER['REQUEST_URI'];?>"></th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td align="center"><button>Return</button></td>
                                   
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
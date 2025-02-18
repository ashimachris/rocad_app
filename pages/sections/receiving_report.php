<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
  
$preby =$_SESSION['admin_rocad'];
$msg="";
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["qty"];
//$num = count($assetname);
if(isset($_GET["v"])and (!empty($_GET["v"]))){
  $reference=$_GET["v"];
}
  else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
$qryasset="SELECT * FROM assets where status=1";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
$site = "SELECT * FROM `rocad_site` where sitename!='' order by sitename Asc";
$notiqry1="SELECT * FROM `storeloadingdetails` WHERE dept is not null AND status IN(2,4) AND reference in($reference)";
$as_assets=mysqli_query($config,$notiqry1) or die(mysqli_error($config));
$checkassets = mysqli_num_rows($as_assets);



$notiqry2="SELECT * FROM `storeloadingdetails` WHERE dept is not null AND status IN(2,4) AND reference in($reference)";
$as_assets2=mysqli_query($config,$notiqry2) or die(mysqli_error($config));
$row2=mysqli_fetch_assoc($as_assets2);

$assetsInv = "SELECT * FROM `invoices` where reference in($reference)";
$as_assetInv=mysqli_query($config,$assetsInv) or die(mysqli_error($config));
$row3=mysqli_fetch_assoc($as_assetInv);
if(!($row2['inspby'])){
  echo "<script>alert('Please Upload Supplier\'s Invoice!');location.href='receiving_invoice.php?v=$reference';</script>";
}
?>
<?php if(($row2['status']==0)or($row2['status']==1)){?>
<script>
  document.onkeydown = function (e) {
        return false;
}
$(document).bind("contextmenu", function(e) {
    return false;
});
</script>
<?php }?>
<style type="text/css">
input{
  text-transform: uppercase;
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
 <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <h1>
       <?php if(($row2['status']==2)or($row2['status']==4)){?><h3 class="box-title" ><a class="pr" href="#" onclick="$('.main-footer').hide();$('.pr').hide();javascript:window.print();">PRINT</a></h3><?php }?>
      </h1>
       
      </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">           
             
            <!-- /.box-header -->
            <div class="box-body">
               
              <div class="form-group">                               
            
                  <div style="padding:15px 15px;box-shadow: 1px 1px 25px rgb(0 0 0 / 35%);border-radius: 10px;background: #FAFAFA;margin:-40px auto;<?php if(($row2['status']==0)or($row2['status']==1)){?>background-image:url(pace/notap.png); background-repeat:no-repeat; background-position:center;<?php }?>">
                  <center>
                  <table border="1" rules="rows" width="100%">
                    <tr style="text-transform: uppercase;font-weight: bold;">
                      <td width="10%" valign="top"><img src="/rocad_admin/images/icon.png" width="125px"></td>
                     <td colspan="3" valign="top">
                    <center>
                    <h2 style="margin:0"><b>ROCAD CONSTRUCTION LIMITED</b></h2>
                    <h4 style="margin:0">BUILDING AND CIVIL ENGINEERING CONTRACTORS</h4>
                    <h5 style="margin:0">No. 50 sokoto Road, Tarauni GRA, Kano P.O Box 247, Kano Nigeria<hr></h5>
                    <h4 style="margin:0"><u>RECEIVING REPORT</u><hr></h4>
                  </center></td>
                      
                     </tr>
                   <tr>
                       <td><b>Site:</b></td>                     
                       <td><b>(<?php $siteID=$row2['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc'];?>)</b></td>
                       <td align="right"><b>Date::</b></td>
                       <td><b><?php echo $row2['time_date']; ?></b></td>
                       </tr>
                     <tr>
                       <td width="15%"><b>Received from:</b></td>
                       <td align="left"><b>(<?php echo $row2['supl']; ?>)</b></td>
                       <td><b>L.P.O.No:</b></td>
                       <td><b><?php echo $row2['reference']; ?></b></td>
                       </tr>
                       <tr>
                       <td><b></b></td>
                       <td align="left"></td>
                       <td><b>Supplier Invoice No.::</b></td>
                       <td><b><?php echo $row2['lpo']; ?></b></td>
                       </tr>
                       <tr>
                       <td><b></b></td>
                       <td align="left"></td>
                       <td><b>Status.::</b></td>
                       <td><b><?php switch($row3['ispaid']){ case 1: echo "<span class='dropbtn label label-success'>PAID</span>";break; case 0: echo "<span class='dropbtn label label-warning'>UNPAID</span>";break;}?></b></td>
                       </tr>
                      
                       <tr>
                       <td colspan="4">&nbsp;</td>                     
                        
                       </tr>
                      
                  </table> 
                 <table border="2" width="100%" style="text-transform: uppercase;">
                    <?php echo "<tr style='font-weight: bold; width=auto' align='center'><td>Item:</td><td>Quantity:</td><td colspan='2'>Description</td><td>L.P.O.No.:</td> </tr>";?>
                    <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>

                      <?php echo "<tr align='center'><td>Item &nbsp;".$j."</td>";?>
                      <?php echo "<td>".$row_assets['qty']."</td>";?>
                      <?php echo "<td colspan='2'>".$row_assets['infoD']."</td>";?>
                      <?php echo "<td>".$row_assets['reference']."</td>";?>
                       
                                             
                      <?php echo "</tr>";?>              
                    <?php }?>
 
                       <?php
                    $numrow=(30-$checkassets);

                    for($i=1;$i<=$numrow;$i++){?>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                        <?php }?>
                       
                      <tr>
                        <td colspan="6">&nbsp;</td>                                           
                      </tr>
                      <tr>
                        <td colspan="6">&nbsp;</td>                                           
                      </tr>
                      <tr>
                        <td colspan="6">&nbsp;</td>                                           
                      </tr>
                      <tr align="center">
                        <td colspan="2"><b>Received By:</b></td>
                        <td colspan="2"><b>Inspected By:</b></td>
                        <td colspan="2"><b>Delivered by:</td>
                        </tr>
                        <tr align="center">
                        <td colspan="2"><b><?php $prebyID=$row2['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></b></td>
                        <td colspan="2" ><b><?php $prebyID=$row2['inspby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></b></td>
                        <td colspan="2"><b><?php $prebyID=$row2['authby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></b></td>
                        </tr>
                  </table>
                 
                  </center>       
  </div> 
 
<style type="text/css">
  .form-group {
  display: flex;
  
}
.form-wrapper {
    width: 50%;
    &:first-child {
      margin-right: 20px;
    }
  }
  input, textarea, select, button {
  font-family: "Muli-Regular";
  color: #333;
  font-size: 13px;

}
button {
  border: none;
  width: 152px;
  height: 40px;
  margin: auto;
  margin-top: 29px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  background: #305A72;
  font-size: 13px;
  color: #fff;
  text-transform: uppercase;
  font-family: "Muli-SemiBold";
  border-radius: 20px;
  overflow: hidden;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px rgba(0, 0, 0, 0);
  position: relative;
  -webkit-transition-property: color;
  transition-property: color;
  -webkit-transition-duration: 0.5s;
  transition-duration: 0.5s;
  &:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #f11a09;
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 0 50%;
    transform-origin: 0 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  &:hover {
    &:before {
      -webkit-transform: scaleX(1);
      transform: scaleX(1);
      -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
      transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
    }
  }
}
   
.form-style-9{
  max-width: 450px;
  background: #FAFAFA;
  padding: 30px;
  margin: 50px auto;
  box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
  border-radius: 10px;
   
}
.form-style-9 ul{
  padding:0;
  margin:0;
  list-style:none;
}
.form-style-9 ul li{
  display: block;
  margin-bottom: 10px;
  min-height: 35px;
}
.form-style-9 ul li  .field-style{
  box-sizing: border-box; 
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box; 
  padding: 8px;
  outline: none;
  border: 1px solid #B0CFE0;
  -webkit-transition: all 0.30s ease-in-out;
  -moz-transition: all 0.30s ease-in-out;
  -ms-transition: all 0.30s ease-in-out;
  -o-transition: all 0.30s ease-in-out;

}.form-style-9 ul li  .field-style:focus{
  box-shadow: 0 0 5px #B0CFE0;
  border:1px solid #B0CFE0;
}
.form-style-9 ul li .field-split{
  width: 49%;
}
.form-style-9 ul li .field-full{
  width: 100%;
}
.form-style-9 ul li input.align-left{
  float:left;
}
.form-style-9 ul li input.align-right{
  float:right;
}
.form-style-9 ul li textarea{
  width: 100%;
  height: 100px;
}
.form-style-9 ul li input[type="button"], 
.form-style-9 ul li input[type="submit"] {
  -moz-box-shadow: inset 0px 1px 0px 0px #3985B1;
  -webkit-box-shadow: inset 0px 1px 0px 0px #3985B1;
  box-shadow: inset 0px 1px 0px 0px #3985B1;
  background-color: #216288;
  border: 1px solid #17445E;
  display: inline-block;
  cursor: pointer;
  color: #FFFFFF;
  padding: 8px 18px;
  text-decoration: none;
  font: 12px Arial, Helvetica, sans-serif;
}
.form-style-9 ul li input[type="button"]:hover, 
.form-style-9 ul li input[type="submit"]:hover {
  background: linear-gradient(to bottom, #2D77A2 5%, #337DA8 100%);
  background-color: #28739E;
}
@media print {
    @page {
        margin-top: 0;
        margin-bottom: 0;
    }
    body {
        padding-top: 72px;
        padding-bottom: 72px ;
    }
}
</style>
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
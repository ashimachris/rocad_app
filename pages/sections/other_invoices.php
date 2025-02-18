<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
 if(isset($_GET['v'])and (!empty($_GET['v']))){
  $ids=$_GET['v'];
}
else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
if(isset($_GET['f'])and (!empty($_GET['f']))){
  $assetL = "SELECT * FROM `fixed_stock` where reference=$ids";
 $as_asset=mysqli_query($config,$assetL) or die(mysqli_error($config));
$row_assets=mysqli_fetch_assoc($as_asset);
}
else{
  $assetL = "SELECT * FROM `history` where requi=$ids";
 $as_asset=mysqli_query($config,$assetL) or die(mysqli_error($config));
$row_assets=mysqli_fetch_assoc($as_asset);
}
$img=$row_assets['requi'];
 
 $plantno=$row_assets['PlantNo'];

/////////////
$preby =$_SESSION['admin_rocad']; 
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["filename"];

$statusMsg = '';
if(isset($_POST["sbt"])){
  $acname=mysqli_real_escape_string($config,$_POST["acname"]);
  $acno=mysqli_real_escape_string($config,$_POST["acno"]);
  $bank=mysqli_real_escape_string($config,$_POST["bank"]);
  $pst=mysqli_real_escape_string($config,$_POST["pst"]);
  $chk=mysqli_real_escape_string($config,$_POST["chk"]);
  $supl=mysqli_real_escape_string($config,$_POST["supl"]);
  $suplPhone=mysqli_real_escape_string($config,$_POST["suplPhone"]);
  $info=$row_assets['info'];

$InvDate=$row_assets['time_date'];
$lpo=$row_assets['reference'];;
$title="DIESEL";
////////////////////
$sql3="UPDATE `invoices` SET `ckeck_ref`='$chk', `cct`='$pst',`ac_name`='$acname',`ac_bank`='$bank',`ac_no`='$acno',`ispaid`=1,`supl`='$supl',`suplPhone`='$suplPhone',`infoD`='$info' WHERE reference=$ids";
  $insert=mysqli_query($config,$sql3) or die(mysqli_error($config));
/////////////////////
 
              if($insert==1){
                $statusMsg = "<font color='green'>Success</font>";
                echo "<script>setTimeout(function(){window.location='invoices.php';},4200);</script>";
            }else{
                $statusMsg = "<font color='red'>Error.</font>";
            } 
} 
?>
<style type="text/css">
input{
  text-transform: uppercase;
}
@media (max-width: 767px) {
        .hidden-mobile {
          display: none;
        }
      }
      #acd,#chk {
  display:none;
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
 <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
 
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; allow_access_all(1,0,0,0,1,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
         
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="requisition_report.php">Requisition</a></li>
        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title"  ><a href="#"><a href="#"><?php echo "Receipt Upload" ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $statusMsg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/requi.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  <form name="add_name" id="add_name" action="" method="post" enctype="multipart/form-data"  class="form-style-9">           

<ul>
 
<li>
  
              <label for="">Site:</label>
              <?php $siteID=$row_assets['site'];require '../layout/site.php'; ?>
      <select class="form-control" id="from" name="from" disabled title="<?php echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']; ?>">
              <option selected><?php echo $row_site['site_lga']."-".$row_site['site_loc']; ?></option> 
                        </select>
           </li>
              
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Supplier:</label>
              <input type="text" min="1" class="form-control" disabled name="ltr" value="<?php echo $row_assets['supl']?>">
            </div>
            <div class="form-wrapper">
              <label for="">Requisition/Receipt:</label>
              <input type="text" min="1" class="form-control" disabled name="req" value="<?php echo $row_assets['requi']; ?>">
            </div>            
            </div></li>
                      <li>
   
   <div class="form-group"> 
   <div class="form-wrapper">
              <label for="">Invoice Date:</label>
              <input type="text" class="form-control" disabled name="invDate" value="<?php echo $row_assets['time_date']; ?>">
            </div>
             <div class="form-wrapper">
              <label for="">Prepared By:</label>
              <input type="text" min="1" class="form-control" disabled name="preby" value="<?php if($row_assets['pre_by']){$prebyID=$row_assets['pre_by'];}else{$prebyID=$row_assets['preby'];}require '../layout/preby.php';echo $row_preby['fullname']; ?>">

            </div>
            </div>
            </li>
            <li>
   
   <div class="form-group"> 
   <div class="form-wrapper">
              <label for="">Plant No:</label>
              <input type="text" class="form-control" disabled name="invDate" value="<?php echo $row_assets['PlantNo']; ?>">
            </div>
             <div class="form-wrapper">
              <label for="">WayBill NO:</label>
              <input type="text" min="1" class="form-control" disabled name="preby" value="<?php echo $row_assets['itemSerial']; ?>">

            </div>
            </div>
            </li> 
             
                     <li>            
              <label for="">Required For:</label>
               <textarea class="form-control" disabled><?php echo $row_assets['info'];?></textarea>         
        </li> 
         <li>
   
   <div class="form-group"> 
   <div class="form-wrapper">
              <label for="">Supplier Name:</label>
              <input type="text" class="form-control" name="supl">
            </div>
             <div class="form-wrapper">
              <label for="">Supplier Phone No.:</label>
              <input type="Number" min="1" class="form-control" name="suplPhone">

            </div>
            </div>
            </li> 
        <hr><h5 class="box-title"><span style="color:#337ab7;"><?php echo "Bank Details" ?></span></h5>
        <table class="table table-bordered txt" id="dynamic_field" border="0" >
              <tr>
                <ul class="row'+i+'"><li><div>
       <label for="pst">Cash</label>
      <input type="radio" id="pst" name="pst" value="1" required onclick="document.getElementById('chk').style.display = 'none';document.getElementById('acd').style.display = 'none';" />
       <label for="contactChoice3">Check</label>

      <input type="radio" id="pst2" name="pst" value="2" onclick="document.getElementById('chk').style.display = 'block';document.getElementById('acd').style.display = 'none';" />
             <label for="contactChoice3">Transfer</label>

      <input type="radio" id="pst2" name="pst" value="3" onclick="document.getElementById('acd').style.display = 'block';document.getElementById('chk').style.display = 'none';" />
      <div id="chk"><input type="text" name="chk" class="form-control" placeholder="Check Number">
      </div>
      <div id="acd">
        <label for="contactChoice3">Account Details</label>
        <input type="text" name="acname" class="form-control" placeholder="Account Name">
        <input type="Number" min="0" name="acno" class="form-control" placeholder="Account Number">
        <input type="text" name="bank" class="form-control" placeholder="Bank">
      </div>
    </div>
  

                </li>
                  <ul>
                <td align="right" colspan="6">  
                                     
                </tr>
          </table>
  </ul>
                <div align="right">
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="PAY"/> 
                     </div> 
</form>
</div>
<img src="/rocad_admin/pages/sections/uploads/<?php echo $img.'.jpg'; ?>" style="padding-top:50px;padding-left:20px; max-height: 10%;" class="hidden-mobile" height="450px">
</div>
 
<style type="text/css">
  .form-group {
  display: flex;
  
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
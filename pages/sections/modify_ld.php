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
$preby =$_SESSION['admin_rocad'];
$msg="";
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["qty"];
$num = count((is_countable($assetname)?$assetname:[]));
$qryasset="SELECT * FROM assets where status=1";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
$site = "SELECT * FROM `rocad_site` where sitename!='' order by sitename Asc";
$as_site=mysqli_query($config,$site) or die(mysqli_error($config));
$notiqry1="select * from `storeloadingdetails` where id='$ids'";
$noti1=mysqli_query($config,$notiqry1);
$row_notii=mysqli_fetch_assoc($noti1);

if(isset($_POST["sbt"])){
   
 /////////////////////////////
      $desc=mysqli_real_escape_string($config,$_POST["desc"]);      
      $PlantNo=mysqli_real_escape_string($config,$_POST["plantno"]);
      $partno=mysqli_real_escape_string($config,$_POST["partno"]);
      $unit=mysqli_real_escape_string($config,$_POST["unit"]);
      $qty=mysqli_real_escape_string($config,$_POST["qty"]);
      $unitprice=mysqli_real_escape_string($config,$_POST["unitprice"]);
      $tvalue=mysqli_real_escape_string($config,$_POST["tvalue"]);

$sql="UPDATE `storeloadingdetails` SET descrip='$desc',PlantNo='$PlantNo',unit='$unit',qty='$qty',unitprice='$unitprice',totalvalue='$tvalue',partno='$partno',modify_by='$preby' where id='$ids'";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
      $reference=$row_notii['reference'];
      
  $msg="<font color='green'>Data successfully Updated.</font>";
   echo    "<script>setTimeout(function(){window.location='notification.php?id=$reference';},2200);</script>";
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
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
 <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    
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
        ROCAD
         
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a href="#">Loading Note</a></li>
        <li class="active"  ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title"  ><a href="#"><a href="#">Loading Note Update--L.P.O No.: (<?php echo $row_notii['reference']; ?>)</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/loading.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  <form name="add_name" id="add_name" action="" method="post"  class="form-style-9">           
 
         
        <table class="table table-bordered txt" id="w" border="0" >
          <ul><li><div class="form-group"><div class="form-wrapper"><label for="">Description:</label><input type="text" class="form-control" required name="desc" id="qty" value="<?php echo $row_notii['descrip']; ?>"></div><div class="form-wrapper"><label for="">A/C Code:</label><select name="plantno" class="form-control" required><option value="" selected><?php echo $row_notii['PlantNo']; ?></option><option value="N/A">N/A</option><?php while($row_asset=mysqli_fetch_assoc($asset)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select></div></div></li><li><div class="form-group"><div class="form-wrapper"><label for="">Part No.:</label><input type="text" class="form-control" required name="partno" id="qty" value="<?php echo $row_notii['partno']; ?>"></div>
<div class="form-wrapper"><label for="">Unit:</label><input type="number" min="1" class="form-control" required name="unit" id="qty" value="<?php echo $row_notii['unit']; ?>"></div></div></li><li><div class="form-group">
<div class="form-wrapper"><label for="">Quantity:</label><input type="number" min="1" class="form-control" required name="qty" id="qty" value="<?php echo $row_notii['qty']; ?>"></div>
<div class="form-wrapper"><label for="">Unit Price:</label><input type="number" min="1" class="form-control" required name="unitprice" id="qty" value="<?php echo $row_notii['unitprice']; ?>"></div></div></li><li>
   <label for="">Total Value:</label><input type="number" min="1" class="form-control" required name="tvalue" id="qty" value="<?php echo $row_notii['totalvalue']; ?>"> </li>


<ul>
              
          </table>
                <div align="right">
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Update"/> 
                     </div> 
</form>
</div>
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
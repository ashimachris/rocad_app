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
$group = "SELECT * FROM `assets` where id=$ids";
$as_group=mysqli_query($config,$group) or die(mysqli_error($config));
$row_assets=mysqli_fetch_assoc($as_group);
$msg="";
$plantNo=$row_assets["sortno"];
$timeDate=date('Y-m-d H:i:s');
$site=$row_assets["site"];
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["amount"];
//$number = count($assetname);
$number = count((is_countable($assetname)?$assetname:[]));
if(isset($_POST["sbt"])){
  for($i=0; $i<$number; $i++)
  {
    if(trim($assetname[$i] != ''))
    {
 /////////////////////////////
$sql="insert into `history`(PlantNo,time_date,itemName,lprice,supl,suplPhone,info,pre_by,assetID,title,site,itemSerial,manuf_date,tsize,tsno,requi)values('$plantNo','$timeDate','".mysqli_real_escape_string($config,$_POST["itemName"])."','".mysqli_real_escape_string($config,$_POST["amount"][$i])."','".mysqli_real_escape_string($config,$_POST["supl"])."','".mysqli_real_escape_string($config,$_POST["suplPhone"])."','".mysqli_real_escape_string($config,$_POST["info"])."','$preby','$ids','Tyre','$site','$tenDgt','".mysqli_real_escape_string($config,$_POST["ManufacturingDate"][$i])."','".mysqli_real_escape_string($config,$_POST["tsize"][$i])."','".mysqli_real_escape_string($config,$_POST["tsno"][$i])."','".mysqli_real_escape_string($config,$_POST["requi"])."')";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
     $liter=$_POST['liter'];
  $msg="<font color='green'>Data successfully Saved --- <u>$plantNo</u></font>";
   echo    "<script>setTimeout(function(){window.location='tyre.php?v=$ids';},4200);</script>";
  }
   
}}
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

    <?php include_once "../layout/topmenu.php";allow_access(1,0,0,1,0,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Tyre</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"  ><a href="equipments.php">Plant</a></li>
        <li class="active"  ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title"  ><a href="#"><a href="#"><?php echo "Tyre for ".$row_assets['sortno']; ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/tyre.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  <form name="add_name" id="add_name" action="" method="post"  class="form-style-9">           

<ul>
 
<li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Description:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['assetname']; ?>" name="desc" disabled>
            </div>
            <div class="form-wrapper">
              <label for="">Make:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['make']; ?>"  name="make" disabled>
              </div>
            </div></li>
             <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Plant No:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['sortno']; ?>" name="sortno" disabled>
              </div>
             <div class="form-wrapper">
              <label for="">Driver:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['driver']; ?>" name="sortno" disabled>
            </div>
              </div></li>
             <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Model Type:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['modelType']; ?>" name="sortno" disabled>
              </div>
             <div class="form-wrapper">
              <label for="">Site:</label>
              <input type="text" class="form-control" required value="<?php $siteID=$row_assets['site'];require '../layout/site.php'; echo $row_site['sitename']; ?>" name="sortno" disabled>
            </div>
              </div></li>
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Number of Tyre:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['NofTyre']; ?>" disabled>
              </div>
             <div class="form-wrapper">
              <label for="">Size of Tyre:</label>
              <input type="text" class="form-control" required value="<?php echo $row_assets['SofTyre']; ?>" disabled>
            </div>
              </div></li>
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Product Name(<font size="2px">Tyre Name</font>):</label>
              <input type="text" class="form-control" required name="itemName">
              </div>
             <div class="form-wrapper">
              <label for="">Time & Date:</label>
              <input type="text" class="form-control" required value="<?php echo $timeDate; ?>" name="sortno" disabled>
            </div>
              </div></li>
              
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Supplier Name:</label>
              <input type="text" class="form-control" required name="supl">
              </div>
             <div class="form-wrapper">
              <label for="">Supplier Phone:</label>
              <input type="number" min="0" class="form-control" required name="suplPhone">
            </div>
              </div></li>
              <li>            
              <label for="">Requisition/Receipt:</label>
              <input type="text" class="form-control" required name="requi">    
        </li>         
                     <li>            
              <label for="">Description:</label>
               <textarea class="form-control" required name="info"></textarea>         
        </li></ul>
        <hr><h5 class="box-title"><span style="color:#337ab7;"><?php echo "TYRE INFORMATION" ?></span></h5>
        <table class="table table-bordered txt" id="dynamic_field" border="0" >
              <tr>
                <td align="right" colspan="6">  
                                    <button type="button" name="add" id="add" class="btn btn-success">Add Tyre</button></td>
                </tr>
          </table>
                <div align="right">
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/> 
                     </div> 
</form>
</div>
</div>
<script>
  $(document).ready(function(){
   $('#submit').hide();
  var i=1;
  $('#add').click(function(){
    $('#submit').show();
    i++;
     
    $('#dynamic_field').append('<ul class="row'+i+'"><label for="" name="remove" id="'+i+'" class="btn_remove" style="float:right;color:red;cursor:pointer">X</label><li><div class="form-group"><div class="form-wrapper"><label for="">Manufacturing Date:</label><input type="Date" class="form-control" required name="ManufacturingDate[]"></div><div class="form-wrapper"><label for="">Size:</label><input type="text" class="form-control" required name="tsize[]" id="tsize" placeholder="E.g 205mm"></div></div></li><li><div class="form-group"><div class="form-wrapper"><label for="">Serial Number:<font color="red">*</font></label><input type="text" class="form-control" required id="ttl" name="tsno[]"></div><div class="form-wrapper"><label for="">Amount:</label><input type="number" min="1" class="form-control" required name="amount[]" id="amount"></div></div></li><ul>');
  });
});
  var tyre='<?php echo $row_assets['NofTyre']; ?>';
 for (var ii=0; ii<tyre; ii++){
    //$('div.appendHere').append('<div class="appendedDIVs"></div>');
 
    $('#dynamic_field').append('<ul class="row'+ii+'"><label for="" name="remove" id="'+ii+'" class="btn_remove" style="float:right;color:red;cursor:pointer">X</label><li><div class="form-group"><div class="form-wrapper"><label for="">Manufacturing Date:</label><input type="Date" class="form-control" required name="ManufacturingDate[]"></div><div class="form-wrapper"><label for="">Size:</label><input type="text" class="form-control" required name="tsize[]" id="tsize" placeholder="E.g 205mm"></div></div></li><li><div class="form-group"><div class="form-wrapper"><label for="">Serial Number:<font color="red">*</font></label><input type="text" class="form-control" required id="ttl" name="tsno[]"></div><div class="form-wrapper"><label for="">Amount:</label><input type="number" min="1" class="form-control" required name="amount[]" id="amount"></div></div></li><ul>');
    }
  
  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('.row'+button_id+'').remove();
  });
   
   
 $(document).ready(function(){
    $(".input").keyup(function(){
          var val1 = +$("#amount").val();
          var val2 = +$("#qty").val();
          $("#ttl").val(val1+val2);
    });
});
</script>
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
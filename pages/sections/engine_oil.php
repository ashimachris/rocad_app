<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
 if(isset($_GET['v'])and (!empty($_GET['v']))){
  $ids=$_GET['v'];
  
$dp="Engine Oil";
$tenDgt = rand(1000000000,9999999999);
//$conv=$dp;
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
$Plantsite=$row_assets["site"];
$timeDate=date('Y-m-d H:i:s');
$site=$row_assets["site"];
$assets_oil = "SELECT SUM(CASE WHEN cat='Diesel' THEN ltr END) as diesel,
                  SUM(CASE WHEN cat='Petrol' THEN ltr END) as petrol,
                  SUM(CASE WHEN cat='Engine Oil' THEN ltr END) as engineoil,
                  SUM(CASE WHEN cat='Gear Oil' THEN ltr END) as gearoil,
                  SUM(CASE WHEN cat='Hydraulic Oil' THEN ltr END) as hydraulicoil
  FROM `oil_stock` where ltr>0 and `site`='$Plantsite'";
$oil_assets=mysqli_query($config,$assets_oil) or die(mysqli_error($config));
$row_oil_count=mysqli_fetch_assoc($oil_assets);

if(isset($_POST["sbt"])){
 /////////////////////////////
  $ltrupd=mysqli_real_escape_string($config,$_POST["liter"]);
  $sql2="UPDATE `oil_stock` SET `ltr`=`ltr`-$ltrupd,`preby`='$preby',`time_date`='$timeDate' WHERE cat='$dp' and `site`='$Plantsite'";
mysqli_query($config,$sql2) or die(mysqli_error($config));
  ////////////
$sql="insert into `history`(PlantNo,time_date,liter,requi,loadcarry,unit,info,lprice,pre_by,assetID,title,site,itemSerial)values('$plantNo','$timeDate','".mysqli_real_escape_string($config,$_POST["liter"])."','".mysqli_real_escape_string($config,$_POST["requi"])."','".mysqli_real_escape_string($config,$_POST["loadc"])."','".mysqli_real_escape_string($config,$_POST["unit"])."','".mysqli_real_escape_string($config,$_POST["desc"])."','".mysqli_real_escape_string($config,$_POST["lprice"])."','$preby','$ids','$dp','$site','$tenDgt')";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
     $liter=$_POST['liter'];
  $msg="<font color='green'>($liter) Liter of $dp has been allocated to <u>$plantNo</u></font>";
   echo    "<script>setTimeout(function(){window.location='engine_oil.php?v=$ids';},4200);</script>";
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
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; allow_access(1,0,0,1,0,0,$usergroup);?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Diesel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="equipments.php">Plant</a></li>
        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title" ><a href="#"><a href="#"><?php echo "Engine Oil Distribution ---".$plantNo."---".$dp ?></a></h3>

            <h3 class="box-title" style="float: right;"> <a href="#"><a href="#"><?php echo "Available ".$dp." in Stock ("."<font color='red'><b>".number_format($row_oil_count['engineoil'])."L</b></font>)"; ?></a></h3>
          </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/engineoil.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
              <form class="form-style-9" method="POST" onsubmit="return checklbl()">

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
              <label for="">Time & Date:</label>
              <input type="text" class="form-control" required value="<?php echo date('Y-m-d H:i:s'); ?>" name="time_date" disabled>
            </div>
              </div></li>
            
            
             <li>
               
 
<div class="form-group">
             <div class="form-wrapper">
              <label for="">Liter(<?php echo $dp; ?>):</label>
              <input type="Number" min="1" class="form-control" required name="liter" id="lr" value="">
              <input type="hidden" name="dp" value="<?php echo $row_oil_count['engineoil']; ?>" id="dp">
            </div>
 <div class="form-wrapper">
              <label for="">Liter Price(<?php echo $dp; ?>):</label>
              <input type="Number" min="1" class="form-control" required name="lprice" id="aa">
              </div>
          </div>
          </li>
            <li>
            
             <div class="form-group">
           <div class="form-wrapper">
              <label for="">Site:</label>
              <select class="form-control" id="from" required name="site" disabled>
                        
     <option selected <?php $siteID=$row_assets['site'];require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state']."---".$row_site['site_lga']; ?></option>
     <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
    <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['sitename']; ?></option>
                        <?php }?>
                      </select>
              </div>
             <div class="form-wrapper">
              <label for="">Requisition/Receipt:</label>
              <input type="text" class="form-control" name="requi">
            </div>
          </div>
        </li>
            <li>
            
             <div class="form-group">
            <div class="form-wrapper">
              <label for="">Load:</label>
              <select class="form-control" id="from" name="loadc">
               <option value="0">--------- SELECT ---------</option>
               <option>Aggregate</option>
               <option>Laterite (Filling)</option>
               <option>Sand</option>
              <option>Boulder</option>
              <option>Water</option>
              <option>Reinforcement</option>
              <option>Earth Work</option>
              <option>Others</option>
              </select>
            </div>
             <div class="form-wrapper">
              <label for="">Unit:</label>
              <input type="text" class="form-control" required name="unit" placeholder="e.g &#13221;">
            </div>
          </div>
        </li>
        <li>
            
             
             
              <label for="">Description:</label>
               <textarea class="form-control" name="desc"></textarea>
          
              
         
        </li>
        <li>

              <button name="sbt" >Allocate</button>

            </li>

</ul>
</form>
</div>
</div>
<script>
     
$(document).ready(function () {
 $("#aa").focus(function () {
if(  (Number($('#lr').val())) > (Number($('#dp').val() )) ){
      alert("You are requesting higher than Stock!");
     $('#lr').focus();
     }  
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
<?php
if(session_status()===PHP_SESSION_NONE){
  session_start();
  }
  $active_menu = "dashboard";
  include_once "../layout/header.php";
  require_once('../../db_con/config.php'); 
 $error=0;
 $insetme=0;
 $preby =$_SESSION['admin_rocad'];


$msg="";
$date=date('Y-m-d H:m:s');
$error=""; 

if (isset($_POST['create_item'])) {

  $date=mysqli_real_escape_string($config,$_POST['date']);

	$site_id=mysqli_real_escape_string($config,$_POST['site_id']);
  $item=mysqli_real_escape_string($config,$_POST['item']);
  $sitename=mysqli_real_escape_string($config,$_POST['sitename']);
  $unit=mysqli_real_escape_string($config,$_POST['unit']);

	
  $qry_supply=mysqli_query($config,"SELECT * FROM `items_supply` WHERE `site_id`='$site_id' AND item='$item'") or die(mysqli_error($config));
  $supply_result=mysqli_fetch_assoc($qry_supply);

  if(mysqli_num_rows($qry_supply)==0){

    $update="INSERT INTO items_supply(`item`,`site_id`, balance, description, unit) 
    VALUES('$item', '$site_id',0, 'New Stock item added','$unit')";
    $update_record=mysqli_query($config,$update)or die(mysqli_error($config));

      if($update_record){
        $msg="<font color='green'>Data successfully Saved.</font>";
         echo    "<script>setTimeout(function(){window.location='stock_item.php?site_id=$site_id&sitename=$sitename';},3200);</script>";
     }else{
       $msg="<font color='red'>Failed to save the record</font>";
         echo    "<script>setTimeout(function(){window.location='new_supply.php?site_id=$site_id&sitename=$sitename';},3200);</script>";
     }

  
  }else{

    $msg="<font color='red'> ($item) found on this site, please go ahead to supply it!</font>";
     echo    "<script>setTimeout(function(){window.location='#';},3200);</script>";
  }

}
 
$site_id = $_GET['site_id'];
$sitename = $_GET['sitename'];

$site_query = "SELECT * FROM `rocad_site` WHERE sitename != '' ORDER BY sitename ASC ";
$as_site = mysqli_query($config, $site_query) or die(mysqli_error($config));
 

?>

<style type="text/css">
input{
  text-transform: uppercase;
}
@media (max-width: 767px) {
        .hidden-mobile {
          display: none;
          padding-right:20px;
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
        <b>Create StockItem</b>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="new_supply.php">New Stock</a></li>
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
              <!-- Display the message dynamically -->
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <!-- Display an image, hidden on mobile devices -->
                  <img src="pace/stock.png" style="height:350px; padding-top:50px;" class="hidden-mobile">
               
                  <div class="form-group">
                   
                  
                      <div class="form-wrapper">
                        <center><h3 class="box-title">Registering New Stock Items for <?php echo "$sitename site"; ?></h3></center>
                          <form action="" method="post"  class="form-style-9" enctype="multipart/form-data">           
                            <ul>
                            <div class='row' style='width:40rem'>
                              <li>
                                <label for="">Time & Date:</label>
                                <input type="text" name="date" class="form-control" required value="<?php echo $date; ?>"  name="make" disabled>
                              </li>

                               <input type="hidden" name="site_id" id="siteId" class="form-control" required value="<?php echo $site_id; ?>"  name="site_id">
                                <input type="hidden" name="sitename" id="sitename" class="form-control" required value="<?php echo $sitename; ?>"  name="sitename">

                              
                              <li>
                                <label for="">Stock Category</label>
                                  <select class="form-control" name="item" type="text" required>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Petrol">Petrol</option>
                                    <option value="Hydraulic oil">Hydraulic oil</option>
                                    <option value="Engine oil">Engine oil</option>
                                    <option value="Black oil">Black oil</option>
                                    <option value="Gear oil">Gear oil</option>
                                    <option value="Cement">Cement</option>
                                    <option value="8mm Reinforcement">8mm Reinforcement</option>
                                    <option value="10mm Reinforcement">10mm Reinforcement</option>
                                    <option value="12mm Reinforcement">12mm Reinforcement</option>
                                    <option value="16mm Reinforcement">16mm Reinforcement</option>
                                    <option value="20mm Reinforcement">20mm Reinforcement</option>
                                    <option value="1/2 Aggregate">1/2 Aggregate</option>
                                    <option value="3/4 Aggregate">3/4 Aggregate</option>
                                    <option value="3/8 Aggregate">3/8 Aggregate</option> 
                                    <option value="Boulder">Boulder</option>    
                                    <option value="Asphalt">Asphalt</option>
                                    <option value="laterite">Laterite</option>
                                    <option value="mc1">MC1</option>
                                     <option value="sharp-sand">SharpSand</option>
                                    <option value="blocks">Blocks</option>
                                    <option value="nails">Nails</option>
                                    <option value="s125">S125</option>
                                    <option value="binding-wire">Binding Wire</option>
                                    <option value="plywood">Plywood</option>
                                  </select> 
                              </li>

                               <li>
                                <label for="">Unit</label>
                                  <select class="form-control" name="unit" id="unitSelect" onchange="" required>
                                    <option value="">Selec Unit</option>
                                    <option value="-8mm">8mm</option>
                                    <option value="-10mm">10mm</option>
                                    <option value="-12mm">12mm</option>
                                    <option value="-16mm">16mm</option>
                                    <option value="-20mm">20mm</option>
                                    <option value="Ltrs">Ltrs</option>
                                    <option value="Ltrs">Bags</option>
                                    <option value="Tons">Tn</option>
                                    <option value="-Pcs">Pcs</option>
                                    <option value="-Bundle">Bundles</option>
                                    <option title="Cubic Meter">&#13221;</option>
                                  </select> 
                              </li>

                              </div><br>
                                 
                          <div class="flex" align="center"> 
                              <input type="submit" name="create_item" class="btn btn-info w-100" value="Create" style="width: 100%;" />
                          </div> 
                    </form>
                </div>

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
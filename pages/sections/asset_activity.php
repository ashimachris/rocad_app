<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');  
$preby =$_SESSION['admin_rocad']; 
$prebyID=$preby;
require '../layout/preby.php';
$sender = $row_preby['fullname'];

$msg="";
$timeDate=date('Y-m-d H:i:s');
$dp=false;
$qry = false;


if(isset($_GET['id'])){
  $asset_id = $_GET['id'];
}else{
    echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}

$qry_assets = "SELECT * FROM `structural_asset` where id='$asset_id'";

$as_assets=mysqli_query($config,$qry_assets) or die(mysqli_error($config));

$r_assets = mysqli_fetch_assoc($as_assets);

$asset_name = $r_assets['asset_name'];


if(isset($_POST["sbt"])){

$asset_id=mysqli_real_escape_string($config,$_POST["asset_id"]);
$qty_store=mysqli_real_escape_string($config,$_POST["qty_store"]);
$total_qty=mysqli_real_escape_string($config,$_POST["total_qty"]);
$move_qty=mysqli_real_escape_string($config,$_POST["move_qty"]);

$avl_qty = $qty_store;

$check=false;

if($move_qty> $avl_qty){ 
  $check=true;
}


if($check){
    $msg="<font color='red'>Invalid input, please verify and try again </font>"."<font color='green'>Available asset is($avl_qty) and you enter ($move_qty) </font>";
    echo    "<script>setTimeout(function(){history.back();},2500)</script>"; 
 }

$quantiy_left = $qty_store - $move_qty;

$sql="insert into `asset_activity`(asset_id,total_qty,avl_qty,move_qty,date,asset_status,site, store_keeper,preby,site_from)values('$asset_id','".mysqli_real_escape_string($config,$_POST["total_qty"])."','$quantiy_left','".mysqli_real_escape_string($config,$_POST["move_qty"])."','".mysqli_real_escape_string($config,$_POST["date"])."','".mysqli_real_escape_string($config,$_POST["status"])."','".mysqli_real_escape_string($config,$_POST["site"])."','".mysqli_real_escape_string($config,$_POST["store_keeper"])."','$prebyID','".mysqli_real_escape_string($config,$_POST["site_from"])."')";

if($check==false){
  $qry=mysqli_query($config,$sql) or die(mysqli_error($config));
  
  }

if($qry){
  // mysqli_query($config, "UPDATE `structural_asset` set qty_store = '$quantiy_left' where id = '$asset_id' "); need approval

  // Fetch site data for $site
    $siteID = mysqli_real_escape_string($config, $_POST["site"]);
    require_once '../layout/site.php'; 
    $site = $row_site['site_state'] . "-" . $row_site['site_lga'];

    // Fetch different site data for $site_from_name
    $site_from_ID = mysqli_real_escape_string($config, $_POST["site_from"]);
    $qry_site_from="select sitename,id,site_lga,site_state,site_loc from rocad_site where id=$site_from_ID and status=1";
    $site_from=mysqli_query($config,$qry_site_from) or die(mysqli_error($config));
    $row_site_from=mysqli_fetch_assoc($site_from);
    $site_from_name = $row_site_from['site_state'] . "-" . $row_site_from['site_lga'];

   // send mail here
    //$to = "deleakintayo@rocad.com,ronaldo@rocad.com,rene@rocad.com,umar@rocad.com,tamer@rocad.com,agbachris555@gmail.com,john@rocad.com,joshua@rocad.com,ibrahim.labaran@rocad.com";
    $to = "abbas@ereg.ng,agbachris555@gmail.com";
    // Subject
    $subject = "Asset $asset_name move out By  $sender on ".date("d-m-Y");

     // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: app.rocad.com" . "\r\n";

    // Email Body
    $message = "<html><body>";
    $message .= "<h3>Asset: <i>$asset_name</i></h3>";
    $message .= "<h3>Requested from site : $site_from_name on  ".date("d-m-Y")." </h3>";
    $message .= "<h3>Request to site : $site  </h3>";
    $message .= "<h3>Quantity Requested: <i>$move_qty</i></h3>";
    $message .= "<h3>Quantity in store : " .$qty_store ."</h3>";
    $message .= "<h3>Received by : " .$sender ."</h3>";
    $message .= "</body></html>";

    // Sending the Email
    mail($to, $subject, $message, $headers);

  $msg="<font color='green'>Data successfully Saved</font>";
   echo  "<script>setTimeout(function(){window.location='history.php?v=$asset_id';},2500)</script>";
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

    <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,1,0,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Structural Asset</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="tools.php">Plant</a></li>
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
              <h3 class="box-title"><?php  echo $msg; ?></h3>
               <div class="form-group">
                
                  <img src="pace/plant.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
              <form class="form-style-9" method="POST">
                    <?php echo "<h4>Moving <i>$asset_name</i></h4>"; ?>

        <ul> 
            <input type="hidden" name="asset_id" value="<?php echo $asset_id ?>">
            <input type="hidden" name="total_qty" value="<?php echo $r_assets['total_qty'] ?>">
            <input type="hidden" name="qty_store" value="<?php echo $r_assets['qty_store'] ?>">
           
              <li>
               <div class="form-group">
               
                <div class="form-wrapper">
                   <label for="">Total Quantity:</label>
                   <input type="number" class="form-control" required name="total_qty" value="<?php echo $r_assets['total_qty']; ?>" readonly>
                </div>

                <div class="form-wrapper">
                   <label for="">Available Quantity:</label>
                   <input type="number" class="form-control" required name="avl_qty" value="<?php echo $r_assets['qty_store']; ?>" readonly>
                </div>
              </div>
              </li>

                <li>
                   <div class="form-group">
                   <div class="form-wrapper">
                       <label for="">Date:</label>
                       <input type="datetime-local" class="form-control" required name="date">
                    </div>
                    <div class="form-wrapper">
                       <label for="">Quantity to moved:</label>
                       <input type="number" class="form-control" required name="move_qty" min="1">
                    </div>
                </li>

                <li>
                  <div class="form-group">
                    <div class="form-wrapper">
                       <label for="">Requested By:</label>
                       <input type="text" class="form-control" required name="store_keeper">
                    </div>
                    <div class="form-wrapper">
                      <label for="">Status:</label>
                      <select class="form-control" name="status" required><option selected value="">::Select  Status::</option><option value="100">Good</option><option value="50">Idle</option><option value="0">Bad</option></select>
                  </div>     
                   
                 </div>
               </li>

            <li>
             <div class="form-group">
             <div class="form-wrapper">
                   <label for="">Site (Requested from):</label>
                   <select class="form-control" id="site_from" required name="site_from">                          
                      <option selected <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename'];?></option>
                      <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
                      <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                      <?php }?>
                   </select>
                   </div>

                <div class="form-wrapper">
                   <label for="">Site (Requested to):</label>
                   <select class="form-control" id="site" required name="site">                          
                      <option selected <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename'];?></option>
                      <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
                      <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                      <?php }?>
                   </select>
                   </div>
                 </li>
                   <li>
                    <button name="sbt">SUBMIT</button>
                   </li>

</ul>
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
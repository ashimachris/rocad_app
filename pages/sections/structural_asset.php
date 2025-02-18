<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');  
$preby =$_SESSION['admin_rocad']; 
$msg="";
$dp=false;
$qry = false;
if(isset($_POST["sbt"])){
  $asset_number=mysqli_real_escape_string($config,$_POST["asset_number"]);
///////////////////////////////////test diplicate
$assets = "SELECT * FROM `structural_asset` where asset_number='$asset_number'";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));

$checkassets = mysqli_num_rows($as_assets);
if($checkassets==1){
    $dp=true;
    /////////////////////////////////Test Duplicate for Plant No
    $msg="<font color='red'>Duplicate, Plant No. already exist! </font>"."<font color='green'>(".$sort_asset.")</font>";
    echo    "<script>setTimeout(function(){history.back();},2500)</script>"; 
 }
$sql="insert into `structural_asset`(asset_name,asset_number,asset_type,size,purchase_year,qty_store,total_qty,cost,status,store_keeper,site)values('".mysqli_real_escape_string($config,$_POST["asset_name"])."','".mysqli_real_escape_string($config,$_POST["asset_number"])."','".mysqli_real_escape_string($config,$_POST["asset_type"])."','".mysqli_real_escape_string($config,$_POST["size"])."','".mysqli_real_escape_string($config,$_POST["purchase_year"])."','".mysqli_real_escape_string($config,$_POST["qty_store"])."','".mysqli_real_escape_string($config,$_POST["total_qty"])."','".mysqli_real_escape_string($config,$_POST["cost"])."','".mysqli_real_escape_string($config,$_POST["status"])."','".mysqli_real_escape_string($config,$_POST["store_keeper"])."','".mysqli_real_escape_string($config,$_POST["site"])."')";

if($dp==false){
  $qry=mysqli_query($config,$sql) or die(mysqli_error($config));
  $rid = mysqli_insert_id($config);
  }

if($qry){

         
          if (isset($_FILES['image']) && $_FILES['image']['tmp_name'] != '') {
              if (!is_dir("uploads/structural_asset")) {
                  mkdir("uploads/structural_asset", 0755, true);
              }

              $upload = $_FILES['image']['tmp_name'];
              $filename = $_FILES['image']['name'];
              $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension
              $fname = 'uploads/structural_asset/' . $rid . '.' . $file_ext; // Customize the new filename
              $dir_path = $fname;

              if (is_file($dir_path)) {
                  unlink($dir_path);
              }

              $uploaded_img = false; // Initialize the variable

              // Check the file type and compress if it's an image
              if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                  // Create a new image resource from the uploaded file
                  if ($file_ext === 'jpg' || $file_ext === 'jpeg') {
                      $source_image = imagecreatefromjpeg($upload);
                      imagejpeg($source_image, $dir_path, 60); // 75 is the quality, adjust as needed
                  } elseif ($file_ext === 'png') {
                      $source_image = imagecreatefrompng($upload);
                      imagepng($source_image, $dir_path, 8); // Compression level for PNG
                  } elseif ($file_ext === 'gif') {
                      $source_image = imagecreatefromgif($upload);
                      imagegif($source_image, $dir_path);
                  }

                  imagedestroy($source_image);
                  $uploaded_img = file_exists($dir_path);
              } else {
                  // If not an image, simply move the uploaded file
                  $uploaded_img = move_uploaded_file($upload, $dir_path);
              }
              // Update the database if upload was successful
              if($uploaded_img){
              $qry = mysqli_query($config, "UPDATE `structural_asset` set image = concat('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '$rid' ");
              }
          }

  $msg="<font color='green'>Data successfully Saved</font>";
   echo    "<script>setTimeout(function(){window.location='asset.php;</script>";
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

    <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,0,0,0,$usergroup); ?>
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
        <li class="active" ><a href="equipments.php">Plant</a></li>
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
              <form class="form-style-9" method="POST" enctype="multipart/form-data">   


<ul>
                  
       <li>
       <div class="form-group">
                <div class="form-wrapper">
                  <label for="">Name:</label>
                  <input type="text" class="form-control" required name="asset_name">
                </div>
                <div class="form-wrapper">
                  <label for="">Asset No:</label>
                  <input type="text" class="form-control" required  name="asset_number">
                </div></li>           
        <li>
        <div class="form-group">
                <div class="form-wrapper">
                  <label for="">Description:</label>
                  <input type="text" class="form-control" required  name="asset_type">
                </div>
                <div class="form-wrapper">
                  <label for="">Image:</label>
                  <input type="file" id="image" name="image" class="form-control form-control-sm form-control-border" onchange="displayImg(this,$(this))" >
                </div></li>
        <li>
        <div class="form-group">
                <div class="form-wrapper">
                  <label for="">Size:</label>
                  <input type="text" class="form-control" required  name="size">
                </div>
                <div class="form-wrapper">
                  <label for="">Purchase year:</label>
                  <input type="text" class="form-control" required name="purchase_year">
                </div></li>
        <li>
        <div class="form-group">
                <div class="form-wrapper">
                  <label for="">Quantity in Store:</label>
                  <input type="text" class="form-control" required name="qty_store">
                </div>
                <div class="form-wrapper">
                   <label for="">Total Quantity:</label>
                   <input type="text" class="form-control" required name="total_qty">
                </div></li>
          <li>
          <div class="form-group">
                <div class="form-wrapper">
                   <label for="">Store Keeper:</label>
                   <input type="text" class="form-control" required name="store_keeper">
                </div>
                <div class="form-wrapper">
                   <label for="">Cost of Equipment:</label>
                   <input type="text" class="form-control" required name="cost">
                </div></li>
          <li>
          <div class="form-group">
                <div class="form-wrapper">
                   <label for="">Status:</label>
                   <select class="form-control" name="status" required><option selected value="">::Select  Status::</option><option value="100">Good</option><option value="50">Idle</option><option value="0">Bad</option></select>
                </div>     
                <div class="form-wrapper">
                   <label for="">Site:</label>
                   <select class="form-control" id="from" required name="site">                          
                      <option selected <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename'];?></option>
                      <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
                      <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                      <?php }?>
                   </select>
                   </div></li>
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
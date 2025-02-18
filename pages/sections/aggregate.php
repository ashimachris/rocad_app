<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
  
$preby =$_SESSION['admin_rocad'];
$msg="";
 $site = "SELECT * FROM `rocad_site` where sitename!='' and status=1 order by sitename Asc";
$as_site=mysqli_query($config,$site) or die(mysqli_error($config));
$as_site2=mysqli_query($config,$site) or die(mysqli_error($config));
$timeDate=date('Y-m-d H:i:s');
$qryasset="SELECT * FROM assets where status=1 order by sortno";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
/////////////////////////
$site=$row_assets["site"];
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["plantno"];
 
if(isset($_POST["sbt"])){
   // $targetDir = "uploads/";
// $fileName = basename($_FILES["filen"]["name"]);
// $NewName=$tenDgt.".jpg";
// $targetFilePath = $targetDir . $NewName;
// $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
 /////////////////////////////
$sql="insert into `history`(PlantNo,time_date,supl,info,pre_by,title,site,itemSerial,requi,lprice,unit)values('".mysqli_real_escape_string($config,$_POST["plantno"])."','".mysqli_real_escape_string($config,$_POST["make"])."','".mysqli_real_escape_string($config,$_POST["supl"])."','".mysqli_real_escape_string($config,$_POST["info"])."','$preby','".mysqli_real_escape_string($config,$_POST["agcat"])."','".mysqli_real_escape_string($config,$_POST["site"])."','".mysqli_real_escape_string($config,$_POST["waybill"])."','$tenDgt','".mysqli_real_escape_string($config,$_POST["qty"])."','".mysqli_real_escape_string($config,$_POST["units"])."')";
  mysqli_query($config,$sql) or die(mysqli_error($config));
// Allow certain file formats
    if (isset($_FILES['attachement']) && $_FILES['attachement']['tmp_name'] != '') {
        if (!is_dir("uploads")) {
            mkdir("uploads");
        }

        $upload = $_FILES['attachement']['tmp_name'];
        $filename = $_FILES['attachement']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension
        $fname = 'uploads/' . $tenDgt . '.' . $file_ext; // Customize the new filename
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
                imagejpeg($source_image, $dir_path, 75); // 75 is the quality, adjust as needed
            } elseif ($file_ext === 'png') {
                $source_image = imagecreatefrompng($upload);
                imagepng($source_image, $dir_path, 6); // Compression level for PNG
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
        // Insert image file name into database
        $insert = ("INSERT into invoices (file_name,reference,PlantNo,supl,ispaid,title,uploadby,lpo,uploaded_on) 
          VALUES ('".$fname."','".$tenDgt."','".mysqli_real_escape_string($config,$_POST["plantno"])."','".mysqli_real_escape_string($config,$_POST["supl"])."',0,'".mysqli_real_escape_string($config,$_POST["agcat"])."','".$preby."','".mysqli_real_escape_string($config,$_POST["waybill"])."','$timeDate')");
            mysqli_query($config,$insert) or die(mysqli_error($config));
          if($insert){
              $statusMsg = "<font color='green'>The file (".$filename. ") has been uploaded successfully.</font>";
              echo "<script>setTimeout(function(){window.location='aggregate.php';},4200);</script>";
          }else{
              $statusMsg = "<font color='red'>File upload failed, please try again.</font>";
          } 
        }
    }

  /*
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["filen"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
$insert = ("INSERT into invoices (file_name,reference,PlantNo,supl,ispaid,title,uploadby,lpo,uploaded_on) VALUES ('".$NewName."','".$tenDgt."','".mysqli_real_escape_string($config,$_POST["plantno"])."','".mysqli_real_escape_string($config,$_POST["supl"])."',0,'".mysqli_real_escape_string($config,$_POST["agcat"])."','".$preby."','".mysqli_real_escape_string($config,$_POST["waybill"])."',NOW())");
              mysqli_query($config,$insert) or die(mysqli_error($config));
            if($insert){
                $statusMsg = "<font color='green'>The file (".$fileName. ") has been uploaded successfully.</font>";
                echo "<script>setTimeout(function(){window.location='aggregate.php';},4200);</script>";
            }else{
                $statusMsg = "<font color='red'>File upload failed, please try again.</font>";
            } 
        }else{
            $statusMsg = "<font color='red'>Sorry, there was an error uploading your file.</font>";
        }
    }else{
        $statusMsg = "<font color='red'>Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.</font>";
    }
    */

}else{
    $statusMsg = "";
// Display status message
//echo $statusMsg;
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
<script>
$(document).ready(function(){
   
  $("#btn2").change(function(){
    //$("ol").append("<li>Appended item</li>");
    alert('Ok');
  });
});
</script>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
 <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; 
    allow_access_all(1,0,0,1,0,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Aggregate</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="equipments.php">Plant</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title" ><a href="#"><a href="daily-plant-reports.php"><?php echo "Aggregate Update" ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $statusMsg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/agreg.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  
                  <form name="add_name" id="add_name" action="" method="post" enctype="multipart/form-data"  class="form-style-9"><center><div id="msg" style="color:red"></div></center>           

<ul>
 
<li>
   
   
              <label for="">Time & Date:</label>
              <input type="date" class="form-control" required name="make"  >
              </li>
             <li>
   
    
             
              <label for="">Site:</label>
               <select class="form-control" id="fromsite" required name="site">
                        
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_site=mysqli_fetch_assoc($as_site)){?>
                          <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?></option>
                          <?php }?>
                        </select>
            </li>

 <li>
   
    
             
              <label for="">Supplier:</label>
               <input type="text" name="supl" required class="form-control">
            </li>
             
             <li>
              <label for="">Plant No:</label>
               <select name="plantno" required class="form-control"><option value="" selected>::Select Plant No::</option><?php while($row_asset=mysqli_fetch_assoc($asset)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select>
            </li>
             <li>
              <label for="">Category:</label>
               <select name="agcat" required class="form-control"><option value="" selected>::Select Category::</option><option>Aggregate</option><option id="btn2">Laterite</option><option>Sand</option><option>Boulder</option><option>MC1</option><option>Asphalt</option><option>Blocks</option><option>S125</option><option>Reinforcement</option><option>Cement</option></select>
            </li>
            <li>
              <label for="">Quantity</label>
              <input type="number" min="0" name="qty" class="form-control">
            </li>
             <li>
              <label for="">Unit:</label>
               <select name="units" required class="form-control"><option value="" selected>::Select Unit::</option><option title="Pieces">Pc</option><option title="Cubic Meter">&#13221;</option><option title="Ton">Tn</option><option title="Liter">LTR</option><option title="8 Milemeter">8mm</option><option title="10 Milemeter">10mm</option><option title="12 Milemeter">12mm</option><option title="16 Milemeter">16mm</option><option title="bag">Bags</option></select>
            </li>
            <li>
              <label for="">WayBill NO:</label>
              <input type="text" name="waybill" class="form-control">
            </li>
            
            <li>
              <label for="">Upload invoice (JPEG,PNG,SVG):</label>
              <input type="file" class="form-control" required name="attachement" title="Upload Invoice">
                      </li>             
<li><label for="">Description:</label><textarea class="form-control" name="info" required></textarea></li>
                                 <div align="right"> 
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/>
                     </div>  
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
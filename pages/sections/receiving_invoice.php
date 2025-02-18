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
  $assetL = "SELECT * FROM `storeloadingdetails` where reference=$ids";
 $as_asset=mysqli_query($config,$assetL) or die(mysqli_error($config));
$row_assets=mysqli_fetch_assoc($as_asset);
 $plantno=$row_assets['PlantNo'];
 $infoD=$row_assets['infoD'];
 $title=$row_assets['reqfor'];
/////////////
$preby =$_SESSION['admin_rocad']; 
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["filename"];
 
$num = count((is_countable($assetname)?$assetname:[]));
$qryasset="SELECT * FROM assets where status=1";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
$site = "SELECT * FROM `rocad_site` where sitename!='' order by sitename Asc";
$as_site=mysqli_query($config,$site) or die(mysqli_error($config));
/////////////////////////////////
$statusMsg = '';
if(isset($_POST["sbt"])){
  // File upload path
$targetDir = "uploads/";
$fileName = basename($_FILES["filen"]["name"]);
$NewName=$ids.".jpg";
$targetFilePath = $targetDir . $NewName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
////////////////////Vars
$InvDate=mysqli_real_escape_string($config,$_POST['InvDate']);
$supl=mysqli_real_escape_string($config,$_POST["supl"]);
$pay_to=mysqli_real_escape_string($config,$_POST["pay_to"]);

  $lpo=mysqli_real_escape_string($config,$_POST["lpo"]);
  $acname=mysqli_real_escape_string($config,$_POST["acname"]);
  $acno=mysqli_real_escape_string($config,$_POST["acno"]);
  $bank=mysqli_real_escape_string($config,$_POST["bank"]);
  $pst=mysqli_real_escape_string($config,$_POST["pst"]);
  $cct=mysqli_real_escape_string($config,$_POST["cct"]);
  $chk=mysqli_real_escape_string($config,$_POST["chk"]);
  $supl=mysqli_real_escape_string($config,$_POST["supl"]);
  $pay_to=mysqli_real_escape_string($config,$_POST["pay_to"]);
  $bank_name=mysqli_real_escape_string($config,$_POST["bank_name"]);
  $amount=mysqli_real_escape_string($config,$_POST["amount"]);

////////////////////
$sql="UPDATE `storeloadingdetails` SET `supl`='$supl', `pay_to`='$pay_to', `InvDate`='$InvDate',status=5, `lpo`='$lpo', `inspby`=$preby, bank_name='$bank_name',totalvalue='$amount' WHERE reference=$ids";       
mysqli_query($config,$sql) or die(mysqli_error($config));

////////////////////// 

 if (isset($_FILES['attachement']) && $_FILES['attachement']['tmp_name'] != '') {
    if (!is_dir("uploads/")) {
        mkdir("uploads/");
    }

    $upload = $_FILES['attachement']['tmp_name'];
    $filename = $_FILES['attachement']['name'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension
    $fname = 'uploads/' . $ids . '.' . $file_ext; // Customize the new filename
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
            imagejpeg($source_image, $dir_path, 25); // adjust as needed
        } elseif ($file_ext === 'png') {
            $source_image = imagecreatefrompng($upload);
            imagepng($source_image, $dir_path, 4); // Compression level for PNG
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
       $insert = ("INSERT into invoices (ckeck_ref,cct,ac_name,ac_bank,ac_no,file_name,reference,PlantNo,supl,pay_to,ispaid,infoD,title,uploadby,lpo,uploaded_on) 
        VALUES ('$chk','$cct','$acname','$bank','$acno','$NewName','$ids','$plantno','$supl','$pay_to','".mysqli_real_escape_string($config,$_POST["pst"])."','$infoD','$title','$preby','$lpo','$timeDate')");


              mysqli_query($config,$insert) or die(mysqli_error($config));

              $qry = mysqli_query($config, "UPDATE `storeloadingdetails` SET invoice = concat('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE reference='$ids' ");

            if($insert){
                $statusMsg = "<font color='green'>The file (".$fname. ") has been uploaded successfully.</font>";

                $description=mysqli_real_escape_string($config,$_POST["description"]);
                // after saving, then save to daily expenses
                $fetch_details = mysqli_query($config,"SELECT * FROM `storeloadingdetails` WHERE reference='$ids'");
                $row_data = mysqli_fetch_assoc($fetch_details);
                $plantno = $row_data['PlantNo'];
                $fromsite = $row_data['fromsite'];
                $tosite = $row_data['tosite'];
                $preby = $row_data['preby'];
                $time_date = $row_data['time_date'];
                $status = $row_data['status'];
                $totalvalue = $row_data['totalvalue'];
                $conditions = $row_data['conditions'];
                $authby = $row_data['authby'];
                $sign_by = $row_data['sign_by'];

                if($conditions==""){
                  $conditions = "No condition";
                }
                if($totalvalue==""){
                  $totalvalue=0;
                }

                $sql="insert into daily_expenses_reports(plant_no, site, description, amount, wstatus,time_date, preby, reference,fromsite, authby, sign_by)
                values('$plantno','$fromsite','$description', '$amount','$status','$timeDate','$preby','$ids','$fromsite','$authby', '$sign_by')";
                 
                $insert2=mysqli_query($config,$sql) or die(mysqli_error($config));

                  $statusMsg="<font color='green'>Saved successfully.</font>";

                // echo "<script>setTimeout(function(){window.location='receiving_report.php?v=$ids';},4200);</script>";
                echo "<script>setTimeout(function(){window.location='requisition_report.php?w=true';},4200);</script>";
            }else{
                $statusMsg = "<font color='red'>File upload failed, please try again.</font>";
            } 
        }
    }

  /*
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf',);

    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["filen"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database

        }else{
            $statusMsg = "<font color='red'>Sorry, there was an error uploading your file.</font>";
        }
    }else{
        $statusMsg = "<font color='red'>Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.</font>";
    }*/


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
#py {
  display:none;
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
        ROCAD
         
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="requisition_report.php">Requisition</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title" ><a href="#"><a href="#"><?php echo "Invoice Upload --- ".$title; ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="form-responsive">
              <h3 class="box-title"><?php  echo $statusMsg; ?></h3>
              <div class="form-group">
                  <img src="pace/requi.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  <form name="add_name" id="add_name" action="" method="post" class="form-style-9 form-responsive" enctype="multipart/form-data">           

<ul>
 
<li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">From:</label>
              <select class="form-control" id="from" name="from" disabled>
              <option selected><?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></option> 
                        </select>
            </div>
            <div class="form-wrapper">
              <label for="">Department:</label>
              <select name="dept" class="form-control" disabled>
                <option selected><?php $siteID=$row_assets['dept'];require '../layout/site.php'; echo $row_dept['dname']; ?></option>
                  
              </select>
              </div>
            </div></li>
              
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Supplier/Account Name:</label>
              <input type="text" min="1" class="form-control" required name="supl">
            </div>
            <div class="form-wrapper">
              <label for="">Supplier/Acct:</label>
              <input type="text" class="form-control" required name="pay_to">
            </div>            
            </div></li>
                      <li>
   
   <div class="form-group"> 
   <div class="form-wrapper">
              <label for="">L.P.O. No:</label>
              <input type="text" min="1" class="form-control" required name="lpo" value="<?php echo $row_assets['reference'];?>">
            </div>
             <div class="form-wrapper">
              <label for="">Invoice Date:</label>
              <input type="datetime-local" class="form-control" required name="invDate">
            </div>
            </div>
            </li> 
             
            <li>            
              <label for="">Required for:</label>
               <textarea class="form-control" disabled><?php echo $row_assets['reqfor'];?></textarea> 
               <input type="hidden" class="form-control" name="description" value="<?php echo $row_assets['reqfor'];?>">        
        </li>
        <li>   
    
    <center><span style="color:red">PAYMENT STATUS:</span><br>
    <div>
       <label for="pst">Paid</label>
      <input type="radio" id="pst" name="pst" value="1" required onclick="document.getElementById('py').style.display = 'block';" />
       <label for="contactChoice3">Unpaid</label>
      <input type="radio" id="pst2" name="pst" value="0" onclick="document.getElementById('py').style.display = 'none';" />
    </div><br><br>
    <div id="py">
      <table class="table table-bordered txt" id="dynamic_field" border="0" >
              <tr>
                <ul class="row'+i+'"><li><div>
       <label for="pst">Cash</label>
      <input type="radio" id="pst" name="cct" value="1" onclick="document.getElementById('chk').style.display = 'none';document.getElementById('acd').style.display = 'none';" />
       <label for="contactChoice3">Check</label>

      <input type="radio" id="pst2" name="cct" value="2" onclick="document.getElementById('chk').style.display = 'block';document.getElementById('acd').style.display = 'none';" />
             <label for="contactChoice3">Transfer</label>

      <input type="radio" id="pst2" name="cct" value="3" onclick="document.getElementById('acd').style.display = 'block';document.getElementById('chk').style.display = 'none';" />
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
    </div>
  </center>
    </li></ul>
        <hr>
        <!-- <h5 class="box-title"><span style="color:#337ab7;"><?php echo "Invoice" ?></span></h5> -->
        <table class="table table-bordered txt" id="dynamic_field" border="0" >
              <tr>
                <ul class="row'+i+'"><li><label for="">Invoice (JPEG,PNG,SVG):</label><input type="file" class="form-control" required name="attachement" title="Upload Invoice"></li><ul>
                <td align="right" colspan="6"></td>
                <tr>
                <!--<td><label for="">Bank Name:</label><input type="text" class="form-control" name="bank_name"></td>-->
                <td><label for="" >Bank Name:</label> 
                <select class="form-control" required name="bank_name"> 
                <option>Nil</option>
                <option>Access Bank Plc</option>
                <option>Ecobank Nigeria</option>
                <option>Heritage Bank</option>
                <option>Jaiz Bank</option>
                <option>Fidelity Bank Plc</option>
                <option>First Bank of Nigeria Limited</option>
                <option>First City Monument Bank Limited</option>
                <option>Guaranty Trust Bank Plc</option>
                <option>Keystone Bank Limited</option>
                <option>Providus Bank</option>
                <option>MoniePoint</option>
                <option>Opay</option>
                <option>Polaris Bank Limited</option>
                <option>Sterling Bank Plc</option>
                <option>Stanbic IBTC Bank</option>
                <option>Standard Chattered Bank Nigeria</option>
                <option>Union/Titan Trust Bank Limited</option>
                <option>United Bank for Africa Plc</option>
                <option>Wema Bank</option>
                <option>Zenith Bank Plc</option>
                </select> 
                </td>
                <td><label for="">Total Amount:</label><input type="number" class="form-control" name="amount"></td>
              </tr>
              </tr>                     
                </tr>
          </table>
                <div align="right">
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/> 
                     </div> 
</form>
</div>
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
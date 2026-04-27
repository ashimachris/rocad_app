<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');  

$preby = $_SESSION['admin_rocad']; 
$msg="";
$dp=false;

if(isset($_POST["sbt"])){

$sortPlant=mysqli_real_escape_string($config,$_POST["sortno"]);

$assets = "SELECT * FROM `assets` where sortno='$sortPlant'";
$as_assets=mysqli_query($config,$assets) or die(mysqli_error($config));
$checkassets = mysqli_num_rows($as_assets);

if($checkassets==1){
    $dp=true;
    $msg="<div class='alert alert-danger'>Duplicate, Plant No. already exist! ($sortPlant)</div>";
    echo "<script>setTimeout(function(){history.back();},2500)</script>";
}

$sql="insert into `assets`(
assetname,model,sortno,year,workstatus,make,chasis,engMake,engineType,
engSerialNo,EngineType_dies_petr,pre_by,status,asset_type,
Gweight,Aconfiguration,Nofaxles,NofTyre,SofTyre,modeltype,
platen,nofb,operV,operA,mobile_device,imei_device,driver,cost
)values(
'".mysqli_real_escape_string($config,$_POST["assetname"])."',
'".mysqli_real_escape_string($config,$_POST["model"])."',
'".mysqli_real_escape_string($config,$_POST["sortno"])."',
'".mysqli_real_escape_string($config,$_POST["year"])."',
'".mysqli_real_escape_string($config,$_POST["wsts"])."',
'".mysqli_real_escape_string($config,$_POST["make"])."',
'".mysqli_real_escape_string($config,$_POST["chasis"])."',
'".mysqli_real_escape_string($config,$_POST["engMake"])."',
'".mysqli_real_escape_string($config,$_POST["engtype"])."',
'".mysqli_real_escape_string($config,$_POST["engsno"])."',
'".mysqli_real_escape_string($config,$_POST["dp"])."',
'$preby',
1,
2,
'".mysqli_real_escape_string($config,$_POST["Gweight"])."',
'".mysqli_real_escape_string($config,$_POST["Aconfiguration"])."',
'".mysqli_real_escape_string($config,$_POST["Nofaxles"])."',
'".mysqli_real_escape_string($config,$_POST["NofTyre"])."',
'".mysqli_real_escape_string($config,$_POST["SofTyre"])."',
'".mysqli_real_escape_string($config,$_POST["modeltype"])."',
'".mysqli_real_escape_string($config,$_POST["plateno"])."',
'".mysqli_real_escape_string($config,$_POST["nofb"])."',
'".mysqli_real_escape_string($config,$_POST["operV"])."',
'".mysqli_real_escape_string($config,$_POST["operA"])."',
'".mysqli_real_escape_string($config,$_POST["mobile_device"])."',
'".mysqli_real_escape_string($config,$_POST["imei_device"])."',
'".mysqli_real_escape_string($config,$_POST["driver"])."',
'".mysqli_real_escape_string($config,$_POST["cost"])."'
)";

if($dp==false){
    $qry=mysqli_query($config,$sql) or die(mysqli_error($config));
}

if(isset($qry) && $qry==1){

$rid = mysqli_insert_id($config);

if (!is_dir("uploads/manual")) {
    mkdir("uploads/manual", 0755, true);
}

/* MANUAL UPLOAD */
if(isset($_FILES['manual_file']) && $_FILES['manual_file']['tmp_name']!=''){
    $upload = $_FILES['manual_file']['tmp_name'];
    $filename = $_FILES['manual_file']['name'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $fname = 'uploads/manual/manual_'.$rid.'.'.$file_ext;

    if(file_exists($fname)){ unlink($fname); }

    if(in_array($file_ext,['jpg','jpeg','png','gif'])){
        if($file_ext=='jpg'||$file_ext=='jpeg'){
            $src=imagecreatefromjpeg($upload);
            imagejpeg($src,$fname,60);
        }elseif($file_ext=='png'){
            $src=imagecreatefrompng($upload);
            imagepng($src,$fname,8);
        }elseif($file_ext=='gif'){
            $src=imagecreatefromgif($upload);
            imagegif($src,$fname);
        }
        imagedestroy($src);
    }else{
        move_uploaded_file($upload,$fname);
    }

    mysqli_query($config,"UPDATE assets SET manual_url=concat('$fname','?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE id='$rid'");
}

/* IMAGE UPLOAD */
if(isset($_FILES['asset_image']) && $_FILES['asset_image']['tmp_name']!=''){
    $upload = $_FILES['asset_image']['tmp_name'];
    $filename = $_FILES['asset_image']['name'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $fname = 'uploads/manual/image_'.$rid.'.'.$file_ext;

    if(file_exists($fname)){ unlink($fname); }

    move_uploaded_file($upload,$fname);

    mysqli_query($config,"UPDATE assets SET asset_image=concat('$fname','?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE id='$rid'");
}

$msg="<div class='alert alert-success'>Data successfully Saved</div>";
echo "<script>setTimeout(function(){window.location='asset.php';},2000)</script>";
}
}
?>

<style>
.form-modern{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}
.form-modern .form-group{
    margin-bottom:20px;
}
.form-modern label{
    font-weight:600;
    margin-bottom:6px;
}
.form-modern input,
.form-modern select{
    border-radius:8px;
    height:42px;
}
.upload-box{
    border:2px dashed #d2d6de;
    padding:20px;
    border-radius:10px;
    text-align:center;
    background:#fafafa;
    transition:0.3s;
}
.upload-box:hover{
    border-color:#3c8dbc;
    background:#f0f8ff;
}
.btn-modern{
    background:#3c8dbc;
    color:#fff;
    padding:10px 30px;
    border-radius:25px;
    border:none;
    font-weight:600;
    transition:0.3s;
}
.btn-modern:hover{
    background:#367fa9;
}
@media(max-width:768px){
    .form-modern{
        padding:20px;
    }
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,0,0,0,$usergroup); ?>
<?php include_once "../layout/left-sidebar.php"; ?>

<div class="content-wrapper">
<section class="content-header">
<h1>ROCAD <small>Asset</small></h1>
</section>

<section class="content">
<div class="row">
<div class="col-md-10 col-md-offset-1">

<div class="box">
<div class="box-body">

<?php echo $msg; ?>

<form method="POST" enctype="multipart/form-data" class="form-modern">

<div class="row">

<div class="col-md-6 form-group">
<label>Description</label>
<input type="text" class="form-control" name="assetname" required>
</div>

<div class="col-md-6 form-group">
<label>Make</label>
<input type="text" class="form-control" name="make" required>
</div>

<div class="col-md-6 form-group">
<label>Model</label>
<input type="text" class="form-control" name="model" required>
</div>

<div class="col-md-6 form-group">
<label>Number Plate</label>
<input type="text" class="form-control" name="plateno" required>
</div>

<div class="col-md-6 form-group">
<label>Year</label>
<input type="text" class="form-control" name="year" required>
</div>

<div class="col-md-6 form-group">
<label>Plant No</label>
<input type="text" class="form-control" name="sortno" required>
</div>

<div class="col-md-6 form-group">
<label>Engine Type</label>
<input type="text" class="form-control" name="engtype" required>
</div>

<div class="col-md-6 form-group">
<label>Chasis No</label>
<input type="text" class="form-control" name="chasis" required>
</div>

<div class="col-md-6 form-group">
<label>Engine Make</label>
<input type="text" class="form-control" name="engMake" required>
</div>

<div class="col-md-6 form-group">
<label>Engine Serial No</label>
<input type="text" class="form-control" name="engsno" required>
</div>

<div class="col-md-6 form-group">
<label>Status</label>
<select class="form-control" name="wsts" required>
<option value="">Select Work Status</option>
<option value="100">Good</option>
<option value="50">Idle</option>
<option value="0">Bad</option>
</select>
</div>

<div class="col-md-6 form-group">
<label>Engine (Diesel/Petrol)</label>
<select class="form-control" name="dp" required>
<option value="">Select Engine Type</option>
<option value="DIESEL">DIESEL</option>
<option value="PETROL">PETROL</option>
<option value="N/A">N/A</option>
</select>
</div>

<div class="col-md-6 form-group">
<label>Upload Manual</label>
<div class="upload-box">
<input type="file" name="manual_file" class="form-control" required>
</div>
</div>

<div class="col-md-6 form-group">
<label>Upload Asset Image</label>
<div class="upload-box">
<input type="file" name="asset_image" class="form-control" required>
</div>
</div>

</div>

<div class="text-center">
<button name="sbt" class="btn-modern">SUBMIT</button>
</div>

</form>

</div>
</div>

</div>
</div>
</section>
</div>

<?php include_once "../layout/copyright.php"; ?>
<?php include_once "../layout/right-sidebar.php"; ?>
<div class="control-sidebar-bg"></div>
</div>

<?php include_once "../layout/footer.php" ?>
</body>

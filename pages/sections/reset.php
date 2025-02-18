<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "profile";
require_once('../../db_con/config.php');
include_once "../layout/header.php";
$id=$_SESSION['admin_rocad'];
$staff = "SELECT * FROM `admin` where id=$id";
$as_staff=mysqli_query($config,$staff) or die(mysqli_error($config));
$row_staff=mysqli_fetch_assoc($as_staff);
$tenDgt = rand(1000000000,9999999999);
/////////////////////////
$statusMsg = '';
if(isset($_POST["sbt"])){
$targetDir = "../../user_img/";
$fileName = basename($_FILES["filen"]["name"]);
//$fileName = $ids;
$NewName=$tenDgt.".jpg";
$targetFilePath = $targetDir . $NewName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
////////////////////Vars 

////////////////////// 
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["filen"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
$insert = ("UPDATE `admin` SET `images`='$tenDgt' WHERE id=$id");
              mysqli_query($config,$insert) or die(mysqli_error($config));
            if($insert){
                $statusMsg = "<font color='green'>The file (".$fileName. ") has been uploaded successfully.</font>";
            echo "<script>setTimeout(function(){window.location='profile.php';},4200);</script>";
            }else{
                $statusMsg = "<font color='red'>File upload failed, please try again.</font>";
            } 
        }else{
            $statusMsg = "<font color='red'>Sorry, there was an error uploading your file.</font>";
        }
    }else{
        $statusMsg = "<font color='red'>Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.</font>";
    }
}else{
    $statusMsg = "";
// Display status message
//echo $statusMsg;
}
?>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <?php include_once("profile/reset_pass.php") ?>
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="profile/script.js"></script>
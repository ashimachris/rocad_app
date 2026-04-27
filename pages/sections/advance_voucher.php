<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

  $active_menu = "data_tables";

  include_once "../layout/header.php";

 require_once('../../db_con/config.php');

  $mail=false;

$preby =$_SESSION['admin_rocad'];

$msg="";

$timeDate=date('Y-m-d H:i:s');

$tenDgt = rand(1000000000,9999999999);


$qryasset="SELECT * FROM assets where status=1";

$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));

$site = "SELECT * FROM `rocad_site` where sitename!='' and status=1 order by sitename Asc";

$as_site=mysqli_query($config,$site) or die(mysqli_error($config));

if(isset($_POST["sbt"])){
  $sign_by = mysqli_real_escape_string($config,$_POST["sign_by"]);

 
 /////////////////////////////

$sql="insert into `storeloadingdetails`(fromsite,dept,reqfor,PlantNo,preby,reference,status,note,title,time_date,totalvalue,supl,pay_to,bank_name,qty)values('".mysqli_real_escape_string($config,$_POST["from"])."','2023','".mysqli_real_escape_string($config,$_POST["reqfor"])."','".mysqli_real_escape_string($config,$_POST["plantno"])."','$preby','$tenDgt',0,'Advance Voucher','Advance Voucher','".mysqli_real_escape_string($config,$_POST["dt"])."','".mysqli_real_escape_string($config,$_POST["ttlv"])."','".mysqli_real_escape_string($config,$_POST["supl"])."','".mysqli_real_escape_string($config,$_POST["pay_to"])."','".mysqli_real_escape_string($config,$_POST["bank_name"])."','".mysqli_real_escape_string($config,$_POST["qty"])."')";

       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));

    if($insert==1){

     $liter=$_POST['liter'];

     $mail=true;

     // save invoice 

         $rid = mysqli_insert_id($config);
      // ================= MULTIPLE FILE UPLOAD SECTION =================
      if (isset($_FILES['attachement']) && !empty($_FILES['attachement']['name'][0])) {

          // Ensure upload folder exists
          if (!is_dir("uploads/")) {
              mkdir("uploads/", 0755, true);
          }

          $uploaded_files = []; // store uploaded file paths

          foreach ($_FILES['attachement']['tmp_name'] as $key => $tmp_name) {

              if ($tmp_name == '') {
                  continue;
              }

              $filename  = $_FILES['attachement']['name'][$key];
              $file_ext  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
              $new_name  = $rid . "_" . $key . "." . $file_ext; // unique name per file
              $dir_path  = "uploads/" . $new_name;

              // Remove file if already exists
              if (is_file($dir_path)) {
                  unlink($dir_path);
              }

              $uploaded = false;

              // If file is an image ? compress
              if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {

                  if ($file_ext === 'jpg' || $file_ext === 'jpeg') {
                      $source_image = imagecreatefromjpeg($tmp_name);
                      imagejpeg($source_image, $dir_path, 25);
                  } elseif ($file_ext === 'png') {
                      $source_image = imagecreatefrompng($tmp_name);
                      imagepng($source_image, $dir_path, 4);
                  } elseif ($file_ext === 'gif') {
                      $source_image = imagecreatefromgif($tmp_name);
                      imagegif($source_image, $dir_path);
                  }

                  if (isset($source_image)) {
                      imagedestroy($source_image);
                  }

                  $uploaded = file_exists($dir_path);

              } else {
                  // For PDF, Word, Excel, TXT, CSV, etc.
                  $uploaded = move_uploaded_file($tmp_name, $dir_path);
              }

              if ($uploaded) {
                  $uploaded_files[] = $dir_path;
              }
          }

          // Save all uploaded file paths as comma-separated values
          if (!empty($uploaded_files)) {
              $files_string = implode(",", $uploaded_files);

              mysqli_query(
                  $config,
                  "UPDATE `storeloadingdetails`
                  SET sign_by='$sign_by',
                      invoice = concat('{$files_string}','?v=',unix_timestamp(CURRENT_TIMESTAMP))
                  WHERE id = '$rid'"
              );
          }
      }

      $msg = "<font color='green'>Request sent successfully.</font>";

      echo "<script>
              setTimeout(function(){
                  window.location='advance_voucher.php';
              },4200);
            </script>";

  } 

}

$dt=(rand(10,100));

if($mail){

    $prebyID = $preby;
    require '../layout/preby.php';

    $siteID = $_POST["from"];
    require '../layout/site.php';

    // Prepare a professional email subject
    $subject = "Advance Voucher Request on ($timeDate) - Ref: " . $dt . " | Prepared by " . $row_preby['fullname'];

    // Prepare a professional email body
    $msgT = "
    Dear Team,

    A new advance voucher request has been submitted and is pending review. Please find the details below:

    --------------------------------------------------
    Prepared By : " . $row_preby['fullname'] . "
    From (Site)  : " . $row_site['sitename'] . "
    Required For : " . $_POST["reqfor"] . "
    Amount       : " . $_POST["ttlv"] . "
    Time & Date  : " . $timeDate . "
    Status       : Pending Approval
    Reference ID : " . $dt . "
    --------------------------------------------------

    You may log into the ROCAD Management Portal to review and take action:

    https://app.rocad.com

    Thank you.

    Regards,  
    ROCAD Nigeria Ltd.
    ";

    $msgMail = wordwrap($msgT, 70);

    // Send email
    $to = "ronaldo@rocad.com, rene@rocad.com, tamer@rocad.com, umar@rocad.com, deleakintayo@rocad.com";

    // Email headers with From name as subject
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    $headers .= "From: \"" . addslashes($subject) . "\" <no-reply@rocad.com>\r\n";

    mail($to, $subject, $msgMail, $headers);
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

        <li class="active" ><a href="advance_voucher.php">Advance Voucher</a></li>

        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header"> 

              <h3 class="box-title"><a href="#"><a href="advance_voucher.php"><?php echo "Advance Voucher" ?></a></h3>

            </div>

            <!-- /.box-header -->

            <div class="box-body">

              <h3 class="box-title"><?php  echo $msg; ?></h3>

              <div class="form-group">

                  <img src="pace/adv.jpg" style="height:25%; padding-top:50px;" class="hidden-mobile">

                <div class="form-wrapper">

                  <form name="add_name" id="add_name" action="" method="post"  class="form-style-9" enctype="multipart/form-data">           
<ul>


<li>

   

              <label for="">Site:</label>

              <select class="form-control" id="from" required name="from">                        

                          <option value="" selected>Select Location(Site)</option>

                          <?php while($row_site=mysqli_fetch_assoc($as_site)){?>

                          <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?></option>

                          <?php }?>

                        </select>

             </li>

              <li>            

               

              <label for="">Date:</label>

             <input type="datetime-local" name="dt" class="form-control" ?>

                     

        </li>

                         

                     <li>            

              <label for="">Describe Item:</label>

               <textarea class="form-control" required name="reqfor"></textarea>         

        </li>

        <li>

          <label for="">A/C Code:</label>
          <select class="form-control select2" name="plantno" >
            <option value="" selected>::Select Plant No::</option>
            <option value="0">N/A</option>
            <?php while($row_asset=mysqli_fetch_assoc($asset)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select>

        </li>


        <li>            

              <label for="">Signed by:</label>

             <input type="text" name="sign_by" class="form-control"  required>       

         </li>

         <li>
          <label for="">Supplier/Account Name:</label>
          <input type="text" class='form-control account_name' required name="supl" id="supl">
        </li>  

        <div id='pageloader' style='display:none'>
          <center><img id='uploadimage2' src='loader.gif' style='width:10%;height:10%;'></center>
        </div>

          <div class='row' id='userDataResponse' style='width:40rem'></div>

          <!--start beneficiries bank data responses  -->
        <input type='hidden' class='beneficiary_id'  name='beneficiary_id'   value='' required>
        <input type="hidden" class='account_name_load' required name="supl" id="supl">
        <input type="hidden" class='account_number_load' required name="pay_to" id="pay_to">
        <input type='hidden' class='bank_name_load'  name='bank_name'   value=''>

        <div class="account-details" style='display:none'>
         <li>
          <label for="">Account Number:</label>
          <input type="text" class='form-control account_number' required name="pay_to" id="pay_to">
        </li>
          
          <li >
              <label for="" >Bank Name:</label> 
              <input type='text' class='form-control bank_name' required name='bank_name'   value=''>
          </li>
        </div>
          <!-- end beneficiries bank data responses  -->
         
        <div class='col-lg-12' >
            <label class='btn btn-default' id='changeBeneficiary' style='display:none'>Change Beneficiary</label>
         </div>

         
         <li><label for="">Quantity:</label><input type="number" min="0" class="form-control" name="qty"></li>

         <li><label for="">Amount:</label><input type="number" min="1" class="form-control" required name="ttlv" ></li>

         <li class="mb-3">
              <label for="attachement">
                  Upload Voucher 
                  <small class="text-muted">
                      (JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX)
                  </small>
              </label>

              <input 
                  type="file"
                  id="attachement"
                  name="attachement[]"
                  class="form-control form-control-sm form-control-border"
                  multiple
                  accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx"
                  onchange="displayImg(this)"
              >

              <div id="uploadedFilesPreview" class="mt-2"></div>
          </li>


         <div class="row" style="display:none" id="toggleDisplay">
          <div class="form-group col-md-12 text-center">
              <img src="" alt="Invoice" id="invoiceImg" class="border border-gray img-thumbnail">
          </div>
        </div>


   </ul>
                <div align="right" style="display:none" class="submit-button">

                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/> 

                </div> 

</form>

</div>

</div>
 
<script>
$(document).ready(function() {

    // Store selected files globally
    let selectedFiles = [];

    // File selection handler
    window.displayImg = function(input) {

        if (!input.files || input.files.length === 0) {
            return;
        }

        const previewContainer = $('#uploadedFilesPreview');

        // Add newly selected files
        Array.from(input.files).forEach(file => {

            // Prevent duplicate file names (optional safety)
            const exists = selectedFiles.some(f => 
                f.name === file.name && f.size === file.size
            );

            if (!exists) {
                selectedFiles.push(file);
            }
        });

        renderPreview(previewContainer);

        // Reset input so same file can be selected again
        input.value = "";
    };


    function renderPreview(container) {

        container.empty();

        selectedFiles.forEach((file, index) => {

            const fileExt = file.name.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);

            const row = $('<div>', {
                style: `
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    padding:8px;
                    border:1px solid #ddd;
                    border-radius:6px;
                    margin-bottom:8px;
                    background:#f9f9f9;
                `
            });

            const left = $('<div>', {
                style: 'display:flex; align-items:center; gap:10px;'
            });

            const previewImg = $('<img>', {
                style: 'width:60px; height:60px; object-fit:cover; border-radius:4px;'
            });

            if (isImage) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.attr(
                    'src',
                    'https://static.vecteezy.com/system/resources/thumbnails/020/522/575/small/simple-document-icon-png.png'
                );
            }

            const fileName = $('<span>', {
                text: file.name,
                style: 'font-size:13px;'
            });

            const deleteBtn = $('<button>', {
                type: 'button',
                class: 'btn btn-danger btn-sm',
                text: 'Delete',
                click: function() {
                    selectedFiles.splice(index, 1);
                    renderPreview(container);
                }
            });

            left.append(previewImg);
            left.append(fileName);

            row.append(left);
            row.append(deleteBtn);

            container.append(row);
        });
    }


    // IMPORTANT: Before form submit, re-attach files
    $('#add_name').on('submit', function() {

        const dt = new DataTransfer();

        selectedFiles.forEach(file => {
            dt.items.add(file);
        });

        document.getElementById('attachement').files = dt.files;
    });


    // Select2 initialization
    $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
    });

});
</script>

<!-- load logic for beneficiaries   -->
<?php require './beneficiaries_footer.php'; ?>

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

        </div>

        <!-- /.col -->

      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

    </div><!-- /.content-wrapper -->

    <?php include_once "../layout/copyright.php"; ?>

    <?php include_once "../layout/right-sidebar.php"; ?>

    <div class="control-sidebar-bg"></div>

  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>

<script src="js/table.js"></script>
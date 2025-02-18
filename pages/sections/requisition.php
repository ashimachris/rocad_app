<?php

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set active menu for UI purposes
$active_menu = "data_tables";

// Include header layout and database configuration files
include_once "../layout/header.php";
require_once('../../db_con/config.php');

// Initialize email flag
$mail = false;

// Retrieve the session's admin record for prepared by field
$preby = $_SESSION['admin_rocad'];

// Initialize variables for messages and time stamp
$msg = "";
$timeDate = date('Y-m-d H:i:s');
$tenDgt = rand(1000000000, 9999999999); // Generate a unique 10-digit reference

// Get asset and site data from the database for form use
$assetname = $_POST["qty"];
$num = count((is_countable($assetname) ? $assetname : []));
$qryasset = "SELECT * FROM assets where status=1 order by sortno";
$asset = mysqli_query($config, $qryasset) or die(mysqli_error($config));
$site = "SELECT * FROM `rocad_site` where sitename!='' and status=1 order by sitename Asc";
$as_site = mysqli_query($config, $site) or die(mysqli_error($config));

// Process form submission for requisition
if (isset($_POST["sbt"])) {
    $sign_by = mysqli_real_escape_string($config, $_POST["sign_by"]);

    // Loop through each quantity in the form to insert into database
    for ($i = 0; $i < $num; $i++) {
        if (trim($_POST["qty"][$i] != '')) {
            // SQL query to insert requisition details into 'storeloadingdetails' table
            $sql = "INSERT INTO `storeloadingdetails` (fromsite, dept, reqfor, qty, PlantNo, qtyw, infoD, preby, reference, status, note, title, time_date) 
                    VALUES ('" . mysqli_real_escape_string($config, $_POST["from"]) . "', '" . mysqli_real_escape_string($config, $_POST["dept"]) . "', '" . mysqli_real_escape_string($config, $_POST["reqfor"]) . "', '" . mysqli_real_escape_string($config, $_POST["qty"][$i]) . "', '" . mysqli_real_escape_string($config, $_POST["plantno"][$i]) . "', '" . mysqli_real_escape_string($config, $_POST["qtyw"][$i]) . "', '" . mysqli_real_escape_string($config, $_POST["info"][$i]) . "', '$preby', '$tenDgt', 0, 'Requisition', 'Requisition', '" . mysqli_real_escape_string($config, $_POST["dt"]) . "')";
            
            // Execute the insert query
            $insert = mysqli_query($config, $sql) or die(mysqli_error($config));

            // Check if insert was successful
            if ($insert == 1) {
                // Store additional data and prepare for email notification
                $liter = $_POST['liter'];
                $mail = true;

                // Retrieve last inserted ID to use for file naming
                $rid = mysqli_insert_id($config);

                $qry = mysqli_query($config, "UPDATE `storeloadingdetails` SET sign_by='$sign_by' WHERE id = '$rid' ");

                /*
                // Handle file upload if an attachment is provided
                if (isset($_FILES['attachement']) && $_FILES['attachement']['tmp_name'] != '') {
                    if (!is_dir("uploads/invoices")) {
                        mkdir("uploads/invoices", 0755, true);
                    }

                    $upload = $_FILES['attachement']['tmp_name'];
                    $filename = $_FILES['attachement']['name'];
                    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Extract file extension
                    $fname = 'uploads/invoices/' . $rid . '.' . $file_ext; // Custom filename with unique ID
                    $dir_path = $fname;

                    // Remove existing file with the same name, if exists
                    if (is_file($dir_path)) {
                        unlink($dir_path);
                    }

                    $uploaded_img = false; // Initialize upload success flag

                    // Handle image compression for supported formats
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        if ($file_ext === 'jpg' || $file_ext === 'jpeg') {
                            $source_image = imagecreatefromjpeg($upload);
                            imagejpeg($source_image, $dir_path, 60); // Adjust quality as needed
                        } elseif ($file_ext === 'png') {
                            $source_image = imagecreatefrompng($upload);
                            imagepng($source_image, $dir_path, 8); // Compression for PNG
                        } elseif ($file_ext === 'gif') {
                            $source_image = imagecreatefromgif($upload);
                            imagegif($source_image, $dir_path);
                        }

                        imagedestroy($source_image); // Free up memory
                        $uploaded_img = file_exists($dir_path);
                    } else {
                        // If not an image, move the uploaded file as-is
                        $uploaded_img = move_uploaded_file($upload, $dir_path);
                    }

                    // Update database with the file path if upload is successful
                    if ($uploaded_img) {
                        $qry = mysqli_query($config, "UPDATE `storeloadingdetails` SET sign_by='$sign_by', invoice = concat('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '$rid' ");
                    }
                }*/

                // Set success message and redirect with delay
                $msg = "<font color='green'>Request sent successfully.</font>";
                echo "<script>setTimeout(function(){window.location='requisition.php';},4200);</script>";
            }
        }
    }
}

// Send notification email if mail flag is set
if ($mail) {
    $prebyID = $preby;
    require '../layout/preby.php'; // Load prepared-by details

    $siteID = $_POST["from"];
    require '../layout/site.php'; // Load site details

    // Prepare email message
    $msgT = "Prepared By: " . $row_preby['fullname'] . "\nFrom: " . $row_site['sitename'] . "\nAsset: " . $row_asset['plantno'] . "\nRequired for: " . $_POST["reqfor"] . "\nTime & Date: " . $timeDate . "\nStatus: Pending.\nLogin to website:\nhttps://app.rocad.com";
    $msgMail = wordwrap($msgT, 70);

    // Send email notification to specified recipients
    mail("ronaldo@rocad.com,rene@rocad.com,tamer@rocad.com,umar@rocad.com,deleakintayo@rocad.com", "Requisition (" . ($row_preby['fullname']) . ") Ref " . $tenDgt, $msgMail);
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

  <!-- Page-level CSS and JavaScript libraries -->

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <div class="wrapper">

    <!-- Include top menu and left sidebar -->
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper: Contains page content -->
    <div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>ROCAD</h1>
        <ol class="breadcrumb">
          <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Administration</a></li>
          <li class="active"><a href="#">Requisition</a></li>
          <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">

              <!-- Header of the form box -->
              <div class="box-header"> 
                <h3 class="box-title"><a href="#"><a href="#"><?php echo "Requisition" ?></a></h3>
              </div>

              <!-- Box body: Contains the form content -->
              <div class="box-body">
                <h3 class="box-title"><?php echo $msg; ?></h3>
                <div class="form-group">
                  <img src="pace/requi.png" style="height:25%; padding-top:50px;" class="hidden-mobile">

                  <!-- Form wrapper -->
                  <div class="form-wrapper">
                    <!-- Requisition form -->
                    <form name="add_name" id="add_name" action="" method="post" class="form-style-9" enctype="multipart/form-data">
                      <ul>

                        <!-- From Location Selection -->
                        <li>
                          <div class="form-group">
                            <div class="form-wrapper">
                              <label for="">From:</label>
                              <select class="form-control" id="from" required name="from">
                                <option value="" selected>Select Location(Site)</option>
                                <?php while($row_site=mysqli_fetch_assoc($as_site)){?>
                                  <option value="<?php echo $row_site['id']; ?>">
                                    <?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>

                            <!-- Department Selection -->
                            <div class="form-wrapper">
                              <label for="">Department:</label>
                              <select name="dept" class="form-control" required>
                                <option value="1">Site</option>
                                <option value="2">Plant</option>
                                <option value="3">Engineering</option>                  
                                <option value="4">Store In</option>
                                <option value="40">Store Out</option>
                                <option value="5">Mechanical</option>
                                <option value="6">Others</option>
                              </select>
                            </div>
                          </div>
                        </li>

                        <!-- Date Input -->
                        <li>
                          <label for="">Date:</label>
                          <input type="datetime-local" name="dt" class="form-control" ?>
                        </li>

                        <!-- Hidden Row for Pre-invoice Image Display -->
                        <div class="row" style="display:none" id="toggleDisplay">
                          <div class="form-group col-md-12 text-center">
                            <img src="" alt="Invoice" id="invoiceImg" class="border border-gray img-thumbnail">
                          </div>
                        </div>

                        <!-- Signed By Input -->
                        <li>
                          <label for="">Signed by:</label>
                          <input type="text" name="sign_by" class="form-control" required>
                        </li>

                        <!-- Required For Input -->
                        <li>
                          <label for="">Required for:</label>
                          <textarea class="form-control" required name="reqfor"></textarea>
                        </li>

                        <!-- Pre-invoice Upload 
                        <li>
                          <label for="">Pre-invoice:</label>
                          <input type="file" id="attachement" name="attachement" class="form-control form-control-sm form-control-border" onchange="displayImg(this,$(this))" required>
                        </li> -->
                      </ul>

                      <!-- Items Details Header -->
                      <hr>
                      <h5 class="box-title"><span style="color:#337ab7;"><?php echo "DETAILS OF ITEMS" ?></span></h5>

                      <!-- Dynamic Items Table -->
                      <table class="table table-bordered txt" id="dynamic_field" border="0">
                        <tr>
                          <!-- Add Items Button -->
                          <td align="left" colspan="6">
                            <button type="button" name="add" id="add" class="btn btn-success">Add Items</button>
                          </td>
                        </tr>
                      </table>  

                    
                    
                    <!--
                        <li>
                            <div class="col-lg-12">
                                <div class="input-group m-bot15">
                                    <span class="input-group-addon btn-success">Select Here</span>
                                    <select class="form-control m-bot15 select2" required name="account_type" id="categoryId">
                                         <option value="">N/A</option>
                              
                                          
                                    </select>
                                </div>
                            </div>
                        </li>-->

                      <!-- Submit Button -->
                      <div align="right">
                        <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/>
                      </div>

                    </form>
                  </div>
                </div>
              </div>

 <script>             
              // Function to display preview or icon based on uploaded file type
function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        // Get file extension
        var file = document.querySelector('#attachement').value;
        var extension = file.split('.').pop().toLowerCase();
        var reader = new FileReader();

        reader.onload = function (e) {
            // Check if the file is a document type and set icon if true
            if (['pdf', 'docx', 'docs', 'txt', 'csv', 'xlsx'].includes(extension)) {
                $('#invoiceImg').attr('src', 'https://static.vecteezy.com/system/resources/thumbnails/020/522/575/small/simple-document-icon-png.png');
            } else {
                // Otherwise, display the image preview
                $('#invoiceImg').attr('src', e.target.result);
            }
        };

        reader.readAsDataURL(input.files[0]);
        $('#toggleDisplay').show(); // Show the image preview section
    } else {
        $('#invoiceImg').attr('src', ''); // Clear image if no file selected
    }
}

// Initialize document functions when ready
$(document).ready(function() {

    $('#submit').hide(); // Hide submit button initially
    var itemCount = 1;

    // Add new item row on 'Add Items' button click
    $('#add').click(function() {
        $('#submit').show(); // Show submit button once items are added
        itemCount++;

        $('#dynamic_field').append(`
            <ul class="row${itemCount}">
                <label for="" name="remove" id="${itemCount}" class="btn_remove" style="float:right;color:red;cursor:pointer">X</label>
                <li>
                    <div class="form-group">
                        <div class="form-wrapper">
                            <label for="">Quantity:</label>
                            <input type="number" min="1" class="form-control" required name="qty[]" id="qty">
                        </div>|
                        <div class="form-wrapper">
                            <label for="">A/C Code:</label>
                           
                            <select class="form-control m-bot15 select2" required name="plantno[]" >
                                <option value="" selected>::Select Plant No::</option>
                                <option value="0">N/A</option>
                                <?php while($row_asset = mysqli_fetch_assoc($asset)) { ?>
                                    <option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </li>
                <li>
                    <label for="">Description:</label>
                    <textarea class="form-control" required name="info[]"></textarea>
                </li>
            </ul>
        `);
         // Reinitialize select2 for the newly added select elements
        $('.select2').select2({
            placeholder: "Please select here",
            width: "100%"
        });
    });

    // Remove specific item row on 'X' button click
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('.row' + button_id).remove(); // Remove the row associated with this button
    });

    // Update total value when quantity or amount inputs change
    $(".input").keyup(function() {
        var amount = +$("#amount").val();
        var quantity = +$("#qty").val();
        $("#ttl").val(amount + quantity); // Calculate and update total
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


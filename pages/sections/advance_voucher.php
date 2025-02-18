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
         if (isset($_FILES['attachement']) && $_FILES['attachement']['tmp_name'] != '') {
              if (!is_dir("uploads/")) {
                  mkdir("uploads/", 0755, true);
              }

              $upload = $_FILES['attachement']['tmp_name'];
              $filename = $_FILES['attachement']['name'];
              $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension
              $fname = 'uploads/' . $rid . '.' . $file_ext; // Customize the new filename
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
              $qry = mysqli_query($config, "UPDATE `storeloadingdetails` set sign_by='$sign_by', invoice = concat('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '$rid' ");
              }
          }
          

  $msg="<font color='green'>Request sent successfully.</font>";
  
   echo    "<script>setTimeout(function(){window.location='advance_voucher.php';},4200);</script>";

  } 

}

$dt=(rand(10,100));

if($mail){

  $prebyID=$preby;require '../layout/preby.php';

  $siteID=$_POST["from"];require '../layout/site.php';



  $msgT="Prepared By:".$row_preby['fullname']."\nFrom:".$row_site['sitename']."\nRequired for: ".$_POST["reqfor"]."\nAmount: ".$_POST["ttlv"]."\nTime & Date:".$timeDate."\nStatus: Pending."."\nLogin to website:\nhttps://app.rocad.com";

  $msgMail = wordwrap($msgT,70);



// send email

mail("ronaldo@rocad.com,rene@rocad.com,tamer@rocad.com,umar@rocad.com,deleakintayo@rocad.com","Advance_voucher (".($row_preby['fullname']).$dt.")",$msgMail);

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

         <li><label for="">Supplier/Account Name:</label><input type="text" class="form-control" required name="supl" id="supl"></li>  

         <!--<li><label for="">Bank Name:</label><input type="text" class="form-control" name="bank_name"></li> -->
         <li><label for="" >Bank Name:</label> 
                <select class="form-control" required name="bank_name"> 
                <option>N/A</option>
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
          </li>
         <li><label for="">Account Number:</label><input type="text" class="form-control" required name="pay_to" id="pay_to"></li>

         <li><label for="">Quantity:</label><input type="number" min="0" class="form-control" name="qty"></li>

         <li><label for="">Amount:</label><input type="number" min="1" class="form-control" required name="ttlv" ></li>

         <li>
              <label for="">Upload Voucher:</label>

            <input type="file" id="attachement" name="attachement" class="form-control form-control-sm form-control-border" onchange="displayImg(this,$(this))" required>

         </li>



         <div class="row" style="display:none" id="toggleDisplay">
          <div class="form-group col-md-12 text-center">
              <img src="" alt="Invoice" id="invoiceImg" class="border border-gray img-thumbnail">
          </div>
        </div>


   </ul>
                <div align="right">

                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/> 

                     </div> 

</form>

</div>

</div>

 
 <script>
  function displayImg(input,_this) {
        if (input.files && input.files[0]) {
            var file = document.querySelector('#attachement').value;
            var extension = file.split('.').pop();
          var reader = new FileReader();
         
          reader.onload = function (e) {
                if(extension=='pdf' || extension=='docx'||extension=='docs' || extension=='txt' || extension=='csv' || extension=='xlsx'){
                    $('#invoiceImg').attr('src', 'https://static.vecteezy.com/system/resources/thumbnails/020/522/575/small/simple-document-icon-png.png');
                }else{
                    $('#invoiceImg').attr('src', e.target.result);
                }
          }

          reader.readAsDataURL(input.files[0]);
           $('#toggleDisplay').show();
      }else{
            $('#invoiceImg').attr('src', '');
        }
  }

  // Initialize document functions when ready
$(document).ready(function() {

  $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
    })
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
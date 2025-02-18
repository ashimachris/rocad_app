<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');

$preby = $_SESSION['admin_rocad'];
$msg = "";
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000, 9999999999);
//$Authby = "SELECT * FROM `admin` where fullname!='' order by fullname Asc";
//$as_Authby = mysqli_query($config, $Authby) or die(mysqli_error($config));

$site = "SELECT * FROM `rocad_site` where sitename!='' and status=1 order by sitename Asc";
$as_site = mysqli_query($config, $site) or die(mysqli_error($config));

$assets_oil = "SELECT SUM(CASE WHEN cat='Diesel' THEN ltr END) as diesel,
                  SUM(CASE WHEN cat='Petrol' THEN ltr END) as petrol,
                  SUM(CASE WHEN cat='Engine Oil' THEN ltr END) as engineoil,
                  SUM(CASE WHEN cat='Hydraulic Oil' THEN ltr END) as hydraulicoil
                                    
  FROM `oil_stock` where ltr>0";
$oil_assets = mysqli_query($config, $assets_oil) or die(mysqli_error($config));
$row_oil_count = mysqli_fetch_assoc($oil_assets);

$assets = "SELECT count(CASE WHEN cat='Diesel' THEN ltr END) as diesel,
                  count(CASE WHEN cat='Petrol' THEN ltr END) as petrol,
                  count(CASE WHEN cat='Engine Oil' THEN ltr END) as engineoil,
                  count(CASE WHEN cat='Hydraulic Oil' THEN ltr END) as hydraulicoil
                                   
  FROM `oil_stock_history` where ltr>0";

$as_assets = mysqli_query($config, $assets) or die(mysqli_error($config));

$row_oil = mysqli_fetch_assoc($as_assets);

//$checkassets = mysqli_num_rows($as_assets);

if (isset($_POST["sbt"])) {

  $targetDir = "uploads/";
  $fileName = basename($_FILES["filen"]["name"]);
  //$fileName = $ids;
  $NewName = mysqli_real_escape_string($config, $_POST["ltrw"]) . ".jpg";
  $targetFilePath = $targetDir . $NewName;
  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
  ////////////////////Vars
  $InvDate = $row_assets['time_date'];
  $lpo = $row_assets['reference'];;
  //$title="DIESEL";
  ///////////////////
  $ltrup = mysqli_real_escape_string($config, $_POST["ltr"]);
  $catup = mysqli_real_escape_string($config, $_POST["cat"]);
  $siteup = mysqli_real_escape_string($config, $_POST["site"]);
  $tempDate = mysqli_real_escape_string($config, $_POST["dt"]);
  // Allow certain file formats
  $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
  if (in_array($fileType, $allowTypes)) {
    // Upload file to server
    if (move_uploaded_file($_FILES["filen"]["tmp_name"], $targetFilePath)) {
      // Insert image file name into database
      $insert = ("INSERT into invoices (file_name,reference,ispaid,uploadby,title,lpo,uploaded_on) VALUES ('" . $NewName . "','" . mysqli_real_escape_string($config, $_POST["ltrw"]) . "',0,'" . $preby . "','" . $catup . "','" . mysqli_real_escape_string($config, $_POST["ltrw"]) . "','$timeDate')");
      mysqli_query($config, $insert) or die(mysqli_error($config));
      $sql2 = "UPDATE `oil_stock` SET `ltr`=`ltr`+$ltrup,`preby`='$preby',`time_date`='$tempDate' WHERE cat='$catup' and site='$siteup'";
      mysqli_query($config, $sql2) or die(mysqli_error($config));
      $sql3 = "UPDATE `oil_stock_history` SET `invoiceUPD`=1 WHERE reference=$tenDgt";
      mysqli_query($config, $sql3) or die(mysqli_error($config));
      $sql = "insert into `oil_stock_history`(`cat`, `ltr`, `ltrp`, `ltrw`, `preby`, `reference`,`time_date`,`site`,ispaid)values('" . mysqli_real_escape_string($config, $_POST["cat"]) . "','" . mysqli_real_escape_string($config, $_POST["ltr"]) . "','" . mysqli_real_escape_string($config, $_POST["ltrp"]) . "','" . mysqli_real_escape_string($config, $_POST["ltrw"]) . "','$preby','$tenDgt','$tempDate','" . mysqli_real_escape_string($config, $_POST["site"]) . "',0)";
      mysqli_query($config, $sql) or die(mysqli_error($config));

      /* if ($insert) { 

       $amount = mysqli_real_escape_string($config, $_POST["amount"]);
        $description = mysqli_real_escape_string($config, $_POST["ltrw"]);
        $authby = mysqli_real_escape_string($config, $_POST["authby"]);

        $sql = "insert into daily_expenses_reports(site, description, amount,time_date, preby, fromsite,authby)
                             values('$siteup','$description', '$amount','$tempDate','$preby','$siteup','$authby')";


        $insert2 = mysqli_query($config, $sql) or die(mysqli_error($config));

        $msg = "<font color='green'>Data successfully Saved.</font>";
        echo    "<script>setTimeout(function(){window.location='diesel_stock.php';},1200);</script>";
      } else {
       echo "error";
        die;
      } */
    }
   }
 } 

$num = $row_oil_count['diesel'];
switch ($num) { /////Desel % Bar
  case 0:
    $barD = "red";
    $wd = 1;
    break;
  case ($num >= 0 && $num <= 3000):
    $barD = "red";
    $wd = 15;
    break;
  case ($num >= 3001 && $num <= 10000):
    $barD = "yellow";
    $wd = 25;
    break;
  case ($num >= 10001 && $num <= 15000):
    $barD = "aqua";
    $wd = 75;
    break;
  case ($num >= 15001 && $num <= 20000):
    $barD = "green";
    $wd = 100;
    break;
  default:
    $barD = "green";
    $wd = 100;
}
$numP = $row_oil_count['petrol'];
switch ($numP) { /////Desel % Bar
  case 0:
    $barP = "red";
    $wp = 1;
    break;
  case ($numP >= 0 && $numP <= 3000):
    $barP = "red";
    $wp = 15;
    break;
  case ($numP >= 3001 && $numP <= 10000):
    $barP = "yellow";
    $wp = 25;
    break;
  case ($numP >= 10001 && $numP <= 15000):
    $barP = "aqua";
    $wp = 75;
    break;
  case ($numP >= 15001 && $numP <= 20000):
    $barP = "green";
    $wp = 100;
    break;
  default:
    $barP = "green";
    $wp = 100;
}
$numE = $row_oil_count['engineoil'];
switch ($numE) { /////Desel % Bar
  case 0:
    $barE = "red";
    $we = 1;
    break;
  case ($numE >= 0 && $numE <= 3000):
    $barE = "red";
    $we = 15;
    break;
  case ($numE >= 3001 && $numE <= 10000):
    $barE = "yellow";
    $we = 25;
    break;
  case ($numE >= 10001 && $numE <= 15000):
    $barE = "aqua";
    $we = 75;
    break;
  case ($numE >= 15001 && $numE <= 20000):
    $barE = "green";
    $we = 100;
    break;
  default:
    $barE = "green";
    $we = 100;
}
$numH = $row_oil_count['hydraulicoil'];
switch ($numH) { /////Desel % Bar
  case 0:
    $barH = "red";
    $wh = 1;
    break;
  case ($numH >= 0 && $numH <= 3000):
    $barH = "red";
    $wh = 15;
    break;
  case ($numH >= 3001 && $numH <= 10000):
    $barH = "yellow";
    $wh = 25;
    break;
  case ($numH >= 10001 && $numH <= 15000):
    $barH = "aqua";
    $wh = 75;
    break;
  case ($numH >= 15001 && $numH <= 20000):
    $barH = "green";
    $wh = 100;
    break;
  default:
    $barH = "green";
    $wh = 100;
}

?>
<style type="text/css">
  input {
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

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php allow_access(1, 1, 0, 1, 1, 0, $usergroup); ?>
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
          <li class="active"><a href="oil_store.php">Stock</a></li>
          <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title"><a href="#"><a href="oil_store.php"><?php echo "Oil Store" ?></a></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <h3 class="box-title"><?php echo $msg; ?></h3>
                <div class="form-group">

                  <img src="pace/diesel_stock.png" style="height:25%; padding-top:50px;" class="hidden-mobile">

                  <div class="form-wrapper">
                    <form name="add_name" id="add_name" action="" method="post" class="form-style-9" enctype="multipart/form-data">

                      <ul>

                        <li>

                          <label for="">Category:</label>
                          <select class="form-control" id="from" required name="cat">
                            <option value="" selected>::Select::</option>
                            <option>Diesel</option>
                            <option>Petrol</option>
                            <option>Engine Oil</option>
                            <option>Hydraulic Oil</option>
                          </select>


                        </li>
                        <li>

                          <div class="form-group">
                            <div class="form-wrapper">
                              <label for="">Liter:</label>
                              <input type="number" min="0" class="form-control" name="ltr">
                            </div>
                            <div class="form-wrapper">
                              <label for="">Liter Price:</label>
                              <input type="number" min="0" name="ltrp" class="form-control">
                            </div>
                          </div>
                        </li>


                        <li>
                          <label for="">Requisition/Receipt:</label>
                          <input type="text" name="ltrw" class="form-control" required value="<?php echo $_GET['r']; ?>">

                        </li>

                        <!--<li>
                          <label for="">Auth Purchase(Enter the name):</label>
                          <select class="form-control" id="authby" required name="authby">
                            <option value="" selected>Who Authorised(Purchase)</option>
                            <?php while ($row_Authby = mysqli_fetch_assoc($as_Authby)) { ?>
                              <option value="<?php echo $row_Authby['id']; ?>"><?php echo $row_Authby['fullname'] . "---" . $row_Authby['user_mail']; ?></option>
                            <?php } ?>
                          </select>

                        </li>-->

                        <li>



                          <label for="">Site:</label>
                          <select class="form-control" id="from" required name="site">
                            <option value="" selected>Select Location(Site)</option>
                            <?php while ($row_site = mysqli_fetch_assoc($as_site)) { ?>
                              <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state'] . "---" . $row_site['site_lga'] . "---" . $row_site['site_loc']; ?></option>
                            <?php } ?>
                          </select>


                        </li>

                      </ul>
                      <ul>
                        <li>

                          <div class="form-group">
                            <div class="form-wrapper">
                              <label for="">Date:</label>
                              <input type="datetime-local" name="dt" class="form-control">
                            </div>
                            <div class="form-wrapper">
                              <label for="">Prepared By:</label>
                              <select name="dept" class="form-control" disabled>
                                <?php $prebyID = $preby;
                                require '../layout/preby.php';
                                while ($row_preby2 = mysqli_fetch_assoc($preby2)) { ?>
                                  <option><?php echo $row_preby2['fullname']; ?></option>
                                <?php } ?>

                              </select>
                            </div>
                          </div>
                        </li>
                        <?php $need = false;
                        if ($need) { ?>
                          <li>


                            <center><span style="color:red">PAYMENT STATUS:</span><br>
                              <div>
                                <label for="pst">Paid</label>
                                <input type="radio" id="pst" name="pst" value="1" required />
                                <label for="contactChoice3">Unpaid</label>
                                <input type="radio" id="pst2" name="pst" value="0" />
                              </div>
                            </center>
                          </li>
                        <?php } ?>
                        <hr>
                        <h5 class="box-title"><span style="color:#337ab7;"><?php echo "Upload Receipt" ?></span></h5>
                        <table class="table table-bordered txt" id="dynamic_field" border="0">
                          <tr>
                            <ul class="row'+i+'">
                              <li><label for="">Receipt (JPEG,PNG,SVG):</label><input type="file" class="form-control" required name="filen" title="Upload Receipt"></li>
                              <ul>
                                <td align="right" colspan="6">

                          </tr>

                          <!-- <tr>
                            <ul class="row'+i+'">
                              <li><label for="">Total Amount:</label><input type="number" name="amount" class="form-control"></li>
                              <ul>
                                <td align="right" colspan="6">

                          </tr> -->
                        </table>
                      </ul>

                      <div align="right">
                        <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Save" />
                      </div>
                    </form>
                  </div>
                  <div class="col-md-4 hidden-mobile">
                    <p class="text-center">
                      <strong><u>Remains in Stock!</u></strong>
                    </p>

                    <div class="progress-group">
                      <span class="progress-text">Diesel</span>
                      <span class="progress-number"><b><?php echo $row_oil_count['diesel'] . "L"; ?></b>/<?php echo $row_oil['diesel']; ?></span>

                      <div class="progress sm">
                        <div style="width: <?php echo "$wd"; ?>%" class="progress-bar progress-bar-<?php echo $barD; ?>"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Petrol</span>
                      <span class="progress-number"><b><?php echo $row_oil_count['petrol'] . "L"; ?></b>/<?php echo $row_oil['petrol']; ?></span>

                      <div class="progress sm">
                        <div style="width: <?php echo $wp; ?>%" class="progress-bar progress-bar-<?php echo $barP; ?>"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Engine Oil</span>
                      <span class="progress-number"><b><?php echo $row_oil_count['engineoil'] . "L"; ?></b>/<?php echo $row_oil['engineoil']; ?></span>

                      <div class="progress sm">
                        <div style="width: <?php echo $we; ?>%" class="progress-bar progress-bar-<?php echo $barE; ?>"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Hydraulic Oil</span>
                      <span class="progress-number"><b><?php echo $row_oil_count['hydraulicoil'] . "L"; ?></b>/<?php echo $row_oil['hydraulicoil']; ?></span>

                      <div class="progress sm">
                        <div style="width: <?php echo $wh; ?>%" class="progress-bar progress-bar-<?php echo $barH; ?>"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                    <!-- /.progress-group -->
                  </div>

                </div>

                <style type="text/css">
                  .form-group {
                    display: flex;

                  }

                  input,
                  textarea,
                  select,
                  button {
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

                  .form-style-9 {
                    max-width: 450px;
                    background: #FAFAFA;
                    padding: 30px;
                    margin: 50px auto;
                    box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
                    border-radius: 10px;

                  }

                  .form-style-9 ul {
                    padding: 0;
                    margin: 0;
                    list-style: none;
                  }

                  .form-style-9 ul li {
                    display: block;
                    margin-bottom: 10px;
                    min-height: 35px;
                  }

                  .form-style-9 ul li .field-style {
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

                  }

                  .form-style-9 ul li .field-style:focus {
                    box-shadow: 0 0 5px #B0CFE0;
                    border: 1px solid #B0CFE0;
                  }

                  .form-style-9 ul li .field-split {
                    width: 49%;
                  }

                  .form-style-9 ul li .field-full {
                    width: 100%;
                  }

                  .form-style-9 ul li input.align-left {
                    float: left;
                  }

                  .form-style-9 ul li input.align-right {
                    float: right;
                  }

                  .form-style-9 ul li textarea {
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